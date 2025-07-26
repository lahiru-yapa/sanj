<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RefController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReturnProductController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\GRNController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BinProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\RealCategoryController;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\WarehouseTransferController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', function () {
    return view('auth.login'); // Ensure this matches your login view
})->name('login');


Route::middleware(['auth'])->group(function () {
    
    Route::get('/index', [DashboardController::class, 'showInvoiceChart'])->name('dashboard');
    
    Route::get('/shops/credit-limit', [ShopesController::class, 'getShopCreditLimit'])->name('shops.creditLimit');
    Route::get('/products/suggest', [InvoiceController::class, 'suggestProducts'])->name('products.suggest');
    Route::get('/products/details', [InvoiceController::class, 'getProductDetails'])->name('products.details');
    Route::get('/get-average-days', [ShopesController::class, 'getAverageDays']);
    Route::get('/ref-add-invoice', [RefController::class, 'addinvoice'])->name('ref.addinvoice');//ref
       Route::post('/ref-invoices/add', [RefController::class, 'storeInvoice'])->name('ref.invoice.storeInvoice'); //ref
      Route::get('/get-products-by-warehouse/{warehouseId}/{brandId?}', [InvoiceController::class, 'getProductsByWarehouse'])
    ->name('get-products-by-warehouse'); //ref
     Route::get('/ref-invoices', [RefController::class, 'index'])->name('refinvoice.index'); //ref
      
        Route::post('/ref-invoices/action', [RefController::class, 'handleAction'])->name('invoices.ref.action');
        Route::get('/ref-invoices/{id}/edit', [RefController::class, 'edit'])->name('invoices.ref.edit');
        Route::post('/ref2-invoice/{id}', [RefController::class, 'updateInvoice'])->name('ref2.invoice.updateInvoice');
        
        Route::get('/ref-invoices/filter', [RefController::class, 'filterIndex'])->name('invoices.filter');
      
     
    
        Route::controller(ImportExportController::class)->group(function(){
            Route::get('import_export', 'importExport');
            Route::post('import', 'import')->name('import');
            Route::get('export', 'export')->name('export');
        });
       
    Route::middleware(['ref'])->group(function () {
        // Routes that require the 'ref' role
      
        Route::get('/get-products-by-brands/{brandId}', [InvoiceController::class, 'getProductsByBrand'])->name('get-products-by-brands');

    });
    
    Route::middleware(['stock'])->group(function () {
        Route::get('/stock-invoices', [StockController::class, 'index'])->name('stockinvoice.index');
        Route::post('/stock-invoices/action', [StockController::class, 'handleAction'])->name('invoices.stock.action');
        Route::post('/stock-invoice/store', [StockController::class, 'storeInvoice'])->name('invoice.stock.storeInvoice');
        Route::put('/stock-invoice/{id}', [StockController::class, 'updateInvoice'])->name('stock.invoice.updateInvoice');
    });
    Route::middleware(['admin'])->group(function () {
        
        // web.php
        Route::post('/grn-item/delete', [GRNController::class, 'destroyItem']);


        Route::get('/warehouse-transfer', [WarehouseTransferController::class, 'index'])->name('warehouse-transfer.index');
        Route::get('/warehouse-transfer/create', [WarehouseTransferController::class, 'create'])->name('warehouse-transfer.create');
        Route::post('/warehouse-transfer/store', [WarehouseTransferController::class, 'store'])->name('warehouse-transfer.store');
        Route::get('/warehouse-transfer/{id}', [WarehouseTransferController::class, 'show'])->name('warehouse-transfer.show');

        Route::get('/all-user', [UserController::class, 'allUsers'])->name('alluser');
        Route::get('/add-user', [UserController::class, 'adduser'])->name('adduser');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/edit', [UserController::class, 'editUseSstore'])->name('user.editStore');
        Route::get('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');

        Route::get('/payment', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/invoices/{shop_id}', [PaymentController::class, 'getInvoices']);
     
Route::get('/profit-loss', [PaymentController::class, 'getProfitLoss']);


        Route::get('/all-shopes', [ShopesController::class, 'allshopes'])->name('allshopes');
        Route::get('/add-shopes', [ShopesController::class, 'addshopes'])->name('addshopes');
        Route::post('/shops', [ShopesController::class, 'store'])->name('shops.store');
        Route::get('shops/{id}/edit', [ShopesController::class, 'edit'])->name('shops.edit');
        Route::post('/shop/edit', [ShopesController::class, 'editShopstore'])->name('shop.editStore');
        Route::get('/shop/delete/{id}', [ShopesController::class, 'delete'])->name('shop.delete');

         Route::get('/bin-products', [BinProductController::class, 'index'])->name('bin_products');

        Route::get('/all-suppliers', [SupplierController::class, 'allsuppliers'])->name('allsuppliers');
        Route::get('/add-suppliers', [SupplierController::class, 'addsuppliers'])->name('addsuppliers');
        Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::post('/suppliers/edit', [SupplierController::class, 'editSuppliers'])->name('suppliers.editStore');
        Route::get('/suppliers/delete/{id}', [SupplierController::class, 'delete'])->name('suppliers.delete');
        Route::get('suppliers/{id}/view', [SupplierController::class, 'view'])->name('suppliers.view');

        
        Route::get('/all-returns', [ReturnProductController::class, 'allReturns'])->name('allReturns');
        Route::get('/add-returns', [ReturnProductController::class, 'addReturns'])->name('addReturns');
        Route::get('/get-invoice-products', [ReturnProductController::class, 'getInvoiceProducts']);
        Route::post('/return-product', [ReturnProductController::class, 'returnProduct'])->name('product.return');
        Route::get('/get-returned-products', [ReturnProductController::class, 'getReturnedProducts']);
Route::get('product-return/{id}/{product_id}/edit', [ReturnProductController::class, 'edit'])->name('productReturn.edit');


        Route::get('/all-product', [ProductController::class, 'allproducts'])->name('allproduct');
        Route::get('/add-product', [ProductController::class, 'addproduct'])->name('addproduct');
        Route::post('product', [ProductController::class, 'store'])->name('product.store');
        Route::get('product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('/product/edit', [ProductController::class, 'editproduct'])->name('product.editProduct');
        Route::get('/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
        Route::get('product/{id}/view', [ProductController::class, 'view'])->name('product.view');
        Route::get('/admin/products/low-stock', [ProductController::class, 'lowStock'])->name('products.lowstock');
        Route::get('/admin/stock/low/ajax', [ProductController::class, 'lowStockAjax'])->name('stock.low.ajax');


        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('/products/suggest', [InvoiceController::class, 'suggestProducts'])->name('products.suggest');
        Route::get('/invoice/get-shop', [InvoiceController::class, 'getShopByInvoice'])->name('invoice.getShopByInvoice');
        Route::get('/invoice/filter', [InvoiceController::class, 'filter'])->name('invoice.filter');
        Route::post('/admin-invoice/{id}', [InvoiceController::class, 'updateInvoice'])->name('admin.invoice.updateInvoice');

        Route::post('/product/store', [InvoiceController::class, 'store'])->name('invoice.store');
        Route::get('/add-invoice', [InvoiceController::class, 'addinvoice'])->name('addinvoice');
       
        Route::get('/invoice/searchAndSuggest', [InvoiceController::class, 'suggest'])->name('invoice.suggest');
        Route::post('/invoice/store', [InvoiceController::class, 'storeInvoice'])->name('invoice.storeInvoice');
        Route::post('/invoices/action', [InvoiceController::class, 'handleAction'])->name('invoices.action');
        Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{id}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
        // In routes/web.php
        Route::post('/update-invoice-description', [InvoiceController::class, 'updateDescription']);
        Route::put('/invoice/{id}', [InvoiceController::class, 'updateInvoice'])->name('invoice.updateInvoice');
        Route::get('/invoices/filter', [InvoiceController::class, 'filterIndex'])->name('admin.invoices.filter');
        Route::get('/admin/invoice-chart', [DashboardController::class, 'showInvoiceChart'])->name('invoices.chart');

        Route::get('/get-filtered-products', [ProductController::class, 'getFilteredProducts'])->name('get.filtered.products');
        Route::get('/get-filtered-products2', [ProductController::class, 'getFilteredProducts2'])->name('get.filtered.products2');

        Route::get('/brand/add', [BrandController::class, 'create'])->name('add-brand');
        Route::get('/brand/index', [BrandController::class, 'index'])->name('categories.index');
        Route::post('/brand/store', [BrandController::class, 'store'])->name('store-brand');
        Route::get('/brand', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/brand/{id}/edit', [BrandController::class, 'edit'])->name('brand.edit');
        Route::post('/brand/{id}/update', [BrandController::class, 'update'])->name('brand.update');
        Route::get('/brand/{id}/delete', [BrandController::class, 'destroy'])->name('brand.delete');

        Route::resource('real-categories', RealCategoryController::class);
        Route::resource('bikes', BikeController::class);

        Route::get('/all-financial', [ExpenseController::class, 'index'])->name('allfinancial');
        Route::get('/add-financial', [ExpenseController::class, 'create'])->name('addfinancial');
        Route::get('financial/{id}/edit', [ExpenseController::class, 'edit'])->name('financial.edit');
        Route::get('/financial/delete/{id}', [ExpenseController::class, 'delete'])->name('financial.delete');
        Route::get('financial/{id}/view', [ExpenseController::class, 'view'])->name('financial.view');
        Route::post('financial', [ExpenseController::class, 'store'])->name('financial.store');
        Route::put('/financial/{id}', [ExpenseController::class, 'update'])->name('financial.update');

        Route::resource('warehouses', WarehouseController::class);
        Route::resource('grns', GRNController::class);
        Route::post('/grns/store-item', [GRNController::class, 'storeItem'])->name('grns.storeItem');
        Route::post('/grns/update-item', [GRNController::class, 'updateItem'])->name('grns.updateItem');
        Route::post('/grns/store', [GRNController::class, 'storeall'])->name('grns.storeall');
         Route::post('/grns/updateall', [GRNController::class, 'updateall'])->name('grns.updateall');
Route::post('/grns/create-item', [GrnController::class, 'createItem'])->name('grns.createItem');



        Route::controller(ImportExportController::class)->group(function(){
            Route::get('import_export_admin', 'importExport');
            Route::post('import', 'import_admin')->name('import');
            Route::get('export_admin', 'export_admin')->name('export_admin');
        });
      Route::get('/sales-report', [SalesReportController::class, 'getSalesReport'])->name('sales.report');
Route::get('/product-sales-report', [SalesReportController::class, 'getProductSalesReport'])->name('product.sales.report');

     // Route to load the sales report page
Route::get('/sales-report-index', [SalesReportController::class, 'index'])->name('sales.report.index');
Route::get('/stock-summary', [SalesReportController::class, 'getStockSummary']);
Route::get('/get-profit-report', [SalesReportController::class, 'getProfitReport']);

Route::get('/report/filter', [SalesReportController::class, 'filterGRNs'])->name('report.filter');
Route::get('/sales-suplier-report-index', [SalesReportController::class, 'suplierReport'])->name('sales.suplierReport.index');
Route::post('/sales/filter', [SalesReportController::class, 'filter'])->name('sales.filter');
Route::get('/filter-product-sales', [SalesReportController::class, 'filterProductSales'])->name('filter.product.sales');
    });
    
});

 
Route::get('/', function () {
    return redirect('/login');
});