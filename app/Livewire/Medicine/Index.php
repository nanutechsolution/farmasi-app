<?php

namespace App\Livewire\Medicine;

use App\Models\Category;
use App\Models\Medicine;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    // Properti untuk form
    public $name, $barcode, $category_id, $stock, $price, $unit, $expired_date, $cost_price;

    public $medicine_id;
    public bool $isEditMode = false;

    public $medicineIdToDelete;

    public $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';
    public function sortBy(string $field)
    {
        // Jika klik kolom yang sama, balik arahnya
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Jika klik kolom baru, set default ke 'asc'
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage(); // Kembali ke halaman 1 setiap kali sorting
    }
    // Method untuk membuka modal tambah data
    public function create()
    {
        // Reset properti form & mode edit
        $this->reset();
        $this->isEditMode = false;

        // Buka modal
        $this->dispatch('open-modal', 'medicine-modal');
    }

    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);

        // Isi properti dengan data yang ada
        $this->medicine_id = $id;
        $this->name = $medicine->name;
        $this->barcode = $medicine->barcode;
        $this->category_id = $medicine->category_id;
        $this->stock = $medicine->stock;
        $this->price = $medicine->price;
        $this->cost_price = $medicine->cost_price;
        $this->unit = $medicine->unit;
        $this->expired_date = $medicine->expired_date;

        $this->isEditMode = true;
        $this->dispatch('open-modal', 'medicine-modal');
    }

    public function save()
    {
        // Validasi data
        $this->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'expired_date' => 'required|date',
        ]);

        if ($this->isEditMode) {
            $medicine = Medicine::findOrFail($this->medicine_id);
            $medicine->update($this->only(['name', 'barcode', 'category_id', 'stock', 'price', 'cost_price', 'unit', 'expired_date']));
            session()->flash('success', 'Data obat berhasil diperbarui.');
        } else {
            Medicine::create($this->only(['name', 'barcode', 'category_id', 'stock', 'price', 'cost_price', 'unit', 'expired_date']));
            session()->flash('success', 'Data obat berhasil ditambahkan.');
        }

        $this->closeModal();
    }
    public function confirmDelete($id)
    {
        $this->medicineIdToDelete = $id;
        $this->dispatch('open-modal', 'medicine-delete-modal');
    }

    public function destroy()
    {
        Medicine::findOrFail($this->medicineIdToDelete)->delete();
        session()->flash('success', 'Data obat berhasil dihapus.');
        $this->dispatch('close-modal', 'medicine-delete-modal');
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', 'medicine-modal');
    }

    public function render()
    {
        $medicines = Medicine::with('category')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
        $categories = Category::all();

        return view('livewire.medicine.index', [
            'medicines' => $medicines,
            'categories' => $categories,
        ]);
    }
}