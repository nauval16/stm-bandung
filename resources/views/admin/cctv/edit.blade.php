@extends('layouts.admin')

@section('title', 'Edit CCTV')
@section('page_heading', 'Ubah Konfigurasi Perangkat')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    #map { height: 350px; z-index: 10; } 
</style>
@endpush

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.cctv.index') }}" class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1 w-fit transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Perangkat
        </a>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden max-w-4xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-base font-semibold text-gray-800">Form Edit Kamera & Peta Lokasi</h3>
            <p class="text-xs text-gray-500 mt-1">Ubah detail CCTV dan geser pin pada peta untuk mengoreksi titik lokasi.</p>
        </div>
        
        <form action="{{ route('admin.cctv.update', $cctv->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Kolom Kiri -->
                <div>
                    <label for="jalan_id" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Ruas Jalan <span class="text-red-500">*</span></label>
                    <select name="jalan_id" id="jalan_id" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-elegant outline-none transition text-sm bg-white">
                        @foreach ($jalans as $jalan)
                            <option value="{{ $jalan->id }}" {{ $cctv->jalan_id == $jalan->id ? 'selected' : '' }}>
                                {{ $jalan->nama_jalan }} {{ $jalan->kecamatan ? '('.$jalan->kecamatan.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kolom Kanan -->
                <div>
                    <label for="link_akses" class="block text-sm font-medium text-gray-700 mb-1">Link Akses Kamera (URL) <span class="text-red-500">*</span></label>
                    <input type="url" name="link_akses" id="link_akses" value="{{ old('link_akses', $cctv->link_akses) }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-elegant outline-none transition text-sm">
                </div>
            </div>

            <!-- PETA INTERAKTIF UNTUK KOORDINAT -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titik Koordinat Lokasi CCTV <span class="text-red-500">*</span></label>
                <div id="map" class="rounded-lg border border-gray-300 mb-3 shadow-inner"></div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center border border-gray-200 rounded-md bg-gray-50 overflow-hidden">
                            <span class="px-3 py-2 bg-gray-200 text-gray-600 text-xs font-bold border-r border-gray-200">LAT</span>
                            <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $cctv->latitude) }}" readonly required
                                   class="w-full px-3 py-2 text-sm bg-transparent outline-none font-mono text-gray-700">
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center border border-gray-200 rounded-md bg-gray-50 overflow-hidden">
                            <span class="px-3 py-2 bg-gray-200 text-gray-600 text-xs font-bold border-r border-gray-200">LNG</span>
                            <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $cctv->longitude) }}" readonly required
                                   class="w-full px-3 py-2 text-sm bg-transparent outline-none font-mono text-gray-700">
                        </div>
                    </div>
                </div>
                <p class="text-[11px] text-gray-400 mt-2">* Geser pin merah atau klik pada peta untuk mengubah koordinat.</p>
            </div>

            <!-- Upload Gambar (Tampilkan Gambar Lama) -->
            <div class="mb-8">
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-1">Gambar Thumbnail Jalan</label>
                <div class="flex items-start gap-4 mt-2">
                    @if($cctv->gambar)
                        <img src="{{ asset('storage/' . $cctv->gambar) }}" alt="Thumbnail" class="w-24 h-24 object-cover rounded-md border border-gray-200 shadow-sm">
                    @endif
                    <div class="flex-1">
                        <input type="file" name="gambar" id="gambar" accept="image/png, image/jpeg, image/jpg" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-elegant outline-none transition text-sm file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                <a href="{{ route('admin.cctv.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-blue-elegant rounded-md hover:bg-blue-hover shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Mengambil koordinat lama dari database, jika kosong gunakan default Baleendah
        let savedLat = {{ $cctv->latitude ?? '-6.9848' }};
        let savedLng = {{ $cctv->longitude ?? '107.5895' }};

        // Inisialisasi Peta
        const map = L.map('map').setView([savedLat, savedLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Pasang marker di titik koordinat dari database
        let marker = L.marker([savedLat, savedLng], { draggable: true }).addTo(map);

        function updateInputs(latlng) {
            document.getElementById('latitude').value = latlng.lat.toFixed(6);
            document.getElementById('longitude').value = latlng.lng.toFixed(6);
        }

        // Pindahkan marker dan update koordinat saat peta diklik
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng);
        });

        // Update koordinat setelah marker selesai digeser
        marker.on('dragend', function (e) {
            updateInputs(e.target.getLatLng());
        });
    });
</script>
@endpush