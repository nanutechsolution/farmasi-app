<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua obat yang stoknya ada & user dengan role kasir
        $medicines = Medicine::where('stock', '>', 0)->get();
        $kasirUser = User::role('Kasir')->first();

        // Jangan jalankan seeder jika tidak ada obat atau kasir
        if ($medicines->isEmpty() || !$kasirUser) {
            $this->command->info('Tidak dapat menjalankan TransactionSeeder: Tidak ada obat atau user kasir.');
            return;
        }

        $this->command->info('Membuat data transaksi dummy untuk 7 hari terakhir...');

        // Loop untuk setiap hari dalam 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            // Buat 2 sampai 5 transaksi acak per hari
            for ($j = 0; $j < rand(2, 5); $j++) {

                $totalAmount = 0;
                $cart = [];
                $transactionDate = now()->subDays($i)->addHours(rand(8, 17)); // Waktu acak antara jam 8 pagi - 5 sore

                // Setiap transaksi berisi 1 sampai 4 jenis obat
                $itemsInCart = $medicines->random(rand(1, 4));

                foreach($itemsInCart as $medicine) {
                    $quantity = rand(1, 3);
                    $subtotal = $medicine->price * $quantity;
                    $totalAmount += $subtotal;
                    $cart[] = [
                        'medicine_id' => $medicine->id,
                        'quantity' => $quantity,
                        'price' => $medicine->price,
                    ];
                }

                // Buat transaksi utama
                $transaction = Transaction::create([
                    'invoice_number' => 'INV-DUMMY-' . time() . rand(100, 999),
                    'user_id' => $kasirUser->id,
                    'total_amount' => $totalAmount,
                    'paid_amount' => $totalAmount + rand(0, 10000), // Bayar lebih sedikit untuk kembalian
                    'created_at' => $transactionDate,
                    'updated_at' => $transactionDate,
                ]);

                // Simpan detail transaksi
                $transaction->details()->createMany($cart);
            }
        }
    }
}
