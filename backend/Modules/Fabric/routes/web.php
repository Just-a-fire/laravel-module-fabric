<?php

use Illuminate\Support\Facades\Route;
use Modules\Fabric\App\Http\Controllers\FabricController;
use Modules\Fabric\App\Http\Controllers\CatalogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('fabric', FabricController::class)->names('fabric');
});

// Route::get('fabrics/{fabric}', [FabricController::class, 'show'])->name('fabrics.show');

Route::prefix('fabrics')->group(function () {
    // Только методы, возвращающие View
    Route::get('/', [FabricController::class, 'index'])->name('fabrics.index');
    Route::get('/create', [FabricController::class, 'create'])->name('fabrics.create');
    Route::get('/{fabric}/edit', [FabricController::class, 'edit'])->name('fabrics.edit');
    Route::get('/{fabric}', [FabricController::class, 'show'])->name('fabrics.show');
});

Route::get('catalogs', [CatalogController::class, 'index'])->name('catalogs.index');

Route::get('colors', function() { 
    return view('fabric::colors.index'); 
})->name('colors.index');