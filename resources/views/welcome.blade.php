<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmasi App - Solusi Manajemen Apotek Modern</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-800">
    <div class="relative min-h-screen flex flex-col">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                    <a href="/" class="-m-1.5 p-1.5 text-lg font-bold">
                        💊 Farmasi App
                    </a>
                </div>
                <div class="lg:flex lg:flex-1 lg:justify-end">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900" wire:navigate>Dashboard <span aria-hidden="true">&rarr;</span></a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900" wire:navigate>Log in <span aria-hidden="true">&rarr;</span></a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-6 text-sm font-semibold leading-6 text-gray-900" wire:navigate>Register <span aria-hidden="true">&rarr;</span></a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <main class="flex-grow">
            <div class="relative isolate px-6 pt-7 lg:px-8">
                <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                    <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#80ffb5] to-[#00c6ff] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
                <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Solusi Cerdas untuk Manajemen Apotek Anda</h1>
                        <p class="mt-6 text-lg leading-8 text-gray-600">Kelola stok obat, pantau transaksi, dan tingkatkan efisiensi apotek Anda dengan platform digital yang intuitif dan modern.</p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" wire:navigate>Daftar Sekarang</a>
                            <a href="#features" class="text-sm font-semibold leading-6 text-gray-900">Pelajari Lebih Lanjut <span aria-hidden="true">↓</span></a>
                        </div>
                    </div>
                </div>
            </div>

            <section id="features" class="py-24 sm:py-32 bg-white">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl lg:text-center">
                        <p class="text-base font-semibold leading-7 text-indigo-600">Semua Dalam Satu Platform</p>
                        <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Fitur Unggulan untuk Apotek Anda</h2>
                    </div>
                    <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                        <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                            <div class="relative ps-16">
                                <dt class="text-base font-semibold leading-7 text-gray-900">
                                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                                    </div>
                                    Manajemen Inventaris Cerdas
                                </dt>
                                <dd class="mt-2 text-base leading-7 text-gray-600">Pantau stok, harga beli, dan tanggal kadaluarsa secara akurat. Dapatkan notifikasi untuk stok yang menipis.</dd>
                            </div>
                            <div class="relative ps-16">
                                <dt class="text-base font-semibold leading-7 text-gray-900">
                                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013 4.5h.75m0 0h.75A.75.75 0 015.25 6v.75m0 0v.75A.75.75 0 014.5 8.25h-.75m0 0h-.75A.75.75 0 012.25 7.5v-.75M3 3.75a.75.75 0 01.75-.75h.75a.75.75 0 01.75.75v.75H3v-.75z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 13.5h6m-3-3v6M16.5 21a6 6 0 00-12 0" /></svg>
                                    </div>
                                    Transaksi Cepat & Akurat
                                </dt>
                                <dd class="mt-2 text-base leading-7 text-gray-600">Proses penjualan dengan cepat menggunakan antarmuka Point of Sale yang modern, didukung integrasi barcode scanner.</dd>
                            </div>
                            <div class="relative ps-16">
                                <dt class="text-base font-semibold leading-7 text-gray-900">
                                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 100 15 7.5 7.5 0 000-15z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197" /></svg>
                                    </div>
                                    Laporan Analitik Powerfull
                                </dt>
                                <dd class="mt-2 text-base leading-7 text-gray-600">Dapatkan wawasan bisnis dengan laporan keuangan dinamis. Pantau omzet, modal, dan laba kotor berdasarkan rentang tanggal.</dd>
                            </div>
                             <div class="relative ps-16">
                                <dt class="text-base font-semibold leading-7 text-gray-900">
                                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                    </div>
                                    Keamanan & Pelacakan
                                </dt>
                                <dd class="mt-2 text-base leading-7 text-gray-600">Sistem hak akses berbasis peran dan fitur Log Aktivitas memastikan data Anda aman dan semua perubahan tercatat.</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-white">
            <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
                <div class="mt-8 border-t border-gray-900/10 pt-8">
                    <p class="text-center text-xs leading-5 text-gray-500">&copy; {{ date('Y') }} Farmasi App. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
