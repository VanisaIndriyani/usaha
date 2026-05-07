<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Gaji;
use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $month = (int) ($request->integer('bulan') ?: now()->month);
        $year = (int) ($request->integer('tahun') ?: now()->year);
        $status = (string) $request->query('status', '');
        $q = (string) $request->query('q', '');

        $query = Gaji::query()
            ->with('karyawan')
            ->where('bulan', $month)
            ->where('tahun', $year)
            ->when($status !== '', fn ($qb) => $qb->where('status', $status))
            ->when($q !== '', fn ($qb) => $qb->whereHas('karyawan', fn ($k) => $k->where('nama', 'like', "%{$q}%")));

        $gaji = (clone $query)->latest()->paginate(12)->withQueryString();

        $totalDibayar = (int) (clone $query)->where('status', 'dibayar')->sum('nominal');
        $totalBelum = (int) (clone $query)->where('status', 'belum_dibayar')->sum('nominal');

        return view('gaji.index', [
            'gaji' => $gaji,
            'bulan' => $month,
            'tahun' => $year,
            'status' => $status,
            'q' => $q,
            'stats' => [
                'dibayar' => $totalDibayar,
                'belum' => $totalBelum,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        return view('gaji.create', [
            'karyawanList' => Karyawan::query()->orderBy('nama')->get(),
            'bulan' => (int) ($request->integer('bulan') ?: now()->month),
            'tahun' => (int) ($request->integer('tahun') ?: now()->year),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'karyawan_id' => ['required', 'exists:karyawan,id'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'gaji_harian' => ['required', 'integer', 'min:0'],
            'hari_kerja' => ['required', 'integer', 'min:0', 'max:31'],
            'bonus' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:belum_dibayar,dibayar'],
            'tanggal_bayar' => ['nullable', 'date', 'required_if:status,dibayar'],
        ]);

        $data['gaji_pokok'] = $data['gaji_harian'] * $data['hari_kerja'];
        $data['nominal'] = $data['gaji_pokok'] + $data['bonus'];
        $data['created_by'] = Auth::id();

        $exists = Gaji::query()
            ->where('karyawan_id', $data['karyawan_id'])
            ->where('bulan', $data['bulan'])
            ->where('tahun', $data['tahun'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['bulan' => 'Gaji untuk periode ini sudah ada.'])
                ->withInput();
        }

        $gaji = Gaji::create($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah gaji',
            'subject_type' => Gaji::class,
            'subject_id' => $gaji->id,
            'meta' => ['karyawan_id' => (int) $gaji->karyawan_id, 'bulan' => (int) $gaji->bulan, 'tahun' => (int) $gaji->tahun],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('gaji.index', ['bulan' => $gaji->bulan, 'tahun' => $gaji->tahun])
            ->with('toast', ['type' => 'success', 'message' => 'Gaji berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gaji $gaji)
    {
        return redirect()->route('gaji.edit', $gaji);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gaji $gaji): View
    {
        return view('gaji.edit', [
            'gaji' => $gaji->load('karyawan'),
            'karyawanList' => Karyawan::query()->orderBy('nama')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gaji $gaji)
    {
        $data = $request->validate([
            'karyawan_id' => ['required', 'exists:karyawan,id'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'gaji_harian' => ['required', 'integer', 'min:0'],
            'hari_kerja' => ['required', 'integer', 'min:0', 'max:31'],
            'bonus' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:belum_dibayar,dibayar'],
            'tanggal_bayar' => ['nullable', 'date', 'required_if:status,dibayar'],
        ]);

        $data['gaji_pokok'] = $data['gaji_harian'] * $data['hari_kerja'];
        $data['nominal'] = $data['gaji_pokok'] + $data['bonus'];

        $dupe = Gaji::query()
            ->where('id', '!=', $gaji->id)
            ->where('karyawan_id', $data['karyawan_id'])
            ->where('bulan', $data['bulan'])
            ->where('tahun', $data['tahun'])
            ->exists();

        if ($dupe) {
            return back()
                ->withErrors(['bulan' => 'Gaji untuk periode ini sudah ada.'])
                ->withInput();
        }

        $gaji->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update gaji',
            'subject_type' => Gaji::class,
            'subject_id' => $gaji->id,
            'meta' => ['karyawan_id' => (int) $gaji->karyawan_id, 'bulan' => (int) $gaji->bulan, 'tahun' => (int) $gaji->tahun, 'status' => $gaji->status],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('gaji.index', ['bulan' => $gaji->bulan, 'tahun' => $gaji->tahun])
            ->with('toast', ['type' => 'success', 'message' => 'Gaji berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gaji $gaji)
    {
        $id = $gaji->id;
        $gaji->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus gaji',
            'subject_type' => Gaji::class,
            'subject_id' => $id,
            'meta' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('gaji.index')
            ->with('toast', ['type' => 'success', 'message' => 'Gaji berhasil dihapus.']);
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $karyawan = Karyawan::query()->where('status_kerja', 'aktif')->get();
        $created = 0;

        foreach ($karyawan as $k) {
            $exists = Gaji::query()
                ->where('karyawan_id', $k->id)
                ->where('bulan', $data['bulan'])
                ->where('tahun', $data['tahun'])
                ->exists();

            if ($exists) {
                continue;
            }

            Gaji::create([
                'karyawan_id' => $k->id,
                'bulan' => $data['bulan'],
                'tahun' => $data['tahun'],
                'gaji_harian' => (int) $k->gaji_harian,
                'hari_kerja' => 0,
                'gaji_pokok' => 0,
                'bonus' => 0,
                'nominal' => 0,
                'status' => 'belum_dibayar',
                'tanggal_bayar' => null,
                'created_by' => Auth::id(),
            ]);
            $created++;
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Generate gaji bulanan',
            'subject_type' => Gaji::class,
            'subject_id' => null,
            'meta' => ['bulan' => (int) $data['bulan'], 'tahun' => (int) $data['tahun'], 'created' => $created],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('gaji.index', ['bulan' => $data['bulan'], 'tahun' => $data['tahun']])
            ->with('toast', ['type' => 'success', 'message' => "Generate gaji selesai. Dibuat: {$created}"]);
    }

    public function pay(Request $request, Gaji $gaji)
    {
        $data = $request->validate([
            'tanggal_bayar' => ['required', 'date'],
        ]);

        $gaji->update([
            'status' => 'dibayar',
            'tanggal_bayar' => $data['tanggal_bayar'],
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Bayar gaji',
            'subject_type' => Gaji::class,
            'subject_id' => $gaji->id,
            'meta' => ['status' => 'dibayar', 'tanggal_bayar' => (string) $gaji->tanggal_bayar],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('gaji.index', ['bulan' => $gaji->bulan, 'tahun' => $gaji->tahun])
            ->with('toast', ['type' => 'success', 'message' => 'Gaji ditandai sudah dibayar.']);
    }

    public function slip(Gaji $gaji)
    {
        $gaji->load('karyawan');

        $pdf = Pdf::loadView('gaji.slip', [
            'gaji' => $gaji,
        ])->setPaper('a4');

        $filename = 'slip-gaji-' . $gaji->karyawan->nama . '-' . $gaji->bulan . '-' . $gaji->tahun . '.pdf';

        return $pdf->download($filename);
    }
}
