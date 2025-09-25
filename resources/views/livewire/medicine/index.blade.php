<div x-data="{
    selected: @entangle('selectedMedicines'),
    paginatedIds: @json($paginatedMedicineIds),
    get allSelected() {
        return this.paginatedIds.length > 0 && this.paginatedIds.every(id => this.selected.includes(id));
    },
    toggleAll() {
        if (this.allSelected) {
            this.selected = this.selected.filter(id => !this.paginatedIds.includes(id));
        } else {
            this.paginatedIds.forEach(id => {
                if (!this.selected.includes(id)) {
                    this.selected.push(id);
                }
            });
        }
    }
}">
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
        <div class="mx-auto max-w-7xl">

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                <x-primary-button wire:click="create" class="w-full justify-center">
                    Tambah Obat
                </x-primary-button>

                <a href="{{ route('medicines.export') }}" class="inline-flex justify-center items-center w-full px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Ekspor ke Excel
                </a>

                <x-secondary-button wire:click="openImportModal" class="w-full justify-center">
                    Impor dari Excel
                </x-secondary-button>

                <a x-bind:href="'{{ route('medicines.print-labels') }}?medicines=' + selected.join(',')" x-bind:class="{ 'opacity-50 cursor-not-allowed': selected.length === 0 }" x-on:click="if (selected.length === 0) $event.preventDefault()" target="_blank" class="inline-flex justify-center items-center w-full px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 transition ease-in-out duration-150">
                    Cetak Label (<span x-text="selected.length"></span>)
                </a>
            </div>
            <div class="flex items-center space-x-2 mb-4">
                <input type="checkbox" x-on:click="toggleAll()" x-bind:checked="allSelected" class="rounded">
                <label class="text-sm font-medium">Pilih Semua </label>
            </div>
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto bg-white shadow-sm sm:rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-2 py-3 text-left">
                                <input type="checkbox" x-on:click="toggleAll()" x-bind:checked="allSelected" class="rounded">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button wire:click="sortBy('name')" class="flex items-center space-x-1">
                                    <span>Nama Obat</span>
                                    @if ($sortField === 'name')
                                    <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span>Total Stok</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button wire:click="sortBy('price')" class="flex items-center space-x-1">
                                    <span>Harga</span>
                                    @if ($sortField === 'price')
                                    <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" wire:loading.class.delay="opacity-50">
                        @forelse ($medicines as $medicine)
                        <tr>
                            <td class="px-2 py-4"><input type="checkbox" wire:model.live="selectedMedicines" value="{{ $medicine->id }}"></td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $medicine->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $medicine->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $medicine->total_stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($medicine->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-4">
                                <button wire:click="edit({{ $medicine->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button wire:click="confirmDelete({{ $medicine->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data obat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4 px-6">
                    {{ $medicines->links() }}
                </div>
            </div>
            <div class="md:hidden space-y-4">
                @forelse ($medicines as $medicine)
                <div class="p-4 bg-white shadow-sm rounded-lg border">
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" wire:model.live="selectedMedicines" value="{{ $medicine->id }}" class="rounded mt-1">
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $medicine->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $medicine->category->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-600">Total Stok:</span> <span class="font-bold">{{ $medicine->total_stock }} {{ $medicine->unit }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Harga Jual:</span> <span class="font-semibold">Rp {{ number_format($medicine->price) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Harga Modal (Avg):</span> <span class="font-semibold">Rp {{ number_format($medicine->cost_price) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Margin:</span> <span class="font-semibold">{{ $medicine->margin }}%</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Barcode:</span> <span class="font-mono text-xs">{{ $medicine->barcode ?? '-' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Kadaluarsa Terdekat:</span>
                            <span class="font-semibold">
                                {{ $medicine->next_expiry_date ? \Carbon\Carbon::parse($medicine->next_expiry_date)->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t flex justify-end space-x-2">
                        <button wire:click="edit({{ $medicine->id }})" class="px-3 py-1 text-white bg-indigo-600 rounded hover:bg-indigo-700 text-sm">Edit</button>
                        <button wire:click="confirmDelete({{ $medicine->id }})" class="px-3 py-1 text-white bg-red-600 rounded hover:bg-red-700 text-sm">Hapus</button>
                    </div>
                </div>
                @empty
                <div class="p-4 bg-white shadow-sm rounded-lg text-center text-gray-500">
                    Tidak ada data obat.
                </div>
                @endforelse

                <div class="mt-4">
                    {{ $medicines->links() }}
                </div>
            </div>
        </div>
    </div>
    <x-modal name="import-modal" focusable>
        <form wire:submit="importExcel" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Impor Data Obat
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Unggah file Excel (.xlsx) dengan format kolom yang sesuai. Gunakan file hasil ekspor sebagai template.
            </p>
            <div class="mt-6">
                <x-input-label for="uploadFile" value="File Excel" />
                <x-text-input wire:model="uploadFile" id="uploadFile" name="uploadFile" type="file" class="block w-full mt-1" />
                <div wire:loading wire:target="uploadFile" class="mt-2 text-sm text-gray-500">Mengunggah file...</div>
                <x-input-error :messages="$errors->get('uploadFile')" class="mt-2" />
            </div>
            <div class="flex justify-end mt-6">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>
                <x-primary-button class="ms-3">
                    Impor
                </x-primary-button>
            </div>
        </form>
    </x-modal>
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
            <div class="grid grid-cols-1 gap-3">
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
            </div>
            <!-- Harga & Modal -->
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded shadow-sm">
                    <x-input-label for="price" value="Harga Jual *" />
                    <x-text-input wire:model="price" id="price" type="number" step="100" placeholder="5000" class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('price')" class="text-red-500 mt-1 text-sm" />
                </div>
                <div class="p-3 bg-gray-50 rounded shadow-sm">
                    <x-input-label for="margin" value="Margin Laba (%)" />
                    <x-text-input wire:model="margin" id="margin" type="number" class="w-full mt-1" placeholder="Contoh: 30" />
                    <x-input-error :messages="$errors->get('margin')" class="mt-2" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded shadow-sm">
                    <x-input-label for="unit" value="Satuan *" />
                    <x-text-input wire:model="unit" id="unit" type="text" placeholder="strip, botol" class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('unit')" class="text-red-500 mt-1 text-sm" />
                </div>
                <div class="p-3 bg-gray-50 rounded shadow-sm">
                    <x-input-label for="cost_price" value="Harga Beli (Modal)" />
                    <x-text-input wire:model="cost_price" id="cost_price" type="number" step="100" class="block w-full mt-1" />
                    <x-input-error :messages="$errors->get('cost_price')" class="mt-1 text-red-500 text-sm" />
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
