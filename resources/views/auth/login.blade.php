<!DOCTYPE html>
<html lang="id" class="h-full bg-surface">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — COBIT Explorer</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-surface font-sans text-on-surface antialiased flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
        <!-- Brand Header -->
        <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-xl bg-primary-container text-on-primary flex items-center justify-center shadow-sm mb-3">
                <span class="material-symbols-outlined text-[28px]">shield</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-on-surface">COBIT Explorer</h1>
            <p class="text-xs text-outline mt-1">Sistem Tata Kelola &amp; Manajemen TI (COBIT 2019)</p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="bg-surface-container-lowest py-8 px-6 shadow-sm rounded-2xl border border-outline-variant/30 sm:px-10">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-on-surface">Masuk ke Akun</h2>
                <p class="text-xs text-outline mt-0.5">Silakan masukkan email dan kata sandi Anda</p>
            </div>

            <!-- Error Banner -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-[18px] text-rose-600 flex-shrink-0 mt-0.5">error</span>
                    <div class="space-y-0.5">
                        <span class="font-semibold block">Gagal masuk:</span>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4" x-data="{ showPassword: false }">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-on-surface mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative rounded-lg shadow-2xs">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                            <span class="material-symbols-outlined text-[18px]">mail</span>
                        </div>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            value="{{ old('email') }}"
                            placeholder="nama@perusahaan.com"
                            class="block w-full pl-10 pr-3 py-2 text-xs rounded-lg border border-outline-variant/40 bg-surface-container-low text-on-surface placeholder:text-outline focus:border-primary focus:ring-1 focus:ring-primary focus:bg-surface-container-lowest transition-colors"
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-on-surface mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative rounded-lg shadow-2xs">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                            <span class="material-symbols-outlined text-[18px]">lock</span>
                        </div>
                        <input 
                            id="password" 
                            name="password" 
                            :type="showPassword ? 'text' : 'password'"
                            autocomplete="current-password" 
                            required 
                            placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                            class="block w-full pl-10 pr-10 py-2 text-xs rounded-lg border border-outline-variant/40 bg-surface-container-low text-on-surface placeholder:text-outline focus:border-primary focus:ring-1 focus:ring-primary focus:bg-surface-container-lowest transition-colors"
                        >
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface"
                            tabindex="-1"
                        >
                            <span class="material-symbols-outlined text-[18px]" x-text="showPassword ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant/50"
                        >
                        <span class="text-xs text-outline">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full flex justify-center items-center gap-2 py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-xs font-semibold text-on-primary bg-primary-container hover:bg-primary focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-[18px]">login</span>
                        <span>Masuk ke Dashboard</span>
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-6 pt-5 border-t border-outline-variant/20">
                <div class="rounded-xl bg-surface-container-low p-3.5 border border-outline-variant/20 text-xs">
                    <div class="flex items-center gap-1.5 font-semibold text-on-surface mb-1.5">
                        <span class="material-symbols-outlined text-[16px] text-primary">key</span>
                        <span>Akun Standar untuk Masuk:</span>
                    </div>
                    <div class="space-y-1 text-outline font-mono text-[11px]">
                        <div>Email: <strong class="text-on-surface font-semibold">developer@cobit.local</strong></div>
                        <div>Password: <strong class="text-on-surface font-semibold">devpassword123</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Notice -->
        <p class="mt-6 text-center text-[11px] text-outline">
            &copy; {{ date('Y') }} COBIT Explorer &bull; ISACA 2019 Core Governance Framework
        </p>
    </div>
</body>
</html>
