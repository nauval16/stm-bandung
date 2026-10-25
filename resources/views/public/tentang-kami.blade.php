@extends('layouts.user')

@section('title', 'Tentang Kami - STM Bandung')

@section('content')
<div class="bg-gray-50 min-h-screen pt-12 pb-24">
    <div class="max-w-6xl mx-auto px-6">
        
        <!-- Header Halaman -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4">Tentang Inovasi Kami</h1>
            <p class="text-gray-500 max-w-2xl mx-auto text-lg">
                Mengenal lebih dekat teknologi di balik Smart Traffic Management (STM) Bandung dan tim yang mengembangkannya.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100">
            
            <!-- Sisi Kiri: Narasi Inovasi -->
            <div class="lg:col-span-7">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                    Latar Belakang
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-4 leading-tight">
                    Mewujudkan Tata Kelola Jalan Raya Berbasis Data Presisi.
                </h2>
                <p class="text-gray-600 mt-5 text-sm sm:text-base leading-relaxed">
                    STM Bandung dirancang untuk menjawab tantangan pemantauan lalu lintas konvensional yang masih serba manual. Dengan memadukan kecerdasan buatan (Computer Vision) dan jaringan sensor IoT, sistem ini mampu menyajikan analitik lalu lintas harian serta deteksi dini genangan air secara otomatis dan terukur.
                </p>
                
                <!-- Ringkasan Angka / Stat Sederhana -->
                <div class="grid grid-cols-3 gap-6 mt-10 pt-10 border-t border-gray-100">
                    <div>
                        <p class="text-3xl font-black text-blue-600">99%</p>
                        <p class="text-sm text-gray-500 font-medium mt-1">Uptime Stream</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-teal-600">4 Kelas</p>
                        <p class="text-sm text-gray-500 font-medium mt-1">Deteksi AI YOLO</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-purple-600">&lt; 3 Detik</p>
                        <p class="text-sm text-gray-500 font-medium mt-1">Latensi IoT Cepat</p>
                    </div>
                </div>
            </div>

            <!-- Sisi Kanan: Poin Fokus Sistem -->
            <div class="lg:col-span-5 bg-gray-50 p-8 rounded-2xl border border-gray-100 space-y-8">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-md">
                        01
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-base">Otomatisasi AI YOLOv8</h4>
                        <p class="text-gray-500 text-sm mt-1.5 leading-relaxed">Penghitungan volume kendaraan dua arah otomatis tanpa perlu pemantauan manual oleh petugas lapangan.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-md">
                        02
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-base">Sensor IoT Multi-Titik</h4>
                        <p class="text-gray-500 text-sm mt-1.5 leading-relaxed">Integrasi mikrokontroler di area rawan banjir untuk peringatan dini genangan air secara real-time.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-md">
                        03
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-base">Visualisasi Big Data</h4>
                        <p class="text-gray-500 text-sm mt-1.5 leading-relaxed">Grafik analitik yang memisahkan volume kendaraan per jam dan per kategori sebagai dasar pengambilan keputusan.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection