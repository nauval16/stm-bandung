@extends('layouts.admin')

@section('title', 'Manajemen Jalan')
@section('page_heading', 'Data Ruas Jalan')

@section('content')
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Jalan Pantauan</h3>
            <p class="text-sm text-gray-500">Kelola titik lokasi jalan yang terintegrasi dengan sistem STM.</p>
        </div>
        <!-- Tombol Tambah Data -->
        <a href="{{ route('admin.jalan.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-elegant text-white text-sm font-medium rounded-lg hover:bg-blue-hover transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Jalan
        </a>
    </div>

    <!-- Kotak Tabel -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 font-semibold">No</th>
                        <th class="px-6 py-3 font-semibold">Nama Jalan</th>
                        <th class="px-6 py-3 font-semibold">Kecamatan</th>
                        <th class="px-6 py-3 font-semibold">Kamera Aktif</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($jalans as $index => $jalan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $jalan->nama_jalan }}</td>
                            <td class="px-6 py-4">{{ $jalan->kecamatan ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> 0 Unit
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-3">
                                <!-- Tombol Edit -->
                                <a href="{{ route('admin.jalan.edit', $jalan->id) }}"
                                    class="text-blue-600 hover:text-blue-800 font-medium transition">Edit</a>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.jalan.destroy', $jalan->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus ruas jalan ini? Semua data CCTV yang terhubung juga akan terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 font-medium transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data jalan yang didaftarkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
