<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\UserAddressController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog');
Route::get('/productos/{product:slug}', [CatalogController::class, 'show'])->name('product.show');
Route::get('/carrito', [CartController::class, 'index'])->name('cart');
Route::post('/carrito', [CartController::class, 'store'])->name('cart.store');
Route::patch('/carrito/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/carrito/resumen', [CartController::class, 'summary'])->name('cart.summary');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::get('/pago/error', [PayPalController::class, 'error'])->name('paypal.error');
Route::get('/cuenta', [AccountController::class, 'index'])->name('account');
Route::view('/perfil', 'pages.profile')->name('profile');
Route::view('/nosotros', 'pages.nosotros')->name('nosotros');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/iniciar-sesion', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/iniciar-sesion', [AuthenticatedSessionController::class, 'store']);

    Route::get('/registro', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/registro', [RegisteredUserController::class, 'store']);

    Route::get('/recuperar-contrasena', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/recuperar-contrasena', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/restablecer-contrasena/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/restablecer-contrasena', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/verificar-correo', EmailVerificationPromptController::class)->name('verification.notice');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/email/verificacion', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('/checkout/pago', [PayPalController::class, 'store'])->name('paypal.order.store');
    Route::get('/pago/exito/{order:uuid}', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/pago/cancelado/{order:uuid}', [PayPalController::class, 'cancel'])->name('paypal.cancel');

    Route::post('/direcciones', [UserAddressController::class, 'store'])->name('user-addresses.store');
    Route::put('/direcciones/{userAddress}', [UserAddressController::class, 'update'])->name('user-addresses.update');
    Route::patch('/direcciones/{userAddress}/predeterminada', [UserAddressController::class, 'setDefault'])->name('user-addresses.default');
    Route::delete('/direcciones/{userAddress}', [UserAddressController::class, 'destroy'])->name('user-addresses.destroy');

    Route::get('/mis-pedidos', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/mis-pedidos/{order:uuid}', [OrderController::class, 'historyShow'])->name('orders.history.show');

    Route::get('/pedido/confirmacion/{order:uuid}', [OrderController::class, 'show'])->name('order.confirmation');
});

Route::get('/verificar-correo/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'admin'])
    ->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
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
    Route::get('/reportes/ventas-mensuales', [DashboardController::class, 'downloadMonthlySales'])->name('reports.monthly');
    Route::get('/reportes/ventas-semanales', [DashboardController::class, 'downloadWeeklySales'])->name('reports.weekly');
    Route::get('/reportes/ventas-categorias', [DashboardController::class, 'downloadCategorySales'])->name('reports.categories');
    Route::get('/reportes/ventas-productos', [DashboardController::class, 'downloadProductSales'])->name('reports.products');
    Route::get('/pedidos', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/pedidos/{order}/estado', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/pedidos/{order}/notas', [AdminOrderController::class, 'storeNote'])->name('orders.notes.store');
    Route::delete('/pedidos/{order}/notas/{note}', [AdminOrderController::class, 'destroyNote'])->name('orders.notes.destroy');
    Route::get('/clientes', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::view('/configuracion', 'admin.settings.index')->name('settings');
});
