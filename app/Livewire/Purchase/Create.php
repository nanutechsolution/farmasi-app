<?php

namespace App\Livewire\Purchase;

use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Create extends Component
{
    public $suppliers;
    public $supplier_id;

    public $search = '';
    public $purchaseList = [];
    public $total = 0;

    // Inisialisasi data supplier saat komponen dimuat
    public function mount()
    {
        $this->suppliers = Supplier::all();
    }

    // Method untuk menambah obat ke daftar pembelian
    public function addToList(Medicine $medicine)
    {
        if (isset($this->purchaseList[$medicine->id])) {
            $this->purchaseList[$medicine->id]['quantity']++;
        } else {
            $this->purchaseList[$medicine->id] = [
                'medicine_id' => $medicine->id,
                'name' => $medicine->name,
                'purchase_price' => $medicine->price, // Harga beli awal = harga jual
                'quantity' => 1,
            ];
        }
        $this->calculateTotal();
        $this->search = '';
    }

    // Method untuk menghapus item dari daftar
    public function removeFromList($medicineId)
    {
        unset($this->purchaseList[$medicineId]);
        $this->calculateTotal();
    }

    // Method untuk mengupdate kuantitas atau harga beli
    public function updatedPurchaseList($value, $key)
    {
        $keys = explode('.', $key);
        $id = $keys[0];
        $field = $keys[1];
        if ($value === '') {
            $this->purchaseList[$id][$field] = 0;
        }

        $this->calculateTotal();
    }

    // Method untuk menghitung total
    private function calculateTotal()
    {
        $this->total = collect($this->purchaseList)->sum(function ($item) {
            return (float)$item['purchase_price'] * (int)$item['quantity'];
        });
    }

    // app/Livewire/Purchase/Create.php

    public function savePurchase()
    {
        $this->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchaseList' => 'required|array|min:1'
        ]);

        // Memulai transaksi database untuk memastikan semua proses berhasil
        DB::transaction(function () {
            // Buat record pembelian utama
            $purchase = Purchase::create([
                'supplier_id' => $this->supplier_id,
                'user_id' => Auth::id(),
                'total_amount' => $this->total,
            ]);

            // Loop untuk setiap item dalam daftar pembelian
            foreach ($this->purchaseList as $item) {
                // Simpan detail pembelian
                $purchase->details()->create($item);

                $medicine = Medicine::find($item['medicine_id']);

                // --- Logika Harga Rata-Rata (Moving Average) ---

                // 1. Ambil data lama sebelum diubah
                $oldStock = $medicine->stock;
                $oldCost = $medicine->cost_price;

                // 2. Ambil data baru dari form pembelian
                $newStock = $item['quantity'];
                $newCost = $item['purchase_price'];

                // 3. Hitung total stok baru
                $totalStock = $oldStock + $newStock;

                // 4. Hitung harga rata-rata baru (pastikan tidak ada pembagian dengan nol)
                $newAverageCost = ($totalStock > 0)
                    ? (($oldStock * $oldCost) + ($newStock * $newCost)) / $totalStock
                    : $newCost;

                // 5. Update stok dan harga modal obat dengan nilai baru
                $medicine->update([
                    'stock' => $totalStock,
                    'cost_price' => $newAverageCost,
                ]);
            }
        });

        session()->flash('success', 'Data pembelian berhasil disimpan.');
        return redirect()->route('medicines.index');
    }

    public function render()
    {
        $medicines = [];
        if (strlen($this->search) >= 2) {
            $medicines = Medicine::where('name', 'like', '%' . $this->search . '%')->take(10)->get();
        }

        return view('livewire.purchase.create', [
            'medicines' => $medicines,
        ]);
    }
}