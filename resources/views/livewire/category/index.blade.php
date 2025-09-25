<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen Kategori</h2>
    </x-slot>

    <div class="py-6 px-2 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                @if (session()->has('success')) <div class="px-4 py-2 mb-4 text-green-800 bg-green-200 rounded">{{ session('success') }}</div> @endif
                @if (session()->has('error')) <div class="px-4 py-2 mb-4 text-red-800 bg-red-200 rounded">{{ session('error') }}</div> @endif
                <x-primary-button wire:click="create">Tambah Kategori</x-primary-button>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Nama Kategori</th>
                                <th class="px-4 py-2 text-center">Jumlah Produk</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($categories as $category)
                            <tr>
                                <td class="px-4 py-2">{{ $category->name }}</td>
                                <td class="px-4 py-2 text-center">{{ $category->medicines_count }}</td>
                                <td class="px-4 py-2 text-center">
                                    <button wire:click="edit({{ $category->id }})" class="text-indigo-600">Edit</button>
                                    <button wire:click="confirmDelete({{ $category->id }})" class="ml-2 text-red-600">Hapus</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center">Belum ada data kategori.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $categories->links() }}</div>
            </div>
        </div>
    </div>

    <x-modal name="category-modal" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium">{{ $isEditMode ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h2>
            <div class="mt-6">
                <x-input-label for="name" value="Nama Kategori" />
                <x-text-input wire:model="name" id="name" type="text" class="w-full mt-1" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="flex justify-end mt-6">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button class="ms-3">Simpan</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="category-delete-modal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium">Apakah Anda yakin?</h2>
            <p class="mt-1 text-sm text-gray-600">Menghapus kategori ini tidak akan berhasil jika masih ada obat yang menggunakannya.</p>
            <div class="flex justify-end mt-6">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>
                <x-danger-button class="ms-3" wire:click="destroy">Ya, Hapus</x-danger-button>
            </div>
        </div>
    </x-modal>
</div>
