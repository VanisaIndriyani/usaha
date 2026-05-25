<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\ModalUsaha;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\ProfitSharing;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function pemasukanPdf(Request $request)
    {
        [$start, $end] = $this->range($request);

        $rows = Pemasukan::query()
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('exports.pemasukan', [
            'rows' => $rows,
            'start' => $start,
            'end' => $end,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-pemasukan-{$start}-{$end}.pdf");
    }

    public function pemasukanExcel(Request $request)
    {
        [$start, $end] = $this->range($request);

        $rows = Pemasukan::query()
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get();

        return Excel::download($this->simpleExport(
            headings: ['Tanggal', 'Nama', 'Nominal', 'Catatan'],
            rows: $rows->map(fn ($r) => [
                $r->tanggal?->toDateString(),
                $r->nama_pemasukan,
                (int) $r->nominal,
                $r->catatan,
            ])
        ), "laporan-pemasukan-{$start}-{$end}.xlsx");
    }

    public function pengeluaranPdf(Request $request)
    {
        [$start, $end] = $this->range($request);

        $rows = Pengeluaran::query()
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('exports.pengeluaran', [
            'rows' => $rows,
            'start' => $start,
            'end' => $end,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-pengeluaran-{$start}-{$end}.pdf");
    }

    public function pengeluaranExcel(Request $request)
    {
        [$start, $end] = $this->range($request);

        $rows = Pengeluaran::query()
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get();

        return Excel::download($this->simpleExport(
            headings: ['Tanggal', 'Nama', 'Kategori', 'Nominal', 'Catatan'],
            rows: $rows->map(fn ($r) => [
                $r->tanggal?->toDateString(),
                $r->nama_pengeluaran,
                $r->kategori,
                (int) $r->nominal,
                $r->catatan,
            ])
        ), "laporan-pengeluaran-{$start}-{$end}.xlsx");
    }

    public function labaRugiPdf(Request $request)
    {
        [$start, $end] = $this->range($request);

        $income = (int) Pemasukan::query()->whereBetween('tanggal', [$start, $end])->sum('nominal');
        $expenseManual = (int) Pengeluaran::query()->whereBetween('tanggal', [$start, $end])->sum('nominal');
        $expenseBarang = 0;
        $expenseGaji = (int) Gaji::query()
            ->where('status', 'dibayar')
            ->whereBetween('tanggal_bayar', [$start, $end])
            ->sum('nominal');

        $totalExpense = $expenseManual + $expenseGaji;
        $profit = $income - $totalExpense;

        $pdf = Pdf::loadView('exports.laba-rugi', [
            'start' => $start,
            'end' => $end,
            'income' => $income,
            'expenseManual' => $expenseManual,
            'expenseBarang' => $expenseBarang,
            'expenseGaji' => $expenseGaji,
            'totalExpense' => $totalExpense,
            'profit' => $profit,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-laba-rugi-{$start}-{$end}.pdf");
    }

    public function labaRugiExcel(Request $request)
    {
        [$start, $end] = $this->range($request);

        $income = (int) Pemasukan::query()->whereBetween('tanggal', [$start, $end])->sum('nominal');
        $expenseManual = (int) Pengeluaran::query()->whereBetween('tanggal', [$start, $end])->sum('nominal');
        $expenseBarang = 0;
        $expenseGaji = (int) Gaji::query()
            ->where('status', 'dibayar')
            ->whereBetween('tanggal_bayar', [$start, $end])
            ->sum('nominal');

        $totalExpense = $expenseManual + $expenseGaji;
        $profit = $income - $totalExpense;

        return Excel::download($this->simpleExport(
            headings: ['Komponen', 'Nominal'],
            rows: collect([
                ['Pemasukan', $income],
                ['Pengeluaran (manual)', $expenseManual],
                ['Pengeluaran Barang', $expenseBarang],
                ['Gaji Dibayar', $expenseGaji],
                ['Total Pengeluaran', $totalExpense],
                ['Laba/Rugi', $profit],
            ])
        ), "laporan-laba-rugi-{$start}-{$end}.xlsx");
    }

    public function modalPdf(Request $request)
    {
        [$start, $end] = $this->range($request);

        $rows = ModalUsaha::query()
            ->with('owner')
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('exports.modal', [
            'rows' => $rows,
            'start' => $start,
            'end' => $end,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-modal-{$start}-{$end}.pdf");
    }

    public function modalExcel(Request $request)
    {
        [$start, $end] = $this->range($request);

        $rows = ModalUsaha::query()
            ->with('owner')
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get();

        return Excel::download($this->simpleExport(
            headings: ['Tanggal', 'Owner', 'Nominal', 'Catatan'],
            rows: $rows->map(fn ($r) => [
                $r->tanggal?->toDateString(),
                $r->owner?->name,
                (int) $r->nominal,
                $r->catatan,
            ])
        ), "laporan-modal-{$start}-{$end}.xlsx");
    }

    public function gajiPdf(Request $request)
    {
        [$start, $end] = $this->range($request);

        $rows = Gaji::query()
            ->with('karyawan')
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->get();

        $pdf = Pdf::loadView('exports.gaji', [
            'rows' => $rows,
            'start' => $start,
            'end' => $end,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-gaji-{$start}-{$end}.pdf");
    }

    public function gajiExcel(Request $request)
    {
        [$start, $end] = $this->range($request);

        $rows = Gaji::query()
            ->with('karyawan')
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->get();

        return Excel::download($this->simpleExport(
            headings: ['Karyawan', 'Periode', 'Gaji Harian', 'Hari Kerja', 'Gaji Pokok', 'Bonus', 'Total Gaji', 'Status', 'Tanggal Bayar'],
            rows: $rows->map(fn ($r) => [
                $r->karyawan?->nama,
                str_pad((string) $r->bulan, 2, '0', STR_PAD_LEFT) . '/' . $r->tahun,
                (int) $r->gaji_harian,
                (int) $r->hari_kerja,
                (int) $r->gaji_pokok,
                (int) $r->bonus,
                (int) $r->nominal,
                $r->status,
                $r->tanggal_bayar?->toDateString(),
            ])
        ), "laporan-gaji-{$start}-{$end}.xlsx");
    }

    public function profitSharingPdf(Request $request)
    {
        [$start, $end] = $this->range($request);

        $rows = ProfitSharing::query()
            ->whereBetween('periode_selesai', [$start, $end])
            ->orderBy('periode_selesai')
            ->get();

        $pdf = Pdf::loadView('exports.profit-sharing', [
            'rows' => $rows,
            'start' => $start,
            'end' => $end,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-profit-sharing-{$start}-{$end}.pdf");
    }

    public function profitSharingExcel(Request $request)
    {
        [$start, $end] = $this->range($request);

        $rows = ProfitSharing::query()
            ->whereBetween('periode_selesai', [$start, $end])
            ->orderBy('periode_selesai')
            ->get();

        return Excel::download($this->simpleExport(
            headings: ['Periode Mulai', 'Periode Selesai', 'Laba Bersih', 'Owner A (%)', 'Owner A (Nominal)', 'Owner B (%)', 'Owner B (Nominal)', 'Catatan'],
            rows: $rows->map(fn ($r) => [
                $r->periode_mulai?->toDateString(),
                $r->periode_selesai?->toDateString(),
                (int) $r->laba_bersih,
                (float) $r->owner_a_persen,
                (int) $r->owner_a_nominal,
                (float) $r->owner_b_persen,
                (int) $r->owner_b_nominal,
                $r->catatan,
            ])
        ), "laporan-profit-sharing-{$start}-{$end}.xlsx");
    }

    private function range(Request $request): array
    {
        $start = $request->query('start') ?: now()->startOfMonth()->toDateString();
        $end = $request->query('end') ?: now()->endOfMonth()->toDateString();

        return [$start, $end];
    }

    private function simpleExport(array $headings, Collection $rows): object
    {
        return new class($headings, $rows) implements FromCollection, WithHeadings {
            public function __construct(private array $headings, private Collection $rows)
            {
            }

            public function collection(): Collection
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return $this->headings;
            }
        };
    }
}
