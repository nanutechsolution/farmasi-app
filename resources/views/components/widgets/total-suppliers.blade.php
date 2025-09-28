@props(['count'])

<div class="p-6 overflow-hidden bg-white rounded-lg shadow-sm">
    <h3 class="text-sm font-medium text-gray-500 truncate">Total Supplier</h3>
    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $count }}</p>
    <a href="{{ route('suppliers.index') }}" wire:navigate class="mt-2 text-sm text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
</div>
