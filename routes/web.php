<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn () => redirect()->route('login'));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/kategori/data', [CategoryController::class, 'data'])->name('kategori.data');
    Route::resource('/kategori', CategoryController::class);

    Route::get('/pelanggan/data', [CustomerController::class, 'data'])->name('pelanggan.data');
    Route::resource('/pelanggan', CustomerController::class);

    Route::get('/produk/data', [ProductController::class, 'data'])->name('produk.data');
    Route::post('/produk/delete_selected', [ProductController::class, 'deleteSelected'])->name('produk.delete_selected');
    Route::resource('/produk', ProductController::class);
});
