<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function printFinancialReport(Request $request)
    {
        // Ambil tanggal dari request, jika tidak ada, gunakan bulan ini
        $startDate = $request->input('startDate', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('endDate', Carbon::now()->endOfMonth()->toDateString());

        // 1. Ambil data transaksi
        $transactions = Transaction::with('details.medicine')->whereBetween('created_at', [$startDate, $endDate])->get();
        $totalRevenue = $transactions->sum('total_amount');

        // 2. Hitung HPP dan Laba Kotor
        $totalCogs = $transactions->flatMap->details->sum(fn($detail) => $detail->medicine->cost_price * $detail->quantity);
        $grossProfit = $totalRevenue - $totalCogs;

        // 3. Ambil data biaya operasional
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->get();
        $totalExpenses = $expenses->sum('amount');

        // 4. Hitung Laba Bersih
        $netProfit = $grossProfit - $totalExpenses;

        // 5. Ambil Top 5 Produk Terlaris
        $topProducts = TransactionDetail::with('medicine')
            ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->select('medicine_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * price) as total_revenue'))
            ->groupBy('medicine_id')
            ->orderBy('total_revenue', 'desc')
            ->take(5)
            ->get();

        // 6. Siapkan data untuk Pie Chart Biaya (menggunakan API QuickChart)
        $expenseChartUrl = '';
        $expenseByCategory = $expenses->groupBy('category')->map->sum('amount');
        if ($expenseByCategory->isNotEmpty()) {
            $chartConfig = [
                'type' => 'pie',
                'data' => [
                    'labels' => $expenseByCategory->keys()->all(),
                    'datasets' => [[
                        'data' => $expenseByCategory->values()->all(),
                        'backgroundColor' => ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc949'], // contoh warna slice
                    ]]
                ],
                'options' => [
                    'plugins' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Komposisi Biaya Operasional'
                        ],
                        'legend' => [
                            'labels' => [
                                'color' => 'white'   // warna teks legend putih
                            ]
                        ],
                        'datalabels' => [
                            'color' => 'white',    // warna angka di dalam chart jadi putih
                            'font' => [
                                'weight' => 'bold'
                            ]
                        ]
                    ]
                ]
            ];

            $expenseChartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig));
        }
        // Kumpulkan semua data untuk dikirim ke view
        $data = compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalCogs',
            'grossProfit',
            'totalExpenses',
            'netProfit',
            'topProducts',
            'expenses',
            'expenseChartUrl'
        );

        // Render PDF
        $pdf = Pdf::loadView('reports.financial-pdf', $data);
        $pdf->setOption('isRemoteEnabled', true);
        return $pdf->stream('laporan-keuangan-' . $startDate . '-' . $endDate . '.pdf');
    }
}
