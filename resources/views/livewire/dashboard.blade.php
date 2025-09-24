<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

                <div class="p-6 overflow-hidden bg-white rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500 truncate">Total Jenis Obat</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $medicineCount }}</p>
                    <a href="{{ route('medicines.index') }}"
                        class="mt-2 text-sm text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                </div>

                <div class="p-6 overflow-hidden bg-white rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500 truncate">Total Supplier</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $supplierCount }}</p>
                    <a href="{{ route('suppliers.index') }}"
                        class="mt-2 text-sm text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                </div>

                <div class="p-6 overflow-hidden bg-yellow-100 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-yellow-800 truncate">Obat Stok Menipis</h3>
                    <p class="mt-1 text-3xl font-semibold text-yellow-900">{{ $lowStockCount }}</p>
                    <a href="{{ route('medicines.index') }}"
                        class="mt-2 text-sm text-yellow-700 hover:text-yellow-900">Periksa Stok</a>
                </div>

                <div class="p-6 overflow-hidden bg-red-100 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-red-800 truncate">Obat Akan Kadaluarsa</h3>
                    <p class="mt-1 text-3xl font-semibold text-red-900">{{ $expiringSoonCount }}</p>
                    <a href="{{ route('medicines.index') }}"
                        class="mt-2 text-sm text-red-700 hover:text-red-900">Periksa Tanggal</a>
                </div>

            </div>
        </div>
    </div>
</div>
