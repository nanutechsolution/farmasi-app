<?php

namespace App\Livewire\Report;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $userId;
    public $users;

    public function mount()
    {
        $this->endDate = Carbon::now()->toDateString();
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->users = User::orderBy('name')->get();
    }

    public function render()
    {
        $attendances = Attendance::with('user')
            ->when($this->startDate, fn($q) => $q->whereDate('clock_in_time', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('clock_in_time', '<=', $this->endDate))
            ->when($this->userId, fn($q) => $q->where('user_id', $this->userId))
            ->latest('clock_in_time')
            ->paginate(15);

        return view('livewire.report.attendance', [
            'attendances' => $attendances,
        ]);
    }
}
