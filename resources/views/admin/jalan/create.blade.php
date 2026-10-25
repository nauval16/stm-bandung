@extends('layouts.admin')

@section('title', 'Tambah Data Jalan')
@section('page_heading', 'Tambah Ruas Jalan Baru')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.jalan.index') }}" class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1 w-fit transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Jalan
        </a>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden max-w-2xl">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-800">Form Informasi Jalan</h3>
        </div>
        
        <form action="{{ route('admin.jalan.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="mb-5">
                <label for="nama_jalan" class="block text-sm font-medium text-gray-700 mb-1">Nama Jalan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_jalan" id="nama_jalan" required placeholder="Contoh: Jl. Raya Bojongsoang" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-elegant focus:border-blue-elegant outline-none transition text-sm">
                @error('nama_jalan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan (Opsional)</label>
                <input type="text" name="kecamatan" id="kecamatan" placeholder="Contoh: Bojongsoang" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-elegant focus:border-blue-elegant outline-none transition text-sm">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.jalan.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-elegant rounded-md hover:bg-blue-hover transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection