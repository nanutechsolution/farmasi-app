<?php

namespace App\Livewire\Role;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $name;
    public $role_id;
    public $selectedPermissions = [];
    public bool $isEditMode = false;

    public function create()
    {
        $this->reset();
        $this->isEditMode = false;
        $this->dispatch('open-modal', 'role-modal');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->role_id = $id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->all();
        $this->isEditMode = true;
        $this->dispatch('open-modal', 'role-modal');
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|unique:roles,name,' . $this->role_id,
            'selectedPermissions' => 'required|array',
        ]);

        if ($this->isEditMode) {
            $role = Role::findOrFail($this->role_id);
            $role->update(['name' => $validated['name']]);
            $role->syncPermissions($validated['selectedPermissions']);
            session()->flash('success', 'Role berhasil diperbarui.');
        } else {
            $role = Role::create(['name' => $validated['name']]);
            $role->syncPermissions($validated['selectedPermissions']);
            session()->flash('success', 'Role berhasil ditambahkan.');
        }
        $this->closeModal();
    }

    public function closeModal() { $this->dispatch('close-modal', 'role-modal'); }

    public function render()
    {
        return view('livewire.role.index', [
            'roles' => Role::paginate(10),
            'permissions' => Permission::all(),
        ]);
    }
}
