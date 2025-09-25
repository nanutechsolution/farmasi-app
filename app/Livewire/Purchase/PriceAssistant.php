<?php

namespace App\Livewire\Purchase;

use App\Models\Medicine;
use App\Models\Purchase;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PriceAssistant extends Component
{
    public Purchase $purchase;
    public $medicinesToUpdate = [];

    public function mount(Purchase $purchase)
    {
        $this->purchase = $purchase->load('details.medicine');

        // Ambil daftar obat unik yang ada di dalam pembelian ini
        $medicines = $this->purchase->details->map(fn($detail) => $detail->medicine)->unique('id');

        foreach ($medicines as $medicine) {
            // Kalkulasi harga jual saran
            $suggestedPrice = $medicine->cost_price * (1 + ($medicine->margin / 100));

            $this->medicinesToUpdate[$medicine->id] = [
                'name' => $medicine->name,
                'old_price' => $medicine->price,
                'new_cost_price' => $medicine->cost_price,
                'margin' => $medicine->margin,
                'suggested_price' => round($suggestedPrice, -2), // Dibulatkan ke ratusan terdekat
                'new_price' => round($suggestedPrice, -2), // Harga baru di-default ke harga saran
            ];
        }
    }

    public function savePrices()
    {
        foreach ($this->medicinesToUpdate as $id => $data) {
            Medicine::find($id)->update(['price' => $data['new_price']]);
        }

        session()->flash('success', 'Harga jual telah berhasil diperbarui.');
        return redirect()->route('medicines.index');
    }

    public function render()
    {
        return view('livewire.purchase.price-assistant');
    }
}
