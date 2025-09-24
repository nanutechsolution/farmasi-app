<?php

namespace App\Livewire\Supplier;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    // Properti untuk form
    public $name, $contact_person, $phone, $address;

    // Properti untuk mode edit
    public $supplier_id;
    public bool $isEditMode = false;

    // Properti untuk konfirmasi hapus
    public $supplierIdToDelete;

    public function create()
    {
        $this->reset();
        $this->isEditMode = false;
        $this->dispatch('open-modal', 'supplier-modal');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);

        $this->supplier_id = $id;
        $this->name = $supplier->name;
        $this->contact_person = $supplier->contact_person;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;

        $this->isEditMode = true;
        $this->dispatch('open-modal', 'supplier-modal');
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        if ($this->isEditMode) {
            $supplier = Supplier::findOrFail($this->supplier_id);
            $supplier->update($validated);
            session()->flash('success', 'Data supplier berhasil diperbarui.');
        } else {
            Supplier::create($validated);
            session()->flash('success', 'Data supplier berhasil ditambahkan.');
        }
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->supplierIdToDelete = $id;
        $this->dispatch('open-modal', 'confirm-delete-modal');
    }

    public function destroy()
    {
        Supplier::findOrFail($this->supplierIdToDelete)->delete();
        session()->flash('success', 'Data supplier berhasil dihapus.');
        $this->dispatch('close-modal', 'confirm-delete-modal');
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', 'supplier-modal');
    }

    public function render()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('livewire.supplier.index', [
            'suppliers' => $suppliers
        ]);
    }
}