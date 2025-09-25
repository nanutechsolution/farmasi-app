<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Stok Opname (Perhitungan per Batch)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                @if (session()->has('error'))
                <div class="px-4 py-2 mb-4 text-sm text-red-800 bg-red-200 rounded">
                    {{ session('error') }}
                </div>
                @endif

                <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
                    <div>
                        <x-input-label for="notes" value="Catatan Opname (Opsional)" />
                        <x-text-input wire:model="notes" id="notes" type="text" class="w-full mt-1" placeholder="Contoh: Opname bulanan" />
                    </div>
                    <div>
                        <x-input-label for="search" value="Cari Obat" />
                        <x-text-input wire:model.live.debounce.300ms="search" id="search" type="text" class="w-full mt-1" placeholder="Ketik nama obat untuk mencari..." />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Nama Obat</th>
                                <th class="px-4 py-2 text-left">No. Batch</th>
                                <th class="px-4 py-2 text-center">Tgl. Kadaluarsa</th>
                                <th class="px-4 py-2 text-center">Stok Sistem</th>
                                <th class="w-40 px-4 py-2 text-left">Stok Fisik (Hasil Hitung)</th>
                                <th class="px-4 py-2 text-center">Selisih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($batches as $batch)
                            <tr wire:key="batch-{{ $batch->id }}">
                                <td class="px-4 py-2">{{ $batch->medicine->name }}</td>
                                <td class="px-4 py-2">{{ $batch->batch_number ?? 'N/A' }}</td>
                                <td class="px-4 py-2 text-center">{{ $batch->expired_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 text-center font-semibold">{{ $batch->quantity }}</td>
                                <td class="px-4 py-2">
                                    <x-text-input type="number" wire:model.live="physicalStocks.{{ $batch->id }}" class="w-full text-center" placeholder="-" />
                                </td>
                                <td class="px-4 py-2 text-center font-bold">
                                    @php
                                    $physical = $physicalStocks[$batch->id] ?? '';
                                    if ($physical !== '') {
                                    $difference = (int)$physical - $batch->quantity;
                                    $colorClass = $difference == 0 ? 'text-gray-700' : ($difference > 0 ? 'text-green-600' : 'text-red-600');
                                    echo "<span class='{$colorClass}'>" . ($difference > 0 ? '+' : '') . $difference . "</span>";
                                    } else {
                                    echo "<span class='text-gray-400'>-</span>";
                                    }
                                    @endphp
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-gray-500">Batch obat tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $batches->links() }}
                </div>

                <div class="flex justify-end pt-6 mt-6 border-t">
                    <x-primary-button wire:click="saveOpname" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="saveOpname">Simpan Hasil & Sesuaikan Stok</span>
                        <span wire:loading wire:target="saveOpname">Menyimpan...</span>
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>
</div>
