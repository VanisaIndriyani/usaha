<?php

namespace App\Http\Controllers;

use App\Models\BarangUsaha;
use App\Models\Gaji;
use App\Models\ModalUsaha;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\ProfitSharing;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $start = $request->query('start') ?: now()->startOfMonth()->toDateString();
        $end = $request->query('end') ?: now()->endOfMonth()->toDateString();

        $income = (int) Pemasukan::query()->whereBetween('tanggal', [$start, $end])->sum('nominal');
        $expenseManual = (int) Pengeluaran::query()->whereBetween('tanggal', [$start, $end])->sum('nominal');
        $expenseBarang = (int) BarangUsaha::query()
            ->whereBetween('tanggal_beli', [$start, $end])
            ->selectRaw('COALESCE(SUM(harga * jumlah), 0) as total')
            ->value('total');
        $expenseGaji = (int) Gaji::query()
            ->where('status', 'dibayar')
            ->whereBetween('tanggal_bayar', [$start, $end])
            ->sum('nominal');

        $totalExpense = $expenseManual + $expenseBarang + $expenseGaji;
        $profit = $income - $totalExpense;

        $modal = (int) ModalUsaha::query()->whereBetween('tanggal', [$start, $end])->sum('nominal');
        $profitSharing = (int) ProfitSharing::query()->whereBetween('periode_selesai', [$start, $end])->sum('laba_bersih');

        return view('laporan.index', [
            'start' => $start,
            'end' => $end,
            'summary' => [
                'income' => $income,
                'expenseManual' => $expenseManual,
                'expenseBarang' => $expenseBarang,
                'expenseGaji' => $expenseGaji,
                'totalExpense' => $totalExpense,
                'profit' => $profit,
                'modal' => $modal,
                'profitSharing' => $profitSharing,
            ],
        ]);
    }
}
