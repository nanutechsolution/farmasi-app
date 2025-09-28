<?php

namespace App\Livewire\System;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class LogViewer extends Component
{
    use WithPagination;

    public $selectedLog;
    public $showDetailModal = false;
    public $confirmingClearLog = false;

    public function getLogsProperty()
    {
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile) || empty(File::get($logFile))) {
            return collect();
        }

        $logEntries = preg_split(
            '/(?=\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\])/',
            File::get($logFile),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        return collect($logEntries)->map(function ($entry) {
            preg_match('/^\[(.*?)\]\s+([a-zA-Z0-9_-]+)\.([A-Z]+): (.*)$/s', $entry, $matches);

            return [
                'timestamp' => $matches[1] ?? 'N/A',
                'env' => $matches[2] ?? 'N/A',
                'level' => strtoupper($matches[3] ?? 'UNKNOWN'),
                'message' => trim($matches[4] ?? 'Could not parse message.'),
                'full' => trim($entry),
            ];
        })->reverse();
    }


    public function showDetail($logIndex)
    {
        $this->selectedLog = $this->logs->get($logIndex);
        $this->dispatch('open-modal', 'log-detail-modal');
    }

    public function confirmClearLog()
    {
        $this->dispatch('open-modal', 'confirm-clear-log-modal');
    }

    public function clearLog()
    {
        File::put(storage_path('logs/laravel.log'), '');
        $this->dispatch('close-modal', 'confirm-clear-log-modal');
        session()->flash('success', 'File log berhasil dibersihkan.');
        $this->resetPage(); // Kembali ke halaman pertama setelah log dibersihkan
    }

    public function render()
    {
        $logs = $this->logs;
        // Paginasi manual untuk koleksi
        $paginatedLogs = new LengthAwarePaginator(
            $logs->forPage($this->getPage(), 20),
            $logs->count(),
            20,
            $this->getPage(),
            ['path' => request()->url()]
        );

        return view('livewire.system.log-viewer', ['logs' => $paginatedLogs]);
    }
}
