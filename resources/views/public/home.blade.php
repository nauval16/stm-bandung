@extends('layouts.user')

@section('title', 'Smart Traffic Management Bandung')

@section('content')
    <section class="relative isolate overflow-hidden bg-[#0A2540] text-white">
        <div class="absolute inset-0 -z-10 opacity-20" aria-hidden="true">
            <svg class="h-full w-full" viewBox="0 0 1440 720" preserveAspectRatio="xMidYMid slice" fill="none">
                <path d="M-80 570C170 420 250 690 520 490S900 260 1110 390 1390 490 1530 250" stroke="#38BDF8" stroke-width="2"/>
                <path d="M-60 650C180 500 300 740 570 550S930 320 1130 450 1410 550 1550 310" stroke="#38BDF8" stroke-width="1"/>
                <path d="M180 -70C320 150 150 260 380 400S660 590 620 790M960 -80C820 130 1120 230 940 410S810 650 990 780" stroke="#fff" stroke-width="1"/>
                <circle cx="1090" cy="355" r="180" stroke="#38BDF8"/>
                <circle cx="1090" cy="355" r="235" stroke="#38BDF8"/>
            </svg>
        </div>
        <div class="absolute -right-32 -top-36 -z-10 h-96 w-96 rounded-full bg-blue-500/20 blur-3xl" aria-hidden="true"></div>

        <div class="mx-auto grid max-w-7xl items-center gap-12 px-6 py-16 sm:px-8 sm:py-20 lg:grid-cols-2 lg:gap-16 lg:px-10 lg:py-24">
            <div class="max-w-2xl">
                {{-- <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-2 text-xs font-semibold tracking-wide text-sky-200">
                    <span class="h-2 w-2 rounded-full bg-teal-400"></span>
                    SMART TRAFFIC MANAGEMENT · BANDUNG
                </div> --}}
                <h1 class="mt-6 text-4xl font-bold leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                    Pantau kondisi jalan
                    <span class="block text-sky-300">Bandung dengan lebih mudah.</span>
                </h1>
                <p class="mt-6 max-w-xl text-base leading-7 text-slate-300 sm:text-lg">
                    Akses informasi CCTV, kondisi lalu lintas, dan peringatan genangan dalam satu tempat untuk membantu perjalanan Anda.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('cctv') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-400 px-6 py-3.5 text-sm font-bold text-[#0A2540] shadow-lg shadow-sky-950/20 transition hover:bg-sky-300">
                        Lihat pantauan CCTV
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                    </a>
                    <a href="{{ route('rute') }}" class="inline-flex items-center justify-center rounded-xl border border-white/20 bg-white/5 px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10">
                        Jelajahi rute cerdas
                    </a>
                </div>
                <p class="mt-5 text-xs text-slate-400">Informasi status mengikuti data terakhir yang diterima sistem.</p>
            </div>

            <div class="relative mx-auto w-full max-w-xl lg:ml-auto">
                <div class="absolute -inset-4 rounded-[2rem] bg-sky-400/10 blur-2xl" aria-hidden="true"></div>
                <div class="relative overflow-hidden rounded-3xl border border-white/15 bg-slate-900/70 p-3 shadow-2xl shadow-slate-950/40 backdrop-blur">
                    <div class="flex items-center justify-between px-3 py-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-200">Command center</p>
                            <p class="mt-1 text-sm font-medium text-white">Kondisi titik pemantauan</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-2.5 py-1 text-[10px] font-semibold text-slate-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-teal-400"></span>
                            STM BANDUNG
                        </span>
                    </div>

                    <div class="relative mt-2 h-56 overflow-hidden rounded-2xl bg-[#102f4a] sm:h-64">
                        <svg class="absolute inset-0 h-full w-full" viewBox="0 0 560 280" preserveAspectRatio="xMidYMid slice" fill="none" aria-hidden="true">
                            <path d="M-30 214 86 142 161 166 249 92 327 111 411 48 592 84M-20 254 101 181 176 205 265 132 343 151 425 87 585 123" stroke="#45657d" stroke-width="13"/>
                            <path d="m32-20 40 90-6 62 50 68-10 92M200-18l-25 78 25 53-11 62 56 105M371-20l-35 78 24 62-14 65 48 125M515-14l-53 93 31 62-11 63 39 102" stroke="#35556e" stroke-width="9"/>
                            <path d="M-30 214 86 142 161 166 249 92 327 111 411 48 592 84" stroke="#38BDF8" stroke-width="3" stroke-dasharray="7 9"/>
                            <path d="M0 36h560M0 266h560" stroke="#fff" stroke-opacity=".06"/>
                            <circle cx="249" cy="92" r="15" fill="#38BDF8" fill-opacity=".2"/>
                            <circle cx="249" cy="92" r="6" fill="#7dd3fc"/>
                            <circle cx="411" cy="48" r="15" fill="#2dd4bf" fill-opacity=".2"/>
                            <circle cx="411" cy="48" r="6" fill="#5eead4"/>
                            <circle cx="161" cy="166" r="15" fill="#fb923c" fill-opacity=".2"/>
                            <circle cx="161" cy="166" r="6" fill="#fdba74"/>
                        </svg>
                        <div class="absolute bottom-3 left-3 rounded-lg border border-white/10 bg-slate-950/65 px-3 py-2 backdrop-blur">
                            <p class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Peta pemantauan</p>
                            <p class="mt-0.5 text-xs font-semibold text-white">Bandung, Jawa Barat</p>
                        </div>
                        <div class="absolute right-3 top-3 flex items-center gap-2 rounded-lg border border-white/10 bg-slate-950/65 px-3 py-2 text-[10px] font-medium text-slate-200 backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-teal-400"></span>                            Lalin aman
                            <span class="ml-1 h-2 w-2 rounded-full bg-orange-400"></span>Macet
                            <span class="ml-1 h-2 w-2 rounded-full bg-red-400"></span>Genangan
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2.5 p-1 pt-3">
                        <div class="rounded-xl border border-white/10 bg-white/[0.04] p-3">
                            <p class="text-[10px] font-medium text-slate-400">Titik CCTV</p>
                            <p class="mt-1 text-xl font-bold text-white">{{ number_format($stats['cctv']) }}</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/[0.04] p-3">
                            <p class="text-[10px] font-medium text-slate-400">Titik macet</p>
                            <p class="mt-1 text-xl font-bold text-orange-300">{{ number_format($stats['macet']) }}</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/[0.04] p-3">
                            <p class="text-[10px] font-medium text-slate-400">Peringatan air</p>
                            <p class="mt-1 text-xl font-bold text-red-300">{{ number_format($stats['banjir']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-0 px-6 py-7 sm:px-8 md:grid-cols-4 lg:px-10">
            <div class="border-r border-slate-100 px-4 py-2 text-center md:text-left">
                <p class="text-2xl font-bold tracking-tight text-[#0A2540]">{{ number_format($stats['cctv']) }}</p>
                <p class="mt-1 text-xs font-medium text-slate-500">Titik CCTV terdaftar</p>
            </div>
            <div class="px-4 py-2 text-center md:border-r md:border-slate-100 md:text-left">
                <p class="text-2xl font-bold tracking-tight text-[#0A2540]">{{ number_format($stats['jalan']) }}</p>
                <p class="mt-1 text-xs font-medium text-slate-500">Ruas jalan terdata</p>
            </div>
            <div class="border-r border-slate-100 px-4 py-2 text-center md:text-left">
                <p class="text-2xl font-bold tracking-tight text-orange-500">{{ number_format($stats['macet']) }}</p>
                <p class="mt-1 text-xs font-medium text-slate-500">Titik terindikasi macet</p>
            </div>
            <div class="px-4 py-2 text-center md:text-left">
                <p class="text-2xl font-bold tracking-tight text-red-500">{{ number_format($stats['banjir']) }}</p>
                <p class="mt-1 text-xs font-medium text-slate-500">Peringatan genangan</p>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-6 sm:px-8 lg:px-10">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">Satu platform untuk perjalanan</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-[#0A2540] sm:text-4xl">Informasi yang membantu Anda bergerak.</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">Kenali kondisi sekitar dan rencanakan perjalanan dengan informasi dari titik pemantauan.</p>
                </div>
                <a href="{{ route('cctv') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-700 hover:text-blue-900">
                    Buka semua CCTV
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </a>
            </div>

            <div class="mt-9 grid gap-5 md:grid-cols-3">
                <a href="{{ route('cctv') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-900/5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 10l4.55-2.28A1 1 0 0121 8.62v6.76a1 1 0 01-1.45.9L15 14M4 6h10a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-[#0A2540]">Pantauan CCTV</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Lihat daftar kamera dan ringkasan kondisi di ruas jalan yang terpantau.</p>
                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-blue-700">Jelajahi kamera <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span></span>
                </a>

                <a href="{{ route('cctv') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-teal-200 hover:shadow-lg hover:shadow-teal-900/5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-teal-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12h3l3-8 4 16 3-8h5"/></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-[#0A2540]">Analitik lalu lintas</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Telusuri ringkasan volume kendaraan menurut jenis dan arah perjalanan.</p>
                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-teal-700">Lihat analitik <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span></span>
                </a>

                <a href="{{ route('rute') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-orange-200 hover:shadow-lg hover:shadow-orange-900/5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5.45-2.72A1 1 0 013 16.38V7.62a1 1 0 01.55-.9L9 4m0 16 6-3m-6 3V4m6 13 5.45 2.72a1 1 0 001.45-.9V7.62a1 1 0 00-.55-.9L15 4m0 13V4"/></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-[#0A2540]">Rute cerdas</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Pilih titik awal dan tujuan, lalu periksa kondisi bahaya di sekitar rute.</p>
                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-orange-600">Coba peta rute <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span></span>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-6 sm:px-8 lg:px-10">
            <div class="grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">Bagaimana sistem bekerja</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-[#0A2540] sm:text-4xl">Dari pemantauan menjadi informasi yang berguna.</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">STM menghubungkan kamera, analisis kendaraan, dan sensor lingkungan agar kondisi jalan dapat dipahami lebih cepat.</p>
                    <a href="{{ route('tentang') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-blue-700 hover:text-blue-900">
                        Kenali sistem STM
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                    </a>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-sm font-bold text-blue-800">01</span>
                        <h3 class="mt-4 font-bold text-[#0A2540]">Pantau</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Kamera dan sensor mengirim informasi dari titik jalan.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-100 text-sm font-bold text-teal-800">02</span>
                        <h3 class="mt-4 font-bold text-[#0A2540]">Analisis</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">AI dan sistem sensor membantu membaca kondisi kendaraan dan genangan.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-sm font-bold text-orange-800">03</span>
                        <h3 class="mt-4 font-bold text-[#0A2540]">Informasikan</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Ringkasan kondisi membantu warga mempertimbangkan perjalanan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-6 sm:px-8 lg:px-10">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">Titik terbaru</p>
                    <h2 class="mt-3 text-2xl font-bold tracking-tight text-[#0A2540] sm:text-3xl">Pantauan di sekitar Bandung</h2>
                </div>
                <a href="{{ route('cctv') }}" class="text-sm font-bold text-blue-700 hover:text-blue-900">Lihat seluruh titik →</a>
            </div>

            <div class="mt-7 grid gap-4 md:grid-cols-3">
                @forelse ($cctvs as $cctv)
                    <a href="{{ url('/cctv/detail/' . $cctv->id) }}" class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                        <div class="min-w-0">
                            <p class="truncate font-bold text-[#0A2540]">{{ $cctv->jalan->nama_jalan ?? 'Lokasi belum diatur' }}</p>
                            <p class="mt-1 truncate text-xs text-slate-500">{{ $cctv->jalan->kecamatan ?? 'Bandung' }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $cctv->kondisi_terkini === 'Macet' ? 'bg-orange-500' : 'bg-teal-500' }}"></span>
                                    {{ $cctv->kondisi_terkini === 'Aman' ? 'Aman dari macet' : ($cctv->kondisi_terkini ?? 'Status lalu lintas belum tersedia') }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $cctv->status_banjir === 'Banjir' ? 'bg-red-500' : 'bg-teal-500' }}"></span>
                                    {{ $cctv->status_banjir === 'Aman' ? 'Aman dari banjir' : ($cctv->status_banjir ?? 'Status genangan belum tersedia') }}
                                </span>
                            </div>
                        </div>
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                        </span>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center md:col-span-3">
                        <p class="font-semibold text-slate-700">Belum ada titik CCTV terdaftar.</p>
                        <p class="mt-1 text-sm text-slate-500">Informasi pantauan akan tampil di sini setelah data tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="px-6 py-14 sm:px-8 sm:py-16 lg:px-10">
        <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 overflow-hidden rounded-3xl bg-[#0A2540] px-7 py-9 text-white shadow-xl shadow-blue-950/10 sm:flex-row sm:items-center sm:px-10 sm:py-11">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-sky-300">Perjalanan lebih terinformasi</p>
                <h2 class="mt-3 text-2xl font-bold tracking-tight sm:text-3xl">Cek kondisi jalan sebelum berangkat.</h2>
                <p class="mt-2 text-sm leading-6 text-slate-300">Jelajahi pantauan kamera dan informasi rute dalam satu platform.</p>
            </div>
            <div class="flex w-full flex-col gap-3 sm:w-auto sm:shrink-0 sm:flex-row">
                <a href="{{ route('cctv') }}" class="inline-flex items-center justify-center rounded-xl bg-sky-400 px-5 py-3 text-sm font-bold text-[#0A2540] transition hover:bg-sky-300">Buka CCTV</a>
                <a href="{{ route('rute') }}" class="inline-flex items-center justify-center rounded-xl border border-white/20 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Buka peta rute</a>
            </div>
        </div>
    </section>
@endsection
