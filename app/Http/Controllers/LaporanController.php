<?php

namespace App\Http\Controllers;

use App\Models\CatatanStok;
use App\Models\Gaji;
use App\Models\ModalUsaha;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Periode;
use App\Models\ProfitSharing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $activePeriode = Periode::getActivePeriod();
        
        $start = $request->query('start') ?: now()->startOfMonth()->toDateString();
        $end = $request->query('end') ?: now()->endOfMonth()->toDateString();

        $queryBuilder = function ($model) use ($activePeriode) {
            $qb = $model->newQuery();
            if ($activePeriode) {
                $qb->where('periode_id', $activePeriode->id);
            }
            return $qb;
        };

        $income = (int) $queryBuilder(Pemasukan::query())->whereBetween('tanggal', [$start, $end])->sum('nominal');
        $expenseManual = (int) $queryBuilder(Pengeluaran::query())->whereBetween('tanggal', [$start, $end])->sum('nominal');
        $expenseBarang = 0;
        if (Schema::hasTable('catatan_stok')) {
            $expenseBarang = (int) $queryBuilder(CatatanStok::query())
                ->where('jenis', 'Pembelian')
                ->where('sumber_dana', 'saldo_usaha')
                ->whereBetween('tanggal', [$start, $end])
                ->sum('nominal');
        }
        $expenseGaji = (int) Gaji::query()
            ->where('status', 'dibayar')
            ->whereBetween('tanggal_bayar', [$start, $end])
            ->sum('nominal');

        $totalExpense = $expenseManual + $expenseGaji + $expenseBarang;
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
