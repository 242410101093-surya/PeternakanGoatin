<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\ProdukController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\KatalogController;
use App\Models\Artikel;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    // If already logged in, redirect to the appropriate dashboard
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.produk');
    }

    $featuredProducts = \App\Models\Produk::with(['inventaris.rekamMedis'])
        ->whereHas('inventaris', function ($q) {
            $q->where('status_stok', 'Tersedia');
        })
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    return view('welcome', compact('featuredProducts'));
})->name('landing');

Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('customer.produk');
})->middleware('auth')->name('dashboard');

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('guest');

// Registration
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit')->middleware('guest');

// Password reset
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('password.email')->middleware('guest');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [AuthController::class, 'resetPasswordWithCode'])->name('password.update')->middleware('guest');

// Google Auth Mock Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google')->middleware('guest');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// CUSTOMER
Route::prefix('customer')->name('customer.')->middleware(['auth', 'customer.role'])->group(function () {

    // Main Features
    Route::get('/dashboard', function () {
        $artikels = Artikel::orderBy('created_at', 'desc')->take(7)->get();
        return view('customer.dashboard', compact('artikels'));
    })->name('dashboard');

    Route::get('/artikel/{artikel}', function (Artikel $artikel) {
        return view('customer.artikel.show', compact('artikel'));
    })->name('artikel.show');

    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::post('/produk/{produk}/beli', [ProdukController::class, 'notifikasiBeli'])->name('produk.notifikasi-beli');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/send-verification', [ProfileController::class, 'sendVerificationCode'])->name('profile.send-verification');
    Route::post('/profile/verify-email', [ProfileController::class, 'verifyEmail'])->name('profile.verify-email');
    Route::post('/profile/send-password-otp', [ProfileController::class, 'sendPasswordOtp'])->name('profile.send-password-otp');



    Route::get('/monitoring', function () {
        $pesanans = \App\Models\Pesanan::with('produk.inventaris.rekamMedis')
            ->where('user_id', auth()->id())
            ->whereIn('status', ['Disetujui', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'])
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('customer.monitoring', compact('pesanans'));
    })->name('monitoring');
});




use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RekamMedisController;
use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\KeuanganController;

// ADMIN ROUTES
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.role'])->group(function () {

    // Main Features
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications/check', [DashboardController::class, 'checkNew'])->name('notifications.check');
    Route::post('/notifications/read-all', [DashboardController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [DashboardController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/{notification}/confirm', [DashboardController::class, 'confirmNotification'])->name('notifications.confirm');
    Route::post('/notifications/{notification}/reject', [DashboardController::class, 'rejectNotification'])->name('notifications.reject');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/send-password-otp', [ProfileController::class, 'sendPasswordOtp'])->name('profile.send-password-otp');

    // Accounts
    Route::resource('accounts', AccountController::class)->except(['create', 'show', 'edit']);

    // Artikel
    Route::resource('artikel', ArtikelController::class)->except(['create', 'show', 'edit']);
    Route::post('/artikel/{id}/hapus-foto', [ArtikelController::class, 'hapusFoto'])->name('artikel.hapus-foto');

    // Keuangan
    Route::resource('keuangan', KeuanganController::class)->except(['create', 'show', 'edit']);
    Route::post('/keuangan/{id}/update-jenis', [KeuanganController::class, 'updateJenis'])->name('keuangan.update-jenis');

    Route::resource('inventaris', InventarisController::class)->except(['create', 'show', 'edit']);
    Route::post('/inventaris/{id}/jual', [InventarisController::class, 'jual'])->name('inventaris.jual');

    Route::resource('katalog', KatalogController::class)->only(['index', 'update', 'destroy']);

    // Rekam Medis & Pertumbuhan Ternak
    Route::get('rekam-medis/export-pdf', [RekamMedisController::class, 'exportPdf'])->name('rekam-medis.export-pdf');
    Route::resource('rekam-medis', RekamMedisController::class)->except(['create', 'show', 'edit']);
    Route::post('rekam-medis/berat', [RekamMedisController::class, 'storeBerat'])->name('rekam-medis.berat');
});

// Fallback lokal untuk serve asset storage agar tidak terkena error 403 / masalah symlink Windows
if (app()->environment('local')) {
    app()->booted(function () {
        Route::get('/storage/{path}', function ($path) {
            $fullPath = storage_path('app/public/' . $path);
            if (!file_exists($fullPath)) {
                abort(404);
            }
            return response()->file($fullPath);
        })->where('path', '.*')->name('storage.local');
    });
}

