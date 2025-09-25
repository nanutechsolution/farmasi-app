<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Asisten Penentuan Harga Jual
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                <div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-300 rounded-md">
                    <h3 class="font-bold">Pembelian Berhasil Disimpan!</h3>
                    <p class="text-sm">Harga modal rata-rata untuk beberapa obat telah berubah. Silakan tinjau dan perbarui harga jualnya di bawah ini.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Nama Obat</th>
                                <th class="px-4 py-2 text-center">Harga Jual Lama</th>
                                <th class="px-4 py-2 text-center">Harga Modal Baru</th>
                                <th class="px-4 py-2 text-center">Margin</th>
                                <th class="px-4 py-2 text-center">Harga Jual Saran</th>
                                <th class="w-48 px-4 py-2 text-left">Harga Jual Baru</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($medicinesToUpdate as $id => $data)
                            <tr wire:key="med-{{ $id }}">
                                <td class="px-4 py-2 font-semibold">{{ $data['name'] }}</td>
                                <td class="px-4 py-2 text-center text-gray-500 line-through">Rp {{ number_format($data['old_price']) }}</td>
                                <td class="px-4 py-2 text-center text-blue-600">Rp {{ number_format($data['new_cost_price']) }}</td>
                                <td class="px-4 py-2 text-center">{{ $data['margin'] }}%</td>
                                <td class="px-4 py-2 text-center font-bold text-green-600">Rp {{ number_format($data['suggested_price']) }}</td>
                                <td class="px-4 py-2">
                                    <x-text-input type="number" wire:model="medicinesToUpdate.{{ $id }}.new_price" class="w-full text-sm"/>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between pt-6 mt-6 border-t">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-sm text-gray-600 hover:underline">
                        Lewati & Kembali ke Dashboard
                    </a>
                    <x-primary-button wire:click="savePrices">
                        Simpan & Terapkan Harga Baru
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>
</div>
