<?php

namespace App\Livewire\Medicine;

use App\Models\Category;
use App\Models\Medicine;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Imports\MedicinesImport;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination, WithFileUploads;

    public bool $showImportModal = false;
    public $uploadFile;

    // Properti untuk form
    public $name, $barcode, $category_id, $stock, $price, $unit, $margin, $cost_price;

    public $medicine_id;
    public bool $isEditMode = false;

    public $medicineIdToDelete;

    public $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';
    public $selectedMedicines = [];

    public function openImportModal()
    {
        $this->dispatch('open-modal', 'import-modal');
    }
    public function importExcel()
    {
        $this->validate([
            'uploadFile' => 'required|file|mimes:xlsx,csv'
        ]);

        try {
            Excel::import(new MedicinesImport, $this->uploadFile);
            session()->flash('success', 'Data obat berhasil diimpor.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }

        $this->dispatch('close-modal', 'import-modal');
    }
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
        $this->price = $medicine->price;
        $this->cost_price = $medicine->cost_price;
        $this->margin = $medicine->margin;
        $this->unit = $medicine->unit;
        $this->isEditMode = true;
        $this->dispatch('open-modal', 'medicine-modal');
    }

    public function save()
    {
        // Validasi data
        $this->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'margin' => 'required|integer|min:0|max:100',
            'unit' => 'required|string|max:50',
        ]);

        if ($this->isEditMode) {
            $medicine = Medicine::findOrFail($this->medicine_id);
            $medicine->update($this->only(['name', 'barcode', 'category_id', 'price', 'cost_price', 'margin', 'unit']));
            session()->flash('success', 'Data obat berhasil diperbarui.');
        } else {
            Medicine::create($this->only(['name', 'barcode', 'category_id', 'price', 'cost_price', 'margin', 'unit']));
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
        try {
            Medicine::findOrFail($this->medicineIdToDelete)->delete();
            session()->flash('success', 'Data obat berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                session()->flash('error', 'Obat ini tidak bisa dihapus karena masih ada transaksi terkait.');
            } else {
                session()->flash('error', 'Terjadi kesalahan saat menghapus data obat.');
            }
        }
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
        $paginatedMedicineIds = $medicines->pluck('id')->all();
        $categories = Category::all();

        return view('livewire.medicine.index', [
            'medicines' => $medicines,
            'categories' => $categories,
            'paginatedMedicineIds' => $paginatedMedicineIds,
        ]);
    }
}
