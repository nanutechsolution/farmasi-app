<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Medicine\Index as MedicineIndex;
use App\Livewire\Supplier\Index as SupplierIndex;
use App\Livewire\Transaction\Create as TransactionCreate;
use App\Http\Controllers\TransactionController;
use App\Livewire\Transaction\Index as TransactionIndex;
use App\Livewire\Purchase\Create as PurchaseCreate;
use App\Livewire\Report\Financial as FinancialReport;
use App\Livewire\User\Index as UserIndex;

Route::view('/', 'welcome');
Route::get('dashboard', Dashboard::class)
    ->middleware(['verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('medicines', MedicineIndex::class)
    ->middleware(['role:Admin|Apoteker'])
    ->name('medicines.index');

Route::get('suppliers', SupplierIndex::class)
    ->middleware(['role:Admin|Apoteker'])
    ->name('suppliers.index');

Route::get('transactions/create', TransactionCreate::class)
    ->middleware(['role:Apoteker|Kasir|Admin'])
    ->name('transactions.create');

Route::get('transactions', TransactionIndex::class)
    ->middleware(['role:Admin|Apoteker'])
    ->name('transactions.index');

Route::get('transactions/print/{invoice_number}', [TransactionController::class, 'print'])
    ->middleware(['role:Admin|Apoteker|Kasir'])
    ->name('transactions.print');
Route::get('purchases/create', PurchaseCreate::class)
    ->middleware(['role:Admin|Apoteker'])
    ->name('purchases.create');

Route::get('reports/financial', FinancialReport::class)
    ->middleware(['role:Admin|Apoteker'])
    ->name('reports.financial');

Route::get('users', UserIndex::class)
    ->middleware(['role:Admin'])
    ->name('users.index');
require __DIR__ . '/auth.php';