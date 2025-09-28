@props(['count'])
<div class="p-6 overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow-sm">
    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Jenis Obat</h3>
    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $count }}</p>
    <a href="{{ route('medicines.index') }}" wire:navigate class="mt-2 text-sm text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
</div>
