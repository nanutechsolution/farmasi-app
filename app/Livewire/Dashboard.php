<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    // app/Livewire/Dashboard.php

    public function render()
    {
        // --- Statistik untuk Kartu (Tidak Berubah) ---
        $medicineCount = Medicine::count();
        $supplierCount = Supplier::count();
        $lowStockCount = Medicine::where('stock', '<=', 10)->count();
        $expiringSoonCount = Medicine::where('expired_date', '<=', now()->addDays(30))->count();

        // --- LOGIKA BARU YANG LEBIH AMAN UNTUK GRAFIK ---

        // 1. Ambil data mentah seperti sebelumnya, tapi kita buat 'date' sebagai key
        $salesDataRaw = Transaction::where('created_at', '>=', now()->subDays(6)) // Ambil 7 hari termasuk hari ini
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date'); // Jadikan tanggal sebagai key/index

        // 2. Siapkan array kosong untuk menampung hasil akhir
        $salesLabels = [];
        $salesData = [];

        // 3. Loop selama 7 hari terakhir untuk memastikan semua hari ada
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');

            // Buat label (contoh: 25 Sep)
            $salesLabels[] = now()->subDays($i)->format('d M');

            // Cek apakah ada data penjualan di tanggal ini
            // Jika ada, masukkan totalnya. Jika tidak, masukkan 0.
            $salesData[] = (float) ($salesDataRaw->get($date)->total ?? 0);
        }

        return view('livewire.dashboard', [
            'medicineCount' => $medicineCount,
            'supplierCount' => $supplierCount,
            'lowStockCount' => $lowStockCount,
            'expiringSoonCount' => $expiringSoonCount,
            'salesLabels' => $salesLabels,
            'salesData' => $salesData,
        ]);
    }
}
