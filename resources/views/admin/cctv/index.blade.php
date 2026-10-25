@extends('layouts.admin')

@section('title', 'Manajemen CCTV & IoT')
@section('page_heading', 'Data Kamera AI & Sensor IoT')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Perangkat Terintegrasi</h3>
            <p class="text-sm text-gray-500">Kelola unit CCTV dan sensor ultrasonik yang terpasang di lapangan.</p>
        </div>
        <a href="{{ route('admin.cctv.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-elegant text-white text-sm font-medium rounded-lg hover:bg-blue-hover transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Perangkat
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 font-semibold">ID</th>
                        <th class="px-6 py-3 font-semibold">Lokasi Jalan</th>
                        <th class="px-6 py-3 font-semibold text-center">Status AI (Trafik)</th>
                        <th class="px-6 py-3 font-semibold text-center">Status IoT (Air)</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($cctvs as $cctv)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">#{{ $cctv->id }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $cctv->jalan->nama_jalan ?? 'Jalan Dihapus' }}</td>

                            <!-- Indikator AI -->
                            <td class="px-6 py-4 text-center">
                                @php
                                    $aiBg = 'bg-green-50 text-green-700 border-green-200';
                                    if ($cctv->kondisi_terkini == 'Macet') {
                                        $aiBg = 'bg-red-50 text-red-700 border-red-200';
                                    } elseif ($cctv->kondisi_terkini == 'Ramai Lancar') {
                                        $aiBg = 'bg-orange-50 text-orange-700 border-orange-200';
                                    }
                                @endphp
                                <div class="flex flex-col items-center gap-1">
                                    <span
                                        class="inline-block px-2.5 py-1 rounded-md text-xs font-bold border {{ $aiBg }}">
                                        {{ $cctv->kondisi_terkini }}
                                    </span>
                                    <span class="text-xs text-gray-400 font-medium">{{ $cctv->jumlah_kendaraan }}
                                        Kendaraan</span>
                                </div>
                            </td>

                            <!-- Indikator IoT -->
                            <td class="px-6 py-4 text-center">
                                @php
                                    $iotBg = 'bg-cyan-50 text-cyan-700 border-cyan-200';
                                    if ($cctv->status_banjir == 'Banjir') {
                                        $iotBg = 'bg-red-50 text-red-700 border-red-200 font-bold animate-pulse';
                                    }
                                @endphp
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-block px-2.5 py-1 rounded-md text-xs border {{ $iotBg }}">
                                        {{ $cctv->status_banjir }}
                                    </span>
                                    <span class="text-xs text-gray-400 font-medium">{{ $cctv->ketinggian_air_cm }} cm</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right flex justify-end gap-3 items-center h-full mt-2">
                                <!-- Tombol Edit -->
                                <a href="{{ route('admin.cctv.edit', $cctv->id) }}"
                                    class="text-blue-600 hover:text-blue-800 font-medium transition">Edit</a>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.cctv.destroy', $cctv->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus perangkat ini? Semua riwayat data sensor akan hilang.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 font-medium transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h11a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z">
                                    </path>
                                </svg>
                                Belum ada kamera CCTV yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
