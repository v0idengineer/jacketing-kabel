<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logoapp.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('logoapp.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('logoapp.png') }}">
    <title>{{ $title ?? 'RnD Jacketing' }}</title>
</head>

@php
// Satu sumber menu untuk desktop (sidebar) & mobile (topbar dropdown)
$items = [
['route'=>'dashboard','label'=>'Dashboard','icon'=>'M3 12l7-7 7 7M13 5v14'],
['route'=>'history','label'=>'Riwayat','icon'=>'M8 7V3m8 4V3M5 21h14M7 11h10'],
// Tambah item lain di sini bila perlu...
];
@endphp

<body class="min-h-screen bg-gray-100"
    x-data="{ open: false, mobileMenu:false, profileOpen:false }"
    @keydown.escape.window="mobileMenu=false; profileOpen=false">

    <!-- ======== TOP NAV (Mobile) ======== -->
    <header class="md:hidden sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200">
        <div class="px-4 h-14 flex items-center justify-between">
            <!-- Brand -->
            <div class="flex items-center gap-2">
                <img src="{{ asset('logoapp.png') }}" alt="Logo" class="h-8 w-8 rounded object-contain">
                <span class="font-semibold text-slate-900">RnD Jacketing</span>
            </div>

            <div class="flex items-center gap-2">
                <!-- User miniature -->
                @auth
                <button @click="profileOpen = !profileOpen"
                    class="h-8 w-8 grid place-items-center rounded bg-slate-200 text-slate-700 ring-1 ring-slate-300/60"
                    aria-label="Akun">
                    {{ strtoupper(substr(Auth::user()->name,0,2)) }}
                </button>
                @endauth

                <!-- Hamburger -->
                <button @click="mobileMenu = !mobileMenu"
                    class="ml-1 p-2 rounded-md text-slate-700 hover:bg-slate-100"
                    aria-label="Menu">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Dropdown menu mobile -->
        <div x-show="mobileMenu" x-cloak
            x-transition.origin.top
            class="border-t border-slate-200 bg-white">
            <nav class="px-2 py-2 space-y-1">
                @foreach($items as $it)
                <a href="{{ route($it['route']) }}"
                    @click="mobileMenu=false"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg
                              {{ request()->routeIs($it['route']) ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-50' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $it['icon'] }}" />
                    </svg>
                    <span class="font-medium">{{ $it['label'] }}</span>
                </a>
                @endforeach
            </nav>

            @auth
            <div class="px-2 pb-3 pt-2 border-t border-slate-200">
                <div class="px-3 py-2">
                    <p class="text-sm font-semibold text-slate-900 break-words">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 break-words">{{ Auth::user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="px-2">
                    @csrf
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2a1 1 0 011 1v8a1 1 0 11-2 0V3a1 1 0 011-1zm5.657 3.343a1 1 0 011.414 1.414A8 8 0 1112 4a1 1 0 110-2 10 10 0 105.657 3.343z" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
            @endauth
        </div>

        <!-- Panel profil kecil (opsional terpisah dari menu) -->
        <div x-show="profileOpen" x-cloak
            @click.outside="profileOpen=false"
            x-transition.origin.top
            class="absolute right-2 top-14 w-64 rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden z-50">
            <div class="p-3 space-y-2">
                <div class="pt-1">
                    <p class="text-sm font-semibold text-slate-900 break-words">{{ Auth::user()->name ?? '' }}</p>
                    <p class="text-xs text-slate-500 break-words">{{ Auth::user()->email ?? '' }}</p>
                </div>
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2a1 1 0 011 1v8a1 1 0 11-2 0V3a1 1 0 011-1zm5.657 3.343a1 1 0 011.414 1.414A8 8 0 1112 4a1 1 0 110-2 10 10 0 105.657 3.343z" />
                        </svg>
                        Logout
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </header>
    <!-- ======== /TOP NAV (Mobile) ======== -->

    <div class="flex">
        <!-- ======== SIDEBAR (Desktop) ======== -->
        <aside
            :class="open ? 'w-64' : 'w-16'"
            class="hidden md:flex relative z-40 h-screen sticky top-0 bg-white border-r border-slate-200 transition-all duration-300 flex-col overflow-visible">

            <!-- Header sidebar -->
            <div class="px-3 py-3">
                <!-- Expanded -->
                <div class="flex items-center gap-2" x-show="open" x-cloak>
                    <img src="{{ asset('logoapp.png') }}" alt="Logo" class="h-8 w-8 rounded object-contain">
                    <button @click="open=!open" class="ml-auto text-slate-600 hover:text-slate-900" aria-label="Toggle sidebar">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Collapsed -->
                <div class="flex items-center justify-center" x-show="!open" x-cloak>
                    <button @click="open=!open" class="text-slate-600 hover:text-slate-900" aria-label="Toggle sidebar">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-2">
                <div class="h-px bg-slate-200"></div>
            </div>

            <!-- Menu -->
            <nav class="flex-1 py-2">
                @foreach($items as $it)
                <div x-data="{h:false}" @mouseenter="h=true" @mouseleave="h=false" class="relative">
                    <a href="{{ route($it['route']) }}"
                        class="mx-2 my-1 flex items-center gap-3 px-3 py-2 rounded-lg
                              {{ request()->routeIs($it['route']) ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $it['icon'] }}" />
                        </svg>
                        <span x-show="open" x-cloak class="font-medium">{{ $it['label'] }}</span>
                    </a>

                    <!-- Tooltip saat collapsed -->
                    <div x-show="!open && h" x-cloak
                        class="pointer-events-none absolute left-full top-1/2 -translate-y-1/2 ml-2 z-[60] whitespace-nowrap rounded-lg bg-slate-900 text-white text-xs px-2 py-1.5 shadow-lg">
                        {{ $it['label'] }}
                    </div>
                </div>
                @endforeach
            </nav>

            <div class="px-2 pb-3">
                <div class="h-px bg-slate-200 mb-2"></div>

                @auth
                <div class="relative" x-data="{panel:false}">
                    <!-- ROW user -->
                    <button @click="panel=!panel"
                        class="w-full flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-50 focus:outline-none">
                        <div class="h-8 w-8 rounded bg-slate-200 grid place-items-center text-slate-700 ring-1 ring-slate-300/60">
                            {{ strtoupper(substr(Auth::user()->name,0,2)) }}
                        </div>
                        <div x-show="open" x-cloak class="leading-tight text-left min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </button>

                    <!-- PANEL (expanded) -->
                    <div x-show="open && panel" x-cloak @click.outside="panel=false"
                        class="absolute inset-x-0 bottom-full mb-2 rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden z-50"
                        x-transition.origin.bottom>
                        <div class="p-3 space-y-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2a1 1 0 011 1v8a1 1 0 11-2 0V3a1 1 0 011-1zm5.657 3.343a1 1 0 011.414 1.414A8 8 0 1112 4a1 1 0 110-2 10 10 0 105.657 3.343z" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                            <div class="pt-1">
                                <p class="text-sm font-semibold text-slate-900 break-words">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 break-words">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- PANEL (collapsed) -->
                    <div x-show="!open && panel" x-cloak @click.outside="panel=false"
                        class="fixed z-[60] left-[4.5rem] bottom-4 w-60 rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden"
                        x-transition.origin.bottom>
                        <div class="p-3 space-y-2 max-h-[calc(100vh-2rem)] overflow-auto">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2a1 1 0 011 1v8a1 1 0 11-2 0V3a1 1 0 011-1zm5.657 3.343a1 1 0 011.414 1.414A8 8 0 1112 4a1 1 0 110-2 10 10 0 105.657 3.343z" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                            <div class="pt-1">
                                <p class="text-sm font-semibold text-slate-900 break-words">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 break-words">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </aside>
        <!-- ======== /SIDEBAR (Desktop) ======== -->

        <!-- ======== KONTEN ======== -->
        <!-- Tambah padding-top agar konten tidak ketutup navbar di mobile -->
        <main class="relative z-10 flex-1 py-5 px-3 md:px-4 lg:px-5 xl:px-6 2xl:px-8 overflow-auto">
            @yield('content')
        </main>
        <!-- ======== /KONTEN ======== -->
    </div>

    @stack('scripts')
</body>

</html>