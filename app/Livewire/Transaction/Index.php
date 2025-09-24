<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $transactions = Transaction::with('user')
            ->where('invoice_number', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.transaction.index', [
            'transactions' => $transactions,
        ]);
    }
}