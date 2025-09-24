<?php

namespace App\Livewire\Log;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ActivityLog extends Component
{
    use WithPagination;

    public function render()
    {
        $activities = Activity::with('causer') // Ambil juga data user yang melakukan aksi
            ->latest() // Urutkan dari yang terbaru
            ->paginate(15);

        return view('livewire.log.activity-log', [
            'activities' => $activities,
        ]);
    }
}
