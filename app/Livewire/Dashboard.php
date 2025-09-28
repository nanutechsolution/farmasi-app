<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
#[Layout('layouts.app')]
class Dashboard extends Component
{

    // Preferensi widget yang aktif milik pengguna
    public $activeWidgets = [];
    public $todaysAttendance;
    public $isClockedIn = false;
    public $allWidgets = [
        ['key' => 'total-medicines', 'name' => 'Total Obat'],
        ['key' => 'total-suppliers', 'name' => 'Total Supplier'],
        ['key' => 'low-stock', 'name' => 'Stok Menipis'],
        ['key' => 'expiring-soon', 'name' => 'Akan Kadaluarsa'],
        ['key' => 'sales-chart', 'name' => 'Grafik Penjualan'],
    ];


    public function updateWidgetOrder($orderedIds)
    {
        $newOrder = [];
        foreach ($orderedIds as $idData) {
            $key = $idData['value'];
            // Cari widget di daftar widget aktif berdasarkan key
            $widget = collect($this->activeWidgets)->firstWhere('key', $key);
            if ($widget) {
                $newOrder[] = $widget;
            }
        }
        $this->activeWidgets = $newOrder;
        $this->saveSettings();
    }
    // Method untuk toggle menampilkan/menyembunyikan widget
    public function toggleWidget($key)
    {
        $widgetCollection = collect($this->activeWidgets);

        if ($widgetCollection->contains('key', $key)) {
            // Jika sudah ada, hapus (sembunyikan)
            $this->activeWidgets = $widgetCollection->where('key', '!=', $key)->values()->all();
        } else {
            // Jika tidak ada, tambahkan (tampilkan)
            $widget = collect($this->allWidgets)->firstWhere('key', $key);
            if ($widget) {
                $this->activeWidgets[] = $widget;
            }
        }
        $this->saveSettings();
    }  // Method untuk menyimpan preferensi ke database
    public function saveSettings()
    {
        $user = auth()->user();
        $user->dashboard_settings = $this->activeWidgets;
        $user->save();

    }
    public function showSettingsModal()
    {
        $this->dispatch('open-modal', 'settings-modal');

    }
    public function render()
    {
        // --- Statistik untuk Kartu (Tidak Berubah) ---
        $medicineCount = Medicine::count();
        $supplierCount = Supplier::count();
        $lowStockCount = Medicine::with('batches')->get()->filter(function ($medicine) {
            return $medicine->total_stock <= 10;
        })->count();
        $expiringSoonCount = MedicineBatch::where('quantity', '>', 0)
            ->whereBetween('expired_date', [now()->toDateString(), now()->addDays(30)->toDateString()])
            ->count();
        // 1. Ambil data mentah seperti sebelumnya, tapi kita buat 'date' sebagai key
        $salesDataRaw = Transaction::where('created_at', '>=', now()->subDays(6)) // Ambil 7 hari termasuk hari ini
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        // 2. Siapkan array kosong untuk menampung hasil akhir
        $salesLabels = [];
        $salesData = [];

        // 3. Loop selama 7 hari terakhir untuk memastikan semua hari ada
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');

            // Buat label (contoh: 25 Sep)
            $salesLabels[] = now()->subDays($i)->format('d M');

            // Cek apakah ada data penjualan di tanggal ini
            // Jika ada, masukkan totalnya. Jika tidak, masukkan 0.
            $salesData[] = (float) ($salesDataRaw->get($date)->total ?? 0);
        }

        return view('livewire.dashboard', [
            'medicineCount' => $medicineCount,
            'supplierCount' => $supplierCount,
            'lowStockCount' => $lowStockCount,
            'expiringSoonCount' => $expiringSoonCount,
            'salesLabels' => $salesLabels,
            'salesData' => $salesData,
        ]);
    }
    public function checkAttendanceStatus()
    {
        $this->todaysAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('clock_in_time', today())
            ->first();

        $this->isClockedIn = $this->todaysAttendance && is_null($this->todaysAttendance->clock_out_time);
    }


    public function clockIn($latitude, $longitude)
    {
        $settings = app(GeneralSettings::class);
        $officeLat = $settings->office_latitude;
        $officeLon = $settings->office_longitude;
        $maxDistance = $settings->max_clock_in_distance ?? 100;

        if (!$officeLat || !$officeLon) {
            $this->dispatch('attendance-error', 'Lokasi kantor belum diatur oleh Admin.');
            return;
        }
        $distance = $this->calculateDistance($latitude, $longitude, $officeLat, $officeLon);

        if ($distance <= $maxDistance) {
            Attendance::create([
                'user_id' => Auth::id(),
                'clock_in_time' => now(),
                'clock_in_latitude' => $latitude,
                'clock_in_longitude' => $longitude,
            ]);
            $this->checkAttendanceStatus();
            $this->dispatch('attendance-success', 'Clock in berhasil!');
        } else {
            $this->dispatch('attendance-error', 'Anda berada terlalu jauh dari lokasi kerja. Jarak Anda: ' . round($distance) . ' meter.');
        }
    }

    public function clockOut()
    {
        if ($this->todaysAttendance) {
            $this->todaysAttendance->update(['clock_out_time' => now()]);
            $this->checkAttendanceStatus();
            $this->dispatch('attendance-success', 'Clock out berhasil!');
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
    public function mount()
    {
        // Muat pengaturan dari database, jika tidak ada, gunakan semua widget sebagai default
        $this->activeWidgets = auth()->user()->dashboard_settings ?? $this->allWidgets;
        $this->checkAttendanceStatus();
    }

}
