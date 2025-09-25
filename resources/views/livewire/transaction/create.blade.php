<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Transaksi Penjualan Baru
        </h2>
    </x-slot>
    <div class="py-6 px-2 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

                <div class="lg:col-span-2">
                    <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                        <h3 class="text-lg font-semibold">Daftar Obat</h3>
                        <div class="mt-4">
                            <x-text-input wire:model.live.debounce.300ms="search" wire:keydown.enter.prevent="scanOrSearch" type="text" class="w-full" placeholder="Cari nama obat atau scan barcode..." autofocus />
                        </div>
                        @if (session()->has('scan-error'))
                        <div class="mt-2 text-sm text-red-600">
                            {{ session('scan-error') }}
                        </div>
                        @endif
                        <div class="mt-4 overflow-y-auto border rounded-md  sm:h-80 lg:h-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($medicines as $medicine)
                                    <tr wire:click="addToCart({{ $medicine->id }})" class="cursor-pointer hover:bg-gray-100">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $medicine->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">Stok: {{ $medicine->stock }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="text-sm font-semibold text-gray-900">Rp
                                                {{ number_format($medicine->price, 0, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                                            @if (strlen($search) < 2) Ketik minimal 2 huruf untuk mencari... @else Obat tidak ditemukan. @endif </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                        <h3 class="text-lg font-semibold">Keranjang</h3>
                        <div class="mt-4 border-t border-b divide-y overflow-y-auto
          h-auto">
                            @forelse ($cart as $id => $item)
                            <div class="flex items-center justify-between p-4">
                                <div>
                                    <p class="font-semibold">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-600">Rp
                                        {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center">
                                    <button wire:click="decrementQuantity({{ $id }})" class="px-2 py-1 text-lg font-bold text-gray-600 bg-gray-200 rounded-l">-</button>
                                    <span class="px-4 py-1 bg-white">{{ $item['quantity'] }}</span>
                                    <button wire:click="incrementQuantity({{ $id }})" class="px-2 py-1 text-lg font-bold text-gray-600 bg-gray-200 rounded-r">+</button>
                                    <button wire:click="removeFromCart({{ $id }})" class="ml-4 text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @empty
                            <div class="flex items-center justify-center h-full">
                                <p class="text-gray-500">Keranjang masih kosong.</p>
                            </div>
                            @endforelse
                        </div>
                        <div class="pt-4 mt-4 border-t">
                            @if (session()->has('error'))
                            <div class="px-4 py-2 mb-4 text-sm text-red-800 bg-red-200 rounded">
                                {{ session('error') }}
                            </div>
                            @endif
                            <div class="mt-2 bg-white pt-2 border-t lg:relative lg:mt-4 space-y-2">
                                <div class="flex justify-between font-semibold">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">Bayar</span>
                                    <x-text-input type="number" wire:model.live="paid_amount" class="w-32 text-right" placeholder="0" />
                                </div>
                                <div class="flex justify-between font-semibold">
                                    <span>Kembalian</span>
                                    <span>Rp {{ number_format($change, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-primary-button wire:click="processTransaction" class="w-full text-center" :disabled="empty($cart) || $paid_amount < $total">
                                    <span class="w-full">Proses & Simpan Transaksi</span>
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
