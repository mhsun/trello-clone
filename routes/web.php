<?php

use App\Http\Controllers\Ajax\CardReorderController;
use App\Http\Controllers\Ajax\ColumnCardController;
use App\Http\Controllers\Ajax\ColumnController;
use App\Http\Controllers\Ajax\DbExportController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPageController::class);

Route::prefix('ajax')->group(function () {
    Route::resource('/columns', ColumnController::class)->only(['index', 'store', 'destroy']);

    Route::resource('columns.cards', ColumnCardController::class)
        ->only(['store', 'update'])->shallow();

    Route::get('export-db', DbExportController::class);
    Route::post('/columns/{id}/reorder', CardReorderController::class);
});

Route::fallback(function () { return redirect('/'); });
