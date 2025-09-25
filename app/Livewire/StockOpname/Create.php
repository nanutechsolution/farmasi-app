<?php

namespace App\Livewire\StockOpname;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\StockOpname;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithPagination;

    public $physicalStocks = [];
    public $notes;
    public $search = '';

    public function saveOpname()
    {
        if (empty($this->physicalStocks)) {
            session()->flash('error', 'Harap isi setidaknya satu data stok fisik.');
            return;
        }

        $affectedMedicineIds = [];

        DB::transaction(function () use (&$affectedMedicineIds) {
            $stockOpname = StockOpname::create([
                'user_id' => Auth::id(),
                'opname_date' => now(),
                'notes' => $this->notes,
                'status' => 'completed',
            ]);

            foreach ($this->physicalStocks as $batchId => $physicalStock) {
                if ($physicalStock === '' || is_null($physicalStock)) continue;

                $physicalStock = (int)$physicalStock;
                $batch = MedicineBatch::find($batchId);

                if ($batch) {
                    $systemStock = $batch->quantity;
                    $difference = $physicalStock - $systemStock;

                    $stockOpname->details()->create([
                        'medicine_id' => $batch->medicine_id,
                        'system_stock' => $systemStock,
                        'physical_stock' => $physicalStock,
                        'difference' => $difference,
                    ]);

                    // Update stok di batch yang spesifik
                    $batch->update(['quantity' => $physicalStock]);

                    // Kumpulkan ID obat induknya untuk dihitung ulang nanti
                    if(!in_array($batch->medicine_id, $affectedMedicineIds)) {
                        $affectedMedicineIds[] = $batch->medicine_id;
                    }
                }
            }

            // Hitung ulang harga modal rata-rata untuk setiap obat yang stoknya berubah
            foreach ($affectedMedicineIds as $medicineId) {
                $medicine = Medicine::with('batches')->find($medicineId);
                if ($medicine) {
                    $totalStock = $medicine->batches->sum('quantity');
                    $totalValue = $medicine->batches->sum(fn ($batch) => $batch->quantity * $batch->purchase_price);
                    $newAverageCost = ($totalStock > 0) ? $totalValue / $totalStock : 0;
                    $medicine->update(['cost_price' => $newAverageCost]);
                }
            }
        });

        session()->flash('success', 'Hasil stok opname berhasil disimpan dan stok telah disesuaikan.');
        return redirect()->route('stock-opnames.index');
    }

    public function render()
    {
        // Query sekarang ke tabel medicine_batches
        $batches = MedicineBatch::with('medicine')
            ->whereHas('medicine', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('expired_date', 'asc')
            ->paginate(15);

        return view('livewire.stock-opname.create', [
            'batches' => $batches,
        ]);
    }
}
