<?php

namespace App\Livewire\Report;

use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StockAnalysis extends Component
{
    public $startDate;
    public $endDate;

    public function mount()
    {
        // Set default rentang tanggal ke 30 hari terakhir
        $this->endDate = Carbon::now()->toDateString();
        $this->startDate = Carbon::now()->subDays(30)->toDateString();
    }

    public function render()
    {
        // Query dasar untuk menganalisis detail transaksi dalam rentang tanggal
        $baseQuery = TransactionDetail::with('medicine')
            ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$this->startDate, $this->endDate]))
            ->select('medicine_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('medicine_id');

        // Clone query dasar untuk mendapatkan produk terlaris
        $fastMovingProducts = (clone $baseQuery)
            ->orderBy('total_quantity', 'desc')
            ->take(10) // Ambil 10 teratas
            ->get();

        // Clone query dasar untuk mendapatkan produk paling lambat laku
        $slowMovingProducts = (clone $baseQuery)
            ->orderBy('total_quantity', 'asc')
            ->take(10) // Ambil 10 terbawah
            ->get();

        return view('livewire.report.stock-analysis', [
            'fastMovingProducts' => $fastMovingProducts,
            'slowMovingProducts' => $slowMovingProducts,
        ]);
    }
}
