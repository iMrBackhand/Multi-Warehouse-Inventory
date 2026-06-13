<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.index');
});


// kapag naglog in ang user dito rerekta
Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');



Route::get('brand',[BrandController::class, 'brand'])->name('brand');

// warehouse
Route::get('warehouse',[WarehouseController::class,'index'])->name('warehouse');
Route::post('warehouse/store',[WarehouseController::class,'store'])->name('warehouse.store');
Route::get('warehouse/edit/{id}',[WarehouseController::class,'edit'])->name('edit.warehouse');
Route::post('warehouse/update/{id}',[WarehouseController::class,'update'])->name('update.warehouse');
Route::get('archive/warehouse',[WarehouseController::class,'archived'])->name('archived.warehouse');
Route::delete('delete/warehouse/{id}',[WarehouseController::class, 'deleteWarehouse'])->name('delete.warehouse');
Route::put('restore/warehouse/{id}',[WarehouseController::class,'restoreWarehouse'])->name('restore.warehouse');

// suppliers
Route::get('suppliers',[SupplierController::class,'index'])->name('suppliers');
Route::post('supplier/store',[SupplierController::class,'store'])->name('store.supplier');
Route::get('supplier/edit/{id}',[SupplierController::class,'edit'])->name('edit.supplier');
Route::post('supplier/update/{id}',[SupplierController::class,'update'])->name('update.supplier');
Route::delete('supplier/archive/{id}',[SupplierController::class,'archive'])->name('archive.supplier');
Route::get('supplier/InActive',[SupplierController::class,'archiveSuppliers'])->name('inactive.supplier');
Route::put('supplier/restore/{id}',[SupplierController::class,'restoreSupplier'])->name('restore.supplier');


Route::get('customer',[CustomerController::class,'index'])->name('customers');
