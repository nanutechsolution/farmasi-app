<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Application Log Viewer</h2>
        </div>
    </x-slot>

    <div class="py-6">

        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-4">
                <x-danger-button wire:click="confirmClearLog">Bersihkan Log</x-danger-button>
            </div>
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">

                @if (session()->has('success')) <div class="px-4 py-2 mb-4 text-green-800 bg-green-200 rounded">{{ session('success') }}</div> @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Level</th>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Pesan</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($logs as $log)
                            @php
                            $levelClass = match($log['level']) {
                            'ERROR' => 'bg-red-100 text-red-800',
                            'WARNING' => 'bg-yellow-100 text-yellow-800',
                            'INFO' => 'bg-blue-100 text-blue-800',
                            default => 'bg-gray-100 text-gray-800',
                            };
                            @endphp
                            <tr>
                                <td class="px-4 py-2"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $levelClass }}">{{ $log['level'] }}</span></td>
                                <td class="px-4 py-2 text-sm text-gray-500 whitespace-nowrap">{{ $log['timestamp'] }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 truncate" style="max-width: 500px;">{{ $log['message'] }}</td>
                                <td class="px-4 py-2 text-center">
                                    <button wire:click="showDetail({{ $loop->index }})" class="text-indigo-600">Detail</button> </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center">File log kosong.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $logs->links() }}</div>
            </div>
        </div>
    </div>

    <x-modal name="log-detail-modal" maxWidth="2xl" focuscable>
        <div class="p-6">
            <h2 class="text-lg font-medium">Detail Log</h2>
            @if ($selectedLog)
            <div class="p-4 mt-4 text-sm text-gray-800 bg-gray-50 rounded-lg">
                <p><strong>Level:</strong> {{ $selectedLog['level'] }}</p>
                <p><strong>Tanggal:</strong> {{ $selectedLog['timestamp'] }}</p>
                {{-- <p class="mt-2"><strong>Message:</strong> {{ $selectedLog['message'] }}</p> --}}
            </div>
            <pre class="w-full p-4 mt-2 overflow-x-auto text-xs text-white bg-gray-800 rounded-md"><code>{{ $selectedLog['full'] }}</code></pre>
            @endif
            <div class="flex justify-end mt-6">
                <x-secondary-button x-on:click="$dispatch('close')">Tutup</x-secondary-button>
            </div>
        </div>
    </x-modal>

    <x-modal name="confirm-clear-log-modal">
        <div class="p-6">
            <h2 class="text-lg font-medium">Anda yakin ingin membersihkan file log?</h2>
            <p class="mt-1 text-sm text-gray-600">Aksi ini tidak bisa dibatalkan. Semua catatan error akan hilang.</p>
            <div class="flex justify-end mt-6">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-danger-button wire:click="clearLog" class="ms-3">Ya, Bersihkan</x-danger-button>
            </div>
        </div>
    </x-modal>
</div>
