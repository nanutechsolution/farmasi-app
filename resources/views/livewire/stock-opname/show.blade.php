<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Detail Stok Opname: {{ \Carbon\Carbon::parse($stockOpname->opname_date)->format('d M Y') }}
        </h2>
    </x-slot>

     <div class="py-6 px-2 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 mb-6 bg-white shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-2 gap-4">
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($stockOpname->opname_date)->format('d F Y') }}</p>
                    <p><strong>Petugas:</strong> {{ $stockOpname->user->name }}</p>
                    <p class="col-span-2"><strong>Catatan:</strong> {{ $stockOpname->notes ?? '-' }}</p>
                </div>
            </div>

            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                <h3 class="mb-4 text-lg font-semibold">Rincian Penghitungan</h3>
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Nama Obat</th>
                            <th class="px-4 py-2 text-center">Stok Sistem</th>
                            <th class="px-4 py-2 text-center">Stok Fisik</th>
                            <th class="px-4 py-2 text-center">Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($stockOpname->details as $detail)
                        <tr>
                            <td class="px-4 py-2">{{ $detail->medicine->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $detail->system_stock }}</td>
                            <td class="px-4 py-2 text-center font-bold">{{ $detail->physical_stock }}</td>
                            <td class="px-4 py-2 text-center font-bold {{ $detail->difference == 0 ? '' : ($detail->difference > 0 ? 'text-green-600' : 'text-red-600') }}">
                                {{ $detail->difference > 0 ? '+' : '' }}{{ $detail->difference }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
