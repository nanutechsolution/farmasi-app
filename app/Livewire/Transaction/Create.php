<?php

namespace App\Livewire\Transaction;

use App\Models\Medicine;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Create extends Component
{
    public $search = '';
    public $cart = [];
    public $total = 0;

    // -- PROPERTI BARU --
    public $paid_amount = 0;
    public $change = 0;

    // Method addToCart, incrementQuantity, decrementQuantity, removeFromCart (Tidak berubah)
    public function addToCart($id)
    {
        $medicine = Medicine::find($id);
        if (!$medicine || $medicine->stock <= 0) return;

        if (isset($this->cart[$id])) {
            if ($this->cart[$id]['quantity'] < $medicine->stock) {
                $this->cart[$id]['quantity']++;
            }
        } else {
            $this->cart[$id] = [
                'medicine_id' => $medicine->id,
                'name' => $medicine->name,
                'price' => $medicine->price,
                'stock' => $medicine->stock,
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

    // -- METHOD BARU: untuk memproses dan menyimpan transaksi --
    public function processTransaction()
    {
        // Validasi
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang tidak boleh kosong.');
            return;
        }
        if ($this->paid_amount < $this->total) {
            session()->flash('error', 'Jumlah pembayaran tidak mencukupi.');
            return;
        }

        DB::transaction(function () {
            // 1. Buat record transaksi utama
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . time(),
                'user_id' => Auth::id(),
                'total_amount' => $this->total,
                'paid_amount' => $this->paid_amount,
            ]);

            // 2. Buat record detail transaksi & kurangi stok
            foreach ($this->cart as $item) {
                $transaction->details()->create([
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Kurangi stok obat
                Medicine::find($item['medicine_id'])->decrement('stock', $item['quantity']);
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
        $medicines = [];
        if (strlen($this->search) >= 2) {
            $medicines = Medicine::where('name', 'like', '%' . $this->search . '%')
                ->where('stock', '>', 0)
                ->take(10)
                ->get();
        }
        return view('livewire.transaction.create', compact('medicines'));
    }
}