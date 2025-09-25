<?php

namespace App\Livewire\StockOpname;

use App\Models\Medicine;
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

        DB::transaction(function () {
            // 1. Buat record utama Stok Opname
            $stockOpname = StockOpname::create([
                'user_id' => Auth::id(),
                'opname_date' => now(),
                'notes' => $this->notes,
                'status' => 'completed',
            ]);

            // 2. Loop melalui item yang dihitung & simpan detailnya
            foreach ($this->physicalStocks as $medicineId => $physicalStock) {
                // Konversi input kosong menjadi 0
                $physicalStock = $physicalStock === '' ? 0 : (int)$physicalStock;

                $medicine = Medicine::find($medicineId);
                if ($medicine) {
                    $systemStock = $medicine->stock;
                    $difference = $physicalStock - $systemStock;

                    // Buat record detail
                    $stockOpname->details()->create([
                        'medicine_id' => $medicineId,
                        'system_stock' => $systemStock,
                        'physical_stock' => $physicalStock,
                        'difference' => $difference,
                    ]);

                    // 3. Update stok di tabel medicines
                    $medicine->update(['stock' => $physicalStock]);
                }
            }
        });

        session()->flash('success', 'Hasil stok opname berhasil disimpan dan stok telah disesuaikan.');
        return redirect()->route('medicines.index');
    }

    public function render()
    {
        $medicines = Medicine::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.stock-opname.create', [
            'medicines' => $medicines,
        ]);
    }
}
