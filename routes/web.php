<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('api.session')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/warehouse/select', [AuthController::class, 'selectWarehouse'])->name('warehouse.select');

    Route::get('/', [WarehouseController::class, 'dashboard'])->name('dashboard');
    Route::get('/scanner', [WarehouseController::class, 'scanner'])->name('scanner');

    Route::get('/opname', [WarehouseController::class, 'opname'])->name('opname');
    Route::post('/opname', [WarehouseController::class, 'storeOpname'])->name('opname.store');

    Route::get('/inbound', [WarehouseController::class, 'inbound'])->name('inbound');
    Route::get('/inbound/{id}', [WarehouseController::class, 'inbound'])->name('inbound.show');
    Route::post('/inbound/{id}/receive', [WarehouseController::class, 'receiveInbound'])->name('inbound.receive');

    Route::get('/picking', [WarehouseController::class, 'picking'])->name('picking');
    Route::get('/picking/{id}', [WarehouseController::class, 'picking'])->name('picking.show');
    Route::post('/picking/{id}/verify', [WarehouseController::class, 'verifyPickingItem'])->name('picking.verify');
    Route::post('/picking/{id}/complete', [WarehouseController::class, 'completePicking'])->name('picking.complete');

    Route::get('/transfers', [WarehouseController::class, 'transfers'])->name('transfers');
    Route::get('/transfers/{id}', [WarehouseController::class, 'transfers'])->name('transfers.show');
    Route::post('/transfers/{id}/receive', [WarehouseController::class, 'receiveTransfer'])->name('transfers.receive');
});
