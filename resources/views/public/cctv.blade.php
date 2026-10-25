@extends('layouts.user')

@section('title', 'Pemantauan CCTV - Smart Traffic')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-container { z-index: 0; font-family: inherit; }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-slate-50">
        <section class="bg-[#0A2540] text-white">
            <div class="mx-auto max-w-7xl px-6 py-10 sm:px-8 sm:py-12 lg:px-10">
                <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-sky-300">Command center · Bandung</p>
                        <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Pantauan CCTV</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300 sm:text-base">Pantau kondisi lalu lintas dan genangan dari titik kamera yang terdaftar.</p>
                    </div>
                    {{-- <div class="inline-flex items-center gap-2 self-start rounded-full border border-white/15 bg-white/5 px-3 py-2 text-xs font-medium text-slate-200 md:self-auto">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-teal-400 opacity-60"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-teal-400"></span>
                        </span>
                        Status diperbarui otomatis
                    </div> --}}
                </div>
            </div>
        </section>

        <div class="mx-auto max-w-7xl px-6 py-8 sm:px-8 lg:px-10">
            <section class="-mt-1 grid grid-cols-2 gap-3 lg:grid-cols-4" aria-label="Ringkasan kondisi CCTV">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-xs font-semibold text-slate-500">Total titik kamera</p>
                    <p id="summary-total" class="mt-2 text-2xl font-bold tracking-tight text-[#0A2540]">{{ number_format($stats['total']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">CCTV terdaftar</p>
                </div>
                <div class="rounded-2xl border border-teal-100 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-xs font-semibold text-slate-500">Kondisi aman</p>
                    <p id="summary-safe" class="mt-2 text-2xl font-bold tracking-tight text-teal-700">{{ number_format($stats['aman']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Lalu lintas dan genangan</p>
                </div>
                <div class="rounded-2xl border border-orange-100 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-xs font-semibold text-slate-500">Titik macet</p>
                    <p id="summary-traffic" class="mt-2 text-2xl font-bold tracking-tight text-orange-600">{{ number_format($stats['macet']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Terindikasi kepadatan</p>
                </div>
                <div class="rounded-2xl border border-red-100 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-xs font-semibold text-slate-500">Peringatan banjir</p>
                    <p id="summary-flood" class="mt-2 text-2xl font-bold tracking-tight text-red-600">{{ number_format($stats['banjir']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Titik dengan genangan</p>
                </div>
            </section>

            <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-label="Peta sebaran CCTV">
                <div class="flex flex-col justify-between gap-4 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:px-6">
                    <div>
                        <h2 class="font-bold text-[#0A2540]">Peta sebaran CCTV</h2>
                        <p class="mt-1 text-xs text-slate-500"><span id="map-point-count">{{ $mapCctvs->count() }}</span> titik dengan koordinat</p>
                    </div>
                    <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs font-medium text-slate-600">
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-teal-500"></span>Aman</span>
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-orange-500"></span>Macet</span>
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>Banjir</span>
                    </div>
                </div>
                @if ($mapCctvs->isNotEmpty())
                    <div id="cctv-map" class="h-72 w-full sm:h-96"></div>
                @else
                    <div class="flex h-56 items-center justify-center bg-slate-50 px-6 text-center">
                        <div>
                            <p class="font-semibold text-slate-700">Koordinat kamera belum tersedia</p>
                            <p class="mt-1 text-sm text-slate-500">Tambahkan latitude dan longitude pada data CCTV agar lokasinya muncul di peta.</p>
                        </div>
                    </div>
                @endif
            </section>

            <section class="mt-8" aria-label="Daftar kamera CCTV">
                <div class="mb-5 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-[#0A2540]">Titik pemantauan</h2>
                        <p class="mt-1 text-sm text-slate-500"><span id="visible-count">{{ $cctvs->count() }}</span> dari {{ $cctvs->count() }} kamera</p>
                    </div>
                    <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
                        <label class="relative block sm:w-64">
                            <span class="sr-only">Cari nama jalan atau kecamatan</span>
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="m20 20-4-4"/></svg>
                            <input id="cctv-search" type="search" placeholder="Cari jalan atau kecamatan..." class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        </label>
                        <label>
                            <span class="sr-only">Filter kondisi CCTV</span>
                            <select id="cctv-filter" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 sm:w-48">
                                <option value="all">Semua kondisi</option>
                                <option value="safe">Aman</option>
                                <option value="macet">Ada kemacetan</option>
                                <option value="banjir">Ada genangan</option>
                            </select>
                        </label>
                    </div>
                </div>

                <div id="cctv-grid" class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($cctvs as $cctv)
                        @php
                            $trafficStatus = $cctv->kondisi_terkini ?? 'Status belum tersedia';
                            $floodStatus = $cctv->status_banjir ?? 'Status belum tersedia';
                            $isTrafficSafe = $trafficStatus === 'Aman';
                            $isFloodSafe = $floodStatus === 'Aman';
                            $locationName = $cctv->jalan->nama_jalan ?? 'Lokasi belum diatur';
                            $districtName = $cctv->jalan->kecamatan ?? 'Kecamatan belum diatur';
                        @endphp
                        <article class="cctv-card group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-950/5"
                            data-id="{{ $cctv->id }}"
                            data-ai-stream-url="{{ $aiStreamUrls[$cctv->id] ?? '' }}"
                            data-search="{{ mb_strtolower($locationName . ' ' . $districtName) }}"
                            data-traffic="{{ $trafficStatus }}"
                            data-flood="{{ $floodStatus }}"
                            data-traffic-safe="{{ $isTrafficSafe ? 'true' : 'false' }}"
                            data-flood-safe="{{ $isFloodSafe ? 'true' : 'false' }}">
                            <div class="relative h-48 overflow-hidden bg-gradient-to-br from-[#0A2540] to-blue-800">
                                @if ($cctv->gambar)
                                    <img src="{{ asset('storage/' . $cctv->gambar) }}" alt="Kamera CCTV di {{ $locationName }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-full items-center justify-center text-sky-200/80">
                                        <svg class="h-14 w-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M15 10l4.55-2.28A1 1 0 0121 8.62v6.76a1 1 0 01-1.45.9L15 14M4 6h10a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-slate-950/10"></div>
                                <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-slate-950/55 px-2.5 py-1 text-[10px] font-semibold text-white backdrop-blur">
                                    <span class="h-1.5 w-1.5 rounded-full bg-teal-400"></span>
                                    TITIK CCTV
                                </span>
                                <span class="absolute bottom-3 left-4 right-4 truncate text-lg font-bold text-white drop-shadow">{{ $locationName }}</span>
                            </div>

                            <div class="flex flex-1 flex-col p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="font-bold text-[#0A2540]">{{ $locationName }}</h3>
                                        <p class="mt-1 text-xs text-slate-500">{{ $districtName }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        <span id="jumlah-kendaraan-summary-{{ $cctv->id }}">{{ $cctv->jumlah_kendaraan ?? 0 }}</span> kendaraan
                                    </span>
                                </div>

                                <div class="mt-5 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3.5">
                                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">AI Traffic</p>
                                        <p id="ai-connection-{{ $cctv->id }}" class="mt-1 flex items-center gap-1.5 text-[10px] font-medium text-slate-500" data-state="loading">
                                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-blue-500"></span>
                                            Menghubungkan ke server AI…
                                        </p>
                                        <span id="ai-badge-{{ $cctv->id }}" class="status-badge mt-2 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold {{ $isTrafficSafe ? 'bg-teal-50 text-teal-700' : ($trafficStatus === 'Macet' ? 'bg-orange-50 text-orange-700' : 'bg-slate-200 text-slate-700') }}">
                                            <span class="status-dot h-1.5 w-1.5 shrink-0 rounded-full {{ $isTrafficSafe ? 'bg-teal-500' : ($trafficStatus === 'Macet' ? 'bg-orange-500' : 'bg-slate-500') }}"></span>
                                            <span class="status-label">{{ $trafficStatus }}</span>
                                        </span>
                                        <p class="mt-2 text-xs text-slate-600"><span id="jumlah-kendaraan-{{ $cctv->id }}">{{ $cctv->jumlah_kendaraan ?? 0 }}</span> kendaraan</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3.5">
                                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">IoT Sensor</p>
                                        <span id="iot-badge-{{ $cctv->id }}" class="status-badge mt-2 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold {{ $isFloodSafe ? 'bg-teal-50 text-teal-700' : ($floodStatus === 'Banjir' ? 'bg-red-50 text-red-700' : 'bg-slate-200 text-slate-700') }}">
                                            <span class="status-dot h-1.5 w-1.5 shrink-0 rounded-full {{ $isFloodSafe ? 'bg-teal-500' : ($floodStatus === 'Banjir' ? 'bg-red-500' : 'bg-slate-500') }}"></span>
                                            <span class="status-label">{{ $floodStatus }}</span>
                                        </span>
                                        <p class="mt-2 text-xs text-slate-600">Tinggi air: <span id="ketinggian-air-{{ $cctv->id }}">{{ $cctv->ketinggian_air_cm ?? 0 }}</span> cm</p>
                                    </div>
                                </div>

                                <button type="button" data-url="{{ $cctv->link_akses }}" data-title="{{ $locationName }}" class="lihat-cctv mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-900 transition hover:bg-blue-100">
                                    Lihat stream kamera
                                </button>
                                <a href="{{ url('/cctv/detail/' . $cctv->id) }}" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#0A2540] px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                                    Lihat detail & analitik
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center md:col-span-2 xl:col-span-3">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.55-2.28A1 1 0 0121 8.62v6.76a1 1 0 01-1.45.9L15 14M4 6h10a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                            </div>
                            <h3 class="mt-4 font-bold text-[#0A2540]">Belum ada kamera terdaftar</h3>
                            <p class="mt-1 text-sm text-slate-500">Titik pemantauan akan ditampilkan di sini setelah data CCTV tersedia.</p>
                        </div>
                    @endforelse
                </div>
                <div id="no-filter-results" class="hidden rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center">
                    <p class="font-semibold text-slate-700">Tidak ada kamera yang cocok.</p>
                    <p class="mt-1 text-sm text-slate-500">Coba kata pencarian atau filter kondisi yang berbeda.</p>
                </div>
            </section>
        </div>
    </div>

    {{-- MODAL PLAYER & MATA AI --}}
    <div id="cctvModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-900/90 backdrop-blur-sm hidden z-50 transition-opacity">
        <div class="bg-black p-4 rounded-2xl shadow-2xl w-full max-w-4xl mx-4 border border-gray-800 relative">

            <div class="flex justify-between items-center mb-4 px-2">
                <div class="flex items-center gap-3 text-white">
                    <span class="flex h-3 w-3 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                    <h2 id="modalTitle" class="text-lg font-semibold tracking-wide">Live Camera</h2>
                </div>

                <!-- SAKELAR TOGGLE AI -->
                <div class="flex items-center gap-3 bg-gray-900 px-4 py-1.5 rounded-full border border-gray-700 mx-auto">
                    <span id="labelNormal" class="text-xs font-bold text-blue-400">Normal</span>

                    <div class="relative inline-block w-10 align-middle select-none">
                        <input type="checkbox" id="aiToggle" class="peer sr-only" />
                        <div class="w-10 h-5 bg-gray-600 rounded-full peer-focus:outline-none peer-checked:bg-blue-600 transition-colors cursor-pointer"
                            onclick="document.getElementById('aiToggle').click()"></div>
                        <div
                            class="absolute top-1 left-1 bg-white w-3 h-3 rounded-full transition-transform peer-checked:translate-x-5 pointer-events-none">
                        </div>
                    </div>

                    <span id="labelAI" class="text-xs font-bold text-gray-500">Mata AI</span>
                </div>

                <button id="closeModal"
                    class="text-gray-400 hover:text-white bg-gray-800 hover:bg-gray-700 p-2 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div
                class="aspect-video w-full bg-gray-900 rounded-xl overflow-hidden ring-1 ring-white/10 relative flex justify-center items-center">

                <!-- 1. Video Asli (Normal) -->
                <video id="cctvPlayer" class="w-full h-full object-contain absolute inset-0 z-10" controls autoplay muted
                    playsinline></video>

                <!-- 2. Video Streaming AI dari Python (Hidden secara default) -->
                <img id="aiPlayer" src="" alt="Menunggu Koneksi Mata AI..."
                    class="w-full h-full object-contain absolute inset-0 z-20 hidden bg-black">

                <div id="aiStreamState" class="absolute inset-0 z-30 hidden items-center justify-center bg-slate-950/90 px-6 text-center text-white">
                    <div class="max-w-sm">
                        <div id="aiStreamSpinner" class="mx-auto mb-4 h-10 w-10 animate-spin rounded-full border-4 border-white/20 border-t-sky-400"></div>
                        <p id="aiStreamTitle" class="font-semibold">Menghubungkan ke server AI…</p>
                        <p id="aiStreamMessage" class="mt-2 text-sm text-slate-300">Menunggu frame pertama dari stream.</p>
                        <button id="retryAiStream" type="button" class="mt-4 hidden rounded-lg bg-sky-400 px-4 py-2 text-sm font-bold text-[#0A2540] transition hover:bg-sky-300">Coba lagi</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mapPoints = @json($mapCctvs);
            const mapMarkers = new Map();
            let cctvMap = null;

            function markerColor(point) {
                if (point.status_banjir === "Banjir") return "#ef4444";
                if (point.ai_connection_status !== "connected") return "#94a3b8";
                if (point.kondisi_terkini === "Macet") return "#f97316";
                return "#14b8a6";
            }

            function createMapPopup(point) {
                const popup = document.createElement("div");
                popup.className = "min-w-40";

                const title = document.createElement("strong");
                title.className = "block text-sm text-slate-900";
                title.textContent = point.nama_jalan;

                const traffic = document.createElement("p");
                traffic.className = "mt-1 text-xs text-slate-600";
                traffic.textContent = `AI Traffic: ${point.kondisi_terkini || "Status belum tersedia"} · ${point.jumlah_kendaraan ?? 0} kendaraan`;

                const connection = document.createElement("p");
                connection.className = "mt-1 text-xs text-slate-600";
                connection.textContent = point.ai_connection_status === "connected"
                    ? "Server AI terhubung"
                    : "Data AI belum diperbarui";

                const flood = document.createElement("p");
                flood.className = "mt-1 text-xs text-slate-600";
                flood.textContent = `IoT Sensor: ${point.status_banjir || "Status belum tersedia"} · Tinggi air ${point.ketinggian_air_cm ?? 0} cm`;

                const link = document.createElement("a");
                link.className = "mt-2 inline-block text-xs font-semibold text-blue-700";
                link.href = `{{ url('/cctv/detail') }}/${point.id}`;
                link.textContent = "Lihat detail";

                popup.append(title, traffic, connection, flood, link);
                return popup;
            }

            if (mapPoints.length > 0) {
                cctvMap = L.map("cctv-map").setView([-6.9147, 107.6098], 12);
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: "&copy; OpenStreetMap contributors",
                    maxZoom: 19
                }).addTo(cctvMap);

                const bounds = [];
                mapPoints.forEach(point => {
                    const marker = L.circleMarker([point.latitude, point.longitude], {
                        radius: 9,
                        fillColor: markerColor(point),
                        color: "#fff",
                        weight: 2,
                        fillOpacity: 1
                    }).addTo(cctvMap).bindPopup(createMapPopup(point));

                    mapMarkers.set(String(point.id), marker);
                    bounds.push([point.latitude, point.longitude]);
                });

                cctvMap.fitBounds(bounds, { padding: [32, 32], maxZoom: 14 });
                window.setTimeout(() => cctvMap.invalidateSize(), 100);
            }

            const modal = document.getElementById("cctvModal");
            const video = document.getElementById("cctvPlayer");
            const aiPlayer = document.getElementById("aiPlayer");
            const closeModal = document.getElementById("closeModal");
            const modalTitle = document.getElementById("modalTitle");

            const aiToggle = document.getElementById("aiToggle");
            const labelNormal = document.getElementById("labelNormal");
            const labelAI = document.getElementById("labelAI");
            const aiStreamState = document.getElementById("aiStreamState");
            const aiStreamSpinner = document.getElementById("aiStreamSpinner");
            const aiStreamTitle = document.getElementById("aiStreamTitle");
            const aiStreamMessage = document.getElementById("aiStreamMessage");
            const retryAiStream = document.getElementById("retryAiStream");
            let currentAiStreamUrl = "";
            let activeAiStreamUrl = "";
            let aiStreamTimeout;

            function showAiStreamState(state, message = "") {
                aiStreamState.classList.remove("hidden");
                aiStreamState.classList.add("flex");
                aiStreamSpinner.classList.toggle("hidden", state !== "loading");
                retryAiStream.classList.toggle("hidden", state !== "error");
                aiStreamTitle.textContent = state === "loading"
                    ? "Menghubungkan ke server AI…"
                    : "Stream AI tidak dapat ditampilkan";
                aiStreamMessage.textContent = message || (state === "loading"
                    ? "Menunggu frame pertama dari server."
                    : "Periksa koneksi server AI lalu coba lagi.");
            }

            function startAiStream() {
                if (!currentAiStreamUrl) {
                    showAiStreamState("error", "Alamat server AI belum dikonfigurasi.");
                    return;
                }

                window.clearTimeout(aiStreamTimeout);
                showAiStreamState("loading");
                aiPlayer.classList.remove("hidden");
                const streamUrl = new URL(currentAiStreamUrl, window.location.href);
                streamUrl.searchParams.set("_", Date.now().toString());
                activeAiStreamUrl = streamUrl.href;
                aiPlayer.removeAttribute("src");
                aiPlayer.src = activeAiStreamUrl;
                const attemptUrl = activeAiStreamUrl;
                aiStreamTimeout = window.setTimeout(() => {
                    if (activeAiStreamUrl === attemptUrl && (aiPlayer.currentSrc !== attemptUrl || !aiPlayer.naturalWidth)) {
                        showAiStreamState("error", "Server AI belum mengirim video. Pastikan server sedang berjalan dan alamat stream dapat diakses.");
                    }
                }, 12000);
            }

            aiPlayer.addEventListener("load", () => {
                if (!activeAiStreamUrl || aiPlayer.currentSrc !== activeAiStreamUrl) return;
                window.clearTimeout(aiStreamTimeout);
                aiStreamState.classList.add("hidden");
                aiStreamState.classList.remove("flex");
            });
            aiPlayer.addEventListener("error", () => {
                if (!activeAiStreamUrl || aiPlayer.currentSrc !== activeAiStreamUrl) return;
                window.clearTimeout(aiStreamTimeout);
                showAiStreamState("error");
            });
            retryAiStream.addEventListener("click", startAiStream);

            // TRIGGER KLIK TOMBOL CCTV
            document.querySelectorAll(".lihat-cctv").forEach(btn => {
                btn.addEventListener("click", () => {
                    const url = btn.dataset.url;
                    currentAiStreamUrl = btn.closest(".cctv-card").dataset.aiStreamUrl;
                    modalTitle.innerText = btn.dataset.title || "Live CCTV";

                    // 1. RESET tampilan ke Mode Normal saat modal baru dibuka
                    aiToggle.checked = false;
                    video.classList.remove("hidden");
                    aiPlayer.classList.add("hidden");
                    activeAiStreamUrl = "";
                    aiPlayer.removeAttribute("src");
                    aiStreamState.classList.add("hidden");
                    aiStreamState.classList.remove("flex");
                    labelNormal.classList.replace('text-gray-500', 'text-blue-400');
                    labelAI.classList.replace('text-blue-400', 'text-gray-500');

                    // 2. LOGIKA PEMUTAR VIDEO VERSI LAMA
                    video.innerHTML = "";
                    const source = document.createElement("source");
                    source.src = url;
                    source.type = "application/x-mpegURL";
                    video.appendChild(source);

                    video.load();
                    video.play();

                    modal.classList.remove("hidden");
                });
            });

            const tutupModalAksi = () => {
                window.clearTimeout(aiStreamTimeout);
                video.pause();
                video.innerHTML = "";
                activeAiStreamUrl = "";
                aiPlayer.removeAttribute("src");
                aiStreamState.classList.add("hidden");
                aiStreamState.classList.remove("flex");
                modal.classList.add("hidden");
            };

            closeModal.addEventListener("click", tutupModalAksi);
            modal.addEventListener("click", (e) => {
                if (e.target === modal) tutupModalAksi();
            });

            // --- SCRIPT SAKELAR MATA AI ---
            aiToggle.addEventListener("change", function() {
                if (this.checked) {
                    video.classList.add("hidden");
                    video.pause();
                    startAiStream();

                    labelNormal.classList.replace('text-blue-400', 'text-gray-500');
                    labelAI.classList.replace('text-gray-500', 'text-blue-400');
                } else {
                    // Mode Normal
                    video.classList.remove("hidden");
                    video.play();

                    aiPlayer.classList.add("hidden");
                    activeAiStreamUrl = "";
                    aiPlayer.removeAttribute("src");
                    window.clearTimeout(aiStreamTimeout);
                    aiStreamState.classList.add("hidden");
                    aiStreamState.classList.remove("flex");

                    labelNormal.classList.replace('text-gray-500', 'text-blue-400');
                    labelAI.classList.replace('text-blue-400', 'text-gray-500');
                }
            });

            const cards = Array.from(document.querySelectorAll(".cctv-card"));
            const searchInput = document.getElementById("cctv-search");
            const filterSelect = document.getElementById("cctv-filter");
            const visibleCount = document.getElementById("visible-count");
            const noFilterResults = document.getElementById("no-filter-results");

            function updateBadge(element, status, kind) {
                if (!element) return;

                const isSafe = status === "Aman";
                const isAlert = kind === "traffic" ? status === "Macet" : status === "Banjir";

                element.className = `status-badge mt-2 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold ${
                    isSafe ? "bg-teal-50 text-teal-700" : (isAlert ? (kind === "traffic" ? "bg-orange-50 text-orange-700" : "bg-red-50 text-red-700") : "bg-slate-200 text-slate-700")
                }`;

                const dot = element.querySelector(".status-dot");
                dot.className = `status-dot h-1.5 w-1.5 shrink-0 rounded-full ${
                    isSafe ? "bg-teal-500" : (isAlert ? (kind === "traffic" ? "bg-orange-500" : "bg-red-500") : "bg-slate-500")
                }`;
                element.querySelector(".status-label").textContent = status;
            }

            function applyFilters() {
                const query = searchInput.value.trim().toLocaleLowerCase();
                const filter = filterSelect.value;
                let shown = 0;

                cards.forEach(card => {
                    const matchesQuery = card.dataset.search.includes(query);
                    const matchesFilter = filter === "all"
                        || (filter === "safe" && card.dataset.trafficSafe === "true" && card.dataset.floodSafe === "true")
                        || (filter === "macet" && card.dataset.traffic === "Macet")
                        || (filter === "banjir" && card.dataset.flood === "Banjir");
                    const isVisible = matchesQuery && matchesFilter;

                    card.classList.toggle("hidden", !isVisible);
                    if (isVisible) shown++;
                });

                visibleCount.textContent = shown;
                noFilterResults.classList.toggle("hidden", shown > 0 || cards.length === 0);
            }

            function updateSummary() {
                document.getElementById("summary-safe").textContent = cards.filter(card =>
                    card.dataset.trafficSafe === "true" && card.dataset.floodSafe === "true"
                ).length;
                document.getElementById("summary-traffic").textContent = cards.filter(card =>
                    card.dataset.traffic === "Macet"
                ).length;
                document.getElementById("summary-flood").textContent = cards.filter(card =>
                    card.dataset.flood === "Banjir"
                ).length;
            }

            function updateAiConnectionBadge(cctvId, data) {
                const badge = document.getElementById(`ai-connection-${cctvId}`);
                if (!badge) return;

                const status = data.ai_connection_status || "waiting";
                const lastSeen = data.ai_last_seen_at ? new Date(data.ai_last_seen_at) : null;
                const time = lastSeen && !Number.isNaN(lastSeen.getTime())
                    ? lastSeen.toLocaleTimeString("id-ID")
                    : null;
                const messages = {
                    connected: `Terhubung ke server AI${time ? ` · ${time}` : ""}`,
                    stale: `Data AI terlambat${time ? ` · terakhir ${time}` : ""}`,
                    waiting: "Menunggu data pertama dari AI"
                };
                const colors = {
                    connected: "bg-teal-500",
                    stale: "bg-amber-500",
                    waiting: "bg-slate-400"
                };

                badge.dataset.state = status;
                badge.className = `mt-1 flex items-center gap-1.5 text-[10px] font-medium ${
                    status === "connected" ? "text-teal-700" : (status === "stale" ? "text-amber-700" : "text-slate-500")
                }`;
                badge.replaceChildren();
                const dot = document.createElement("span");
                dot.className = `h-1.5 w-1.5 rounded-full ${colors[status] || colors.waiting}`;
                const text = document.createElement("span");
                text.textContent = messages[status] || messages.waiting;
                badge.append(dot, text);
            }

            searchInput.addEventListener("input", applyFilters);
            filterSelect.addEventListener("change", applyFilters);

            async function updateAllCctvStatus() {
                await Promise.all(cards.map(async card => {
                    const cctvId = card.dataset.id;
                    try {
                        const response = await fetch(`/api/cctv-sensor/status/${cctvId}`, {
                            cache: "no-store",
                            headers: { "Accept": "application/json" }
                        });
                        if (!response.ok) {
                            throw new Error(`Status API returned ${response.status}`);
                        }

                        const data = await response.json();
                        const trafficStatus = data.kondisi_terkini || "Status belum tersedia";
                        const floodStatus = data.status_banjir || "Status belum tersedia";

                        card.dataset.traffic = trafficStatus;
                        card.dataset.flood = floodStatus;
                        card.dataset.trafficSafe = String(trafficStatus === "Aman");
                        card.dataset.floodSafe = String(floodStatus === "Aman");

                        const jumlahEl = document.getElementById(`jumlah-kendaraan-${cctvId}`);
                        if (jumlahEl) jumlahEl.textContent = data.jumlah_kendaraan ?? 0;
                        const jumlahSummaryEl = document.getElementById(`jumlah-kendaraan-summary-${cctvId}`);
                        if (jumlahSummaryEl) jumlahSummaryEl.textContent = data.jumlah_kendaraan ?? 0;

                        const airEl = document.getElementById(`ketinggian-air-${cctvId}`);
                        if (airEl) airEl.textContent = data.ketinggian_air_cm ?? 0;

                        updateBadge(document.getElementById(`ai-badge-${cctvId}`), trafficStatus, "traffic");
                        updateBadge(document.getElementById(`iot-badge-${cctvId}`), floodStatus, "flood");
                        updateAiConnectionBadge(cctvId, data);

                        const mapPoint = mapPoints.find(point => String(point.id) === String(cctvId));
                        const marker = mapMarkers.get(String(cctvId));
                        if (mapPoint && marker) {
                            mapPoint.kondisi_terkini = trafficStatus;
                            mapPoint.status_banjir = floodStatus;
                            mapPoint.ai_connection_status = data.ai_connection_status;
                            mapPoint.jumlah_kendaraan = data.jumlah_kendaraan ?? 0;
                            mapPoint.ketinggian_air_cm = data.ketinggian_air_cm ?? 0;
                            marker.setStyle({ fillColor: markerColor(mapPoint) });
                            marker.setPopupContent(createMapPopup(mapPoint));
                        }
                    } catch (error) {
                        console.error(`Gagal memperbarui status CCTV ${cctvId}.`, error);
                        const badge = document.getElementById(`ai-connection-${cctvId}`);
                        if (badge) {
                            badge.dataset.state = "unreachable";
                            badge.className = "mt-1 flex items-center gap-1.5 text-[10px] font-medium text-red-700";
                            badge.replaceChildren();
                            const dot = document.createElement("span");
                            dot.className = "h-1.5 w-1.5 rounded-full bg-red-500";
                            const text = document.createElement("span");
                            text.textContent = "Server status tidak dapat dihubungi";
                            badge.append(dot, text);
                        }
                    }
                })).then(() => {
                    updateSummary();
                    applyFilters();
                });
            }

            applyFilters();
            updateAllCctvStatus();
            setInterval(updateAllCctvStatus, 15000);
        });
    </script>
@endpush
