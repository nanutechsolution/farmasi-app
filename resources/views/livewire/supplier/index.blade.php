<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manajemen Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session()->has('success'))
                        <div class="px-4 py-2 mb-4 text-green-800 bg-green-200 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <x-primary-button wire:click="create">
                        Tambah Supplier
                    </x-primary-button>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Nama Supplier</th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Kontak Person</th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Telepon</th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($suppliers as $supplier)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->contact_person }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->phone }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button wire:click="edit({{ $supplier->id }})"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                            <button wire:click="confirmDelete({{ $supplier->id }})"
                                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-4 text-center text-gray-500 whitespace-nowrap">
                                            Tidak ada data supplier.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $suppliers->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <x-modal name="supplier-modal" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ $isEditMode ? 'Edit Data Supplier' : 'Tambah Data Supplier Baru' }}
            </h2>

            <div class="mt-6">
                <x-input-label for="name" value="Nama Supplier" />
                <x-text-input wire:model="name" id="name" type="text" class="block w-full mt-1" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="contact_person" value="Kontak Person" />
                <x-text-input wire:model="contact_person" id="contact_person" type="text"
                    class="block w-full mt-1" />
                <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="phone" value="Telepon" />
                <x-text-input wire:model="phone" id="phone" type="text" class="block w-full mt-1" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="address" value="Alamat" />
                <textarea wire:model="address" id="address"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ $isEditMode ? 'Simpan Perubahan' : 'Simpan' }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="confirm-delete-modal" :show="$errors->isNotEmpty()" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Apakah Anda yakin ingin menghapus data supplier ini?
            </h2>
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
