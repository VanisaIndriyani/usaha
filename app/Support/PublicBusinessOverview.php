<?php

namespace App\Support;

use App\Models\BarangUsaha;
use App\Models\CatatanStok;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\ModalUsaha;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Periode;
use App\Models\UtangOperasional;
use Carbon\Carbon;
use Throwable;
use Illuminate\Support\Facades\Schema;

class PublicBusinessOverview
{
    public function build(?int $year = null, ?int $periodeId = null): array
    {
        $year = $year ?: now()->year;

        try {
            if (! $this->requiredTablesExist()) {
                return $this->emptyState($year);
            }

            $selectedPeriode = $periodeId ? Periode::find($periodeId) : Periode::getActivePeriod();
            
            $queryBuilder = function ($model) use ($selectedPeriode) {
                $qb = $model->newQuery();
                if ($selectedPeriode) {
                    $qb->where('periode_id', $selectedPeriode->id);
                }
                return $qb;
            };

            $totalModal = (int) ModalUsaha::query()->sum('nominal');
            $totalPemasukan = (int) $queryBuilder(Pemasukan::query())->sum('nominal');
            $totalPengeluaranManual = (int) $queryBuilder(Pengeluaran::query())->sum('nominal');
            $totalGajiDibayar = (int) Gaji::query()
                ->where('status', 'dibayar')
                ->sum('nominal');
            $totalPembelianStokSaldo = 0;
            if (Schema::hasTable('catatan_stok')) {
                $totalPembelianStokSaldo = (int) $queryBuilder(CatatanStok::query())
                    ->where('jenis', 'Pembelian')
                    ->where('sumber_dana', 'saldo_usaha')
                    ->sum('nominal');
            }

            $totalPengeluaran = $totalPengeluaranManual + $totalGajiDibayar + $totalPembelianStokSaldo;
            $totalKeuntungan = $totalPemasukan - $totalPengeluaran;
            $saldoAkhir = $totalModal + $totalPemasukan - $totalPengeluaran;
            $utangOwner = $this->sumUtangByPihak('owner');
            $utangKasir = $this->sumUtangByPihak('kasir');
            $totalUtang = $utangOwner + $utangKasir;

            $monthlyIncome = $this->monthlySum($queryBuilder(Pemasukan::query()), 'tanggal', 'nominal', $year);
            $monthlyExpenseManual = $this->monthlySum($queryBuilder(Pengeluaran::query()), 'tanggal', 'nominal', $year);
            $monthlyExpenseGaji = $this->monthlySum(Gaji::query()->where('status', 'dibayar'), 'tanggal_bayar', 'nominal', $year);
            $monthlyExpenseStok = array_fill(1, 12, 0);
            if (Schema::hasTable('catatan_stok')) {
                $monthlyExpenseStok = $this->monthlySum(
                    $queryBuilder(CatatanStok::query())->where('jenis', 'Pembelian')->where('sumber_dana', 'saldo_usaha'),
                    'tanggal',
                    'nominal',
                    $year
                );
            }

            $monthlyExpenseTotal = [];
            $monthlyProfit = [];
            for ($month = 1; $month <= 12; $month++) {
                $expense = ($monthlyExpenseManual[$month] ?? 0) + ($monthlyExpenseGaji[$month] ?? 0) + ($monthlyExpenseStok[$month] ?? 0);
                $monthlyExpenseTotal[$month] = $expense;
                $monthlyProfit[$month] = ($monthlyIncome[$month] ?? 0) - $expense;
            }

            $pieExpense = $queryBuilder(Pengeluaran::query())
                ->select('kategori')
                ->selectRaw('SUM(nominal) as total')
                ->whereYear('tanggal', $year)
                ->groupBy('kategori')
                ->orderByDesc('total')
                ->limit(6)
                ->get()
                ->map(fn ($row) => [
                    'label' => (string) ($row->kategori ?: 'Lainnya'),
                    'value' => (int) $row->total,
                ])
                ->values()
                ->all();

            $latestIncomeEntries = $queryBuilder(Pemasukan::query())
                ->selectRaw("'Pemasukan' as tipe, nama_pemasukan as nama, nominal, tanggal")
                ->latest('tanggal')
                ->limit(4)
                ->get()
                ->toBase()
                ->map(fn ($row) => [
                    'tipe' => 'Pemasukan',
                    'nama' => (string) $row->nama,
                    'nominal' => (int) $row->nominal,
                    'sort_at' => optional($row->tanggal)->format('Y-m-d') ?? '',
                    'tanggal' => optional($row->tanggal)?->translatedFormat('d M Y') ?? '-',
                ]);

            $latestExpenseEntries = $queryBuilder(Pengeluaran::query())
                ->selectRaw("'Pengeluaran' as tipe, nama_pengeluaran as nama, nominal, tanggal")
                ->latest('tanggal')
                ->limit(4)
                ->get()
                ->toBase()
                ->map(fn ($row) => [
                    'tipe' => 'Pengeluaran',
                    'nama' => (string) $row->nama,
                    'nominal' => (int) $row->nominal,
                    'sort_at' => optional($row->tanggal)->format('Y-m-d') ?? '',
                    'tanggal' => optional($row->tanggal)?->translatedFormat('d M Y') ?? '-',
                ]);

            $latestEntries = $latestIncomeEntries
                ->concat($latestExpenseEntries)
                ->sortByDesc('sort_at')
                ->take(6)
                ->map(fn ($row) => [
                    'tipe' => $row['tipe'],
                    'nama' => $row['nama'],
                    'nominal' => $row['nominal'],
                    'tanggal' => $row['tanggal'],
                ])
                ->values()
                ->all();
        } catch (Throwable) {
            return $this->emptyState($year);
        }

        return [
            'year' => $year,
            'periodes' => Periode::latest()->get(),
            'selectedPeriode' => $selectedPeriode,
            'summary' => [
                'totalModal' => $totalModal,
                'totalPemasukan' => $totalPemasukan,
                'totalPengeluaran' => $totalPengeluaran,
                'totalKeuntungan' => $totalKeuntungan,
                'saldoAkhir' => $saldoAkhir,
                'saldoBri' => $saldoAkhir,
                'jumlahKaryawan' => (int) Karyawan::query()->count(),
                'jumlahBarang' => (int) BarangUsaha::query()->count(),
                'utangOwner' => $utangOwner,
                'utangKasir' => $utangKasir,
                'totalUtang' => $totalUtang,
            ],
            'charts' => [
                'months' => collect(range(1, 12))->map(fn ($month) => Carbon::createFromDate($year, $month, 1)->translatedFormat('M'))->all(),
                'income' => array_values($monthlyIncome),
                'expense' => array_values($monthlyExpenseTotal),
                'profit' => array_values($monthlyProfit),
                'pieExpense' => $pieExpense,
            ],
            'latestEntries' => $latestEntries,
        ];
    }

    private function requiredTablesExist(): bool
    {
        foreach (['modal_usaha', 'pemasukan', 'pengeluaran', 'gaji', 'karyawan', 'barang_usaha'] as $table) {
            if (! Schema::hasTable($table)) {
                return false;
            }
        }

        return true;
    }

    private function emptyState(int $year): array
    {
        return [
            'year' => $year,
            'periodes' => collect(),
            'selectedPeriode' => null,
            'summary' => [
                'totalModal' => 0,
                'totalPemasukan' => 0,
                'totalPengeluaran' => 0,
                'totalKeuntungan' => 0,
                'saldoAkhir' => 0,
                'saldoBri' => 0,
                'jumlahKaryawan' => 0,
                'jumlahBarang' => 0,
                'utangOwner' => 0,
                'utangKasir' => 0,
                'totalUtang' => 0,
            ],
            'charts' => [
                'months' => collect(range(1, 12))->map(fn ($month) => Carbon::createFromDate($year, $month, 1)->translatedFormat('M'))->all(),
                'income' => array_fill(0, 12, 0),
                'expense' => array_fill(0, 12, 0),
                'profit' => array_fill(0, 12, 0),
                'pieExpense' => [],
            ],
            'latestEntries' => [],
        ];
    }

    private function monthlySum($query, string $dateColumn, string $sumColumn, int $year): array
    {
        $rows = $query
            ->selectRaw('MONTH(' . $dateColumn . ') as month, COALESCE(SUM(' . $sumColumn . '), 0) as total')
            ->whereYear($dateColumn, $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->all();

        $output = [];
        for ($month = 1; $month <= 12; $month++) {
            $output[$month] = (int) ($rows[$month] ?? 0);
        }

        return $output;
    }

    private function sumUtangByPihak(string $pihak): int
    {
        if (! Schema::hasTable('utang_operasional')) {
            return 0;
        }

        return (int) UtangOperasional::query()
            ->where('pihak', $pihak)
            ->where('status', 'belum_lunas')
            ->sum('nominal');
    }
}
