<?php

namespace App\Livewire\StockOpname;

use App\Models\StockOpname;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Show extends Component
{
    public StockOpname $stockOpname;

    public function mount(StockOpname $stockOpname)
    {
        $this->stockOpname = $stockOpname->load('user', 'details.medicine');
    }

    public function render()
    {
        return view('livewire.stock-opname.show');
    }
}
