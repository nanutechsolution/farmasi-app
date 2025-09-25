<?php

namespace App\Livewire\Report;

use App\Models\Expense;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Financial extends Component
{
    public $startDate;
    public $endDate;

    // Method ini dijalankan saat komponen pertama kali dimuat
    public function mount()
    {
        // Set default rentang tanggal ke bulan ini
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    public function render()
    {
        // Ambil transaksi dalam rentang tanggal yang dipilih
        // Eager load relasi untuk menghindari N+1 problem
        $transactions = Transaction::with('details.medicine')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->latest()
            ->get();

        // --- Kalkulasi Metrik Keuangan ---

        // 1. Total Omzet (Pendapatan Kotor)
        $totalRevenue = $transactions->sum('total_amount');

        // 2. Total Modal (Harga Pokok Penjualan)
        $totalCogs = 0;
        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $detail) {
                // HPP = harga beli obat * jumlah yang terjual
                $totalCogs += $detail->medicine->cost_price * $detail->quantity;
            }
        }

        // 3. Total Laba (Profit)
        $grossProfit = $totalRevenue - $totalCogs;

        // 4. Total Biaya Operasional
        $totalExpenses = Expense::whereBetween('expense_date', [$this->startDate, $this->endDate])
            ->sum('amount');

        // 5. Laba Bersih (Net Profit)
        $netProfit = $grossProfit - $totalExpenses;
        return view('livewire.report.financial', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalCogs' => $totalCogs,
            'grossProfit' => $grossProfit,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
        ]);
    }
}
