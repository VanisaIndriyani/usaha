<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Pemasukan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PemasukanController extends Controller
{
    private const METODE = [
        'Cash',
        'Transfer',
        'QRIS',
        'E-Wallet',
        'Lainnya',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $start = $request->date('start');
        $end = $request->date('end');

        $query = Pemasukan::query()
            ->when($q !== '', function ($qBuilder) use ($q) {
                $qBuilder->where('nama_pemasukan', 'like', "%{$q}%")
                    ->orWhere('metode_pembayaran', 'like', "%{$q}%")
                    ->orWhere('catatan', 'like', "%{$q}%");
            })
            ->when($start, fn ($qb) => $qb->whereDate('tanggal', '>=', $start))
            ->when($end, fn ($qb) => $qb->whereDate('tanggal', '<=', $end));

        $pemasukan = (clone $query)->latest('tanggal')->paginate(12)->withQueryString();

        $today = now()->toDateString();
        $totalHarian = (int) Pemasukan::query()->whereDate('tanggal', $today)->sum('nominal');
        $totalMingguan = (int) Pemasukan::query()->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])->sum('nominal');
        $totalBulanan = (int) Pemasukan::query()->whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->sum('nominal');
        $totalTahunan = (int) Pemasukan::query()->whereYear('tanggal', now()->year)->sum('nominal');

        $year = (int) ($request->integer('year') ?: now()->year);
        $monthly = Pemasukan::query()
            ->selectRaw('MONTH(tanggal) as month, COALESCE(SUM(nominal), 0) as total')
            ->whereYear('tanggal', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->all();

        $series = [];
        $labels = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::createFromDate($year, $m, 1)->format('M');
            $series[] = (int) ($monthly[$m] ?? 0);
        }

        return view('pemasukan.index', [
            'pemasukan' => $pemasukan,
            'q' => $q,
            'start' => $start?->toDateString(),
            'end' => $end?->toDateString(),
            'stats' => [
                'harian' => $totalHarian,
                'mingguan' => $totalMingguan,
                'bulanan' => $totalBulanan,
                'tahunan' => $totalTahunan,
            ],
            'chart' => [
                'year' => $year,
                'labels' => $labels,
                'series' => $series,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pemasukan.create', [
            'metodeList' => self::METODE,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'nominal' => preg_replace('/\D+/', '', (string) $request->input('nominal', '')),
        ]);

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'nama_pemasukan' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'integer', 'min:1'],
            'metode_pembayaran' => ['required', 'string', 'in:'.implode(',', self::METODE)],
            'catatan' => ['nullable', 'string'],
        ]);

        $data['created_by'] = Auth::id();
        $pemasukan = Pemasukan::create($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah pemasukan',
            'subject_type' => Pemasukan::class,
            'subject_id' => $pemasukan->id,
            'meta' => ['nama_pemasukan' => $pemasukan->nama_pemasukan, 'nominal' => (int) $pemasukan->nominal],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('pemasukan.index')
            ->with('toast', ['type' => 'success', 'message' => 'Pemasukan berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pemasukan $pemasukan)
    {
        return redirect()->route('pemasukan.edit', $pemasukan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pemasukan $pemasukan): View
    {
        return view('pemasukan.edit', [
            'pemasukan' => $pemasukan,
            'metodeList' => self::METODE,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pemasukan $pemasukan)
    {
        $request->merge([
            'nominal' => preg_replace('/\D+/', '', (string) $request->input('nominal', '')),
        ]);

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'nama_pemasukan' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'integer', 'min:1'],
            'metode_pembayaran' => ['required', 'string', 'in:'.implode(',', self::METODE)],
            'catatan' => ['nullable', 'string'],
        ]);

        $pemasukan->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update pemasukan',
            'subject_type' => Pemasukan::class,
            'subject_id' => $pemasukan->id,
            'meta' => ['nama_pemasukan' => $pemasukan->nama_pemasukan, 'nominal' => (int) $pemasukan->nominal],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('pemasukan.index')
            ->with('toast', ['type' => 'success', 'message' => 'Pemasukan berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pemasukan $pemasukan)
    {
        $id = $pemasukan->id;
        $pemasukan->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus pemasukan',
            'subject_type' => Pemasukan::class,
            'subject_id' => $id,
            'meta' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('pemasukan.index')
            ->with('toast', ['type' => 'success', 'message' => 'Pemasukan berhasil dihapus.']);
    }
}
