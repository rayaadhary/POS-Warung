<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseDetailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesDetailController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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

    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
    Route::resource('/supplier', SupplierController::class);

    Route::get('/pengeluaran/data', [ExpenseController::class, 'data'])->name('pengeluaran.data');
    Route::resource('/pengeluaran', ExpenseController::class);

    Route::get('/pengguna/data', [UserController::class, 'data'])->name('pengguna.data');
    Route::resource('/pengguna', UserController::class);

    Route::get('/pembelian/data', [PurchaseController::class, 'data'])->name('pembelian.data');
    Route::get('/pembelian/{id}/create', [PurchaseController::class, 'create'])->name('pembelian.create');
    Route::resource('/pembelian', PurchaseController::class)
        ->except('create');


    Route::get('/pembelian_detail/{id}/data', [PurchaseDetailController::class, 'data'])->name('pembelian_detail.data');
    Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PurchaseDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
    Route::resource('/pembelian_detail', PurchaseDetailController::class)
        ->except('create', 'show', 'edit');

    Route::get('/produk/data', [ProductController::class, 'data'])->name('produk.data');
    Route::post('/produk/delete_selected', [ProductController::class, 'deleteSelected'])->name('produk.delete_selected');
    Route::resource('/produk', ProductController::class);

    Route::get('/penjualan/data', [SalesController::class, 'data'])->name('penjualan.data');
    Route::get('/penjualan', [SalesController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/{id}', [SalesController::class, 'show'])->name('penjualan.show');
    Route::delete('/penjualan/{id}', [SalesController::class, 'destroy'])->name('penjualan.destroy');

    Route::get('/transaksi/baru', [SalesController::class, 'create'])->name('transaksi.baru');
    Route::post('/transaksi/simpan', [SalesController::class, 'store'])->name('transaksi.simpan');
    Route::get('/transaksi/selesai', [SalesController::class, 'selesai'])->name('transaksi.selesai');
    Route::get('/transaksi/nota-kecil', [SalesController::class, 'notaKecil'])->name('transaksi.nota_kecil');
    Route::get('/transaksi/nota-besar', [SalesController::class, 'notaBesar'])->name('transaksi.nota_besar');

    Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [SalesDetailController::class, 'loadForm'])->name('transaksi.load_form');
    Route::get('/transaksi/{id}/data', [SalesDetailController::class, 'data'])->name('transaksi.data');
    Route::resource('/transaksi', SalesDetailController::class)
        ->except('show', 'create');

    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/data/{awal}/{akhir}', [ReportController::class, 'data'])->name('laporan.data');
    Route::get('/laporan/pdf/{awal}/{akhir}', [ReportController::class, 'exportPDF'])->name('laporan.export_pdf');
});
