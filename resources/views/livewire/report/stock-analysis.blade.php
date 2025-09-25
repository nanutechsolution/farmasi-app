<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Analisis Pergerakan Stok
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">

                <div class="flex items-center space-x-4 mb-6 pb-6 border-b">
                    <div>
                        <x-input-label for="startDate" value="Tanggal Mulai" />
                        <x-text-input wire:model.live="startDate" id="startDate" type="date" />
                    </div>
                    <div>
                        <x-input-label for="endDate" value="Tanggal Selesai" />
                        <x-text-input wire:model.live="endDate" id="endDate" type="date" />
                    </div>
                </div>

                <div wire:loading class="w-full text-center py-4">
                    <span class="text-gray-500">Memuat data...</span>
                </div>

                <div wire:loading.remove>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        <div>
                            <h3 class="text-lg font-semibold text-green-700">🚀 Top 10 Produk Terlaris (Fast-Moving)</h3>
                            <p class="text-sm text-gray-500 mb-4">Produk yang paling banyak terjual pada periode ini.</p>
                            <ol class="list-decimal list-inside space-y-2">
                                @forelse ($fastMovingProducts as $product)
                                <li class="p-2 rounded-md hover:bg-gray-50">
                                    <span class="font-semibold">{{ $product->medicine->name }}</span> -
                                    <span class="text-green-600 font-bold">{{ $product->total_quantity }}</span> unit terjual
                                </li>
                                @empty
                                <p class="text-gray-500">Tidak ada data penjualan.</p>
                                @endforelse
                            </ol>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-orange-700">🐢 Top 10 Produk Lambat Laku (Slow-Moving)</h3>
                            <p class="text-sm text-gray-500 mb-4">Produk yang paling sedikit terjual pada periode ini.</p>
                            <ol class="list-decimal list-inside space-y-2">
                                @forelse ($slowMovingProducts as $product)
                                <li class="p-2 rounded-md hover:bg-gray-50">
                                    <span class="font-semibold">{{ $product->medicine->name }}</span> -
                                    <span class="text-orange-600 font-bold">{{ $product->total_quantity }}</span> unit terjual
                                </li>
                                @empty
                                <p class="text-gray-500">Tidak ada data penjualan.</p>
                                @endforelse
                            </ol>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
