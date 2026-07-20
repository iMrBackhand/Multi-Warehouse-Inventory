<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\GcashPaymentController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnPurchaseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityLogController;



Route::redirect('/', '/login');


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

    // paymongo
    Route::post('/webhooks/paymongo', [GcashPaymentController::class, 'webhook'])->name('gcash.webhook');



Route::middleware(['auth'])->group(function () {
    // admin
    Route::get('admin/profile',[AdminProfileController::class,'adminProfile'])->name('admin.profile');
    Route::post('admin/update/profile/{id}',[AdminProfileController::class,'updateProfile'])->name('update.profile');
    Route::post('admin/update/password{id}',[AdminProfileController::class,'updatePassword'])->name('update.password');

    // brand
    // Route::get('brand',[BrandController::class, 'brand'])->name('brand');
    // Route::post('brand/add',[BrandController::class,'createBrand'])->name('add.brand');
    // Route::get('brand/edit{id}',[BrandController::class,'editBrand'])->name('brand.view');
    // Route::put('brand/update/{id}',[BrandController::class,'updateBrand'])->name('update.brand');
    // Route::get('brand/archive',[BrandController::class,'archiveBrand'])->name('brand.archive');
    // Route::delete('brand/delete/{id}',[BrandController::class,'deleteBrand'])->name('brand.delete');
    // Route::put('brand/restore/{id}',[BrandController::class, 'restoreBrand'])->name('brand.restore');

    Route::prefix('brand')->group(function () {

    // View Brand
    Route::middleware('permission:brand.view')->group(function () {
        Route::get('/', [BrandController::class, 'brand'])->name('brand');
        Route::get('/archive', [BrandController::class, 'archiveBrand'])->name('brand.archive');
    });

    // Create Brand
    Route::middleware('permission:brand.create')->group(function () {
        Route::post('/add', [BrandController::class, 'createBrand'])->name('add.brand');
    });

    // Update Brand
    Route::middleware('permission:brand.update')->group(function () {
        Route::get('/edit/{id}', [BrandController::class, 'editBrand'])->name('brand.edit');
        Route::put('/update/{id}', [BrandController::class, 'updateBrand'])->name('update.brand');
    });

    // Delete / Restore Brand
    Route::middleware('permission:brand.delete')->group(function () {
        Route::delete('/delete/{id}', [BrandController::class, 'deleteBrand'])->name('brand.delete');
        Route::put('/restore/{id}', [BrandController::class, 'restoreBrand'])->name('brand.restore');
    });

});

    // customers
    Route::get('customer',[CustomerController::class,'index'])->name('customers');
    Route::post('customer/store',[CustomerController::class,'store'])->name('store.customer');
    Route::get('customer/edit/{id}',[CustomerController::class,'editCustomer'])->name('edit.customer');
    Route::put('customer/update/{id}',[CustomerController::class,'updateCustomer'])->name('update.customer');
    Route::delete('customer/archive/{id}',[CustomerController::class,'deleteCustomer'])->name('archive.customer');
    Route::get('customer/archive',[CustomerController::class,'archiveCustomer'])->name('deleted.customer');
    Route::put('customer/restore/{id}',[CustomerController::class,'restoreCustomer'])->name('restore.customers');
    Route::post('customer/import', [CustomerController::class, 'importCustomer'])->name('import.customer');

    // warehouse

    Route::get('warehouse',[WarehouseController::class,'index'])->name('warehouse');
    Route::post('warehouse/store',[WarehouseController::class,'store'])->name('warehouse.store');
    Route::get('warehouse/edit/{id}',[WarehouseController::class,'edit'])->name('edit.warehouse');
    Route::put('warehouse/update/{id}',[WarehouseController::class,'update'])->name('update.warehouse');
    Route::get('warehouse/archive',[WarehouseController::class,'archived'])->name('archived.warehouse');
    Route::delete('warehouse/delete/{id}',[WarehouseController::class,'deleteWarehouse'])->name('delete.warehouse');
    Route::put('warehouse/restore/{id}',[WarehouseController::class,'restoreWarehouse'])->name('restore.warehouse');

    // reviews
    Route::get('review', [ReviewController::class, 'allReview'])->name('all.review');
    Route::post('review/add',[ReviewController::class,'addReview'])->name('add.review');
    Route::get('review/edit/{id}',[ReviewController::class,'editReview'])->name('edit.review');
    Route::put('review/update/{id}',[ReviewController::class,'updateReview'])->name('update.review');
    Route::delete('review/delete/{id}',[ReviewController::class,'deleteReview'])->name('delete.review');
    Route::get('archive/review',[ReviewController::class,'archiveReview'])->name('archive.Review');
    Route::put('restore/review/{id}',[ReviewController::class,'restoreReview'])->name('restore.review');

    // suppliers
    Route::get('suppliers',[SupplierController::class,'index'])->name('suppliers');
    Route::post('supplier/store',[SupplierController::class,'store'])->name('store.supplier');
    Route::get('supplier/edit/{id}',[SupplierController::class,'edit'])->name('edit.supplier');
    Route::post('supplier/update/{id}',[SupplierController::class,'update'])->name('update.supplier');
    Route::delete('supplier/archive/{id}',[SupplierController::class,'archive'])->name('archive.supplier');
    Route::get('supplier/inactive',[SupplierController::class,'archiveSuppliers'])->name('inactive.supplier');
    Route::put('supplier/restore/{id}',[SupplierController::class,'restoreSupplier'])->name('restore.supplier');

    // sliders
    Route::get('finances',[SliderController::class,'editSlider'])->name('finances');
    Route::put('finances/edit/{id}',[SliderController::class, 'updateSlider'])->name('finances.update');
    Route::get('features/edit/{id}',[FeaturesController::class,'editFeatures'])->name('features.edit');
    Route::get('features',[FeaturesController::class,'index'])->name('features');
    Route::post('feature/add',[FeaturesController::class, 'createFeature'])->name('add.features');
    Route::put('feature/update/{id}',[FeaturesController::class, 'updateFeature'])->name('update.feature');
    Route::get('feature/deleted/',[FeaturesController::class, 'deletedFeatures'])->name('deleted.features');
    Route::delete('feture/delete/{id}',[FeaturesController::class, 'deleteFeatures'])->name('delete.features');
    Route::put('feature/restor/{id}',[FeaturesController::class, 'restoreFeature'])->name('restore.features');

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
    Route::delete('delete/purchase/{id}',[PurchaseController::class, 'deletePurchase'])->name('delete.purchase');
    Route::get('archive/purchase',[PurchaseController::class,'archivedPurchase'])->name('archived.purchase');
    Route::put('restore/purchase/{id}',[PurchaseController::class, 'restorePurchase'])->name('restore.purchase');
    Route::get('/admin/dashboard/purchase-chart/{year}',[DashboardController::class,'purchaseChart']);
    Route::get('/admin/dashboard/purchase-summary/{year}', [DashboardController::class, 'purchaseSummary']);
    Route::get('/admin/dashboard/sales-chart/{year}', [DashboardController::class, 'salesChart']);
    Route::get('/admin/dashboard/sales-summary/{year}', [DashboardController::class, 'salesSummary']);

    Route::get('purchase/product/search',[PurchaseController::class,'PurchaseProductSearch'])->name('purchase.product.search');
    Route::get('/admin/dashboard/purchase-chart/{year}',[DashboardController::class,'purchaseChart']);
    Route::get('/admin/dashboard/purchase-summary/{year}', [DashboardController::class, 'purchaseSummary']);
    Route::get('/purchase/edit/{id}', [PurchaseController::class, 'edit'])->name('purchase.edit');
    Route::put('/purchase/update/{id}', [PurchaseController::class, 'update'])->name('purchase.update');
    Route::get('/purchase/view/{id}', [PurchaseController::class, 'ViewPurchase'])
    ->name('purchase.view');

    // Return Purchase
    Route::get('all/return/purchase',[ReturnPurchaseController::class,'index'])->name('return.purchase');
    Route::get('purchase/add/return',[ReturnPurchaseController::class,'addReturn'])->name('return.purchase.add');
    Route::post('purchase//return',[ReturnPurchaseController::class,'StoreReturnPurchase'])->name('store.return.purchase');
    Route::get('/purchase/return/edit/{id}', [ReturnPurchaseController::class, 'edit'])->name('purchase.return.edit');
    Route::put('return/purchase/update/{id}', [ReturnPurchaseController::class, 'updateReturnPurchase'])->name('return.purchase.update');
    Route::get('return/purchase/view/{id}', [ReturnPurchaseController::class, 'ViewReturnPurchase'])
    ->name('return.purchase.view');
    Route::delete('delete/return/purchase/{id}',[ReturnPurchaseController::class, 'deleteReturnPurchase'])->name('delete.returnPurchase');
    Route::get('inactive/return/purchase',[ReturnPurchaseController::class,'inactiveReturn'])->name('inactive.return');
    Route::put('restore/return/{id}',[ReturnPurchaseController::class,'restoreReturn'])->name('restore.return');

    // Sales
    Route::get('all/sales',[SaleController::class,'index'])->name('all.sales');
    Route::get('add/sales',[SaleController::class, 'addSale'])->name('add.sales');
    Route::post('store/sales',[SaleController::class, 'storeSale'])->name('store.sale');
    Route::delete('delete/sales/{id}',[SaleController::class,'deleteSales'])->name('delete.sales');
    Route::get('inactive/sales',[SaleController::class,'inactiveSales'])->name('inactive.sales');
    Route::put('restore/sales/{id}',[SaleController::class, 'restoreSales'])->name('restore.sale');
    Route::get('edit/sales/{id}',[SaleController::class, 'editSales'])->name('edit.sale');
    Route::put('update/sales/{id}',[SaleController::class, 'updateSales'])->name('update.sale');
    Route::get('view/sale/{id}', [SaleController::class, 'viewSales'])->name('view.sale');

    // Sale Return
    Route::get('all/return/sales',[SaleReturnController::class, 'index'])->name('allreturn.sales');
    Route::get('add/return/sales',[SaleReturnController::class, 'addReturnSales'])->name('addreturn.sale');
    Route::put('store/return/sales',[SaleReturnController::class,'storeSaleReturn'])->name('storereturn.sale');
    Route::get('edit/return/sale/{id}',[SaleReturnController::class,'editReturnSales'])->name('editreturn.sales');
    Route::put('update/return/sale/{id}',[SaleReturnController::class,'updateReturnSales'])->name('update.return.sales');
    Route::get('return-sales/{id}/view', [SaleReturnController::class, 'view'])->name('viewreturn.sales');
    Route::delete('delete/return/sale/{id}',[SaleReturnController::class,'deleteReturnSale'])->name('delete.return.sale');
    Route::get('inactive/return/sale',[SaleReturnController::class,'inactiveReturnSales'])->name('inactive.return.sale');
    Route::put('restore/sale/return/{id}',[SaleReturnController::class,'restore'])->name('restore.return.sale');

    // Due
    Route::get('view/due/sale', [SaleController::class, 'DueSale'])->name('due.sale');

    // payment
    Route::get('/sales/{sale}/pay-gcash', [GcashPaymentController::class, 'create'])->name('gcash.pay');
    Route::get('/payment/success', [GcashPaymentController::class, 'success'])->name('gcash.success');
    Route::get('/payment/failed', [GcashPaymentController::class, 'failed'])->name('gcash.failed');

    // Transfer
    Route::get('all/transfer',[TransferController::class, 'index'])->name('all.transfer');
    Route::get('add/transfer/form',[TransferController::class, 'addTransferView'])->name('add.transfer');
    Route::post('/transfer/store', [TransferController::class, 'storeTransfer'])->name('transfer.storeTransfer');
    Route::patch('/transfer/{transfer}/received', [TransferController::class, 'markReceived'])->name('transfer.markReceived');
    Route::get('view/transfer/{id}',[TransferController::class,'viewTransfer'])->name('view.transfer');

    Route::get('/products/warehouse/{warehouse}', [ProductController::class, 'warehouseProducts'])
    ->name('warehouse.products');

    // reports
    Route::get('all/reports',[ReportController::class,'index'])->name('all.reports');
    Route::get('sales/export',[ReportController::class, 'exportSales'])->name('sales.export');
    Route::get('purchase/export', [ReportController::class, 'exportPurchase'])->name('purchase.export');
    Route::get('sale-return/export', [ReportController::class, 'exportSaleReturn'])->name('salereturn.export');
    Route::get('purchase-return/export', [ReportController::class, 'exportPurchaseReturn'])->name('purchasereturn.export');

    Route::controller(RoleController::class)->group(function () {

        // Permission
        Route::get('permission/all', 'allPermission')->middleware('permission:permission.view')->name('all.permission');
        Route::post('permission/store', 'storePermission')->middleware('permission:permission.create')->name('store.permission');
        Route::put('permission/update/{id}', 'updatePermission')->middleware('permission:permission.edit')->name('update.permission');
        Route::delete('permission/delete/{id}', 'deletePermission')->middleware('permission:permission.delete')->name('delete.permission');

        // Role
        Route::get('role/all', 'allRoles')->middleware('permission:role.view')->name('all.roles');
        Route::post('role/store', 'storeRoles')->middleware('permission:role.create')->name('add.roles');
        Route::put('role/update/{id}', 'updateRole')->middleware('permission:role.edit')->name('store.roles');
        Route::delete('role/delete/{id}', 'deleteRole')->middleware('permission:role.delete')->name('delete.role');

        Route::get('role/permission/add', 'AddRolePermission')->middleware('permission:role.permission.assign')->name('addinrole.permission');
        Route::post('role/permission/store', 'storeRolePermission')->middleware('permission:role.permission.assign')->name('storerole.permission');
        Route::get('role/permission/all', 'allRolesPermission')->middleware('permission:role.permission.assign')->name('all.roles.permission');
        Route::get('role/permission/edit/{id}', 'editRolePermission')->middleware('permission:role.permission.assign')->name('editrole.permission');
        Route::post('role/permission/update/{id}', 'updateRolePermission')->middleware('permission:role.permission.assign')->name('updaterole.permission');
        Route::delete('role/permission/delete/{id}', 'deleteRolePermission')->middleware('permission:role.permission.assign')->name('deleterole.permission');

        // Admin
        Route::get('admin/all', 'allAdmin')->middleware('permission:admin.view')->name('all.admin');
        Route::post('admin/store', 'storeAdmin')->middleware('permission:admin.create')->name('store.admin');
        Route::get('admin/edit/{id}', 'editAdmin')->middleware('permission:admin.edit')->name('edit.admin');
        Route::put('admin/update/{id}', 'updateAdmin')->middleware('permission:admin.edit')->name('update.admin');
        Route::delete('admin/delete/{id}', 'deleteAdmin')->middleware('permission:admin.delete')->name('delete.admin');

    });


        Route::get('activity/log', [ActivityLogController::class, 'index'])->middleware('permission:activity-log.view')->name('activity.log');
});
