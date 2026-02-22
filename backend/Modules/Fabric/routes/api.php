<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Modules\Fabric\App\Http\Controllers\Api\FabricApiController;

use Modules\Fabric\App\Http\Controllers\Api\CatalogApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('fabric', fn (Request $request) => $request->user())->name('fabric');
});

// API для тканей
Route::prefix('fabrics')->group(function () {
    Route::get('/', [FabricApiController::class, 'index']);
    Route::post('/', [FabricApiController::class, 'store']);
    Route::get('/{fabric}', [FabricApiController::class, 'show']);
    // Route::patch('/{fabric}/archive', [FabricApiController::class, 'archive']);
    // Route::patch('/{fabric}/unarchive', [FabricApiController::class, 'unarchive']);
    Route::patch('/{fabric}/toggle-archive', [FabricApiController::class, 'toggleArchive']);
});

// API для каталогов
Route::prefix('catalogs')->group(function () {
    Route::get('/', [CatalogApiController::class, 'index']);
    Route::delete('/{catalog}', [CatalogApiController::class, 'destroy']); // Добавляем DELETE
});