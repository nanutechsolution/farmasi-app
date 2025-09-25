<?php

namespace App\Livewire\Expense;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    // Properti form
    public $expense_date, $category, $amount, $description;

    // Properti state
    public $expense_id;
    public bool $isEditMode = false;
    public $confirmingDeletion = false;
    public $idToDelete;

    // Opsi kategori agar konsisten
    public $categories = ['Gaji', 'Sewa', 'Listrik', 'Air', 'Internet', 'Promosi', 'Lainnya'];

    public function mount()
    {
        $this->expense_date = now()->toDateString();
    }

    public function create()
    {
        $this->reset(['expense_id', 'category', 'amount', 'description']);
        $this->isEditMode = false;
        $this->expense_date = now()->toDateString();
        $this->dispatch('open-modal', 'expense-modal');
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $this->expense_id = $id;
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->category = $expense->category;
        $this->amount = $expense->amount;
        $this->description = $expense->description;
        $this->isEditMode = true;
        $this->dispatch('open-modal', 'expense-modal');
    }

    public function save()
    {
        $validated = $this->validate([
            'expense_date' => 'required|date',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();

        if ($this->isEditMode) {
            Expense::findOrFail($this->expense_id)->update($validated);
            session()->flash('success', 'Biaya berhasil diperbarui.');
        } else {
            Expense::create($validated);
            session()->flash('success', 'Biaya berhasil ditambahkan.');
        }
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->idToDelete = $id;
        $this->confirmingDeletion = true;
        $this->dispatch('open-modal', 'expense-delete-modal');
    }

    public function destroy()
    {
        Expense::findOrFail($this->idToDelete)->delete();
        session()->flash('success', 'Biaya berhasil dihapus.');
        $this->confirmingDeletion = false;
        $this->dispatch('close-modal', 'expense-delete-modal');
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', 'expense-modal');
    }

    public function render()
    {
        $expenses = Expense::with('user')->latest()->paginate(10);
        return view('livewire.expense.index', compact('expenses'));
    }
}
