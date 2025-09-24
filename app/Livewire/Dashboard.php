<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        // Menghitung statistik
        $medicineCount = Medicine::count();
        $supplierCount = Supplier::count();

        // Stok dianggap menipis jika jumlahnya <= 10
        $lowStockCount = Medicine::where('stock', '<=', 10)->count();

        // Dianggap akan kadaluarsa jika dalam 30 hari ke depan
        $expiringSoonCount = Medicine::where('expired_date', '<=', now()->addDays(30))->count();

        return view('livewire.dashboard', [
            'medicineCount' => $medicineCount,
            'supplierCount' => $supplierCount,
            'lowStockCount' => $lowStockCount,
            'expiringSoonCount' => $expiringSoonCount,
        ]);
    }
}