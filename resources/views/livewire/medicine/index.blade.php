<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manajemen Obat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session()->has('success'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                            class="px-4 py-2 mb-4 text-green-800 bg-green-200 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <x-primary-button wire:click="create">
                        Tambah Obat
                    </x-primary-button>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        <button wire:click="sortBy('name')" class="flex items-center">
                                            Nama Obat
                                            @if ($sortField === 'name')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </button>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Kategori</th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        <button wire:click="sortBy('stock')" class="flex items-center">
                                            Stok
                                            @if ($sortField === 'stock')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </button>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        <button wire:click="sortBy('price')" class="flex items-center">
                                            Harga
                                            @if ($sortField === 'price')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </button>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" wire:loading.class.delay="opacity-50">
                                @forelse ($medicines as $medicine)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $medicine->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $medicine->category->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $medicine->stock }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp
                                            {{ number_format($medicine->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button wire:click="edit({{ $medicine->id }})"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                            <button wire:click="confirmDelete({{ $medicine->id }})"
                                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 text-center text-gray-500 whitespace-nowrap">
                                            Tidak ada data obat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $medicines->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <x-modal name="medicine-modal" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ $isEditMode ? 'Edit Data Obat' : 'Tambah Data Obat Baru' }}
            </h2>

            <div class="mt-6">
                <x-input-label for="name" value="Nama Obat" />
                <x-text-input wire:model="name" id="name" type="text" class="block w-full mt-1" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="barcode" value="Barcode (Opsional)" />
                <x-text-input wire:model="barcode" id="barcode" type="text" class="block w-full mt-1" />
                <x-input-error :messages="$errors->get('barcode')" class="mt-2" />
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <x-input-label for="category_id" value="Kategori" />
                    <select wire:model="category_id" id="category_id"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="stock" value="Stok" />
                    <x-text-input wire:model="stock" id="stock" type="number" class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <x-input-label for="price" value="Harga Jual" />
                    <x-text-input wire:model="price" id="price" type="number" step="100"
                        class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="cost_price" value="Harga Beli (Modal)" />
                    <x-text-input wire:model="cost_price" id="cost_price" type="number" step="100"
                        class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('cost_price')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <x-input-label for="unit" value="Satuan (e.g., strip, botol)" />
                    <x-text-input wire:model="unit" id="unit" type="text" class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="expired_date" value="Tanggal Kadaluarsa" />
                    <x-text-input wire:model="expired_date" id="expired_date" type="date"
                        class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('expired_date')" class="mt-2" />
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <x-secondary-button type="button" wire:click="closeModal">
                    Batal
                </x-secondary-button>

                <x-primary-button class="ms-3" wire:loading.attr="disabled" wire:target="save">
                    <svg wire:loading wire:target="save" class="w-5 h-5 mr-3 -ml-1 text-white animate-spin"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span>{{ $isEditMode ? 'Simpan Perubahan' : 'Simpan' }}</span>
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="medicine-delete-modal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Apakah Anda yakin ingin menghapus data obat ini?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Data yang sudah dihapus tidak dapat dikembalikan.
            </p>

            <div class="flex justify-end mt-6">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>
                <x-danger-button class="ms-3" wire:click="destroy">
                    Ya, Hapus
                </x-danger-button>
            </div>
        </div>
    </x-modal>
</div>
