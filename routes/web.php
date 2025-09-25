<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Medicine\Index as MedicineIndex;
use App\Livewire\Supplier\Index as SupplierIndex;
use App\Livewire\Transaction\Create as TransactionCreate;
use App\Http\Controllers\TransactionController;
use App\Livewire\Transaction\Index as TransactionIndex;
use App\Livewire\Purchase\Create as PurchaseCreate;
use App\Livewire\Report\Financial as FinancialReport;
use App\Http\Controllers\ReportController;
use App\Livewire\Report\StockAnalysis;
use App\Livewire\User\Index as UserIndex;
use App\Livewire\Log\ActivityLog;
use App\Livewire\StockOpname\Create as StockOpnameCreate;
use App\Livewire\StockOpname\Index as StockOpnameIndex;
use App\Livewire\StockOpname\Show as StockOpnameShow;
use App\Livewire\Expense\Index as ExpenseIndex;
use App\Livewire\Role\Index as RoleIndex;
use App\Livewire\Purchase\PriceAssistant;
use App\Livewire\Category\Index as CategoryIndex;
use App\Livewire\Setting\Index as SettingIndex;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama untuk tamu
Route::view('/', 'welcome');

// Grup untuk semua route yang memerlukan login
Route::middleware(['auth', 'verified'])->group(function () {

    // Rute dasar
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
    // Manajemen Data
    Route::get('medicines', MedicineIndex::class)->middleware('permission:manage-medicines')->name('medicines.index');
    Route::get('suppliers', SupplierIndex::class)->middleware('permission:manage-suppliers')->name('suppliers.index');

    // Operasional
    Route::get('transactions/create', TransactionCreate::class)->middleware('permission:create-transaction')->name('transactions.create');
    Route::get('purchases/create', PurchaseCreate::class)->middleware('permission:create-purchase')->name('purchases.create');
    Route::get('stock-opnames/create', StockOpnameCreate::class)->middleware('permission:perform-stock-opname')->name('stock-opnames.create');

    // Laporan & Riwayat
    Route::get('transactions', TransactionIndex::class)->middleware('permission:view-reports')->name('transactions.index');
    Route::get('transactions/print/{invoice_number}', [TransactionController::class, 'print'])->name('transactions.print');
    Route::get('reports/financial', FinancialReport::class)->middleware('permission:view-reports')->name('reports.financial');
    Route::get('reports/financial/print', [ReportController::class, 'printFinancialReport'])->middleware('permission:view-reports')->name('reports.financial.print');
    Route::get('reports/stock-analysis', StockAnalysis::class)->middleware('permission:view-reports')->name('reports.stock-analysis');
    Route::get('stock-opnames', StockOpnameIndex::class)->middleware('permission:perform-stock-opname')->name('stock-opnames.index');
    Route::get('stock-opnames/{stockOpname}', StockOpnameShow::class)->middleware('permission:perform-stock-opname')->name('stock-opnames.show');

    // Administrasi
    Route::get('expenses', ExpenseIndex::class)->middleware('permission:manage-medicines')->name('expenses.index'); // Anggap saja yg bisa manage obat, bisa manage biaya
    Route::get('users', UserIndex::class)->middleware('permission:manage-users')->name('users.index');
    Route::get('roles', RoleIndex::class)->middleware('permission:manage-roles')->name('roles.index');
    Route::get('activity-log', ActivityLog::class)->middleware('permission:view-activity-log')->name('activity-log.index');

    Route::get('medicines/export', [ReportController::class, 'exportMedicines'])
        ->middleware(['permission:manage-medicines'])
        ->name('medicines.export');

    Route::get('medicines/print-labels', [ReportController::class, 'printBarcodeLabels'])
        ->middleware(['permission:manage-medicines'])
        ->name('medicines.print-labels');

    Route::get('purchases/{purchase}/price-assistant', PriceAssistant::class)
        ->middleware(['role:Admin|Apoteker'])
        ->name('purchases.price-assistant');
    Route::get('categories', CategoryIndex::class)
        ->middleware(['permission:manage-medicines'])
        ->name('categories.index');

    Route::get('settings', SettingIndex::class)
        ->middleware(['role:Admin'])
        ->name('settings.index');
});
// Route otentikasi (login, register, dll.)
require __DIR__ . '/auth.php';
