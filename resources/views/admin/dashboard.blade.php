@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_heading', 'Dashboard')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="flex flex-col justify-between gap-4 rounded-2xl bg-[#0A2540] px-6 py-6 text-white shadow-sm sm:flex-row sm:items-center">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Ringkasan operasional</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight">Dashboard Admin STM</h1>
                <p class="mt-1 text-sm text-slate-300">Pantau data jalan, kamera, dan kondisi yang membutuhkan perhatian.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('admin.jalan.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/20 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"/></svg>
                    Tambah jalan
                </a>
                <a href="{{ route('admin.cctv.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-400 px-4 py-2.5 text-sm font-bold text-[#0A2540] transition hover:bg-sky-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"/></svg>
                    Tambah CCTV
                </a>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan data">
            <a href="{{ route('admin.jalan.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Ruas jalan</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5.45-2.72A1 1 0 013 16.38V7.62a1 1 0 01.55-.9L9 4m0 16 6-3m-6 3V4m6 13 5.45 2.72a1 1 0 001.45-.9V7.62a1 1 0 00-.55-.9L15 4m0 13V4"/></svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($stats['jalan']) }}</p>
                <p class="mt-1 text-xs text-gray-500">Terdaftar di sistem</p>
            </a>

            <a href="{{ route('admin.cctv.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Titik CCTV</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 10l4.55-2.28A1 1 0 0121 8.62v6.76a1 1 0 01-1.45.9L15 14M4 6h10a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($stats['cctv']) }}</p>
                <p class="mt-1 text-xs text-gray-500">Kamera terdaftar</p>
            </a>

            <a href="{{ route('admin.cctv.index') }}" class="rounded-xl border border-orange-100 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Titik terindikasi macet</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12h3l3-8 4 16 3-8h5"/></svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-orange-600">{{ number_format($stats['macet']) }}</p>
                <p class="mt-1 text-xs text-gray-500">Berdasarkan status AI</p>
            </a>

            <a href="{{ route('admin.cctv.index') }}" class="rounded-xl border border-red-100 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Peringatan banjir</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3.5s6 6.1 6 11a6 6 0 11-12 0c0-4.9 6-11 6-11z"/></svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-red-600">{{ number_format($stats['banjir']) }}</p>
                <p class="mt-1 text-xs text-gray-500">Titik berstatus banjir</p>
            </a>
        </section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm xl:col-span-2" aria-labelledby="alerts-heading">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 id="alerts-heading" class="font-bold text-gray-900">Peringatan aktif</h2>
                        <p class="mt-1 text-xs text-gray-500">Titik yang membutuhkan perhatian</p>
                    </div>
                    <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-bold text-red-700">{{ $stats['peringatan'] }}</span>
                </div>

                @if ($alerts->isNotEmpty())
                    <div class="divide-y divide-gray-100">
                        @foreach ($alerts as $cctv)
                            <div class="flex items-start justify-between gap-3 px-5 py-4">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-gray-900">{{ $cctv->jalan->nama_jalan ?? 'Lokasi belum diatur' }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $cctv->jalan->kecamatan ?? 'Kecamatan belum diatur' }}</p>
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        @if ($cctv->kondisi_terkini === 'Macet')
                                            <span class="rounded-full bg-orange-50 px-2 py-1 text-[10px] font-bold text-orange-700">Macet · {{ $cctv->jumlah_kendaraan }} kendaraan</span>
                                        @endif
                                        @if ($cctv->status_banjir === 'Banjir')
                                            <span class="rounded-full bg-red-50 px-2 py-1 text-[10px] font-bold text-red-700">Banjir · {{ $cctv->ketinggian_air_cm }} cm</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('admin.cctv.edit', $cctv) }}" class="shrink-0 rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs font-semibold text-gray-600 transition hover:border-blue-200 hover:text-blue-700">Kelola</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-5 py-10 text-center">
                        <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-teal-50 text-teal-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <p class="mt-3 text-sm font-semibold text-gray-800">Tidak ada peringatan aktif</p>
                        <p class="mt-1 text-xs text-gray-500">Belum ada titik berstatus macet atau banjir.</p>
                    </div>
                @endif
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm xl:col-span-3" aria-labelledby="recent-heading">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 id="recent-heading" class="font-bold text-gray-900">Pembaruan CCTV terbaru</h2>
                        <p class="mt-1 text-xs text-gray-500">Diurutkan berdasarkan pembaruan data terakhir</p>
                    </div>
                    <a href="{{ route('admin.cctv.index') }}" class="text-xs font-bold text-blue-700 hover:text-blue-900">Semua CCTV →</a>
                </div>

                @if ($recentCctvs->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[620px] text-left text-sm">
                            <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-5 py-3 font-semibold">Lokasi</th>
                                    <th class="px-5 py-3 font-semibold">AI Traffic</th>
                                    <th class="px-5 py-3 font-semibold">IoT Sensor</th>
                                    <th class="px-5 py-3 font-semibold">Pembaruan</th>
                                    <th class="px-5 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($recentCctvs as $cctv)
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="px-5 py-3.5">
                                            <p class="font-semibold text-gray-900">{{ $cctv->jalan->nama_jalan ?? 'Lokasi belum diatur' }}</p>
                                            <p class="mt-0.5 text-xs text-gray-500">{{ $cctv->jalan->kecamatan ?? 'Kecamatan belum diatur' }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold {{ $cctv->kondisi_terkini === 'Macet' ? 'text-orange-700' : 'text-teal-700' }}">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $cctv->kondisi_terkini === 'Macet' ? 'bg-orange-500' : 'bg-teal-500' }}"></span>
                                                {{ $cctv->kondisi_terkini ?? 'Belum ada data' }}
                                            </span>
                                            <p class="mt-1 text-[11px] text-gray-500">{{ $cctv->jumlah_kendaraan ?? 0 }} kendaraan</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold {{ $cctv->status_banjir === 'Banjir' ? 'text-red-700' : 'text-teal-700' }}">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $cctv->status_banjir === 'Banjir' ? 'bg-red-500' : 'bg-teal-500' }}"></span>
                                                {{ $cctv->status_banjir ?? 'Belum ada data' }}
                                            </span>
                                            <p class="mt-1 text-[11px] text-gray-500">Tinggi air {{ $cctv->ketinggian_air_cm ?? 0 }} cm</p>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-xs text-gray-500">{{ $cctv->updated_at?->diffForHumans() ?? 'Belum ada data' }}</td>
                                        <td class="px-5 py-3.5 text-right">
                                            <a href="{{ route('admin.cctv.edit', $cctv) }}" class="text-xs font-semibold text-blue-700 hover:text-blue-900">Kelola</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-5 py-10 text-center">
                        <p class="text-sm font-semibold text-gray-800">Belum ada data CCTV</p>
                        <p class="mt-1 text-xs text-gray-500">Tambahkan titik kamera untuk mulai memantau statusnya.</p>
                        <a href="{{ route('admin.cctv.create') }}" class="mt-4 inline-flex rounded-lg bg-[#0A2540] px-4 py-2 text-xs font-bold text-white hover:bg-blue-800">Tambah CCTV</a>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
