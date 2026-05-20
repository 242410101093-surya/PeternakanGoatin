<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\ProdukController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\KatalogController;
use App\Models\Artikel;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('customer.dashboard');
})->middleware('auth')->name('dashboard');

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('guest');

// Registration
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit')->middleware('guest');

// Password reset
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// CUSTOMER
Route::prefix('customer')->name('customer.')->middleware('auth')->group(function () {

    // Main Features
    Route::get('/dashboard', function () {
        $artikels = Artikel::orderBy('created_at', 'desc')->take(4)->get();
        return view('customer.dashboard', compact('artikels'));
    })->name('dashboard');

    Route::get('/artikel/{artikel}', function (Artikel $artikel) {
        return view('customer.artikel.show', compact('artikel'));
    })->name('artikel.show');

    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');

    Route::get('/profile', function () {
        return view('customer.profile');
    })->name('profile');

    Route::get('/rekam-medis', function () {
        $rekamMedis = [
            ['id_ternak' => '1001', 'jenis' => 'Kambing Etawa', 'tanggal' => '2023-10-01', 'diagnosa' => 'Sehat', 'tindakan' => 'Vaksin PMK', 'status' => 'Sehat'],
            ['id_ternak' => '1005', 'jenis' => 'Kambing Boer', 'tanggal' => '2023-10-05', 'diagnosa' => 'Flu Ringan', 'tindakan' => 'Vitamin', 'status' => 'Masa Pemulihan'],
        ];
        return view('customer.rekam-medis', compact('rekamMedis'));
    })->name('rekam-medis');

    Route::get('/monitoring', function () {
        $ternak = [
            ['id' => '1001', 'jenis' => 'Kambing Etawa', 'umur' => 12, 'berat' => 45, 'status' => 'Sehat', 'last_update' => '2023-10-01'],
            ['id' => '1005', 'jenis' => 'Kambing Boer', 'umur' => 8, 'berat' => 30, 'status' => 'Masa Pemulihan', 'last_update' => '2023-10-05'],
            ['id' => '1010', 'jenis' => 'Domba Garut', 'umur' => 14, 'berat' => 50, 'status' => 'Sehat', 'last_update' => '2023-09-28'],
        ];
        return view('customer.monitoring', compact('ternak'));
    })->name('monitoring');
});




use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RekamMedisController;
use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\KeuanganController;

// ADMIN ROUTES
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Main Features
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');

    // Artikel
    Route::resource('artikel', ArtikelController::class)->except(['create', 'show', 'edit']);

    // Keuangan
    Route::resource('keuangan', KeuanganController::class)->except(['create', 'show', 'edit']);

    Route::resource('inventaris', InventarisController::class)->except(['create', 'show', 'edit']);
    Route::post('/inventaris/{id}/jual', [InventarisController::class, 'jual'])->name('inventaris.jual');

    Route::resource('katalog', KatalogController::class)->only(['index', 'update', 'destroy']);

    // Rekam Medis & Pertumbuhan Ternak
    Route::resource('rekam-medis', RekamMedisController::class)->except(['create', 'show', 'edit']);
    Route::post('rekam-medis/berat', [RekamMedisController::class, 'storeBerat'])->name('rekam-medis.berat');
});
