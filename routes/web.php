<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LatihanController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/halo', function() {
//     return "Hallo Nama Saya Albert";
// });

// Route::get('/latihan', [LatihanController::class, 'index']);
// Route::get('/blog/{idblog}', [LatihanController::class, 'blog']);
// Route::get('/blog/{idblog}/komentar/{idkomentar}', [LatihanController::class, 'komentar']);
// Route::get('/beranda',[LatihanController::class, 'beranda']);

Route::get('/', [HomepageController::class, 'index']);

Route::get('/about', [HomepageController::class, 'about']);

Route::get('/kontak', [HomepageController::class, 'kontak']);

Route::get('/kategori', [HomepageController::class, 'kategori']);

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('admin');

    //Tambahan route package kategori
    Route::resource('/kategori', KategoriController::class)->names('kategori');

    //Tambahan route package produk
    Route::resource('/produk', ProdukController::class)->names('produk');

    //Tambahan route package customer
    Route::resource('/customer', CustomerController::class)->names('customer');

    //Tambahan route package transaksi
    Route::resource('/transaksi', TransaksiController::class)->names('transaksi');

    // upload image produk
    Route::post('produkimage', [ProdukController::class, 'uploadimage']);
// hapus image produk
    Route::delete('produkimage/{id}', [ProdukController::class, 'deleteimage']);

    //Tambahan route package user
    Route::get('/profil', [UserController::class, 'index'])->name('profil');
    Route::get('/setting', [UserController::class, 'setting'])->name('setting');

    //image
    Route::get('/image', [ImageController::class, 'index'])->name('image.index');
    //simpan image
    Route::post('/image', [ImageController::class, 'store'])->name('image.store');
    //hapus image
    Route::delete('/image/{id}', [ImageController::class, 'destroy'])->name('image.destroy');
    // upload image kategori
      Route::post('imagekategori', [KategoriController::class, 'uploadimage']);
      // hapus image kategori
      Route::delete('imagekategori/{id}', [KategoriController::class, 'deleteimage']);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');