<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Catat Pembelian Stok Baru (Batch)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <x-input-label for="supplier_id" value="Pilih Supplier" />
                        <select wire:model="supplier_id" id="supplier_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                    </div>
                    <div class="relative">
                        <x-input-label for="search" value="Cari & Tambah Obat" />
                        <x-text-input wire:model.live="search" id="search" type="text" class="w-full mt-1" placeholder="Ketik nama obat..." />
                        @if(count($medicines) > 0)
                        <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                            @foreach($medicines as $medicine)
                            <div wire:click="addToList({{ $medicine->id }})" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                {{ $medicine->name }}
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-semibold">Daftar Item & Batch Pembelian</h3>
                    <x-input-error :messages="$errors->get('purchaseList')" class="mt-2" />
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Nama Obat</th>
                                    <th class="px-4 py-2 text-left">No. Batch</th>
                                    <th class="px-4 py-2 text-left">Jumlah</th>
                                    <th class="px-4 py-2 text-left">Harga Beli</th>
                                    <th class="px-4 py-2 text-left">Tgl. Kadaluarsa</th>
                                    <th class="px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchaseList as $index => $item)
                                <tr class="border-b" wire:key="item-{{$index}}">
                                    <td class="px-4 py-2">{{ $item['name'] }}</td>
                                    <td>
                                        <x-text-input type="text" wire:model="purchaseList.{{ $index }}.batch_number" class="w-32 text-sm" />
                                    </td>
                                    <td>
                                        <x-text-input type="number" wire:model.live="purchaseList.{{ $index }}.quantity" class="w-20 text-sm" />
                                    </td>
                                    <td>
                                        <x-text-input type="number" wire:model.live="purchaseList.{{ $index }}.purchase_price" class="w-32 text-sm" />
                                    </td>
                                    <td>
                                        <x-text-input type="date" wire:model="purchaseList.{{ $index }}.expired_date" class="w-40 text-sm" />
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button wire:click="removeFromList({{ $index }})" class="text-red-500 hover:text-red-700">Hapus</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-center text-gray-500">Belum ada item.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="px-4 py-2 font-bold text-right">Total Keseluruhan</td>
                                    <td class="px-4 py-2 font-bold">Rp {{ number_format($total) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <x-primary-button wire:click="savePurchase">
                        Simpan Data Pembelian
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>
</div>
