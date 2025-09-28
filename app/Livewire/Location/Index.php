<?php

namespace App\Livewire\Location;

use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $name, $description;
    public $location_id;
    public bool $isEditMode = false;
    public $idToDelete;

    public function create()
    {
        $this->reset();
        $this->isEditMode = false;
        $this->dispatch('open-modal', 'location-modal');
    }

    public function edit($id)
    {
        $location = Location::findOrFail($id);
        $this->location_id = $id;
        $this->name = $location->name;
        $this->description = $location->description;
        $this->isEditMode = true;
        $this->dispatch('open-modal', 'location-modal');
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|unique:locations,name,' . $this->location_id,
            'description' => 'nullable|string',
        ]);

        if ($this->isEditMode) {
            Location::findOrFail($this->location_id)->update($validated);
            session()->flash('success', 'Lokasi berhasil diperbarui.');
        } else {
            Location::create($validated);
            session()->flash('success', 'Lokasi berhasil ditambahkan.');
        }
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->idToDelete = $id;
        $this->dispatch('open-modal', 'confirm-deletion-modal');
    }

    public function destroy()
    {
        $location = Location::withCount('batches')->findOrFail($this->idToDelete);
        if ($location->batches_count > 0) {
            session()->flash('error', 'Lokasi ini tidak bisa dihapus karena masih digunakan oleh batch obat.');
            $this->dispatch('close-modal', 'confirm-deletion-modal');
            return;
        }

        $location->delete();
        session()->flash('success', 'Lokasi berhasil dihapus.');

        $this->dispatch('close-modal', 'confirm-deletion-modal');
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', 'location-modal');
    }

    public function render()
    {
        $locations = Location::withCount('batches')->paginate(10);
        return view('livewire.location.index', compact('locations'));
    }
}
