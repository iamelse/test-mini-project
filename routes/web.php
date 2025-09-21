<?php

use App\Http\Controllers\Web\ChartOfAccountController;
use App\Http\Controllers\AJAX\ChartOfAccountController as AJAXChartOfAccountController;
use App\Http\Controllers\Web\JournalController;
use App\Http\Controllers\AJAX\JournalController as AJAXJournalController;
use Illuminate\Support\Facades\Route;

// -----------------
// Chart of Accounts
// -----------------
Route::get('/chart-of-accounts', [ChartOfAccountController::class, 'index'])
    ->name('chart-of-accounts.index');

// -----------------
// Journals
// -----------------
Route::get('/journals', [JournalController::class, 'index'])
    ->name('journals.index');

// -----------------
// Invoices
// -----------------
Route::get('/invoices', fn () => view('pages.invoices.index'))
    ->name('invoices.index');

// -----------------
// Payments
// -----------------
Route::get('/payments', fn () => view('pages.payments.index'))
    ->name('payments.index');

// -----------------
// Trial Balance
// -----------------
Route::get('/trial-balance', fn () => view('pages.trial-balance.index'))
    ->name('trial-balance.index');


// ============================
// AJAX ROUTES
// ============================
Route::prefix('ajax')->group(function () {

    // -----------------
    // Chart of Accounts
    // -----------------
    Route::prefix('chart-of-accounts')
        ->name('ajax.chart-of-accounts.')
        ->controller(AJAXChartOfAccountController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('/{id}', 'detail')->name('detail');
            Route::post('/', 'create')->name('create');
            Route::put('/{id}', 'edit')->name('edit');
            Route::delete('/{id}', 'delete')->name('delete');
        });

    // -----------------
    // Journals
    // -----------------
    Route::prefix('journals')
        ->name('ajax.journals.')
        ->controller(AJAXJournalController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list');          // route('ajax.journals.list')
            Route::get('/{id}', 'detail')->name('detail');  // route('ajax.journals.detail')
            Route::post('/', 'create')->name('create');     // route('ajax.journals.create')
            Route::put('/{id}', 'edit')->name('edit');      // route('ajax.journals.edit')
            Route::delete('/{id}', 'delete')->name('delete'); // route('ajax.journals.delete')
        });

    // -----------------
    // Invoices (dummy)
    // -----------------
    Route::prefix('invoices')
        ->name('ajax.invoices.')
        ->group(function () {
            Route::get('/', fn () => response()->json(['message' => 'Invoices list (coming soon)']))->name('list');
            Route::get('/{id}', fn ($id) => response()->json(['message' => "Invoice {$id} detail (coming soon)"]))->name('detail');
        });

    // -----------------
    // Payments (dummy)
    // -----------------
    Route::prefix('payments')
        ->name('ajax.payments.')
        ->group(function () {
            Route::get('/', fn () => response()->json(['message' => 'Payments list (coming soon)']))->name('list');
            Route::get('/{id}', fn ($id) => response()->json(['message' => "Payment {$id} detail (coming soon)"]))->name('detail');
        });

    // -----------------
    // Trial Balance (dummy)
    // -----------------
    Route::prefix('trial-balance')
        ->name('ajax.trial-balance.')
        ->group(function () {
            Route::get('/', fn () => response()->json(['message' => 'Trial Balance data (coming soon)']))->name('list');
        });
});