<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Route;

// ito para pag open ng http://127.0.0.1:8000/ ito lalabas
// Route::get('/', function () {
//     return view('home.index');
// });
// Route::get('/', [HomeController::class, 'index'])->name('home');

Route::redirect('/', '/login');
    // kapag naglog in ang user dito rerekta
    // Route::get('/dashboard', function () {
    //     return view('admin.index');
    // })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::post('admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
Route::post('admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');
Route::get('admin/verify', [AdminController::class, 'ShowVerification'])->name('custom.verification.form');
Route::post('admin/verify', [AdminController::class, 'VerificationVerify'])->name('custom.verification.verify');
Route::post('admin/resend-otp', [AdminController::class, 'resendOtp'])->name('admin.resend.otp');


// brand
Route::middleware(['auth'])->group(function(){
Route::get('brand',[BrandController::class, 'brand'])->name('brand');
Route::post('brand/add',[BrandController::class,'createBrand'])->name('add.brand');
Route::get('brand/edit{id}',[BrandController::class,'editBrand'])->name('brand.view');
Route::put('brand/update/{id}',[BrandController::class,'updateBrand'])->name('update.brand');
Route::get('brand/archive',[BrandController::class,'archiveBrand'])->name('brand.archive');
Route::delete('brand/delete/{id}',[BrandController::class,'deleteBrand'])->name('brand.delete');
Route::put('brand/restore/{id}',[BrandController::class, 'restoreBrand'])->name('brand.restore');
});



// warehouse
Route::middleware(['auth'])->group(function () {

    Route::get('warehouse',[WarehouseController::class,'index'])->name('warehouse');
    Route::post('warehouse/store',[WarehouseController::class,'store'])->name('warehouse.store');
    Route::get('warehouse/edit/{id}',[WarehouseController::class,'edit'])->name('edit.warehouse');
    Route::put('warehouse/update/{id}',[WarehouseController::class,'update'])->name('update.warehouse');
    Route::get('warehouse/archive',[WarehouseController::class,'archived'])->name('archived.warehouse');
    Route::delete('warehouse/delete/{id}',[WarehouseController::class,'deleteWarehouse'])->name('delete.warehouse');
    Route::put('warehouse/restore/{id}',[WarehouseController::class,'restoreWarehouse'])->name('restore.warehouse');

});

// suppliers
Route::middleware(['auth'])->group(function () {

    Route::get('suppliers',[SupplierController::class,'index'])->name('suppliers');
    Route::post('supplier/store',[SupplierController::class,'store'])->name('store.supplier');
    Route::get('supplier/edit/{id}',[SupplierController::class,'edit'])->name('edit.supplier');
    Route::post('supplier/update/{id}',[SupplierController::class,'update'])->name('update.supplier');
    Route::delete('supplier/archive/{id}',[SupplierController::class,'archive'])->name('archive.supplier');
    Route::get('supplier/inactive',[SupplierController::class,'archiveSuppliers'])->name('inactive.supplier');
    Route::put('supplier/restore/{id}',[SupplierController::class,'restoreSupplier'])->name('restore.supplier');

});

// admin
Route::middleware(['auth'])->group(function(){
    Route::get('admin/profile',[AdminProfileController::class,'adminProfile'])->name('admin.profile');
    Route::post('admin/update/profile/{id}',[AdminProfileController::class,'updateProfile'])->name('update.profile');
    Route::post('admin/update/password{id}',[AdminProfileController::class,'updatePassword'])->name('update.password');
});


// customers
Route::middleware(['auth'])->group(function(){
    Route::get('customer',[CustomerController::class,'index'])->name('customers');
    Route::post('customer/store',[CustomerController::class,'store'])->name('store.customer');
    Route::get('customer/edit/{id}',[CustomerController::class,'editCustomer'])->name('edit.customer');
    Route::put('customer/update/{id}',[CustomerController::class,'updateCustomer'])->name('update.customer');
    Route::delete('customer/archive/{id}',[CustomerController::class,'deleteCustomer'])->name('archive.customer');
    Route::get('customer/archive',[CustomerController::class,'archiveCustomer'])->name('deleted.customer');
    Route::put('customer/restore/{id}',[CustomerController::class,'restoreCustomer'])->name('restore.customers');
    Route::post('customer/import', [CustomerController::class, 'importCustomer'])->name('import.customer');
});


// review
Route::middleware(['auth'])->group(function () {

    Route::get('review', [ReviewController::class, 'allReview'])->name('all.review');
    Route::post('review/add',[ReviewController::class,'addReview'])->name('add.review');
    Route::get('review/edit/{id}',[ReviewController::class,'editReview'])->name('edit.review');
    Route::put('review/update/{id}',[ReviewController::class,'updateReview'])->name('update.review');
    Route::delete('review/delete/{id}',[ReviewController::class,'deleteReview'])->name('delete.review');
    Route::get('archive/review',[ReviewController::class,'archiveReview'])->name('archive.Review');
    Route::put('restore/review/{id}',[ReviewController::class,'restoreReview'])->name('restore.review');
});


Route::middleware(['auth'])->group(function () {
    Route::get('finances',[SliderController::class,'editSlider'])->name('finances');
    Route::put('finances/edit/{id}',[SliderController::class, 'updateSlider'])->name('finances.update');
    Route::get('features/edit/{id}',[FeaturesController::class,'editFeatures'])->name('features.edit');
    Route::get('features',[FeaturesController::class,'index'])->name('features');
    Route::post('feature/add',[FeaturesController::class, 'createFeature'])->name('add.features');
    Route::put('feature/update/{id}',[FeaturesController::class, 'updateFeature'])->name('update.feature');
    Route::get('feature/deleted/',[FeaturesController::class, 'deletedFeatures'])->name('deleted.features');
    Route::delete('feture/delete/{id}',[FeaturesController::class, 'deleteFeatures'])->name('delete.features');
    Route::put('feature/restor/{id}',[FeaturesController::class, 'restoreFeature'])->name('restore.features');
});


Route::middleware(['auth'])->group(function () {

    // categories
    Route::get('categories',[ProductCategoriesController::class, 'index'])->name('categories');
    Route::post('categories/add',[ProductCategoriesController::class,'createCategory'])->name('add.categories');
    Route::put('categories/update/{id}',[ProductCategoriesController::class,'updateCategory'])->name('update.categories');
    Route::delete('categories/delete/{id}',[ProductCategoriesController::class,'deleteCategory'])->name('delete.categories');
    Route::get('categories/archive',[ProductCategoriesController::class, 'archivedCategories'])->name('archive.categories');
    Route::put('categories/restore/{id}',[ProductCategoriesController::class, 'restoreCategory'])->name('restore.categories');

    Route::get('product',[ProductController::class, 'index'])->name('product');
    Route::post('product/add',[ProductController::class,'store'])->name('add.product');
    Route::put('product/update/{id}',[ProductController::class,'update'])->name('update.product');
    Route::delete('product/delete/{id}',[ProductController::class,'deleteProduct'])->name('delete.product');
    Route::get('product/archive',[ProductController::class,'archiveProduct'])->name('archive.product');
    Route::put('product/restore/{id}',[ProductController::class,'restoreProduct'])->name('restore.product');
    Route::get('/product/view/{id}', [ProductController::class, 'view'])->name('product.view');

    // Purchase
    Route::get('purchase',[PurchaseController::class,'index'])->name('purchase');
    Route::get('purchase/add',[PurchaseController::class,'addPurchase'])->name('purchase.add');
    Route::post('purchase/store',[PurchaseController::class,'store'])->name('store.purchase');

    Route::get('purchase/product/search',[PurchaseController::class,'PurchaseProductSearch'])->name('purchase.product.search');
    Route::get('/admin/dashboard/purchase-chart/{year}',[DashboardController::class,'purchaseChart']);
    Route::get('/admin/dashboard/purchase-summary/{year}', [DashboardController::class, 'purchaseSummary']);
    Route::get('/purchase/edit/{id}', [PurchaseController::class, 'edit'])->name('purchase.edit');
    Route::put('/purchase/update/{id}', [PurchaseController::class, 'update'])->name('purchase.update');
    Route::get('/purchase/view/{id}', [PurchaseController::class, 'ViewPurchase'])
    ->name('purchase.view');
});
