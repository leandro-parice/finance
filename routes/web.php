<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPurchaseController;
use App\Http\Controllers\ImageFileController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('purchase', PurchaseController::class);
    Route::resource('product', ProductController::class);

    Route::resource('/purchase/{purchase}/product', ProductPurchaseController::class)->names([
        'index' => 'purchase.product.index',
        'create' => 'purchase.product.create',
        'store' => 'purchase.product.store',
        'show' => 'purchase.product.show',
        'edit' => 'purchase.product.edit',
        'update' => 'purchase.product.update',
        'destroy' => 'purchase.product.destroy',
    ]);

    Route::resource('image-file', ImageFileController::class);
});

Route::get('/test/read-images', [TestController::class, 'readImages']);
Route::get('/test/import-json', [TestController::class, 'importJsonFiles']);
Route::get('/test/ocr', [TestController::class, 'testTesseract']);

require __DIR__.'/auth.php';
