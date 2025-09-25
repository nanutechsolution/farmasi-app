<?php

namespace App\Livewire\StockOpname;

use App\Models\StockOpname;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $opnames = StockOpname::with('user')->latest()->paginate(10);

        return view('livewire.stock-opname.index', [
            'opnames' => $opnames,
        ]);
    }
}
