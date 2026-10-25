<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin STM') - Smart Traffic Management</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'blue-elegant': '#0A2540',
                        'blue-hover': '#113A64',
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR (Solid & Clean) -->
    <aside class="w-64 bg-blue-elegant text-white flex flex-col z-20">
        <div class="h-16 flex items-center px-6 border-b border-white/10">
            <h1 class="text-xl font-bold tracking-wide">STM <span class="font-normal text-gray-300">Admin</span></h1>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-hover text-white font-medium' : 'hover:bg-white/5 text-gray-300 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.jalan.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.jalan.*') ? 'bg-blue-hover text-white font-medium' : 'hover:bg-white/5 text-gray-300 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V7.618a1 1 0 01.553-.894L9 4m6 16l5.447-2.724A1 1 0 0021 16.382V7.618a1 1 0 00-.553-.894L15 4m0 16V4m-6 0v16">
                    </path>
                </svg>
                Manajemen Jalan
            </a>

            <a href="{{ route('admin.cctv.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.cctv.*') ? 'bg-blue-hover text-white font-medium' : 'hover:bg-white/5 text-gray-300 hover:text-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h11a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z">
                    </path>
                </svg>
                Data Kamera & IoT
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- TOP NAVBAR (Flat White) -->
        <header class="h-16 bg-white flex items-center justify-between px-6 border-b border-gray-200 z-10">
            <h2 class="text-lg font-semibold text-gray-800">@yield('page_heading', 'Dashboard')</h2>

            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-600">Admin Pusat</span>
                <div
                    class="w-8 h-8 rounded-full bg-blue-elegant text-white flex items-center justify-center text-sm font-bold">
                    AP
                </div>
            </div>
        </header>

        <!-- DYNAMIC CONTENT -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>

</html>
