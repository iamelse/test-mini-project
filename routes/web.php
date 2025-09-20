<?php

use App\Http\Controllers\Web\ChartOfAccountController;
use App\Http\Controllers\AJAX\ChartOfAccountController as AJAXChartOfAccountController;
use Illuminate\Support\Facades\Route;

Route::get('/chart-of-accounts', [ChartOfAccountController::class, 'index'])
    ->name('chart-of-accounts.index');

// Group AJAX routes
Route::prefix('ajax')->group(function () {
    Route::prefix('chart-of-accounts')
        ->name('ajax.chart-of-accounts.')
        ->controller(AJAXChartOfAccountController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list');          // route('ajax.chart-of-accounts.list')
            Route::get('/{id}', 'detail')->name('detail');  // route('ajax.chart-of-accounts.detail')
            Route::post('/', 'create')->name('create');     // route('ajax.chart-of-accounts.create')
            Route::put('/{id}', 'edit')->name('edit');      // route('ajax.chart-of-accounts.edit')
            Route::delete('/{id}', 'delete')->name('delete'); // route('ajax.chart-of-accounts.delete')
        });
});