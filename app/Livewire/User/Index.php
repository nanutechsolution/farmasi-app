<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    // Properti form
    public $name, $email, $password, $password_confirmation, $role;

    // Properti state
    public $user_id;
    public bool $isEditMode = false;
    public $confirmingUserDeletion = false;
    public $userIdToDelete;

    public function create()
    {
        $this->reset();
        $this->isEditMode = false;
        $this->dispatch('open-modal', 'user-modal');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()->name ?? ''; // Ambil role pertama

        $this->isEditMode = true;
        $this->dispatch('open-modal', 'user-modal');
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'role' => 'required|exists:roles,name',
        ];

        // Tambahkan validasi password hanya saat membuat user baru atau saat password diisi
        if (!$this->isEditMode || !empty($this->password)) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        $validated = $this->validate($rules);

        if ($this->isEditMode) {
            $user = User::findOrFail($this->user_id);
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }
            $user->update($updateData);
            $user->syncRoles($validated['role']);
            session()->flash('success', 'User berhasil diperbarui.');
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->assignRole($validated['role']);
            session()->flash('success', 'User berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->userIdToDelete = $id;
        $this->confirmingUserDeletion = true;
        $this->dispatch('open-modal', 'user-delete-modal');
    }

    public function destroy()
    {
        // Jangan biarkan user menghapus dirinya sendiri
        if ($this->userIdToDelete == auth()->id()) {
            session()->flash('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
            $this->confirmingUserDeletion = false;
            return;
        }
        User::findOrFail($this->userIdToDelete)->delete();
        session()->flash('success', 'User berhasil dihapus.');
        $this->confirmingUserDeletion = false;
        $this->dispatch('close-modal', 'user-delete-modal');
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', 'user-modal');
    }

    public function render()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();
        return view('livewire.user.index', compact('users', 'roles'));
    }
}
