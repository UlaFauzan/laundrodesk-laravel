<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\StatusLaundryController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LaporanPendapatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ErrorReportController;

// ===== PUBLIC - Auth Routes =====
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::redirect('/staff/login', '/login');
Route::post('/staff/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public endpoint that customer phone hits after scanning QR to complete payment
Route::get('pembayaran/complete/{pembayaran}/{token}', [PembayaranController::class, 'complete'])->name('pembayaran.complete');

// Redirect root to login
Route::redirect('/', '/login');

// ===== TEST ROUTES (Remove in production) =====
Route::get('/test-error-page', function () {
    return view('error.error-page', [
        'page_name' => 'Halaman Riwayat Transaksi',
        'error_message' => 'Database connection failed',
        'error_code' => 500,
    ]);
});

// ===== ADMIN ONLY - Sistem Management =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin Dashboard
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Role Management
    Route::resource('roles', RoleController::class);
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Layanan Management
    Route::resource('layanan', LayananController::class);

    // Status Laundry Master Data
    Route::resource('status-laundry', StatusLaundryController::class);
    
    // Pelanggan Management
    Route::resource('pelanggan', PelangganController::class)->where(['pelanggan' => '[0-9]+']);
    
    // Log Aktivitas - Read-only for audit trail
    Route::resource('log-aktivitas', LogAktivitasController::class)->only(['index', 'show', 'destroy']);

    // Error Reports
    Route::resource('error-reports', ErrorReportController::class)->only(['index', 'show']);
    
    // Job Queue - Disembunyikan (belum digunakan)
    // Route::resource('jobs', JobController::class)->only(['index', 'show', 'destroy']);
});

// ===== KASIR - Transaksi & Status Management =====
Route::middleware(['auth', 'role:kasir'])->group(function () {
    // Transaksi Management
    Route::resource('transaksi', TransaksiController::class);
    
    // Pembayaran Management
    Route::resource('pembayaran', PembayaranController::class);

    // Cashier payment flow helpers
    Route::get('pembayaran/confirm/{pembayaran}', [PembayaranController::class, 'confirm'])->name('pembayaran.confirm');
    Route::get('pembayaran/status/{pembayaran}', [PembayaranController::class, 'status'])->name('pembayaran.status');
    Route::get('pembayaran/notifications', [PembayaranController::class, 'notifications'])->name('pembayaran.notifications');
    Route::post('pembayaran/notify/{pembayaran}', [PembayaranController::class, 'notify'])->name('pembayaran.notify');
    
    // Detail Transaksi
    Route::resource('detail-transaksi', DetailTransaksiController::class);
    
    // Notifikasi
    Route::resource('notifikasi', NotifikasiController::class);

    // Kasir dashboard listing pelanggan
    Route::get('/kasir/dashboard', [App\Http\Controllers\KasirDashboardController::class, 'index'])->name('kasir.dashboard');
});

// ===== MANAGER - Laporan Pendapatan =====
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::resource('laporan-pendapatan', LaporanPendapatanController::class)->only(['index']);
});

// ===== PELANGGAN ONLY - View Profil =====
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/pelanggan/profile', [PelangganController::class, 'profile'])->name('pelanggan.profile');
    Route::get('/pelanggan/profile/edit', [PelangganController::class, 'editProfile'])->name('pelanggan.profile.edit');
    Route::put('/pelanggan/profile', [PelangganController::class, 'updateProfile'])->name('pelanggan.profile.update');
    Route::get('/pelanggan/status', [PelangganController::class, 'status'])->name('pelanggan.status');

    // Riwayat transaksi pelanggan
    Route::get('/pelanggan/transactions', [PelangganController::class, 'transactions'])->name('pelanggan.transactions');
    Route::get('/pelanggan/transactions/{transaksi}', [PelangganController::class, 'showTransaction'])->name('pelanggan.transactions.show');

    // Error Reporting
    Route::post('/error-reports', [ErrorReportController::class, 'store'])->name('error-reports.store');
});
