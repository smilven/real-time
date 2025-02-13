<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productController;
use App\Http\Controllers\updateStatusController;
use  App\Http\Controllers\cartController;
use  App\Http\Controllers\OrderController;
use  App\Http\Controllers\welcomeController;
use App\Http\Controllers\adminDashboardController;
use App\Exports\OrderItemsExport;
use App\Http\Controllers\adminSettingController;
use Maatwebsite\Excel\Facades\Excel;

Auth::routes();
git checkout feature-branch

Route::get('/', [App\Http\Controllers\welfcomeController::class, 'homePage'])->name('welcome');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get("userProducts",[productController::class,"userIndex"])->name("products.userIndex");
Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get("/updateStatus",[updateStatusController::class,"index"])->name("productUpdate");
Route::post('/cart/addToCart', [CartController::class, 'addToCart'])->name('addToCart.add');
Route::get("myCart",[CartController::class,"myCartIndex"])->name("myCart.index");
Route::post('/place-order', [CartController::class, 'placeOrder'])->name('place.order');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');




    Route::get("manageProducts", [ProductController::class, "index"])->name("products.index");
    Route::post("products", [ProductController::class, "store"])->name("products.store");
    Route::get('/adminSetting', [adminSettingController::class, 'indexAdminSetting'])->name('admin.setting');
    Route::put('/settings', [adminSettingController::class, 'update'])->name('settings.update');


    Route::get('/manageOrders', [OrderController::class, 'adminIndex'])->name('admin.orders');
    Route::post('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update');
    
    Route::get('/adminDashboard',[adminDashboardController::class,'indexAdminDashboard'])->name('admin.dashboard');
    Route::get('/adminDashboard/dailySales', [AdminDashboardController::class, 'getDailySalesData']);
    Route::get('/adminDashboard/weeklySales', [AdminDashboardController::class, 'getWeeklySalesData']);
    Route::get('/adminDashboard/monthlySales', [AdminDashboardController::class, 'getMonthlySalesData']);
    Route::get('/admin/order-items/export', [AdminDashboardController::class, 'exportOrderItemsToExcel'])->name('order-items.export');
