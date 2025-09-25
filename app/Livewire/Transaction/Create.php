<?php

namespace App\Livewire\Transaction;

use App\Models\Medicine;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\MedicineBatch;

#[Layout('layouts.app')]
class Create extends Component
{
    public $search = '';
    public $cart = [];
    public $total = 0;

    public $paid_amount = 0;
    public $change = 0;

    public function addToCart($batchId)
    {
        $batch = MedicineBatch::with('medicine')->find($batchId);
        if (!$batch || $batch->quantity <= 0) return;

        // Kunci keranjang sekarang adalah ID BATCH
        if (isset($this->cart[$batchId])) {
            if ($this->cart[$batchId]['quantity'] < $batch->quantity) {
                $this->cart[$batchId]['quantity']++;
            }
        } else {
            $this->cart[$batchId] = [
                'medicine_id' => $batch->medicine_id,
                'batch_id' => $batch->id,
                'name' => $batch->medicine->name . ' (Exp: ' . $batch->expired_date->format('d/m/Y') . ')',
                'price' => $batch->medicine->price,
                'stock' => $batch->quantity, // Stok spesifik batch ini
                'quantity' => 1,
            ];
        }
        $this->calculateTotal();
        $this->search = '';
    }

    public function incrementQuantity($id)
    {
        if ($this->cart[$id]['quantity'] < $this->cart[$id]['stock']) {
            $this->cart[$id]['quantity']++;
            $this->calculateTotal();
        }
    }
    public function decrementQuantity($id)
    {
        if ($this->cart[$id]['quantity'] > 1) {
            $this->cart[$id]['quantity']--;
            $this->calculateTotal();
        }
    }
    public function removeFromCart($id)
    {
        unset($this->cart[$id]);
        $this->calculateTotal();
    }
    private function calculateTotal()
    {
        $this->total = collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $this->calculateChange(); // Hitung kembalian setiap total berubah
    }

    // -- METHOD BARU: untuk menghitung kembalian secara real-time --
    public function updatedPaidAmount($value)
    {
        // Pastikan nilai tidak kosong atau non-numerik
        $this->paid_amount = (float) $value;
        $this->calculateChange();
    }

    private function calculateChange()
    {
        $this->change = $this->paid_amount - $this->total;
    }
    public function processTransaction()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang tidak boleh kosong.');
            return;
        }
        if ($this->paid_amount < $this->total) {
            session()->flash('error', 'Jumlah pembayaran tidak mencukupi.');
            return;
        }

        DB::transaction(function () {
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . time(),
                'user_id' => Auth::id(),
                'total_amount' => $this->total,
                'paid_amount' => $this->paid_amount,
            ]);

            foreach ($this->cart as $item) {
                $transaction->details()->create([
                    'medicine_id' => $item['medicine_id'],
                    'medicine_batch_id' => $item['batch_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Kurangi stok dari BATCH yang spesifik
                MedicineBatch::find($item['batch_id'])->decrement('quantity', $item['quantity']);
            }
        });

        session()->flash('success', 'Transaksi berhasil disimpan.');
        $this->reset(['cart', 'total', 'paid_amount', 'change', 'search']);
    }
    public function scanOrSearch()
    {
        $this->search = trim($this->search);
        if (empty($this->search)) return;

        // Prioritas 1: Cari berdasarkan Barcode
        $medicine_by_barcode = Medicine::where('barcode', $this->search)->first();

        if ($medicine_by_barcode) {
            $this->addToCart($medicine_by_barcode->id);
            $this->reset('search');
            return;
        }
        // Prioritas 2: Cari berdasarkan Nama (jika barcode tidak ketemu)
        $medicines_by_name = Medicine::where('name', 'like', '%' . $this->search . '%')
            ->where('stock', '>', 0)
            ->get();
        // Jika hasil pencarian nama hanya ada 1, langsung tambahkan
        if ($medicines_by_name->count() === 1) {
            $this->addToCart($medicines_by_name->first()->id);
            $this->reset('search');
            return;
        }

        // Jika tidak ada yang cocok sama sekali, beri pesan error
        session()->flash('scan-error', 'Barcode tidak ditemukan atau nama obat tidak spesifik.');
    }

    public function render()
    {
        $batches = [];
        if (strlen($this->search) >= 2) {
            $batches = MedicineBatch::with('medicine')
                ->whereHas('medicine', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                ->where('quantity', '>', 0)
                ->whereDate('expired_date', '>', now()) // Hanya tampilkan yg belum kadaluarsa
                ->orderBy('expired_date', 'asc') // Urutkan agar yg cepat expired muncul duluan
                ->take(10)
                ->get();
        }
        return view('livewire.transaction.create', [
            'batches' => $batches,
        ]);
    }
}
