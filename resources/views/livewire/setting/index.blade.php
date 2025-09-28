<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Pengaturan Aplikasi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                @if (session()->has('success'))
                <div class="px-4 py-2 mb-4 text-green-800 bg-green-200 rounded">
                    {{ session('success') }}
                </div>
                @endif

                <form wire:submit="save" class="space-y-6">
                    <div>
                        <x-input-label for="appName" value="Nama Apotek / Aplikasi" />
                        <x-text-input wire:model="appName" id="appName" type="text" class="w-full mt-1" />
                        <x-input-error :messages="$errors->get('appName')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="logoUpload" value="Logo Aplikasi" />
                        <x-text-input wire:model="logoUpload" id="logoUpload" type="file" class="block w-full mt-1" />
                        <x-input-error :messages="$errors->get('logoUpload')" class="mt-2" />
                        @if ($appLogo)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">Logo Saat Ini:</p>
                            <img src="{{ asset('storage/' . $appLogo) }}" alt="Logo" class="h-16 mt-2">
                        </div>
                        @endif
                    </div>
                    <div>
                        <x-input-label for="appAddress" value="Alamat Apotek" />
                        <textarea wire:model="appAddress" id="appAddress" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <x-input-error :messages="$errors->get('appAddress')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="appPhone" value="No. Telepon" />
                        <x-text-input wire:model="appPhone" id="appPhone" type="text" class="w-full mt-1" />
                        <x-input-error :messages="$errors->get('appPhone')" class="mt-2" />
                    </div>
                    <div class="mt-6 pt-6 border-t">
                        <h3 class="text-md font-semibold">Pengaturan Absensi GPS</h3>
                        <p class="text-sm text-gray-500">Isi koordinat lokasi apotek. Anda bisa mendapatkannya dari Google Maps.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="officeLatitude" value="Latitude" />
                                <x-text-input wire:model="officeLatitude" id="officeLatitude" type="text" class="w-full mt-1" placeholder="-6.2088" />
                                <x-input-error :messages="$errors->get('officeLatitude')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="officeLongitude" value="Longitude" />
                                <x-text-input wire:model="officeLongitude" id="officeLongitude" type="text" class="w-full mt-1" placeholder="106.8456" />
                                <x-input-error :messages="$errors->get('officeLongitude')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <x-primary-button>Simpan Pengaturan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
