<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manajemen Obat') }}
        </h2>
    </x-slot>
    <!-- Toast Container -->
    <div class="fixed top-4 right-4 z-50 space-y-2">
        <!-- Sukses -->
        @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="translate-x-20 opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transform transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-20 opacity-0" class="px-4 py-2 text-white bg-green-600 rounded shadow-lg">
            {{ session('success') }}
        </div>
        @endif

        <!-- Error -->
        @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="translate-x-20 opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transform transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-20 opacity-0" class="px-4 py-2 text-white bg-red-600 rounded shadow-lg">
            {{ session('error') }}
        </div>
        @endif
    </div>

    <div class="py-6 px-2 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-primary-button wire:click="create">
                        Tambah Obat
                    </x-primary-button>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        <button wire:click="sortBy('name')" class="flex items-center">
                                            Nama Obat
                                            @if ($sortField === 'name')
                                            <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </button>
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Kategori</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        <button wire:click="sortBy('stock')" class="flex items-center">
                                            Stok
                                            @if ($sortField === 'stock')
                                            <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </button>
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        <button wire:click="sortBy('price')" class="flex items-center">
                                            Harga
                                            @if ($sortField === 'price')
                                            <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </button>
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
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
                                        <button wire:click="edit({{ $medicine->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button wire:click="confirmDelete({{ $medicine->id }})" class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 whitespace-nowrap">
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
        <form wire:submit="save" class="space-y-4 p-4">
            <h2 class="text-xl font-bold text-gray-900">
                {{ $isEditMode ? 'Edit Obat' : 'Tambah Obat Baru' }}
            </h2>
            <p class="text-sm text-gray-500 mb-2">
                Isi informasi obat dengan lengkap.
            </p>

            <!-- Nama Obat -->
            <div class="p-3 bg-gray-50 rounded shadow-sm">
                <x-input-label for="name" value="Nama Obat *" />
                <x-text-input wire:model="name" id="name" type="text" placeholder="Contoh: Paracetamol" class="block w-full mt-1" />
                <x-input-error :messages="$errors->get('name')" class="text-red-500 mt-1 text-sm" />
            </div>

            <!-- Barcode -->
            <div class="p-3 bg-gray-50 rounded shadow-sm">
                <x-input-label for="barcode" value="Barcode (Opsional)" />
                <x-text-input wire:model="barcode" id="barcode" type="text" placeholder="Kosongkan jika tidak ada" class="block w-full mt-1" />
                <x-input-error :messages="$errors->get('barcode')" class="text-red-500 mt-1 text-sm" />
            </div>

            <!-- Kategori & Stok -->
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded shadow-sm">
                    <x-input-label for="category_id" value="Kategori *" />
                    <select wire:model="category_id" id="category_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="text-red-500 mt-1 text-sm" />
                </div>
                <div class="p-3 bg-gray-50 rounded shadow-sm">
                    <x-input-label for="stock" value="Stok *" />
                    <x-text-input wire:model="stock" id="stock" type="number" min="0" placeholder="Jumlah tersedia" class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('stock')" class="text-red-500 mt-1 text-sm" />
                </div>
            </div>

            <!-- Harga & Modal -->
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded shadow-sm">
                    <x-input-label for="price" value="Harga Jual *" />
                    <x-text-input wire:model="price" id="price" type="number" step="100" placeholder="5000" class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('price')" class="text-red-500 mt-1 text-sm" />
                </div>
                <div class="p-3 bg-gray-50 rounded shadow-sm">
                    <x-input-label for="cost_price" value="Harga Beli (Modal) *" />
                    <x-text-input wire:model="cost_price" id="cost_price" type="number" step="100" placeholder="4500" class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('cost_price')" class="text-red-500 mt-1 text-sm" />
                </div>
            </div>

            <!-- Satuan & Tanggal Kadaluarsa -->
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded shadow-sm">
                    <x-input-label for="unit" value="Satuan *" />
                    <x-text-input wire:model="unit" id="unit" type="text" placeholder="strip, botol" class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('unit')" class="text-red-500 mt-1 text-sm" />
                </div>
                <div class="p-3 bg-gray-50 rounded shadow-sm">
                    <x-input-label for="expired_date" value="Tanggal Kadaluarsa *" />
                    <x-text-input wire:model="expired_date" id="expired_date" type="date" class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('expired_date')" class="text-red-500 mt-1 text-sm" />
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-2">
                <x-secondary-button type="button" wire:click="closeModal" class="w-full sm:w-auto">Batal</x-secondary-button>
                <x-primary-button wire:loading.attr="disabled" wire:target="save" class="w-full sm:w-auto flex items-center justify-center">
                    <svg wire:loading wire:target="save" class="w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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
