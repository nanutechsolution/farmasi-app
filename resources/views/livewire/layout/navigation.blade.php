<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false, openInventaris: false,
    openManajemen: false,
    openLaporan: false  }" class="bg-white border-b border-gray-100  sticky top-0 z-50 dark:bg-gray-800 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <!-- Logo -->
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="h-9 w-auto fill-current dark:text-gray-200 mr-2" />
                    </a>
                    <!-- Nama aplikasi, hanya muncul di mobile -->
                    <span class="text-lg font-semibold text-gray-800 sm:hidden">
                        Farmasi Medika
                    </span>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @can('create-transaction')
                    <x-nav-link :href="route('transactions.create')" :active="request()->routeIs('transactions.create')" wire:navigate>
                        {{ __('Transaksi Baru') }}
                    </x-nav-link>
                    @endcan
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out  hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                                    <div>Inventaris</div>
                                    <div class="ms-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @can('create-purchase')
                                <x-dropdown-link :href="route('purchases.create')" wire:navigate>{{ __('Pembelian Baru') }}</x-dropdown-link>
                                @endcan
                                @can('perform-stock-opname')
                                <x-dropdown-link :href="route('stock-opnames.create')" wire:navigate>{{ __('Buat Stok Opname') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('stock-opnames.index')" wire:navigate>{{ __('Riwayat Stok Opname') }}</x-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out  border-transparent hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                                    <div>Manajemen Data</div>
                                    <div class="ms-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @can('manage-medicines')
                                <x-dropdown-link :href="route('medicines.index')" wire:navigate>{{ __('Obat & Alkes') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('categories.index')" wire:navigate>{{ __('Kategori') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('locations.index')" wire:navigate>{{ __('Lokasi') }}</x-dropdown-link>
                                @endcan
                                @can('manage-suppliers')
                                <x-dropdown-link :href="route('suppliers.index')" wire:navigate>{{ __('Supplier') }}</x-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out  hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                                    <div>Laporan & Admin</div>
                                    <div class="ms-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @can('view-reports')
                                <x-dropdown-link :href="route('transactions.index')" wire:navigate>{{ __('Laporan Transaksi') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('reports.financial')" wire:navigate>{{ __('Laporan Keuangan') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('reports.stock-analysis')" wire:navigate>{{ __('Analisis Stok') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('reports.attendance')" wire:navigate>{{ __('Laporan Absensi') }}</x-dropdown-link>
                                @endcan
                                @can('manage-expenses')
                                <x-dropdown-link :href="route('expenses.index')" wire:navigate>{{ __('Biaya Operasional') }}</x-dropdown-link>
                                @endcan
                                <div class="border-t border-gray-200"></div>
                                @can('manage-users')
                                <x-dropdown-link :href="route('users.index')" wire:navigate>{{ __('Manajemen User') }}</x-dropdown-link>
                                @endcan
                                @can('manage-roles')
                                <x-dropdown-link :href="route('roles.index')" wire:navigate>{{ __('Manajemen Role') }}</x-dropdown-link>
                                @endcan
                                @role('Admin')
                                <div class="border-t border-gray-200"></div>
                                <x-dropdown-link :href="route('settings.index')" wire:navigate>{{ __('Pengaturan Aplikasi') }}</x-dropdown-link>
                                @endrole
                                @can('view-activity-log')
                                <x-dropdown-link :href="route('activity-log.index')" wire:navigate>{{ __('Log Aktivitas') }}</x-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-dropdown>
                    </div>

                    @role('Admin')
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Sistem</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('system.logs')" wire:navigate>
                                    {{ __('Log Viewer') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __(key: 'Log Out') }}
                            </x-dropdown-link>
                        </button>
                        <div class="me-4">
                            <x-theme-toggler />
                        </div>

                    </x-slot>


                </x-dropdown>

            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}</x-responsive-nav-link>
            @can('create-transaction')
            <x-responsive-nav-link :href="route('transactions.create')" :active="request()->routeIs('transactions.create')" wire:navigate>{{ __('Transaksi Baru') }}</x-responsive-nav-link>
            @endcan
            <!-- Mobile Inventaris -->
            <div x-data="{ openInventaris: {{ request()->routeIs('purchases.*','stock-opnames.*') ? 'true' : 'false' }} }" class="w-full">
                <button @click="openInventaris = !openInventaris" class="w-full text-left px-4 py-2 font-medium text-gray-700">
                    Inventaris
                </button>

                <div x-show="openInventaris" x-transition class="pl-4">
                    @can('create-purchase')
                    <x-responsive-nav-link :href="route('purchases.create')" :active="request()->routeIs('purchases.create')">
                        Pembelian Baru
                    </x-responsive-nav-link>
                    @endcan

                    @can('perform-stock-opname')
                    <x-responsive-nav-link :href="route('stock-opnames.create')" :active="request()->routeIs('stock-opnames.create')">
                        Buat Stok Opname
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('stock-opnames.index')" :active="request()->routeIs('stock-opnames.index')">
                        Riwayat Stok Opname
                    </x-responsive-nav-link>
                    @endcan
                </div>
            </div>


            <!-- Mobile Manajemen Data -->
            <!-- Mobile Manajemen Data -->
            <div x-data="{ openManajemen: {{ request()->routeIs('medicines.*','categories.*','locations.*','suppliers.*') ? 'true' : 'false' }} }" wire:ignore>
                <button @click="openManajemen = !openManajemen" class="w-full text-left px-4 py-2 font-medium text-gray-700">
                    {{ __('Manajemen Data') }}
                </button>

                <div x-show="openManajemen" x-transition class="pl-4">
                    @can('manage-medicines')
                    <x-responsive-nav-link :href="route('medicines.index')" :active="request()->routeIs('medicines.*')">
                        {{ __('Obat & Alkes') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                        {{ __('Kategori') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">
                        {{ __('Lokasi') }}
                    </x-responsive-nav-link>
                    @endcan

                    @can('manage-suppliers')
                    <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                        {{ __('Supplier') }}
                    </x-responsive-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Mobile Laporan & Admin -->
            <div x-data="{ openLaporan: {{ request()->routeIs(
        'transactions.index',
        'reports.*',
        'expenses.*',
        'users.*',
        'roles.*',
        'settings.*',
        'activity-log.*'
    ) ? 'true' : 'false' }} }" wire:ignore>
                <button @click="openLaporan = !openLaporan" class="w-full text-left px-4 py-2 font-medium text-gray-700">
                    Laporan & Admin
                </button>

                <div x-show="openLaporan" x-transition class="pl-4">
                    @can('view-reports')
                    <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')">
                        Laporan Transaksi
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('reports.financial')" :active="request()->routeIs('reports.*')">
                        Laporan Keuangan
                    </x-responsive-nav-link>
                    @endcan

                    @can('manage-expenses')
                    <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
                        Biaya Operasional
                    </x-responsive-nav-link>
                    @endcan

                    @can('manage-users')
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        Manajemen User
                    </x-responsive-nav-link>
                    @endcan

                    @can('manage-roles')
                    <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                        Manajemen Role
                    </x-responsive-nav-link>
                    @endcan

                    @role('Admin')
                    <x-responsive-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                        Pengaturan
                    </x-responsive-nav-link>
                    @endrole

                    @can('view-activity-log')
                    <x-responsive-nav-link :href="route('activity-log.index')" :active="request()->routeIs('activity-log.*')">
                        Log Aktivitas
                    </x-responsive-nav-link>
                    @endcan
                </div>
            </div>

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
