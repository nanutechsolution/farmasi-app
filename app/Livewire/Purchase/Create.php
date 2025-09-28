<?php

namespace App\Livewire\Purchase;
use App\Models\Location;
use App\Models\Medicine;
use App\Models\MedicineBatch;
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
    public $locations;
    public function mount()
    {
        $this->suppliers = Supplier::all();
        $this->locations = Location::all();
    }

    // Mengubah cara item ditambahkan ke list
    public function addToList(Medicine $medicine)
    {
        // Sekarang kita bisa menambahkan obat yang sama berkali-kali (untuk batch berbeda)
        $this->purchaseList[] = [
            'medicine_id' => $medicine->id,
            'name' => $medicine->name,
            'location_id' => '',
            'batch_number' => '',
            'quantity' => 1,
            'purchase_price' => $medicine->cost_price, // Harga beli awal kita samakan dgn harga jual
            'expired_date' => now()->addYear()->toDateString(),
        ];
        $this->calculateTotal();
        $this->search = '';
    }

    public function removeFromList($index)
    {
        unset($this->purchaseList[$index]);
        $this->purchaseList = array_values($this->purchaseList);
        $this->calculateTotal();
    }

    public function updatedPurchaseList()
    {
        $this->calculateTotal();
    }

    private function calculateTotal()
    {
        $this->total = collect($this->purchaseList)->sum(function ($item) {
            $price = is_numeric($item['purchase_price']) ? (float) $item['purchase_price'] : 0;
            $quantity = is_numeric($item['quantity']) ? (int) $item['quantity'] : 0;
            return $price * $quantity;
        });
    }

    // Logika penyimpanan diubah total untuk menyimpan batch
    public function savePurchase()
    {
        $this->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchaseList' => 'required|array|min:1',
            'purchaseList.*.location_id' => 'required|exists:locations,id',
            'purchaseList.*.quantity' => 'required|integer|min:1',
            'purchaseList.*.purchase_price' => 'required|numeric|min:0',
            'purchaseList.*.expired_date' => 'required|date',
        ]);
        $purchase = DB::transaction(function () {
            $purchase = Purchase::create([
                'supplier_id' => $this->supplier_id,
                'user_id' => Auth::id(),
                'total_amount' => $this->total,
            ]);
            $affectedMedicineIds = collect($this->purchaseList)->pluck('medicine_id')->unique();
            foreach ($this->purchaseList as $item) {
                // Buat detail pembelian
                $purchase->details()->create($item);

                // BUAT RECORD BATCH BARU, BUKAN LAGI MENAMBAH STOK
                MedicineBatch::create([
                    'medicine_id' => $item['medicine_id'],
                      'location_id' => $item['location_id'],
                    'batch_number' => $item['batch_number'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'expired_date' => $item['expired_date'],
                ]);
            }

            foreach ($affectedMedicineIds as $medicineId) {
                $medicine = Medicine::with('batches')->find($medicineId);

                if ($medicine) {
                    $totalStock = $medicine->batches->sum('quantity');
                    $totalValue = $medicine->batches->sum(function ($batch) {
                        return $batch->quantity * $batch->purchase_price;
                    });

                    $newAverageCost = ($totalStock > 0) ? $totalValue / $totalStock : 0;

                    // Update cost_price di tabel medicines
                    $medicine->update(['cost_price' => $newAverageCost]);
                }
            }

            return $purchase;
        });

        session()->flash('success', 'Data pembelian & batch berhasil disimpan.');
        // Kita arahkan ke halaman obat untuk melihat efeknya nanti
        return redirect()->route('purchases.price-assistant', $purchase);
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
