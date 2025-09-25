<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Database\Seeder;

class RealisticMedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada kategori di database
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->info('Membuat kategori default karena kosong...');
            $categories = collect([
                Category::firstOrCreate(['name' => 'Analgesik']),
                Category::firstOrCreate(['name' => 'Antibiotik']),
                Category::firstOrCreate(['name' => 'Antihistamin']),
                Category::firstOrCreate(['name' => 'Vitamin']),
                Category::firstOrCreate(['name' => 'Obat Batuk & Flu']),
            ]);
        }

        $medicines = [
            // Analgesik
            ['name' => 'Paracetamol 500mg', 'category' => 'Analgesik', 'barcode' => '8992761134567', 'price' => 5000, 'cost_price' => 3500, 'margin' => 30, 'unit' => 'strip'],
            ['name' => 'Ibuprofen 400mg', 'category' => 'Analgesik', 'barcode' => '8992761134568', 'price' => 8000, 'cost_price' => 6000, 'margin' => 25, 'unit' => 'strip'],
            ['name' => 'Asam Mefenamat 500mg', 'category' => 'Analgesik', 'barcode' => '8992761134569', 'price' => 7500, 'cost_price' => 5500, 'margin' => 30, 'unit' => 'strip'],

            // Antibiotik
            ['name' => 'Amoxicillin 500mg', 'category' => 'Antibiotik', 'barcode' => '8993241187654', 'price' => 15000, 'cost_price' => 12000, 'margin' => 25, 'unit' => 'strip'],
            ['name' => 'Ciprofloxacin 500mg', 'category' => 'Antibiotik', 'barcode' => '8993241187655', 'price' => 25000, 'cost_price' => 21000, 'margin' => 20, 'unit' => 'strip'],

            // Antihistamin (Alergi)
            ['name' => 'Cetirizine 10mg', 'category' => 'Antihistamin', 'barcode' => '8995543210987', 'price' => 12000, 'cost_price' => 9000, 'margin' => 30, 'unit' => 'strip'],
            ['name' => 'Loratadine 10mg', 'category' => 'Antihistamin', 'barcode' => '8995543210988', 'price' => 13500, 'cost_price' => 10000, 'margin' => 35, 'unit' => 'strip'],

            // Vitamin
            ['name' => 'Vitamin C 500mg (IPI)', 'category' => 'Vitamin', 'barcode' => '8997765432109', 'price' => 9000, 'cost_price' => 7000, 'margin' => 25, 'unit' => 'botol'],
            ['name' => 'Vitamin B Complex', 'category' => 'Vitamin', 'barcode' => '8997765432110', 'price' => 11000, 'cost_price' => 8500, 'margin' => 30, 'unit' => 'botol'],
            ['name' => 'Sangobion', 'category' => 'Vitamin', 'barcode' => '8997765432111', 'price' => 22000, 'cost_price' => 18000, 'margin' => 22, 'unit' => 'strip'],

            // Obat Batuk & Flu
            ['name' => 'OBH Combi Batuk Flu 100ml', 'category' => 'Obat Batuk & Flu', 'barcode' => '8998876543210', 'price' => 18000, 'cost_price' => 15000, 'margin' => 20, 'unit' => 'botol'],
            ['name' => 'Woods Peppermint Expectorant 60ml', 'category' => 'Obat Batuk & Flu', 'barcode' => '8998876543211', 'price' => 23000, 'cost_price' => 19500, 'margin' => 18, 'unit' => 'botol'],
            ['name' => 'Tolak Angin Cair', 'category' => 'Obat Batuk & Flu', 'barcode' => '8998876543212', 'price' => 3500, 'cost_price' => 2800, 'margin' => 25, 'unit' => 'sachet'],
            ['name' => 'Decolgen FX', 'category' => 'Obat Batuk & Flu', 'barcode' => '8998876543213', 'price' => 4000, 'cost_price' => 3000, 'margin' => 33, 'unit' => 'strip'],
            ['name' => 'Bodrex Flu & Batuk', 'category' => 'Obat Batuk & Flu', 'barcode' => '8998876543214', 'price' => 3000, 'cost_price' => 2200, 'margin' => 36, 'unit' => 'strip'],
        ];

        foreach ($medicines as $medicineData) {
            $category = $categories->where('name', $medicineData['category'])->first();

            Medicine::updateOrCreate(
                ['barcode' => $medicineData['barcode']],
                [
                    'name' => $medicineData['name'],
                    'category_id' => $category->id,
                    'price' => $medicineData['price'],
                    'cost_price' => $medicineData['cost_price'],
                    'margin' => $medicineData['margin'],
                    'unit' => $medicineData['unit'],
                ]
            );
        }
    }
}
