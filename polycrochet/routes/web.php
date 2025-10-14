<?php

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog');
Route::get('/productos/{product:slug}', [CatalogController::class, 'show'])->name('product.show');
Route::get('/carrito', [CartController::class, 'index'])->name('cart');
Route::post('/carrito', [CartController::class, 'store'])->name('cart.store');
Route::patch('/carrito/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/carrito/resumen', [CartController::class, 'summary'])->name('cart.summary');
Route::view('/checkout', 'pages.checkout')->name('checkout');
Route::view('/pago/exito', 'pages.paypal.success')->name('paypal.success');
Route::view('/pago/cancelado', 'pages.paypal.cancel')->name('paypal.cancel');
Route::view('/pago/error', 'pages.paypal.error')->name('paypal.error');
Route::view('/pedido/confirmacion', 'pages.order-confirmation')->name('order.confirmation');
Route::view('/cuenta', 'pages.account')->name('account');
Route::view('/perfil', 'pages.profile')->name('profile');
Route::view('/nosotros', 'pages.nosotros')->name('nosotros');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::resource('productos', AdminProductController::class)
        ->except(['show'])
        ->names([
            'index' => 'products.index',
            'create' => 'products.create',
            'store' => 'products.store',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);
    Route::view('/pedidos', 'admin.orders.index')->name('orders');
    Route::view('/clientes', 'admin.customers.index')->name('customers');
    Route::view('/configuracion', 'admin.settings.index')->name('settings');
});
