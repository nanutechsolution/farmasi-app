<?php

namespace App\Livewire\Setting;

use App\Settings\GeneralSettings;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    // Properti untuk menampung data dari form
    public string $appName;
    public string $appAddress;
    public string $appPhone;

    // Method ini dijalankan saat komponen dimuat
    public function mount(GeneralSettings $settings)
    {
        // Isi properti dengan data pengaturan yang ada
        $this->appName = $settings->app_name ?? 'Farmasi App';
        $this->appAddress = $settings->app_address ?? 'Jl. Sehat No. 123';
        $this->appPhone = $settings->app_phone ?? '(021) 123-4567';
    }

    // Method untuk menyimpan pengaturan
    public function save(GeneralSettings $settings)
    {
        $validated = $this->validate([
            'appName' => 'required|string|max:255',
            'appAddress' => 'required|string',
            'appPhone' => 'required|string',
        ]);

        // Simpan data ke pengaturan
        $settings->app_name = $validated['appName'];
        $settings->app_address = $validated['appAddress'];
        $settings->app_phone = $validated['appPhone'];
        $settings->save();

        session()->flash('success', 'Pengaturan berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.setting.index');
    }
}
