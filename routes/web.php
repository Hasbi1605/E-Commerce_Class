<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SlideshowController;

Route::get('/', [HomepageController::class, 'index']);

Route::get('/about', [HomepageController::class, 'about']);

Route::get('/kontak', [HomepageController::class, 'kontak']);

Route::get('/kategori', [HomepageController::class, 'kategori']);

Route::get('/kategori/{slug}', [HomepageController::class, 'kategori']);

Route::get('/produk', [HomepageController::class, 'produk']);

Route::get('/produk/{id}', [HomepageController::class, 'produkdetail']);

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('admin');

    //Tambahan route package kategori
    Route::resource('/kategori', KategoriController::class)->names('kategori');

    //Tambahan route package produk
    Route::resource('/produk', ProdukController::class)->names('produk');

    //Tambahan route package customer
    Route::resource('/customer', CustomerController::class)->names('customer');

    // slideshow
    Route::resource('slideshow', SlideshowController::class);

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

    // produk promo
    Route::resource('promo', \App\Http\Controllers\ProdukPromoController::class);
    // load async produk
    Route::get('loadprodukasync/{id}', [\App\Http\Controllers\ProdukController::class, 'loadasync']);
});

// shopping cart
Route::group(['middleware' => 'auth'], function() {
    // cart
    Route::resource('/cart', \App\Http\Controllers\CartController::class);
    Route::patch('kosongkan/{id}', [\App\Http\Controllers\CartController::class, 'kosongkan']);
    // cart detail
    Route::resource('/cartdetail', \App\Http\Controllers\CartDetailController::class);
    // alamat pengiriman
    Route::resource('/alamatpengiriman', \App\Http\Controllers\AlamatPengirimanController::class);
    // checkout
    Route::get('checkout', [\App\Http\Controllers\CartController::class, 'checkout']);
});

Auth::routes();
