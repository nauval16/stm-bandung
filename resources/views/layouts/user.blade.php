<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'STM - Smart Traffic Management')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'blue-elegant': '#0A2540',
                        'blue-accent': '#38BDF8',
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans antialiased flex flex-col min-h-screen">

    <!-- NAVBAR PUBLIK -->
    <nav class="bg-blue-elegant text-white sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-[72px] flex items-center justify-between">
            
            <!-- Logo & Brand -->
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3 rounded-xl transition hover:opacity-90" aria-label="STM Bandung - Beranda">
                <span class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-xl bg-white p-1 shadow-sm ring-1 ring-white/20">
                    <img src="{{ asset('images/stm-logo.png') }}" alt="" class="h-full w-full object-contain">
                </span>
                <span class="flex flex-col">
                    <span class="text-lg font-bold leading-tight tracking-wide text-white sm:text-xl">STM <span class="font-semibold text-blue-accent">Bandung</span></span>
                    <span class="hidden text-[9px] font-semibold uppercase tracking-[0.16em] text-slate-300 sm:block">Smart Traffic Management</span>
                </span>
            </a>
            
            <!-- Menu Navigasi Desktop -->
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-blue-accent' : 'text-slate-300 hover:text-white' }} transition">
                    Beranda
                </a>
                <a href="{{ route('cctv') }}" class="{{ request()->routeIs('cctv') || request()->is('cctv/detail/*') ? 'text-blue-accent' : 'text-slate-300 hover:text-white' }} transition">
                    Live CCTV
                </a>
                <a href="{{ route('rute') }}" class="{{ request()->routeIs('rute') ? 'text-blue-accent' : 'text-slate-300 hover:text-white' }} transition">
                    Rute Cerdas
                </a>
                <a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'text-blue-accent' : 'text-slate-300 hover:text-white' }} transition">
                    Tentang Kami
                </a>
            </div>

            <!-- Bagian Kanan (Tombol Admin & Hamburger Mobile) -->
            <div class="flex items-center gap-3">
                <!-- Tombol Admin (Desktop) -->
                <a href="{{ route('admin.jalan.index') }}" class="hidden md:block text-xs font-bold bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full transition">
                    Login Admin
                </a>

                <!-- Tombol Hamburger (Mobile) -->
                <button id="mobileMenuBtn" class="md:hidden flex items-center justify-center p-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition focus:outline-none">
                    <!-- Ikon Garis Tiga (Menu) -->
                    <svg id="iconMenu" class="w-6 h-6 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <!-- Ikon X (Close) - Hidden by default -->
                    <svg id="iconClose" class="w-6 h-6 hidden transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- MOBILE MENU DROPDOWN (Tersembunyi secara default) -->
        <!-- Menggunakan absolute agar menutupi konten bawahnya dengan rapi -->
        <div id="mobileDropdown" class="hidden md:hidden absolute w-full bg-blue-elegant border-t border-white/10 shadow-2xl transition-all duration-300 origin-top transform -translate-y-2 opacity-0">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-lg text-base font-medium transition {{ request()->routeIs('home') ? 'bg-blue-accent/10 text-blue-accent' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    Beranda
                </a>
                <a href="{{ route('cctv') }}" class="block px-4 py-3 rounded-lg text-base font-medium transition {{ request()->routeIs('cctv') || request()->is('cctv/detail/*') ? 'bg-blue-accent/10 text-blue-accent' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    Live CCTV
                </a>
                <a href="{{ route('rute') }}" class="block px-4 py-3 rounded-lg text-base font-medium transition {{ request()->routeIs('rute') ? 'bg-blue-accent/10 text-blue-accent' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    Rute Cerdas
                </a>
                <a href="{{ route('tentang') }}" class="block px-4 py-3 rounded-lg text-base font-medium transition {{ request()->routeIs('tentang') ? 'bg-blue-accent/10 text-blue-accent' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    Tentang Kami
                </a>
                
                <!-- Pemisah -->
                <div class="pt-4 mt-2 border-t border-white/10">
                    <a href="{{ route('admin.jalan.index') }}" class="flex justify-center w-full px-4 py-3 text-sm font-bold text-white bg-white/10 rounded-lg hover:bg-white/20 transition">
                        Login Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- KONTEN DINAMIS -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-400 py-8 text-center text-sm mt-auto">
        <p>&copy; 2026 Smart Traffic Management - Kab Bandung. Dibuat untuk pelayanan publik yang lebih baik.</p>
    </footer>

    <!-- SCRIPT UNTUK INTERAKSI MOBILE MENU -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('mobileMenuBtn');
            const menu = document.getElementById('mobileDropdown');
            const iconMenu = document.getElementById('iconMenu');
            const iconClose = document.getElementById('iconClose');

            btn.addEventListener('click', function () {
                const isHidden = menu.classList.contains('hidden');

                if (isHidden) {
                    // Tampilkan Menu (Buka)
                    menu.classList.remove('hidden');
                    // Diberi delay sedikit agar animasi Tailwind berjalan (trick browser rendering)
                    setTimeout(() => {
                        menu.classList.remove('-translate-y-2', 'opacity-0');
                        menu.classList.add('translate-y-0', 'opacity-100');
                    }, 10);
                    
                    // Ganti Ikon ke X
                    iconMenu.classList.add('hidden');
                    iconClose.classList.remove('hidden');
                } else {
                    // Sembunyikan Menu (Tutup) dengan animasi
                    menu.classList.remove('translate-y-0', 'opacity-100');
                    menu.classList.add('-translate-y-2', 'opacity-0');
                    
                    // Tunggu durasi animasi selesai (300ms) baru hilangkan elemennya
                    setTimeout(() => {
                        menu.classList.add('hidden');
                    }, 300);

                    // Ganti Ikon ke Garis Tiga
                    iconClose.classList.add('hidden');
                    iconMenu.classList.remove('hidden');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>