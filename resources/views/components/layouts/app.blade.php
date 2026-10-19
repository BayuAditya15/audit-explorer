<!DOCTYPE html>
<html lang="id" class="h-full bg-surface">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'COBIT Explorer — Governance & Management' }}</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-surface font-sans text-on-surface antialiased" x-data="{ mobileMenuOpen: false }">
    <!-- MOBILE SIDEBAR BACKDROP -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileMenuOpen = false"
        class="fixed inset-0 bg-slate-950/60 z-40 lg:hidden"
        style="display: none;"
    ></div>

    <!-- SIDEBAR -->
    <aside 
        :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed left-0 top-0 bottom-0 w-[250px] bg-inverse-surface border-r border-outline-variant/20 z-50 flex flex-col justify-between overflow-y-auto transition-transform duration-300 ease-in-out"
    >
        <div class="flex flex-col">
            <!-- Brand Header -->
            <div class="h-[68px] px-4 flex items-center justify-between border-b border-outline-variant/15">
                <a href="{{ route('cobit.overview') }}" class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-primary-container flex items-center justify-center text-on-primary shadow-sm flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">shield</span>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-sm font-semibold text-inverse-on-surface truncate leading-tight tracking-tight">COBIT Explorer</span>
                        <span class="text-[11px] text-outline-variant truncate leading-tight font-medium">Governance &amp; Mgt</span>
                    </div>
                </a>
                <button 
                    @click="mobileMenuOpen = false" 
                    type="button" 
                    class="lg:hidden text-outline hover:text-inverse-on-surface p-1 rounded"
                >
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="px-3 py-4 space-y-6">
                <!-- Section 1: Framework COBIT 2019 -->
                <div>
                    <div class="px-2 mb-2 text-[11px] font-semibold uppercase tracking-wider text-outline-variant">
                        COBIT Framework
                    </div>
                    <nav class="flex flex-col gap-1">
                        @php
                            $isOverview = request()->routeIs('cobit.overview');
                            $isFramework = request()->routeIs('cobit.framework');
                            $isRaci = request()->routeIs('raci.matrix');
                        @endphp

                        <a 
                            href="{{ route('cobit.overview') }}" 
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $isOverview ? 'bg-primary-container text-on-primary font-semibold shadow-sm' : 'text-inverse-on-surface/80 hover:bg-surface-variant/10 hover:text-inverse-on-surface' }}"
                        >
                            <span class="material-symbols-outlined text-[19px]">dashboard</span>
                            <span>Overview</span>
                        </a>

                        <a 
                            href="{{ route('cobit.framework') }}" 
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $isFramework ? 'bg-primary-container text-on-primary font-semibold shadow-sm' : 'text-inverse-on-surface/80 hover:bg-surface-variant/10 hover:text-inverse-on-surface' }}"
                        >
                            <span class="material-symbols-outlined text-[19px]">grid_view</span>
                            <span>COBIT Explorer</span>
                        </a>

                        <a 
                            href="{{ route('raci.matrix') }}" 
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $isRaci ? 'bg-primary-container text-on-primary font-semibold shadow-sm' : 'text-inverse-on-surface/80 hover:bg-surface-variant/10 hover:text-inverse-on-surface' }}"
                        >
                            <span class="material-symbols-outlined text-[19px]">table_chart</span>
                            <span>RACI Matrix</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-3 border-t border-outline-variant/15 flex flex-col gap-2 bg-inverse-surface">
            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-surface-variant/10 transition-colors">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="relative flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-on-primary font-bold text-xs">
                            {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'US' }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-inverse-surface"></span>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs font-semibold text-inverse-on-surface truncate leading-tight">{{ auth()->check() ? auth()->user()->name : 'Tamu' }}</span>
                        <span class="text-[11px] text-outline-variant truncate leading-tight">{{ auth()->check() ? auth()->user()->email : 'Belum masuk' }}</span>
                    </div>
                </div>
            </div>

            @auth
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-1.5 text-xs text-rose-300 hover:text-rose-100 hover:bg-rose-500/20 rounded-lg transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">logout</span>
                        <span>Keluar</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 px-3 py-1.5 text-xs text-primary-fixed hover:bg-primary-container/20 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-[16px]">login</span>
                    <span>Masuk</span>
                </a>
            @endauth
        </div>
    </aside>

    <!-- MAIN SHELL (HEADER + CONTENT) -->
    <div class="lg:pl-[250px] min-h-screen flex flex-col">
        <!-- TOPBAR UTILITY HEADER -->
        <header class="fixed top-0 lg:left-[250px] right-0 h-[68px] bg-surface-container-lowest border-b border-outline-variant/30 z-30 flex items-center justify-between px-4 sm:px-6">
            <div class="flex items-center gap-3">
                <!-- Mobile Menu Button -->
                <button 
                    @click="mobileMenuOpen = true" 
                    type="button" 
                    class="lg:hidden p-1.5 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg transition-colors"
                    aria-label="Buka Menu"
                >
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>

                <!-- Breadcrumbs -->
                <div class="flex items-center gap-1.5 text-xs font-medium">
                    <span class="text-outline">Home</span>
                    <span class="text-outline-variant">/</span>
                    <span class="text-on-surface font-semibold">{{ $breadcrumb ?? 'COBIT Framework' }}</span>
                </div>
            </div>

            <!-- Topbar Right Items -->
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-low border border-outline-variant/30 text-xs text-on-surface-variant">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="font-medium">COBIT 2019 Core DB</span>
                </div>

                <div class="h-6 w-px bg-outline-variant/40 hidden sm:block"></div>

                <!-- User Profile & Action -->
                @auth
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-2 p-1 rounded-lg">
                            <div class="w-7 h-7 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-xs uppercase">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="flex flex-col text-left hidden md:flex">
                                <span class="text-xs font-semibold text-on-surface leading-tight">
                                    {{ auth()->user()->name }}
                                </span>
                                <span class="text-[10px] text-outline leading-tight">
                                    {{ auth()->user()->email }}
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button 
                                type="submit" 
                                title="Keluar / Logout"
                                class="p-1.5 rounded-lg text-outline hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-primary-container text-on-primary hover:bg-primary transition-colors">
                        Masuk
                    </a>
                @endauth
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 w-full pt-[68px] bg-surface">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>