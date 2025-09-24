<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manajemen User') }}
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
                    @if (session()->has('error'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                            class="px-4 py-2 mb-4 text-red-800 bg-red-200 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <x-primary-button wire:click="create">Tambah User</x-primary-button>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left">Nama</th>
                                    <th class="px-6 py-3 text-left">Email</th>
                                    <th class="px-6 py-3 text-left">Role</th>
                                    <th class="px-6 py-3 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4">{{ $user->name }}</td>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4">{{ $user->roles->first()->name ?? 'Tanpa Role' }}</td>
                                        <td class="px-6 py-4">
                                            <button wire:click="edit({{ $user->id }})"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                            <button wire:click="confirmDelete({{ $user->id }})"
                                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="user-modal" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">{{ $isEditMode ? 'Edit User' : 'Tambah User Baru' }}</h2>
            <div class="mt-6">
                <x-input-label for="name" value="Nama" />
                <x-text-input wire:model="name" id="name" type="text" class="w-full mt-1" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="email" value="Email" />
                <x-text-input wire:model="email" id="email" type="email" class="w-full mt-1" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="role" value="Role" />
                <select wire:model="role" id="role"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih Role</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="password" value="Password" />
                <x-text-input wire:model="password" id="password" type="password" class="w-full mt-1" />
                @if ($isEditMode)
                    <small class="text-gray-500">Kosongkan jika tidak ingin mengubah password.</small>
                @endif
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                <x-text-input wire:model="password_confirmation" id="password_confirmation" type="password"
                    class="w-full mt-1" />
            </div>
            <div class="flex justify-end mt-6">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button class="ms-3">Simpan</x-primary-button>
            </div>
        </form>
    </x-modal>
    <x-modal name="user-delete-modal" focusable>
        {{-- <x-modal :show="$confirmingUserDeletion" @close="$set('confirmingUserDeletion', false)"> --}}
        <div class="p-6">
            <h2 class="text-lg font-medium">Apakah Anda yakin?</h2>
            <p class="mt-1 text-sm text-gray-600">Data user ini akan dihapus permanen.</p>
            <div class="flex justify-end mt-6">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal</x-secondary-button>
                <x-danger-button class="ms-3" wire:click="destroy">Ya, Hapus</x-danger-button>
            </div>
        </div>
    </x-modal>
</div>
