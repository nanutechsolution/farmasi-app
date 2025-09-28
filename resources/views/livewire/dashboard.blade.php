<div class="col-span-1 mb-6 sm:col-span-2 lg:col-span-4" x-data="{
        status: 'idle', // idle, getting_location, submitting, error, success
        message: '',
        getLocationAndClockIn() {
            this.status = 'getting_location';
            this.message = 'Sedang mendapatkan lokasi Anda...';

            if (!navigator.geolocation) {
                this.status = 'error';
                this.message = 'Browser Anda tidak mendukung Geolocation.';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.status = 'submitting';
                    this.message = 'Lokasi didapatkan, mengirim data...';
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    @this.call('clockIn', lat, lon);
                },
                () => {
                    this.status = 'error';
                    this.message = 'Tidak bisa mendapatkan lokasi. Pastikan Anda mengizinkan akses lokasi.';
                }
            );
        }
    }" @attendance-error.window="status = 'error'; message = $event.detail;" @attendance-success.window="status = 'success'; message = $event.detail; setTimeout(() => status = 'idle', 3000);">
    <div class="p-6 overflow-hidden bg-white rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold">Absensi Hari Ini</h3>
        @if($isClockedIn)
        <div class="mt-4">
            <p class="text-green-600">Anda sudah clock in pada: <strong>{{ $todaysAttendance->clock_in_time->format('H:i:s') }}</strong></p>
            <x-danger-button wire:click="clockOut" class="mt-4">Clock Out</x-danger-button>
        </div>
        @elseif($todaysAttendance && $todaysAttendance->clock_out_time)
        <div class="mt-4">
            <p class="text-gray-600">Terima kasih! Anda sudah clock out hari ini pada: <strong>{{ $todaysAttendance->clock_out_time->format('H:i:s') }}</strong></p>
        </div>
        @else
        <div class="mt-4">
            <x-primary-button @click="getLocationAndClockIn()" x-bind:disabled="status !== 'idle' && status !== 'error'">
                <svg x-show="status === 'getting_location' || status === 'submitting'" class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" ...>...</svg>
                <span x-text="status === 'idle' || status === 'error' ? 'Clock In Sekarang' : message"></span>
            </x-primary-button>
            <p x-show="message" class="mt-2 text-sm" :class="{ 'text-red-600': status === 'error', 'text-green-600': status === 'success', 'text-gray-600': status !== 'error' && status !== 'success' }" x-text="message"></p>
        </div>
        @endif
    </div>
    <div class="py-6 px-2 sm:px-6 lg:px-8 space-y-2">
        {{-- Tombol setting --}}
        <div class="flex justify-end">
            <x-secondary-button wire:click="showSettingsModal">
                ⚙️ Kustomisasi Dashboard
            </x-secondary-button>
        </div>
        {{-- Grid widget --}}
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div wire:sortable="updateWidgetOrder" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($activeWidgets as $widget)
                <div wire:sortable.item="{{ $widget['key'] }}" wire:key="widget-{{ $widget['key'] }}" class="@if ($widget['key'] === 'sales-chart') col-span-1 sm:col-span-2 lg:col-span-4 @endif">
                    <div wire:sortable.handle class="cursor-move h-full">
                        @switch($widget['key'])
                        @case('total-medicines')
                        <x-widgets.total-medicines :count="$medicineCount" />
                        @break
                        @case('total-suppliers')
                        <x-widgets.total-suppliers :count="$supplierCount" />
                        @break
                        @case('low-stock')
                        <x-widgets.low-stock :count="$lowStockCount" />
                        @break
                        @case('expiring-soon')
                        <x-widgets.expiring-soon :count="$expiringSoonCount" />
                        @break
                        @case('sales-chart')
                        <x-widgets.sales-chart :labels="$salesLabels" :data="$salesData" />
                        @break
                        @endswitch
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- Modal Setting Widget --}}
    <x-modal name="settings-modal" focusable>
        <div class="p-6 space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    🛠️ Atur Tampilan Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Aktifkan atau nonaktifkan widget sesuai kebutuhan Anda.
                </p>
            </div>

            {{-- Daftar widget dengan toggle --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($allWidgets as $widget)
                <div class="flex items-center justify-between p-4 rounded-lg border transition hover:bg-gray-50
                    {{ collect($activeWidgets)->contains('key', $widget['key']) ? 'bg-indigo-50 border-indigo-400' : 'bg-white border-gray-200' }}">

                    <span class="font-medium text-gray-800">{{ $widget['name'] }}</span>

                    {{-- Toggle Switch --}}
                    <button type="button" wire:click="toggleWidget('{{ $widget['key'] }}')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none
                        {{ collect($activeWidgets)->contains('key', $widget['key']) ? 'bg-indigo-600' : 'bg-gray-300' }}">

                        <span class="sr-only">Toggle {{ $widget['name'] }}</span>

                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                            {{ collect($activeWidgets)->contains('key', $widget['key']) ? 'translate-x-6' : 'translate-x-1' }}">
                        </span>
                    </button>
                </div>
                @endforeach
            </div>

            {{-- Tombol selesai --}}
            <div class="flex justify-end">
                <x-primary-button x-on:click="$dispatch('close-modal', 'settings-modal')">
                    ✅ Selesai
                </x-primary-button>
            </div>
        </div>
    </x-modal>

</div>
