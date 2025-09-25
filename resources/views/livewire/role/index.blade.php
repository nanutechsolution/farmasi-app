<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen Role & Hak Akses</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                @if (session()->has('success')) <div class="px-4 py-2 mb-4 text-green-800 bg-green-200 rounded">{{ session('success') }}</div> @endif
                <x-primary-button wire:click="create">Tambah Role</x-primary-button>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Nama Role</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($roles as $role)
                            <tr>
                                <td class="px-4 py-2">{{ $role->name }}</td>
                                <td class="px-4 py-2 text-center">
                                    @if($role->name !== 'Admin')
                                    <button wire:click="edit({{ $role->id }})" class="text-indigo-600">Edit</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="role-modal" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium">{{ $isEditMode ? 'Edit Role' : 'Tambah Role Baru' }}</h2>
            <div class="mt-6">
                <x-input-label for="name" value="Nama Role" />
                <x-text-input wire:model="name" id="name" type="text" class="w-full mt-1" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label value="Hak Akses" />
                <div class="grid grid-cols-2 gap-4 mt-2 md:grid-cols-3">
                    @foreach($permissions as $permission)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" class="rounded">
                        <span>{{ $permission->name }}</span>
                    </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('selectedPermissions')" class="mt-2" />
            </div>
            <div class="flex justify-end mt-6">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button class="ms-3">Simpan</x-primary-button>
            </div>
        </form>
    </x-modal>
</div>
