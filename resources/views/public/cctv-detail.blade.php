@extends('layouts.user')

@section('title', 'Detail Analitik CCTV - ATCS Mode')

@push('styles')
<!-- CSS Leaflet Peta -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Styling agar donat terlihat bulat sempurna */
    .apexcharts-datalabel-value { font-weight: 800 !important; font-size: 24px !important; fill: #1f2937 !important; }
</style>
@endpush

@section('content')
<div class="bg-gray-100 min-h-screen py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        
        <!-- BAGIAN 1: PETA ATCS (FULL WIDTH) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="p-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-sm text-gray-700 tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    Peta Lokasi: Ruas {{ $cctv->jalan->nama_jalan ?? '-' }}
                </h3>
                <a href="{{ url('/cctv') }}" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded transition">Kembali</a>
            </div>
            <div id="map" class="w-full h-64 z-0"></div>
        </div>

        <!-- BAGIAN 2: VIDEO & GRAFIK 5 MENITAN -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            
            <!-- Video Player ATCS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                <div class="p-3 border-b border-gray-200 flex justify-between items-center bg-gray-900 text-white">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                        <h3 class="font-bold text-xs tracking-wider">PANTAUAN KAMERA</h3>
                    </div>
                    <span id="ai-connection-status" class="text-[10px] font-medium text-slate-300">Memeriksa koneksi server AI…</span>
                    
                    <div class="flex items-center gap-2 bg-gray-800 px-3 py-1 rounded-full border border-gray-700">
                        <span id="labelNormal" class="text-[10px] font-bold text-blue-400">Normal</span>
                        <div class="relative inline-block w-8 align-middle select-none">
                            <input type="checkbox" id="aiToggle" class="peer sr-only"/>
                            <div class="w-8 h-4 bg-gray-600 rounded-full peer-focus:outline-none peer-checked:bg-blue-600 cursor-pointer" onclick="document.getElementById('aiToggle').click()"></div>
                            <div class="absolute top-0.5 left-0.5 bg-white w-3 h-3 rounded-full transition-transform peer-checked:translate-x-4 pointer-events-none"></div>
                        </div>
                        <span id="labelAI" class="text-[10px] font-bold text-gray-500">Mata AI</span>
                    </div>
                </div>
                
                <div class="aspect-video w-full bg-black relative flex justify-center items-center">
                    <video id="cctvPlayer" class="w-full h-full object-contain absolute inset-0 z-10" controls autoplay muted playsinline>
                        <source src="{{ $cctv->link_akses }}" type="application/x-mpegURL">
                    </video>
                    <img id="aiPlayer" src="" alt="Stream kamera dengan analisis AI" class="w-full h-full object-contain absolute inset-0 z-20 hidden bg-black">
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

            <!-- Bar Chart 5 Menitan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex justify-between items-center border-b pb-2 mb-2">
                    <h3 class="font-bold text-gray-800 text-sm">Volume Kendaraan (kend./5 menit)</h3>
                    <span class="text-[10px] text-gray-500">Diupdate: {{ date('H:i:s') }} WIB</span>
                </div>
                <div id="barChart" class="w-full h-64"></div>
            </div>
        </div>

        <!-- BAGIAN 3: DATA 5 MENIT TERAKHIR (DENGAN APEXCHARTS) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
            <h3 class="font-bold text-gray-800 text-sm border-b pb-2 mb-4">Volume Kendaraan 5 Menit Terakhir (kend./5 menit)</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Arah Normal -->
                <div class="bg-blue-50/30 rounded-xl p-4 border border-blue-100">
                    <h4 class="text-center font-bold text-blue-700 text-xs uppercase mb-2">Arah Normal</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2">
                        <div id="fiveMinuteNormalMotor"></div>
                        <div id="fiveMinuteNormalMobil"></div>
                        <div id="fiveMinuteNormalSedang"></div>
                        <div id="fiveMinuteNormalBus"></div>
                        <div id="fiveMinuteNormalTruk"></div>
                    </div>
                    <div class="mt-4 text-center">
                        <span class="text-xs text-gray-500 font-bold uppercase">Total Kendaraan Normal</span>
                        <div class="text-3xl font-black text-blue-600">{{ $fiveMinute['normal']['total'] ?? 0 }}</div>
                    </div>
                </div>

                <!-- Arah Opposite -->
                <div class="bg-teal-50/30 rounded-xl p-4 border border-teal-100">
                    <h4 class="text-center font-bold text-teal-700 text-xs uppercase mb-2">Arah Opposite</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2">
                        <div id="fiveMinuteOppositeMotor"></div>
                        <div id="fiveMinuteOppositeMobil"></div>
                        <div id="fiveMinuteOppositeSedang"></div>
                        <div id="fiveMinuteOppositeBus"></div>
                        <div id="fiveMinuteOppositeTruk"></div>
                    </div>
                    <div class="mt-4 text-center">
                        <span class="text-xs text-gray-500 font-bold uppercase">Total Kendaraan Opposite</span>
                        <div class="text-3xl font-black text-teal-600">{{ $fiveMinute['opposite']['total'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 4: TOTAL HARIAN -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-bold text-gray-800 text-sm border-b pb-2 mb-4">Total Volume Kendaraan Harian (kend./hari)</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                <div id="dailyMotor"></div>
                <div id="dailyMobil"></div>
                <div id="dailySedang"></div>
                <div id="dailyBus"></div>
                <div id="dailyTruk"></div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<!-- Map & Chart JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. INIT LEAFLET MAP
    const lat = {{ $cctv->latitude ?? -6.914744 }};
    const lng = {{ $cctv->longitude ?? 107.609810 }};
    var map = L.map('map').setView([lat, lng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    
    L.marker([lat, lng]).addTo(map)
        .bindPopup('<b>{{ $cctv->jalan->nama_jalan ?? 'CCTV' }}</b><br>Titik Lokasi Kamera')
        .openPopup();

    // 2. KONEKSI STREAM AI
    const video = document.getElementById("cctvPlayer");
    const aiPlayer = document.getElementById("aiPlayer");
    const aiStreamState = document.getElementById("aiStreamState");
    const aiStreamSpinner = document.getElementById("aiStreamSpinner");
    const aiStreamTitle = document.getElementById("aiStreamTitle");
    const aiStreamMessage = document.getElementById("aiStreamMessage");
    const retryAiStream = document.getElementById("retryAiStream");
    const aiConnectionStatus = document.getElementById("ai-connection-status");
    const aiStreamUrl = @json($aiStreamUrl);
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
            ? "Menunggu frame pertama dari stream."
            : "Periksa koneksi server AI lalu coba lagi.");
    }

    function startAiStream() {
        if (!aiStreamUrl) {
            showAiStreamState("error", "Alamat stream AI belum dikonfigurasi.");
            return;
        }

        window.clearTimeout(aiStreamTimeout);
        showAiStreamState("loading");
        aiPlayer.classList.remove("hidden");
        const streamUrl = new URL(aiStreamUrl, window.location.href);
        streamUrl.searchParams.set("_", Date.now().toString());
        activeAiStreamUrl = streamUrl.href;
        aiPlayer.removeAttribute("src");
        aiPlayer.src = activeAiStreamUrl;
        const attemptUrl = activeAiStreamUrl;
        aiStreamTimeout = window.setTimeout(() => {
            if (activeAiStreamUrl === attemptUrl && (aiPlayer.currentSrc !== attemptUrl || !aiPlayer.naturalWidth)) {
                showAiStreamState("error", "Server AI belum mengirim video. Pastikan server berjalan dan alamat stream dapat diakses dari browser.");
            }
        }, 12000);
    }

    function hideAiStream() {
        window.clearTimeout(aiStreamTimeout);
        activeAiStreamUrl = "";
        aiPlayer.removeAttribute("src");
        aiPlayer.classList.add("hidden");
        aiStreamState.classList.add("hidden");
        aiStreamState.classList.remove("flex");
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

    // 3. STATUS KONEKSI DATA AI
    async function refreshAiConnectionStatus() {
        try {
            const response = await fetch(@json(url('/api/cctv-sensor/status/' . $cctv->id)), {
                cache: "no-store",
                headers: { "Accept": "application/json" }
            });
            if (!response.ok) throw new Error(`Status API returned ${response.status}`);

            const data = await response.json();
            const lastSeen = data.ai_last_seen_at ? new Date(data.ai_last_seen_at) : null;
            const time = lastSeen && !Number.isNaN(lastSeen.getTime())
                ? lastSeen.toLocaleTimeString("id-ID")
                : null;
            const labels = {
                connected: `Terhubung ke server AI${time ? ` · ${time}` : ""}`,
                stale: `Data AI terlambat${time ? ` · terakhir ${time}` : ""}`,
                waiting: "Menunggu data pertama dari server AI"
            };
            const status = data.ai_connection_status || "waiting";
            aiConnectionStatus.textContent = labels[status] || labels.waiting;
            aiConnectionStatus.className = `text-[10px] font-medium ${
                status === "connected" ? "text-teal-300" : (status === "stale" ? "text-amber-300" : "text-slate-300")
            }`;
        } catch (error) {
            console.error("Gagal memeriksa koneksi server AI.", error);
            aiConnectionStatus.textContent = "Server status tidak dapat dihubungi";
            aiConnectionStatus.className = "text-[10px] font-medium text-red-300";
        }
    }

    refreshAiConnectionStatus();
    window.setInterval(refreshAiConnectionStatus, 15000);

    // 4. SAKELAR AI
    document.getElementById("aiToggle").addEventListener("change", function() {
        if (this.checked) {
            video.classList.add("hidden");
            video.pause();
            startAiStream();
        } else {
            video.classList.remove("hidden");
            video.play().catch(error => console.warn("Video kamera tidak dapat diputar otomatis.", error));
            hideAiStream();
        }
    });

    // 5. BAR CHART 5 MENITAN (DATA ASLI DARI DATABASE)
    var barOptions = {
        series: [{
            name: 'Normal',
            data: {!! json_encode($chartNormal ?? []) !!}
        }, {
            name: 'Opposite',
            data: {!! json_encode($chartOpposite ?? []) !!}
        }],
        chart: { type: 'bar', height: 260, toolbar: { show: false } },
        colors: ['#2563EB', '#0D9488'],
        dataLabels: { enabled: false },
        xaxis: { categories: {!! json_encode($chartCategories ?? []) !!} },
        legend: { position: 'top', horizontalAlign: 'right' }
    };
    new ApexCharts(document.querySelector("#barChart"), barOptions).render();

    // 6. FUNGSI PEMBUAT DONUT ABSOLUT
    function createAbsoluteDonut(val, maxKapasitas, label, color) {
        let visualPercent = maxKapasitas > 0 ? (val / maxKapasitas) * 100 : 0;
        if(visualPercent > 100) visualPercent = 100;
        if(val > 0 && visualPercent < 5) visualPercent = 5; 

        return {
            series: [visualPercent],
            chart: { type: 'radialBar', height: 180, sparkline: { enabled: true } },
            plotOptions: {
                radialBar: {
                    hollow: { size: '60%' },
                    track: { background: '#f3f4f6' },
                    dataLabels: {
                        name: { show: true, fontSize: '10px', offsetY: 25, color: '#6b7280' },
                        value: { 
                            show: true, offsetY: -5,
                            formatter: function () { return val.toString(); } 
                        }
                    }
                }
            },
            labels: [label],
            colors: [color],
            stroke: { lineCap: 'round' }
        };
    }

    // 7. RENDER DATA 5 MENIT DENGAN PENYESUAIAN NAMA VARIABEL
    const MAX_FIVE_MINUTES = 250;
    // ARAH NORMAL
    new ApexCharts(document.querySelector("#fiveMinuteNormalMotor"), createAbsoluteDonut({{ $fiveMinute['normal']['sepeda_motor'] ?? 0 }}, MAX_FIVE_MINUTES, 'S. Motor', '#2563EB')).render();
    new ApexCharts(document.querySelector("#fiveMinuteNormalMobil"), createAbsoluteDonut({{ $fiveMinute['normal']['mobil_penumpang'] ?? 0 }}, MAX_FIVE_MINUTES, 'Mobil', '#3B82F6')).render();
    new ApexCharts(document.querySelector("#fiveMinuteNormalSedang"), createAbsoluteDonut({{ $fiveMinute['normal']['kendaraan_sedang'] ?? 0 }}, MAX_FIVE_MINUTES, 'Sedang', '#60A5FA')).render();
    new ApexCharts(document.querySelector("#fiveMinuteNormalBus"), createAbsoluteDonut({{ $fiveMinute['normal']['bus_besar'] ?? 0 }}, MAX_FIVE_MINUTES, 'Bus Besar', '#93C5FD')).render();
    new ApexCharts(document.querySelector("#fiveMinuteNormalTruk"), createAbsoluteDonut({{ $fiveMinute['normal']['truk_barang'] ?? 0 }}, MAX_FIVE_MINUTES, 'Truk', '#BFDBFE')).render();

    // ARAH OPPOSITE
    new ApexCharts(document.querySelector("#fiveMinuteOppositeMotor"), createAbsoluteDonut({{ $fiveMinute['opposite']['sepeda_motor'] ?? 0 }}, MAX_FIVE_MINUTES, 'S. Motor', '#0D9488')).render();
    new ApexCharts(document.querySelector("#fiveMinuteOppositeMobil"), createAbsoluteDonut({{ $fiveMinute['opposite']['mobil_penumpang'] ?? 0 }}, MAX_FIVE_MINUTES, 'Mobil', '#14B8A6')).render();
    new ApexCharts(document.querySelector("#fiveMinuteOppositeSedang"), createAbsoluteDonut({{ $fiveMinute['opposite']['kendaraan_sedang'] ?? 0 }}, MAX_FIVE_MINUTES, 'Sedang', '#2DD4BF')).render();
    new ApexCharts(document.querySelector("#fiveMinuteOppositeBus"), createAbsoluteDonut({{ $fiveMinute['opposite']['bus_besar'] ?? 0 }}, MAX_FIVE_MINUTES, 'Bus Besar', '#5EEAD4')).render();
    new ApexCharts(document.querySelector("#fiveMinuteOppositeTruk"), createAbsoluteDonut({{ $fiveMinute['opposite']['truk_barang'] ?? 0 }}, MAX_FIVE_MINUTES, 'Truk', '#99F6E4')).render();

    // 8. RENDER DATA HARIAN TOTAL KEDUA ARAH
    const MAX_DAY = 5000;
    const harianMotor = {{ ($daily['normal']['sepeda_motor'] ?? 0) + ($daily['opposite']['sepeda_motor'] ?? 0) }};
    const harianMobil = {{ ($daily['normal']['mobil_penumpang'] ?? 0) + ($daily['opposite']['mobil_penumpang'] ?? 0) }};
    const harianSedang = {{ ($daily['normal']['kendaraan_sedang'] ?? 0) + ($daily['opposite']['kendaraan_sedang'] ?? 0) }};
    const harianBus = {{ ($daily['normal']['bus_besar'] ?? 0) + ($daily['opposite']['bus_besar'] ?? 0) }};
    const harianTruk = {{ ($daily['normal']['truk_barang'] ?? 0) + ($daily['opposite']['truk_barang'] ?? 0) }};

    new ApexCharts(document.querySelector("#dailyMotor"), createAbsoluteDonut(harianMotor, MAX_DAY, 'Sepeda Motor', '#9333EA')).render();
    new ApexCharts(document.querySelector("#dailyMobil"), createAbsoluteDonut(harianMobil, MAX_DAY, 'Mobil Penumpang', '#E11D48')).render();
    new ApexCharts(document.querySelector("#dailySedang"), createAbsoluteDonut(harianSedang, MAX_DAY, 'Kendaraan Sedang', '#EA580C')).render();
    new ApexCharts(document.querySelector("#dailyBus"), createAbsoluteDonut(harianBus, MAX_DAY, 'Bus Besar', '#0F766E')).render();
    new ApexCharts(document.querySelector("#dailyTruk"), createAbsoluteDonut(harianTruk, MAX_DAY, 'Truk Barang', '#65A30D')).render();
    
    console.log("Isi Data 5 Menit:", @json($fiveMinute));
console.log("Isi Data Harian:", @json($daily));
});
</script>
@endpush