<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    /**
     * Menghasilkan dan menampilkan struk transaksi dalam format PDF.
     */
    public function print(string $invoice_number)
    {
        // Cari transaksi berdasarkan nomor invoice. Jika tidak ada, tampilkan error 404.
        $transaction = Transaction::where('invoice_number', $invoice_number)->firstOrFail();

        // Eager load relasi yang dibutuhkan untuk efisiensi
        $transaction->load('details.medicine', 'user');

        // Muat view dengan data transaksi, lalu ubah menjadi PDF
        $pdf = Pdf::loadView('transactions.receipt', compact('transaction'));

        // Tampilkan PDF di browser
        return $pdf->stream('struk-' . $transaction->invoice_number . '.pdf');
    }
}