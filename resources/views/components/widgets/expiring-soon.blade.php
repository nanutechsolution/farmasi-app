@props(['count'])

<div class="p-6 overflow-hidden bg-red-100 rounded-lg shadow-sm">
    <h3 class="text-sm font-medium text-red-800 truncate">Obat Akan Kadaluarsa</h3>
    <p class="mt-1 text-3xl font-semibold text-red-900">{{ $count }}</p>
    <a href="{{ route('medicines.index') }}" wire:navigate class="mt-2 text-sm text-red-700 hover:text-red-900">Periksa Tanggal</a>
</div>
