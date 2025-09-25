<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $name;
    public $category_id;
    public bool $isEditMode = false;
    public $confirmingDeletion = false;
    public $idToDelete;

    public function create()
    {
        $this->reset();
        $this->isEditMode = false;
        $this->dispatch('open-modal', 'category-modal');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->isEditMode = true;
        $this->dispatch('open-modal', 'category-modal');
    }

    public function save()
    {
        $validated = $this->validate(['name' => 'required|string|unique:categories,name,' . $this->category_id]);

        if ($this->isEditMode) {
            Category::findOrFail($this->category_id)->update($validated);
            session()->flash('success', 'Kategori berhasil diperbarui.');
        } else {
            Category::create($validated);
            session()->flash('success', 'Kategori berhasil ditambahkan.');
        }
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->idToDelete = $id;
        $this->confirmingDeletion = true;
        $this->dispatch('open-modal', 'category-delete-modal');
    }

    public function destroy()
    {
        // Tambahkan proteksi agar kategori yang masih digunakan tidak bisa dihapus
        $category = Category::withCount('medicines')->findOrFail($this->idToDelete);
        if($category->medicines_count > 0) {
            session()->flash('error', 'Kategori ini tidak bisa dihapus karena masih digunakan oleh obat.');
            $this->dispatch('close-modal', 'category-delete-modal');
            return;
        }
        $category->delete();
        session()->flash('success', 'Kategori berhasil dihapus.');
        $this->dispatch('close-modal', 'category-delete-modal');
    }

    public function closeModal() { $this->dispatch('close-modal', 'category-modal'); }

    public function render()
    {
        // Tampilkan jumlah obat di setiap kategori
        $categories = Category::withCount('medicines')->paginate(10);
        return view('livewire.category.index', compact('categories'));
    }
}
