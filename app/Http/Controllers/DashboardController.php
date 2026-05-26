<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\ModalUsaha;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $year = (int) ($request->integer('year') ?: now()->year);

        $totalModal = (int) ModalUsaha::query()->where('akun', 'BRI')->sum('nominal');
        $totalPemasukan = (int) Pemasukan::query()->where('akun', 'BRI')->sum('nominal');
        $totalPengeluaranManual = (int) Pengeluaran::query()->where('akun', 'BRI')->sum('nominal');
        $totalGajiDibayar = (int) Gaji::query()
            ->where('status', 'dibayar')
            ->sum('nominal');

        $totalPengeluaran = $totalPengeluaranManual + $totalGajiDibayar;
        $totalKeuntungan = $totalPemasukan - $totalPengeluaran;
        $saldoAkhir = $totalModal + $totalPemasukan - $totalPengeluaran;

        $jumlahKaryawan = (int) Karyawan::query()->count();

        $monthlyIncome = $this->monthlySum(Pemasukan::query()->where('akun', 'BRI'), 'tanggal', 'nominal', $year);
        $monthlyExpenseManual = $this->monthlySum(Pengeluaran::query()->where('akun', 'BRI'), 'tanggal', 'nominal', $year);
        $monthlyExpenseGaji = $this->monthlySum(Gaji::query()->where('status', 'dibayar'), 'tanggal_bayar', 'nominal', $year);

        $monthlyExpenseTotal = [];
        $monthlyProfit = [];
        for ($m = 1; $m <= 12; $m++) {
            $expense = ($monthlyExpenseManual[$m] ?? 0) + ($monthlyExpenseGaji[$m] ?? 0);
            $monthlyExpenseTotal[$m] = $expense;
            $monthlyProfit[$m] = ($monthlyIncome[$m] ?? 0) - $expense;
        }

        $pieExpense = Pengeluaran::query()
            ->select('kategori')
            ->selectRaw('SUM(nominal) as total')
            ->where('akun', 'BRI')
            ->whereYear('tanggal', $year)
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn ($row) => ['label' => (string) $row->kategori, 'value' => (int) $row->total])
            ->values();

        $recentActivities = ActivityLog::query()
            ->with('user')
            ->latest()
            ->limit(12)
            ->get();

        return view('dashboard', [
            'year' => $year,
            'kpi' => [
                'totalModal' => $totalModal,
                'totalPemasukan' => $totalPemasukan,
                'totalPengeluaran' => $totalPengeluaran,
                'totalKeuntungan' => $totalKeuntungan,
                'saldoAkhir' => $saldoAkhir,
                'jumlahKaryawan' => $jumlahKaryawan,
            ],
            'charts' => [
                'months' => collect(range(1, 12))->map(fn ($m) => Carbon::createFromDate($year, $m, 1)->format('M'))->all(),
                'income' => array_values($monthlyIncome),
                'expense' => array_values($monthlyExpenseTotal),
                'profit' => array_values($monthlyProfit),
                'pieExpense' => $pieExpense,
            ],
            'recentActivities' => $recentActivities,
            'karyawanView' => null,
        ]);
    }

    private function monthlySum($query, string $dateColumn, string $sumColumn, int $year): array
    {
        $rows = $query
            ->selectRaw('MONTH(' . $dateColumn . ') as month, COALESCE(SUM(' . $sumColumn . '), 0) as total')
            ->whereYear($dateColumn, $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->all();

        $out = [];
        for ($m = 1; $m <= 12; $m++) {
            $out[$m] = (int) ($rows[$m] ?? 0);
        }

        return $out;
    }

    private function monthlySumRaw($query, string $dateColumn, string $expression, int $year): array
    {
        $rows = $query
            ->selectRaw('MONTH(' . $dateColumn . ') as month')
            ->selectRaw('COALESCE(SUM(' . $expression . '), 0) as total')
            ->whereYear($dateColumn, $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->all();

        $out = [];
        for ($m = 1; $m <= 12; $m++) {
            $out[$m] = (int) ($rows[$m] ?? 0);
        }

        return $out;
    }
}
