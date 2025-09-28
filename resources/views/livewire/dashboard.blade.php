<div x-data="{
        status: 'idle', // idle, getting_location, submitting, error, success
        message: '',
        toasts: [],
        getLocationAndClockIn() {
            this.status = 'getting_location';
            this.message = 'Sedang mendapatkan lokasi Anda...';

            if (!navigator.geolocation) {
                this.status = 'error';
                this.message = 'Browser Anda tidak mendukung Geolocation.';
                this.addToast('❌ Browser tidak mendukung Geolocation.', 'error');
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
                    this.addToast('❌ Gagal mendapatkan lokasi.', 'error');
                }
            );
        },
        addToast(msg, type = 'info') {
            let id = Date.now();
            this.toasts.push({ id, msg, type });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 3000);
        }
    }" @attendance-error.window="status = 'error'; message = $event.detail; addToast($event.detail, 'error');" @attendance-success.window="status = 'success'; message = $event.detail; addToast($event.detail, 'success'); setTimeout(() => status = 'idle', 3000);" class="col-span-1 mb-6 sm:col-span-2 lg:col-span-4">

    <div class="p-6 overflow-hidden bg-white rounded-xl shadow-md">
        <h3 class="text-lg font-semibold">Absensi Hari Ini</h3>
        {{-- Status clock-in/out --}}
        @if($isClockedIn)
        <div class="mt-4 flex items-center gap-2 p-3 rounded-lg bg-green-50 border border-green-200">
            <span class="text-green-700">✅ Anda sudah clock in pada:
                <strong>{{ $todaysAttendance->clock_in_time->format('H:i:s') }}</strong>
            </span>
            <x-danger-button wire:click="clockOut" class="ml-auto">Clock Out</x-danger-button>
        </div>
        @elseif($todaysAttendance && $todaysAttendance->clock_out_time)
        <div class="mt-4 p-3 rounded-lg bg-gray-50 border border-gray-200">
            <span class="text-gray-700">👋 Terima kasih! Anda sudah clock out hari ini pada:
                <strong>{{ $todaysAttendance->clock_out_time->format('H:i:s') }}</strong>
            </span>
        </div>
        @else
        <div class="mt-4">
            <x-primary-button @click="getLocationAndClockIn()" :disabled="status !== 'idle' && status !== 'error'" class="flex items-center gap-2">
                <svg x-show="status === 'getting_location' || status === 'submitting'" class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a12 12 0 00-12 12h4z"></path>
                </svg>
                <span x-text="status === 'idle' || status === 'error' ? '🕒 Clock In Sekarang' : message"></span>
            </x-primary-button>
            <p x-show="message" class="mt-2 text-sm font-medium transition" :class="{
                    'text-red-600': status === 'error',
                    'text-green-600': status === 'success',
                    'text-gray-500': status === 'getting_location' || status === 'submitting'
                }" x-text="message"></p>
        </div>
        @endif
    </div>

    {{-- Toast Notifications --}}
    <div class="fixed top-4 right-4 space-y-2 z-50" aria-live="polite">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition class="px-4 py-2 rounded-lg shadow-md text-sm font-medium" :class="{
                    'bg-green-100 text-green-700 border border-green-300': toast.type === 'success',
                    'bg-red-100 text-red-700 border border-red-300': toast.type === 'error',
                    'bg-gray-100 text-gray-700 border border-gray-300': toast.type === 'info'
                }" x-text="toast.msg">
            </div>
        </template>
    </div>

    {{-- Bagian Dashboard --}}
    <div class="py-6 px-2 sm:px-6 lg:px-8 space-y-6">
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
                <div wire:sortable.item="{{ $widget['key'] }}" wire:key="widget-{{ $widget['key'] }}" class="transition bg-white rounded-xl shadow-sm hover:shadow-md cursor-move">
                    <div wire:sortable.handle class="h-full">
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
