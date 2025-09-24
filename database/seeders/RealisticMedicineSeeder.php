<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            $this->command->info('Tidak ada kategori. Silakan jalankan CategorySeeder terlebih dahulu.');
            // Buat beberapa kategori default jika kosong
            $categories = collect([
                Category::create(['name' => 'Analgesik']),
                Category::create(['name' => 'Antibiotik']),
                Category::create(['name' => 'Antihistamin']),
                Category::create(['name' => 'Vitamin']),
                Category::create(['name' => 'Obat Batuk & Flu']),
            ]);
        }

        $medicines = [
            // Analgesik
            ['name' => 'Paracetamol 500mg', 'category' => 'Analgesik', 'barcode' => '8992761134567', 'stock' => 100, 'price' => 5000, 'cost_price' => 3500, 'unit' => 'strip'],
            ['name' => 'Ibuprofen 400mg', 'category' => 'Analgesik', 'barcode' => '8992761134568', 'stock' => 80, 'price' => 8000, 'cost_price' => 6000, 'unit' => 'strip'],
            ['name' => 'Asam Mefenamat 500mg', 'category' => 'Analgesik', 'barcode' => '8992761134569', 'stock' => 50, 'price' => 7500, 'cost_price' => 5500, 'unit' => 'strip'],

            // Antibiotik
            ['name' => 'Amoxicillin 500mg', 'category' => 'Antibiotik', 'barcode' => '8993241187654', 'stock' => 120, 'price' => 15000, 'cost_price' => 12000, 'unit' => 'strip'],
            ['name' => 'Ciprofloxacin 500mg', 'category' => 'Antibiotik', 'barcode' => '8993241187655', 'stock' => 60, 'price' => 25000, 'cost_price' => 21000, 'unit' => 'strip'],

            // Antihistamin (Alergi)
            ['name' => 'Cetirizine 10mg', 'category' => 'Antihistamin', 'barcode' => '8995543210987', 'stock' => 75, 'price' => 12000, 'cost_price' => 9000, 'unit' => 'strip'],
            ['name' => 'Loratadine 10mg', 'category' => 'Antihistamin', 'barcode' => '8995543210988', 'stock' => 65, 'price' => 13500, 'cost_price' => 10000, 'unit' => 'strip'],

            // Vitamin
            ['name' => 'Vitamin C 500mg (IPI)', 'category' => 'Vitamin', 'barcode' => '8997765432109', 'stock' => 200, 'price' => 9000, 'cost_price' => 7000, 'unit' => 'botol'],
            ['name' => 'Vitamin B Complex', 'category' => 'Vitamin', 'barcode' => '8997765432110', 'stock' => 150, 'price' => 11000, 'cost_price' => 8500, 'unit' => 'botol'],
            ['name' => 'Sangobion', 'category' => 'Vitamin', 'barcode' => '8997765432111', 'stock' => 90, 'price' => 22000, 'cost_price' => 18000, 'unit' => 'strip'],

            // Obat Batuk & Flu
            ['name' => 'OBH Combi Batuk Flu 100ml', 'category' => 'Obat Batuk & Flu', 'barcode' => '8998876543210', 'stock' => 100, 'price' => 18000, 'cost_price' => 15000, 'unit' => 'botol'],
            ['name' => 'Woods Peppermint Expectorant 60ml', 'category' => 'Obat Batuk & Flu', 'barcode' => '8998876543211', 'stock' => 85, 'price' => 23000, 'cost_price' => 19500, 'unit' => 'botol'],
            ['name' => 'Tolak Angin Cair', 'category' => 'Obat Batuk & Flu', 'barcode' => '8998876543212', 'stock' => 300, 'price' => 3500, 'cost_price' => 2800, 'unit' => 'sachet'],
            ['name' => 'Decolgen FX', 'category' => 'Obat Batuk & Flu', 'barcode' => '8998876543213', 'stock' => 120, 'price' => 4000, 'cost_price' => 3000, 'unit' => 'strip'],
            ['name' => 'Bodrex Flu & Batuk', 'category' => 'Obat Batuk & Flu', 'barcode' => '8998876543214', 'stock' => 150, 'price' => 3000, 'cost_price' => 2200, 'unit' => 'strip'],
        ];

        foreach ($medicines as $medicine) {
            // Cari ID kategori berdasarkan nama
            $category = $categories->where('name', $medicine['category'])->first();

            Medicine::create([
                'name' => $medicine['name'],
                'category_id' => $category->id,
                'barcode' => $medicine['barcode'],
                'stock' => $medicine['stock'],
                'price' => $medicine['price'],
                'cost_price' => $medicine['cost_price'],
                'unit' => $medicine['unit'],
                'expired_date' => now()->addMonths(rand(6, 24)), // Tanggal kadaluarsa acak 6-24 bulan dari sekarang
            ]);
        }
    }
}
