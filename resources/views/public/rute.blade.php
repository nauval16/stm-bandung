@extends('layouts.user')

@section('title', 'Peta Rute Cerdas & Peringatan Dini')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    .leaflet-container { z-index: 10 !important; font-family: inherit; }
    .fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="bg-slate-50 min-h-screen pb-12">
    <!-- Header Minimalis -->
    <div class="bg-white border-b border-gray-200 pt-8 pb-6 px-6 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                {{-- <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800 mb-3 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Live CCTV
                </a> --}}
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">AI Smart Routing</h1>
                <p class="text-gray-500 mt-2 max-w-2xl text-sm md:text-base">Sistem navigasi pintar yang secara otomatis mencari jalur alternatif jika mendeteksi genangan banjir atau kemacetan parah di rute utama Anda.</p>
            </div>
            
            <!-- Legenda Peta -->
            <div class="flex flex-wrap gap-4 bg-slate-50 border border-gray-200 px-4 py-2 rounded-xl text-xs font-bold text-gray-600 shadow-inner">
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span> Banjir</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.6)]"></span> Macet</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Aman</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-slate-400"></span> Data AI belum tersedia</div>
                <span id="map-status-label" class="border-l border-gray-300 pl-4 font-medium text-gray-500">Pembaruan otomatis aktif</span>
            </div>
        </div>
    </div>

    <!-- Layout Utama -->
    <div class="max-w-7xl mx-auto px-6 mt-8 flex flex-col lg:flex-row gap-8">
        
        <!-- Panel Kiri: Informasi & Kontrol -->
        <div class="w-full lg:w-1/3 flex flex-col gap-6">
            
            <!-- Card Instruksi -->
            <div class="bg-blue-elegant text-white p-6 rounded-2xl shadow-lg relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-accent opacity-20 rounded-full blur-2xl"></div>
                <h3 class="font-bold text-lg flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-blue-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Cara Penggunaan
                </h3>
                <ol class="text-sm text-slate-300 space-y-2 ml-4 list-decimal marker:font-bold marker:text-blue-accent">
                    <li>Klik di atas peta untuk menentukan <strong>Titik Keberangkatan</strong>.</li>
                    <li>Klik lagi untuk menentukan <strong>Titik Tujuan</strong>.</li>
                    <li>Sistem AI akan menghitung rute paling aman.</li>
                </ol>
            </div>

            <!-- Card Hasil Rute -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex-1 flex flex-col">
                <h3 class="font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center justify-between">
                    <span>Analisis Perjalanan</span>
                    <button onclick="resetMap()" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg font-semibold transition">Reset Peta</button>
                </h3>
                
                <!-- Kontainer Info Rute -->
                <div id="info" class="flex-1 flex flex-col justify-center items-center text-center p-6 bg-slate-50 rounded-xl border-2 border-dashed border-gray-200 min-h-[200px]">
                    <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <span class="text-gray-400 text-sm font-medium">Peta siap digunakan.<br>Silakan pilih titik awal pada peta.</span>
                </div>
            </div>
        </div>

        <!-- Panel Kanan: Peta Leaflet -->
        <div class="w-full lg:w-2/3 bg-white p-3 rounded-2xl shadow-sm border border-gray-200">
            <div id="map" class="w-full h-[65vh] rounded-xl z-10 bg-slate-100"></div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    // 1. Inisialisasi Peta
    const map = L.map('map').setView([-6.9848, 107.5895], 13);

    // Menggunakan OpenStreetMap standar yang 100% gratis dan aman tanpa API Key
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // 2. Mengambil Data CCTV dari Backend
    let cctvs = @json($cctvs);
    const dangerZones = [];
    const cctvMarkers = new Map();
    const hazardCircles = new Map();

    function getCctvCondition(cctv) {
        if (cctv.status_banjir === 'Banjir') return { color: '#ef4444', label: 'Banjir (IoT)', type: 'banjir' };
        if (cctv.ai_connection_status !== 'connected') return { color: '#94a3b8', label: 'Data AI belum tersedia', type: null };
        if (cctv.kondisi_terkini === 'Macet') return { color: '#f97316', label: 'Macet (AI)', type: 'macet' };
        return { color: '#10b981', label: 'Aman', type: null };
    }

    function createCctvPopup(cctv, condition) {
        const popup = document.createElement('div');
        popup.className = 'min-w-[140px]';

        if (cctv.gambar) {
            const image = document.createElement('img');
            image.src = `/storage/${cctv.gambar}`;
            image.alt = `CCTV ${cctv.jalan?.nama_jalan ?? ''}`;
            image.className = 'mb-2 h-16 w-full rounded object-cover';
            popup.appendChild(image);
        }

        const title = document.createElement('strong');
        title.className = 'mb-1 block text-xs text-gray-800';
        title.textContent = cctv.jalan?.nama_jalan ?? 'Lokasi CCTV';

        const status = document.createElement('span');
        status.className = 'block rounded px-2 py-1 text-center text-[10px] font-bold text-white';
        status.style.backgroundColor = condition.color;
        status.textContent = condition.label.toUpperCase();

        const connection = document.createElement('span');
        connection.className = 'mt-1 block text-center text-[10px] text-gray-600';
        connection.textContent = cctv.ai_connection_status === 'connected'
            ? 'Server AI terhubung'
            : 'Data AI belum diperbarui';

        popup.append(title, status, connection);
        return popup;
    }

    function updateCctvMarker(cctv) {
        const lat = Number.parseFloat(cctv.latitude);
        const lng = Number.parseFloat(cctv.longitude);
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

        const id = String(cctv.id);
        const condition = getCctvCondition(cctv);
        let marker = cctvMarkers.get(id);

        if (!marker) {
            marker = L.circleMarker([lat, lng], {
                radius: condition.type ? 9 : 6,
                fillColor: condition.color,
                color: '#fff',
                weight: 2,
                fillOpacity: 1
            }).addTo(map);
            cctvMarkers.set(id, marker);
        } else {
            marker.setLatLng([lat, lng]);
            marker.setStyle({
                radius: condition.type ? 9 : 6,
                fillColor: condition.color
            });
        }

        const popupContent = createCctvPopup(cctv, condition);
        if (marker.getPopup()) {
            marker.setPopupContent(popupContent);
        } else {
            marker.bindPopup(popupContent);
        }

        const oldCircle = hazardCircles.get(id);
        if (oldCircle) {
            map.removeLayer(oldCircle);
            hazardCircles.delete(id);
        }

        if (condition.type) {
            const circle = L.circle([lat, lng], {
                radius: condition.type === 'banjir' ? 300 : 200,
                color: condition.color,
                fillColor: condition.color,
                fillOpacity: 0.15,
                weight: 1
            }).addTo(map);
            hazardCircles.set(id, circle);
        }
    }

    function rebuildDangerZones() {
        dangerZones.length = 0;
        cctvs.forEach(cctv => {
            const circle = hazardCircles.get(String(cctv.id));
            if (!circle) return;
            dangerZones.push({
                center: circle.getLatLng(),
                radius: circle.getRadius(),
                id: String(cctv.id),
                tipe: cctv.status_banjir === 'Banjir' ? 'banjir' : 'macet'
            });
        });
    }

    cctvs.forEach(updateCctvMarker);
    rebuildDangerZones();

    // 4. Logika Routing Pintar (OSRM API)
    let routeMarkers = [];
    let routeLayer;
    let routeRequestId = 0;

    function getHazardSignature() {
        return cctvs
            .map(cctv => {
                const condition = getCctvCondition(cctv);
                return condition.type ? `${cctv.id}:${condition.type}:${cctv.latitude}:${cctv.longitude}` : null;
            })
            .filter(Boolean)
            .sort()
            .join('|');
    }

    // Desain Pin Custom
    const createIcon = (color, text) => L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="background-color:${color}; width:28px; height:28px; border-radius:50%; border:3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; color:white; font-size:12px; font-weight:bold;">${text}</div>`,
        iconSize: [28, 28], iconAnchor: [14, 14]
    });

    function distanceToSegmentMeters(point, start, end) {
        const metersPerDegree = 111320;
        const longitudeScale = Math.cos(point.lat * Math.PI / 180);
        const toMeters = coordinate => ({
            x: coordinate[0] * metersPerDegree * longitudeScale,
            y: coordinate[1] * metersPerDegree
        });
        const origin = toMeters([point.lng, point.lat]);
        const startPoint = toMeters(start);
        const endPoint = toMeters(end);
        const segmentX = endPoint.x - startPoint.x;
        const segmentY = endPoint.y - startPoint.y;
        const segmentLengthSquared = segmentX * segmentX + segmentY * segmentY;
        const fraction = segmentLengthSquared === 0
            ? 0
            : Math.max(0, Math.min(1, ((origin.x - startPoint.x) * segmentX + (origin.y - startPoint.y) * segmentY) / segmentLengthSquared));
        const closestX = startPoint.x + fraction * segmentX;
        const closestY = startPoint.y + fraction * segmentY;

        return Math.hypot(origin.x - closestX, origin.y - closestY);
    }

    function getRouteHazards(route) {
        const coordinates = route.geometry.coordinates;
        return dangerZones.filter(zone => {
            for (let index = 1; index < coordinates.length; index++) {
                if (distanceToSegmentMeters(zone.center, coordinates[index - 1], coordinates[index]) <= zone.radius) {
                    return true;
                }
            }

            return coordinates.length === 1
                && map.distance(zone.center, [coordinates[0][1], coordinates[0][0]]) <= zone.radius;
        });
    }

    function getHazardNames(zones) {
        const names = [];
        if (zones.some(zone => zone.tipe === 'banjir')) names.push('banjir');
        if (zones.some(zone => zone.tipe === 'macet')) names.push('kemacetan');
        return names;
    }

    function getRouteRoadNames(route) {
        const names = [];
        (route.legs ?? []).forEach(leg => {
            (leg.steps ?? []).forEach(step => {
                const name = step.name?.trim();
                if (name && name !== '-' && !names.includes(name)) names.push(name);
            });
        });
        return names.slice(0, 6);
    }

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[character]);
    }

    async function fetchOsrmRoutes(coordinates, alternatives) {
        const encodedCoordinates = coordinates.map(point => `${point.lng},${point.lat}`).join(';');
        const url = `https://router.project-osrm.org/route/v1/driving/${encodedCoordinates}?overview=full&geometries=geojson&steps=true&alternatives=${alternatives ? 'true' : 'false'}`;
        const response = await fetch(url);
        if (!response.ok) throw new Error(`OSRM returned ${response.status}`);
        const result = await response.json();
        if (result.code !== 'Ok') throw new Error(result.message || 'OSRM tidak menemukan rute');
        return result.routes ?? [];
    }

    function getDetourPlans(zones, from, to) {
        const plans = [];
        const directions = Array.from({ length: 8 }, (_, index) => index * Math.PI / 4);
        const orderedZones = [...zones].sort((first, second) =>
            map.distance(from, first.center) - map.distance(from, second.center)
        );

        orderedZones.slice(0, 3).forEach(zone => {
            directions.forEach(angle => {
                const radius = zone.radius + 500;
                const latitude = zone.center.lat + Math.cos(angle) * radius / 111320;
                const longitude = zone.center.lng + Math.sin(angle) * radius / (111320 * Math.cos(zone.center.lat * Math.PI / 180));
                plans.push([{ lat: latitude, lng: longitude }]);
            });
        });

        if (orderedZones.length > 1) {
            const latitudeScale = 111320;
            const originLat = from.lat;
            const originLng = from.lng * Math.cos(originLat * Math.PI / 180);
            const destinationLat = to.lat;
            const destinationLng = to.lng * Math.cos(originLat * Math.PI / 180);
            const directionX = (destinationLng - originLng) * latitudeScale;
            const directionY = (destinationLat - originLat) * latitudeScale;
            const length = Math.hypot(directionX, directionY) || 1;

            [-1, 1].forEach(side => {
                const waypoints = orderedZones.slice(0, 3).map(zone => {
                    const offset = zone.radius + 500;
                    const latitude = zone.center.lat + side * directionX / length * offset / latitudeScale;
                    const longitude = zone.center.lng - side * directionY / length * offset / (latitudeScale * Math.cos(zone.center.lat * Math.PI / 180));
                    return { lat: latitude, lng: longitude };
                });
                plans.push(waypoints);
            });
        }

        return plans;
    }

    async function findSafeRoute(from, to, requestId) {
        const routes = await fetchOsrmRoutes([from, to], true);
        if (requestId !== routeRequestId) return null;
        if (routes.length === 0) return { route: null, source: 'utama', hazards: [], primaryHazards: [] };

        const candidates = routes.map((route, index) => ({
            route,
            source: index === 0 ? 'utama' : 'alternatif',
            hazards: getRouteHazards(route)
        }));
        const primaryHazards = candidates[0].hazards;
        const safeCandidate = candidates
            .filter(candidate => candidate.hazards.length === 0)
            .sort((first, second) => first.route.duration - second.route.duration)[0];
        if (safeCandidate) return { ...safeCandidate, primaryHazards };

        const crossedZones = [];
        candidates.forEach(candidate => candidate.hazards.forEach(zone => {
            if (!crossedZones.some(existing => existing.id === zone.id)) crossedZones.push(zone);
        }));

        const plans = getDetourPlans(crossedZones, from, to);
        let nextPlan = 0;
        let bestDetour = null;
        const workers = Array.from({ length: Math.min(4, plans.length) }, async () => {
            while (nextPlan < plans.length && requestId === routeRequestId) {
                const waypoints = plans[nextPlan++];
                try {
                    const detourRoutes = await fetchOsrmRoutes([from, ...waypoints, to], false);
                    detourRoutes.forEach(route => {
                        const hazards = getRouteHazards(route);
                        if (hazards.length === 0 && (!bestDetour || route.duration < bestDetour.route.duration)) {
                            bestDetour = { route, source: 'pengalihan', hazards: [], primaryHazards };
                        }
                    });
                } catch (error) {
                    console.warn('Gagal memeriksa satu kandidat jalan pengalih.', error);
                }
            }
        });

        await Promise.all(workers);
        if (requestId !== routeRequestId) return null;
        if (bestDetour) return bestDetour;

        return { ...candidates[0], source: 'tidak-aman', primaryHazards };
    }

    async function drawRoute(from, to) {
        const requestId = ++routeRequestId;
        const info = document.getElementById('info');
        info.innerHTML = `<span class="text-blue-600 font-bold fade-in">Mencari rute aman dan jalan alternatif...</span>`;

        try {
            const result = await findSafeRoute(from, to, requestId);
            if (requestId !== routeRequestId || !result) return;
            if (!result.route) {
                info.innerHTML = `<span class="text-red-500 font-bold fade-in">Rute tidak ditemukan. Coba pilih titik awal dan tujuan yang berbeda.</span>`;
                return;
            }

            const selectedRoute = result.route;
            const coordinates = selectedRoute.geometry.coordinates.map(coordinate => [coordinate[1], coordinate[0]]);
            const isSafe = result.hazards.length === 0;
            const diverted = result.source === 'pengalihan' || result.source === 'alternatif';
            const distance = (selectedRoute.distance / 1000).toFixed(1);
            const duration = (selectedRoute.duration / 60).toFixed(0);
            const hazardNames = getHazardNames(result.primaryHazards);
            const roadNames = getRouteRoadNames(selectedRoute);

            if (routeLayer) map.removeLayer(routeLayer);
            routeLayer = L.polyline(coordinates, {
                color: isSafe ? (diverted ? '#10b981' : '#3b82f6') : '#ef4444',
                weight: 6,
                opacity: 0.85,
                dashArray: isSafe ? null : '10 10',
                lineCap: 'round'
            }).addTo(map);
            map.fitBounds(routeLayer.getBounds(), { padding: [50, 50] });

            let statusBox;
            if (!isSafe) {
                statusBox = `<div class="mt-4 rounded border-l-4 border-red-500 bg-red-50 p-4 text-left text-sm text-red-700 shadow-sm">
                    <strong class="mb-1 block">Belum ditemukan jalan alternatif yang aman.</strong>
                    Semua kandidat yang tersedia masih melewati ${hazardNames.join(' dan ') || 'zona berbahaya'}. Garis putus-putus menunjukkan rute utama berisiko.</div>`;
            } else if (diverted) {
                statusBox = `<div class="mt-4 rounded border-l-4 border-emerald-500 bg-emerald-50 p-4 text-left text-sm text-emerald-700 shadow-sm">
                    <strong class="mb-1 block">Rute dialihkan otomatis.</strong>
                    Sistem memilih jalan lain untuk menghindari ${hazardNames.join(' dan ') || 'titik bahaya'}.</div>`;
            } else {
                statusBox = `<div class="mt-4 rounded border-l-4 border-blue-500 bg-blue-50 p-4 text-left text-sm text-blue-700 shadow-sm">
                    <strong class="mb-1 block">Rute aman ditemukan.</strong>
                    Tidak terdeteksi titik bahaya pada rute ini.</div>`;
            }

            const roadsSection = roadNames.length > 0 && isSafe
                ? `<div class="mt-4 rounded-xl border border-gray-200 bg-white p-4 text-left">
                    <span class="block text-xs font-bold uppercase text-gray-500">Jalan yang dilalui</span>
                    <p class="mt-2 text-sm leading-6 text-gray-700">${roadNames.map(escapeHtml).join(' <span class="text-gray-400">?</span> ')}</p>
                </div>`
                : '';

            info.className = 'flex-1 flex flex-col justify-start p-0 bg-transparent min-h-[200px]';
            info.innerHTML = `
                <div class="w-full fade-in">
                    <div class="grid grid-cols-2 gap-3 mb-2">
                        <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl text-left">
                            <span class="block text-xs text-gray-500 font-bold uppercase mb-1">Waktu Tempuh</span>
                            <span class="text-3xl font-black text-gray-900">${duration} <span class="text-sm font-bold text-gray-500">Mnt</span></span>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl text-left">
                            <span class="block text-xs text-gray-500 font-bold uppercase mb-1">Jarak</span>
                            <span class="text-2xl font-bold text-gray-900 mt-1 block">${distance} km</span>
                        </div>
                    </div>
                    ${statusBox}
                    ${roadsSection}
                </div>
            `;
        } catch (error) {
            if (requestId !== routeRequestId) return;
            console.error('Gagal menghitung rute.', error);
            info.innerHTML = `<span class="text-red-500 font-bold fade-in">Rute gagal dimuat. Periksa koneksi lalu coba lagi.</span>`;
        }
    }

    let isRefreshingStatuses = false;

    async function refreshCctvStatuses() {
        if (isRefreshingStatuses) return;
        isRefreshingStatuses = true;

        const statusLabel = document.getElementById('map-status-label');
        try {
            const response = await fetch('/api/cctv-sensor/map-status', {
                cache: 'no-store',
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) {
                throw new Error(`Status API returned ${response.status}`);
            }

            const latestCctvs = await response.json();
            const previousSignature = getHazardSignature();
            const latestIds = new Set(latestCctvs.map(cctv => String(cctv.id)));

            cctvMarkers.forEach((marker, id) => {
                if (latestIds.has(id)) return;
                map.removeLayer(marker);
                cctvMarkers.delete(id);
                const circle = hazardCircles.get(id);
                if (circle) map.removeLayer(circle);
                hazardCircles.delete(id);
            });

            cctvs = latestCctvs;
            cctvs.forEach(updateCctvMarker);
            rebuildDangerZones();

            if (previousSignature !== getHazardSignature() && routeMarkers.length === 2) {
                drawRoute(routeMarkers[0].getLatLng(), routeMarkers[1].getLatLng());
            }

            statusLabel.textContent = `Status diperbarui ${new Date().toLocaleTimeString('id-ID')}`;
            statusLabel.classList.remove('text-red-500');
            statusLabel.classList.add('text-gray-500');
        } catch (error) {
            console.error('Gagal memperbarui status CCTV pada peta rute.', error);
            statusLabel.textContent = 'Gagal memperbarui status';
            statusLabel.classList.remove('text-gray-500');
            statusLabel.classList.add('text-red-500');
        } finally {
            isRefreshingStatuses = false;
        }
    }

    refreshCctvStatuses();
    window.setInterval(refreshCctvStatuses, 15000);

    // Interaksi Peta
    map.on('click', function(e) {
        if (routeMarkers.length >= 2) resetMap();

        const color = routeMarkers.length === 0 ? '#3b82f6' : '#10b981'; 
        const label = routeMarkers.length === 0 ? 'A' : 'B'; 
        
        const marker = L.marker(e.latlng, { icon: createIcon(color, label) }).addTo(map);
        routeMarkers.push(marker);

        if (routeMarkers.length === 1) {
            document.getElementById('info').innerHTML = `<span class="text-blue-600 font-semibold fade-in">Titik awal (A) dipilih.<br>Klik lokasi tujuan (B).</span>`;
        }

        if (routeMarkers.length === 2) {
            drawRoute(routeMarkers[0].getLatLng(), routeMarkers[1].getLatLng());
        }
    });

    window.resetMap = function() {
        routeRequestId++;
        routeMarkers.forEach(m => map.removeLayer(m));
        routeMarkers = [];
        if (routeLayer) map.removeLayer(routeLayer);
        routeLayer = null;
        
        document.getElementById('info').className = "flex-1 flex flex-col justify-center items-center text-center p-6 bg-slate-50 rounded-xl border-2 border-dashed border-gray-200 min-h-[200px]";
        document.getElementById('info').innerHTML = `
            <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            <span class="text-gray-400 text-sm font-medium fade-in">Peta siap digunakan.<br>Silakan pilih titik awal pada peta.</span>`;
    };
</script>
@endpush