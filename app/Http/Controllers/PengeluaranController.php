<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengeluaranController extends Controller
{
    private const KATEGORI = [
        'Operasional',
        'Bahan baku',
        'Gaji',
        'Listrik',
        'Transportasi',
        'Internet',
        'Lainnya',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $kategori = (string) $request->query('kategori', '');
        $start = $request->date('start');
        $end = $request->date('end');

        $query = Pengeluaran::query()
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where('nama_pengeluaran', 'like', "%{$q}%")
                    ->orWhere('catatan', 'like', "%{$q}%");
            })
            ->when($kategori !== '', fn ($qb) => $qb->where('kategori', $kategori))
            ->when($start, fn ($qb) => $qb->whereDate('tanggal', '>=', $start))
            ->when($end, fn ($qb) => $qb->whereDate('tanggal', '<=', $end));

        $pengeluaran = (clone $query)->latest('tanggal')->paginate(12)->withQueryString();
        $total = (int) (clone $query)->sum('nominal');

        $year = (int) ($request->integer('year') ?: now()->year);
        $monthly = Pengeluaran::query()
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

        $pie = Pengeluaran::query()
            ->select('kategori')
            ->selectRaw('SUM(nominal) as total')
            ->whereYear('tanggal', $year)
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($r) => ['label' => (string) $r->kategori, 'value' => (int) $r->total])
            ->values();

        return view('pengeluaran.index', [
            'pengeluaran' => $pengeluaran,
            'kategoriList' => self::KATEGORI,
            'q' => $q,
            'kategori' => $kategori,
            'start' => $start?->toDateString(),
            'end' => $end?->toDateString(),
            'total' => $total,
            'chart' => ['year' => $year, 'labels' => $labels, 'series' => $series],
            'pie' => $pie,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pengeluaran.create', [
            'kategoriList' => self::KATEGORI,
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
            'nama_pengeluaran' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'integer', 'min:1'],
            'kategori' => ['required', 'string', 'in:'.implode(',', self::KATEGORI)],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
            'bukti' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['created_by'] = Auth::id();

        if ($request->hasFile('bukti')) {
            $data['bukti_path'] = $request->file('bukti')->store('pengeluaran', 'public');
        }

        $pengeluaran = Pengeluaran::create(collect($data)->except('bukti')->all());

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah pengeluaran',
            'subject_type' => Pengeluaran::class,
            'subject_id' => $pengeluaran->id,
            'meta' => ['nama_pengeluaran' => $pengeluaran->nama_pengeluaran, 'nominal' => (int) $pengeluaran->nominal, 'kategori' => $pengeluaran->kategori],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('pengeluaran.index')
            ->with('toast', ['type' => 'success', 'message' => 'Pengeluaran berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengeluaran $pengeluaran): View
    {
        return view('pengeluaran.show', compact('pengeluaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengeluaran $pengeluaran): View
    {
        return view('pengeluaran.edit', [
            'pengeluaran' => $pengeluaran,
            'kategoriList' => self::KATEGORI,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $request->merge([
            'nominal' => preg_replace('/\D+/', '', (string) $request->input('nominal', '')),
        ]);

        $data = $request->validate([
            'nama_pengeluaran' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'integer', 'min:1'],
            'kategori' => ['required', 'string', 'in:'.implode(',', self::KATEGORI)],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
            'bukti' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('pengeluaran', 'public');
            if ($pengeluaran->bukti_path) {
                Storage::disk('public')->delete($pengeluaran->bukti_path);
            }
            $data['bukti_path'] = $path;
        }

        $pengeluaran->update(collect($data)->except('bukti')->all());

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update pengeluaran',
            'subject_type' => Pengeluaran::class,
            'subject_id' => $pengeluaran->id,
            'meta' => ['nama_pengeluaran' => $pengeluaran->nama_pengeluaran, 'nominal' => (int) $pengeluaran->nominal, 'kategori' => $pengeluaran->kategori],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('pengeluaran.index')
            ->with('toast', ['type' => 'success', 'message' => 'Pengeluaran berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengeluaran $pengeluaran)
    {
        $id = $pengeluaran->id;
        if ($pengeluaran->bukti_path) {
            Storage::disk('public')->delete($pengeluaran->bukti_path);
        }
        $pengeluaran->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus pengeluaran',
            'subject_type' => Pengeluaran::class,
            'subject_id' => $id,
            'meta' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('pengeluaran.index')
            ->with('toast', ['type' => 'success', 'message' => 'Pengeluaran berhasil dihapus.']);
    }
}
