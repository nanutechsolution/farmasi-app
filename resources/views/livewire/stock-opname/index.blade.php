<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Riwayat Stok Opname
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Petugas</th>
                            <th class="px-4 py-2 text-left">Catatan</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($opnames as $opname)
                            <tr>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($opname->opname_date)->format('d M Y') }}</td>
                                <td class="px-4 py-2">{{ $opname->user->name }}</td>
                                <td class="px-4 py-2">{{ $opname->notes ?? '-' }}</td>
                                <td class="px-4 py-2"><span class="px-2 py-1 text-xs text-white bg-green-600 rounded-full">{{ $opname->status }}</span></td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('stock-opnames.show', $opname) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-center text-gray-500">Belum ada riwayat stok opname.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $opnames->links() }}</div>
            </div>
        </div>
    </div>
</div>
