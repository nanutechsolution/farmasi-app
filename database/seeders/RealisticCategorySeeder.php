<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class RealisticCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Analgesik'],
            ['name' => 'Antibiotik'],
            ['name' => 'Antihistamin'],
            ['name' => 'Vitamin & Suplemen'],
            ['name' => 'Obat Batuk & Flu'],
            ['name' => 'Alat Kesehatan'],
            ['name' => 'Perlengkapan Bayi'],
            ['name' => 'Herbal'],
            ['name' => 'Obat Kulit'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }
    }
}
