<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Settings\GeneralSettings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat instance dari class pengaturan
        $settings = app(GeneralSettings::class);

        // Mengisi nilai default
        $settings->app_name = 'Apotek Farmasi Sejahtera';
        $settings->app_address = 'Jl. Digital Raya No. 17, Kota Teknologi';
        $settings->app_phone = '(021) 555-2024';

        // Menyimpan pengaturan ke database
        $settings->save();
    }
}
