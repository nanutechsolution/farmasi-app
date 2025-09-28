<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Database\Seeder;

class RealisticMedicineSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');

        $medicines = [
            // Analgesik
            ['name' => 'Paracetamol 500mg', 'category' => 'Analgesik', 'barcode' => '8992761134567', 'price' => 5000, 'cost_price' => 3500, 'margin' => 30, 'unit' => 'strip'],
            ['name' => 'Bodrex Extra', 'category' => 'Analgesik', 'barcode' => '8992761134588', 'price' => 2500, 'cost_price' => 1800, 'margin' => 30, 'unit' => 'strip'],

            // Antibiotik
            ['name' => 'Amoxicillin 500mg', 'category' => 'Antibiotik', 'barcode' => '8993241187654', 'price' => 15000, 'cost_price' => 12000, 'margin' => 25, 'unit' => 'strip'],

            // Vitamin & Suplemen
            ['name' => 'Vitamin C 500mg (IPI)', 'category' => 'Vitamin & Suplemen', 'barcode' => '8997765432109', 'price' => 9000, 'cost_price' => 7000, 'margin' => 25, 'unit' => 'botol'],
            ['name' => 'Imboost Force', 'category' => 'Vitamin & Suplemen', 'barcode' => '8997765432120', 'price' => 45000, 'cost_price' => 38000, 'margin' => 20, 'unit' => 'strip'],

            // Alat Kesehatan
            ['name' => 'Masker Medis Sensi', 'category' => 'Alat Kesehatan', 'barcode' => '8991111222333', 'price' => 25000, 'cost_price' => 20000, 'margin' => 25, 'unit' => 'box'],
            ['name' => 'Hansaplast Plester', 'category' => 'Alat Kesehatan', 'barcode' => '8991111222444', 'price' => 7000, 'cost_price' => 5000, 'margin' => 40, 'unit' => 'pack'],

            // Perlengkapan Bayi
            ['name' => 'Sabun Bayi Cussons', 'category' => 'Perlengkapan Bayi', 'barcode' => '8994444555666', 'price' => 12000, 'cost_price' => 9500, 'margin' => 26, 'unit' => 'pcs'],
            ['name' => 'Minyak Telon My Baby', 'category' => 'Perlengkapan Bayi', 'barcode' => '8994444555777', 'price' => 28000, 'cost_price' => 24000, 'margin' => 17, 'unit' => 'botol'],
        ];

        foreach ($medicines as $medicineData) {
            Medicine::updateOrCreate(
                ['barcode' => $medicineData['barcode']],
                [
                    'name' => $medicineData['name'],
                    'category_id' => $categories[$medicineData['category']] ?? null,
                    'price' => $medicineData['price'],
                    'cost_price' => $medicineData['cost_price'],
                    'margin' => $medicineData['margin'],
                    'unit' => $medicineData['unit'],
                ]
            );
        }
    }
}
