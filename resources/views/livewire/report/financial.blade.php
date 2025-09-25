<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Keuangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center space-x-4">
                        <div>
                            <x-input-label for="startDate" value="Tanggal Mulai" />
                            <x-text-input wire:model.live="startDate" id="startDate" type="date" />
                        </div>
                        <div>
                            <x-input-label for="endDate" value="Tanggal Selesai" />
                            <x-text-input wire:model.live="endDate" id="endDate" type="date" />
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('reports.financial.print', ['startDate' => $startDate, 'endDate' => $endDate]) }}" target="_blank" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700">
                            Cetak Laporan (PDF)
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2 xl:grid-cols-4">
                    <div class="p-6 bg-blue-100 rounded-lg">
                        <h3 class="text-sm font-medium text-blue-800">Total Omzet (Pendapatan)</h3>
                        <p class="mt-1 text-3xl font-semibold text-blue-900">Rp {{ number_format($totalRevenue) }}</p>
                    </div>
                    <div class="p-6 bg-orange-100 rounded-lg">
                        <h3 class="text-sm font-medium text-orange-800">Total Modal (HPP)</h3>
                        <p class="mt-1 text-3xl font-semibold text-orange-900">Rp {{ number_format($totalCogs) }}</p>
                    </div>
                    <div class="p-6 bg-red-100 rounded-lg">
                        <h3 class="text-sm font-medium text-red-800">Total Biaya Operasional</h3>
                        <p class="mt-1 text-3xl font-semibold text-red-900">Rp {{ number_format($totalExpenses) }}</p>
                    </div>
                    <div class="p-6 bg-green-100 rounded-lg ring-2 ring-green-600">
                        <h3 class="text-sm font-medium text-green-800">LABA BERSIH (NET PROFIT)</h3>
                        <p class="mt-1 text-3xl font-bold text-green-900">Rp {{ number_format($netProfit) }}</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-semibold">Rincian Transaksi ({{ count($transactions) }} Transaksi)</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">No. Invoice</th>
                                    <th class="px-4 py-2 text-left">Tanggal</th>
                                    <th class="px-4 py-2 text-left">Total</th>
                                    <th class="px-4 py-2 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $transaction->invoice_number }}</td>
                                    <td class="px-4 py-2">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-4 py-2">Rp
                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('transactions.print', $transaction->invoice_number) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Cetak
                                            Struk</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada transaksi
                                        pada rentang tanggal ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
