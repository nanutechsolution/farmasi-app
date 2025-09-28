<?php

namespace App\Livewire\Setting;

use App\Settings\GeneralSettings;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Index extends Component
{

    use WithFileUploads;
    // Properti untuk menampung data dari form
    public string $appName;
    public string $appAddress;
    public string $appPhone;

    // Properti baru
    public $appLogo;
    public $logoUpload;

    public $officeLatitude;
    public $officeLongitude;

    // Method ini dijalankan saat komponen dimuat
    public function mount(GeneralSettings $settings)
    {
        // Isi properti dengan data pengaturan yang ada
        $this->appName = $settings->app_name ?? 'Farmasi App';
        $this->appAddress = $settings->app_address ?? 'Jl. Sehat No. 123';
        $this->appPhone = $settings->app_phone ?? '(021) 123-4567';
        $this->officeLatitude = $settings->office_latitude ?? null;
        $this->officeLongitude = $settings->office_longitude ?? null;
        $this->appLogo = $settings->app_logo;
    }

    // Method untuk menyimpan pengaturan
    public function save(GeneralSettings $settings)
    {
        $validated = $this->validate([
            'appName' => 'required|string|max:255',
            'appAddress' => 'required|string',
            'appPhone' => 'required|string',
            'logoUpload' => 'nullable|image|max:1024',
            'officeLatitude' => 'nullable|numeric|between:-90,90',
            'officeLongitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Simpan data ke pengaturan
        $settings->app_name = $validated['appName'];
        $settings->app_address = $validated['appAddress'];
        $settings->app_phone = $validated['appPhone'];
        $settings->office_latitude = $this->officeLatitude;
        $settings->office_longitude = $this->officeLongitude;

        if ($this->logoUpload) {
            $path = $this->logoUpload->store('logos', 'public');
            $settings->app_logo = $path;
            $this->appLogo = $path;
        }
        $settings->save();

        session()->flash('success', 'Pengaturan berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.setting.index');
    }
}
