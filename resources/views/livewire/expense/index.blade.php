<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Pencatatan Biaya Operasional
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                @if (session()->has('success'))
                <div class="px-4 py-2 mb-4 text-green-800 bg-green-200 rounded">
                    {{ session('success') }}
                </div>
                @endif
                <x-primary-button wire:click="create">Tambah Biaya</x-primary-button>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Kategori</th>
                                <th class="px-4 py-2 text-left">Jumlah</th>
                                <th class="px-4 py-2 text-left">Dicatat oleh</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($expenses as $expense)
                            <tr>
                                <td class="px-4 py-2">{{ $expense->expense_date->format('d M Y') }}</td>
                                <td class="px-4 py-2">{{ $expense->category }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($expense->amount) }}</td>
                                <td class="px-4 py-2">{{ $expense->user->name }}</td>
                                <td class="px-4 py-2 text-center">
                                    <button wire:click="edit({{ $expense->id }})" class="text-indigo-600">Edit</button>
                                    <button wire:click="confirmDelete({{ $expense->id }})" class="ml-2 text-red-600">Hapus</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center">Belum ada data biaya.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $expenses->links() }}</div>
            </div>
        </div>
    </div>

    <x-modal name="expense-modal" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium">{{ $isEditMode ? 'Edit Biaya' : 'Tambah Biaya Baru' }}</h2>
            <div class="mt-4">
                <x-input-label for="expense_date" value="Tanggal" />
                <x-text-input wire:model="expense_date" id="expense_date" type="date" class="w-full mt-1" />
                <x-input-error :messages="$errors->get('expense_date')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="category" value="Kategori Biaya" />
                <select wire:model="category" id="category" class="w-full mt-1 border-gray-300 rounded-md">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="amount" value="Jumlah (Rp)" />
                <x-text-input wire:model="amount" id="amount" type="number" class="w-full mt-1" />
                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="description" value="Keterangan (Opsional)" />
                <textarea wire:model="description" id="description" rows="3" class="w-full mt-1 border-gray-300 rounded-md"></textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
            <div class="flex justify-end mt-6">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button class="ms-3">Simpan</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="expense-delete-modal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium">Apakah Anda yakin?</h2>
            <div class="flex justify-end mt-6">
                <x-secondary-button x-on:click="$dispatch('close')"> Batal</x-secondary-button>
                <x-danger-button class="ms-3" wire:click="destroy">Ya, Hapus</x-danger-button>
            </div>
        </div>
    </x-modal>
</div>
