<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BarangUsahaController;
use App\Http\Controllers\CatatanStokController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ModalUsahaController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProfitSharingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\UtangOperasionalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicHomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('periode', PeriodeController::class)->names('periode');
    Route::patch('periode/{periode}/activate', [PeriodeController::class, 'activate'])->name('periode.activate');

    Route::resource('modal-usaha', ModalUsahaController::class)->names('modal-usaha');
    Route::resource('barang-usaha', BarangUsahaController::class)->names('barang-usaha');
    Route::resource('catatan-stok', CatatanStokController::class)->names('catatan-stok');
    Route::get('utang-owner', [UtangOperasionalController::class, 'ownerIndex'])->name('utang-owner.index');
    Route::get('utang-kasir', [UtangOperasionalController::class, 'kasirIndex'])->name('utang-kasir.index');
    Route::patch('utang-operasional/{utangOperasional}/toggle-status', [UtangOperasionalController::class, 'toggleStatus'])->name('utang-operasional.toggle-status');
    Route::resource('pemasukan', PemasukanController::class)->names('pemasukan');
    Route::resource('pengeluaran', PengeluaranController::class)->names('pengeluaran');
    Route::resource('karyawan', KaryawanController::class)->names('karyawan');
    Route::resource('gaji', GajiController::class)->names('gaji');
    Route::post('gaji/generate', [GajiController::class, 'generate'])->name('gaji.generate');
    Route::post('gaji/{gaji}/pay', [GajiController::class, 'pay'])->name('gaji.pay');
    Route::get('gaji/{gaji}/slip', [GajiController::class, 'slip'])->name('gaji.slip');
    Route::resource('profit-sharing', ProfitSharingController::class)->names('profit-sharing');
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('export/pemasukan/pdf', [ExportController::class, 'pemasukanPdf'])->name('export.pemasukan.pdf');
    Route::get('export/pemasukan/excel', [ExportController::class, 'pemasukanExcel'])->name('export.pemasukan.excel');
    Route::get('export/pengeluaran/pdf', [ExportController::class, 'pengeluaranPdf'])->name('export.pengeluaran.pdf');
    Route::get('export/pengeluaran/excel', [ExportController::class, 'pengeluaranExcel'])->name('export.pengeluaran.excel');
    Route::get('export/laba-rugi/pdf', [ExportController::class, 'labaRugiPdf'])->name('export.laba-rugi.pdf');
    Route::get('export/laba-rugi/excel', [ExportController::class, 'labaRugiExcel'])->name('export.laba-rugi.excel');
    Route::get('export/modal/pdf', [ExportController::class, 'modalPdf'])->name('export.modal.pdf');
    Route::get('export/modal/excel', [ExportController::class, 'modalExcel'])->name('export.modal.excel');
    Route::get('export/gaji/pdf', [ExportController::class, 'gajiPdf'])->name('export.gaji.pdf');
    Route::get('export/gaji/excel', [ExportController::class, 'gajiExcel'])->name('export.gaji.excel');
    Route::get('export/profit-sharing/pdf', [ExportController::class, 'profitSharingPdf'])->name('export.profit-sharing.pdf');
    Route::get('export/profit-sharing/excel', [ExportController::class, 'profitSharingExcel'])->name('export.profit-sharing.excel');
    Route::resource('activity-logs', ActivityLogController::class)->only(['index'])->names('activity-logs');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
