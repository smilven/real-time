<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productController;
use App\Http\Controllers\updateStatusController;
use  App\Http\Controllers\cartController;
use  App\Http\Controllers\OrderController;
use  App\Http\Controllers\welcomeController;


Auth::routes();

Route::get('/', [App\Http\Controllers\welcomeController::class, 'homePage'])->name('welcome');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get("userProducts",[productController::class,"userIndex"])->name("products.userIndex");
Route::get("products",[productController::class,"index"])->name("products.index");
Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get("/updateStatus",[updateStatusController::class,"index"])->name("productUpdate");
Route::post("products",[productController::class,"store"])->name("products.store");
Route::post('/cart/addToCart', [CartController::class, 'addToCart'])->name('addToCart.add');

Route::get("myCart",[CartController::class,"myCartIndex"])->name("myCart.index");

Route::post('/place-order', [CartController::class, 'placeOrder'])->name('place.order');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/manageOrders', [OrderController::class, 'adminIndex'])->name('admin.orders');
Route::post('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update');
