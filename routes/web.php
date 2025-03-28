<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminKasirController;
use App\Http\Controllers\Admin\AdminstokbarangController;
use App\Http\Controllers\Admin\AdminlaporanpenjualanController;
use App\Http\Controllers\Admin\AdminpengaturanController;
use App\Http\Controllers\Admin\AdminKategoriController;
use App\Http\Controllers\Admin\AdminSatuanController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Auth\AuthenticatedSessionController; 
use App\Http\Controllers\Admin\AdminDashboardController;

// Redirect ke halaman login jika belum login
Route::get('/', function () {
    return redirect()->route('login');
});

// ---------------------------------------------------------------------
// 1) ROUTE LOGIN DENGAN BREEZE (Untuk guest)
// ---------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

// ---------------------------------------------------------------------
// 2) ROUTE ADMIN (Middleware auth & admin)
// ---------------------------------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::prefix('dashboard')->group(function () {

        // Dashboard Admin
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');

        // Profil Admin
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('admin.profile.edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('admin.profile.update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
            Route::patch('/photo', [ProfileController::class, 'updateProfilePicture'])->name('admin.profile.updatePhoto');
            Route::patch('/info', [ProfileController::class, 'updateProfileInfo'])->name('admin.profile.updateInfo');
        });

        // Stok Barang (Resource + routes tambahan)
        Route::resource('stokbarang', AdminstokbarangController::class)->names([
            'index'   => 'admin.stokbarang.index',
            'create'  => 'admin.stokbarang.create',
            'store'   => 'admin.stokbarang.store',
            'edit'    => 'admin.stokbarang.edit',
            'update'  => 'admin.stokbarang.update',
            'destroy' => 'admin.stokbarang.destroy',
        ]);
        Route::get('stokbarang/import', [AdminstokbarangController::class, 'importForm'])
            ->name('admin.stokbarang.import.form');
        Route::post('stokbarang/import', [AdminstokbarangController::class, 'import'])
            ->name('admin.stokbarang.import');
        Route::post('stokbarang/check-low-stock', [AdminstokbarangController::class, 'checkLowStock'])
            ->name('admin.stokbarang.checkLowStock');
        Route::post('stokbarang/reset-all', [AdminstokbarangController::class, 'resetAll'])
            ->name('admin.stokbarang.resetAll');

        // Kasir
        Route::prefix('kasir')->group(function () {
            // Halaman kasir
            Route::get('/', [AdminKasirController::class, 'index'])->name('admin.kasir.index');
            // Simpan transaksi & item
            Route::post('store-transaction', [AdminKasirController::class, 'storeTransaction'])
                ->name('admin.kasir.storeTransaction');
            // Simpan transaksi kasir (opsional jika berbeda dari storeTransaction)
            Route::post('simpan', [AdminKasirController::class, 'saveTransaction'])
                ->name('kasir.simpan');
            // Hapus transaksi atau item
            Route::delete('/{id}', [AdminKasirController::class, 'hapus'])->name('admin.kasir.hapus');
            // Print struk
            Route::get('print-struk', [AdminKasirController::class, 'printStruk'])
                ->name('admin.print.struk');

               // Route untuk mengunduh PDF barcode (menggunakan view yang sama)
    Route::get('/kasir/pdf', [AdminkasirController::class, 'downloadPdf'])->name('admin.kasir.barcodepdf');
        });

        

        // Laporan Penjualan
        Route::get('/laporan-penjualan', [AdminlaporanpenjualanController::class, 'index'])
            ->name('laporan.index');
        Route::get('/laporan-penjualan/filter', [AdminlaporanpenjualanController::class, 'filter'])
            ->name('laporan.filter');
        Route::get('/laporan-penjualan/export', [AdminlaporanpenjualanController::class, 'exportExcel'])
            ->name('laporan.export');
        // Jika perlu, route duplikat untuk backward compatibility
        Route::get('laporanpenjualan', [AdminlaporanpenjualanController::class, 'index'])
            ->name('admin.laporanpenjualan.index');

        // Pengaturan
        Route::prefix('pengaturan')->group(function () {
            Route::get('/', [AdminpengaturanController::class, 'index'])
                ->name('admin.pengaturan.index');
            Route::post('/', [AdminpengaturanController::class, 'update'])
                ->name('admin.pengaturan.update');
        });

        // Kategori
        Route::prefix('kategori')->group(function () {
            Route::get('/', [AdminKategoriController::class, 'index'])->name('admin.kategori.index');
            Route::post('/', [AdminKategoriController::class, 'store'])->name('admin.kategori.store');
            Route::put('/{id}', [AdminKategoriController::class, 'update'])->name('admin.kategori.update');
            Route::delete('/{id}', [AdminKategoriController::class, 'destroy'])->name('admin.kategori.destroy');
            Route::delete('reset', [AdminKategoriController::class, 'resetAll'])->name('admin.kategori.reset');
        });

        // Satuan
        Route::prefix('satuan')->group(function () {
            Route::get('/', [AdminSatuanController::class, 'index'])->name('admin.satuan.index');
            Route::post('/', [AdminSatuanController::class, 'store'])->name('admin.satuan.store');
            Route::put('/{id}', [AdminSatuanController::class, 'update'])->name('admin.satuan.update');
            Route::delete('/{id}', [AdminSatuanController::class, 'destroy'])->name('admin.satuan.destroy');
            Route::delete('reset', [AdminSatuanController::class, 'resetAll'])->name('admin.satuan.reset');
        });

        // Posts Resource
        Route::resource('posts', PostController::class);
    });
});

// ---------------------------------------------------------------------
// 3) ROUTE KASIR (Di luar admin)
// ---------------------------------------------------------------------
Route::get('/kasir', [AdminKasirController::class, 'index'])->name('kasir.index');
Route::get('/kasir/search', [AdminKasirController::class, 'search'])->name('kasir.search');

// ---------------------------------------------------------------------
// 4) ROUTE AUTENTIKASI LAIN (BREEZE/JETSTREAM)
// ---------------------------------------------------------------------
require __DIR__ . '/auth.php';
