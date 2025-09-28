<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class RealisticLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Etalase Depan', 'description' => 'Area display utama dekat kasir'],
            ['name' => 'Rak A-01', 'description' => 'Obat bebas & vitamin'],
            ['name' => 'Rak A-02', 'description' => 'Obat bebas & vitamin'],
            ['name' => 'Rak B-01', 'description' => 'Obat resep & antibiotik'],
            ['name' => 'Kulkas Farmasi', 'description' => 'Obat yang memerlukan pendingin'],
            ['name' => 'Lemari Psikotropika', 'description' => 'Penyimpanan khusus terkunci'],
            ['name' => 'Area Perlengkapan Bayi', 'description' => 'Sabun, popok, dan lainnya'],
            ['name' => 'Gudang Belakang', 'description' => 'Stok cadangan'],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate(['name' => $location['name']], $location);
        }
    }
}
