<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\CatatanStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CatatanStokController extends Controller
{
    private const JENIS = [
        'Stok',
        'Pembelian',
        'Pemakaian',
        'Penyesuaian',
    ];

    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $jenis = (string) $request->query('jenis', '');
        $start = $request->date('start');
        $end = $request->date('end');

        $catatan = CatatanStok::query()
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where('nama_item', 'like', "%{$q}%")
                    ->orWhere('satuan', 'like', "%{$q}%")
                    ->orWhere('catatan', 'like', "%{$q}%");
            })
            ->when($jenis !== '', fn ($qb) => $qb->where('jenis', $jenis))
            ->when($start, fn ($qb) => $qb->whereDate('tanggal', '>=', $start))
            ->when($end, fn ($qb) => $qb->whereDate('tanggal', '<=', $end))
            ->latest('tanggal')
            ->paginate(12)
            ->withQueryString();

        return view('catatan-stok.index', [
            'catatan' => $catatan,
            'jenisList' => self::JENIS,
            'q' => $q,
            'jenis' => $jenis,
            'start' => $start?->toDateString(),
            'end' => $end?->toDateString(),
        ]);
    }

    public function create(): View
    {
        return view('catatan-stok.create', [
            'jenisList' => self::JENIS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_item' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'string', 'in:'.implode(',', self::JENIS)],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
        ]);

        $data['created_by'] = Auth::id();
        $row = CatatanStok::create($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah catatan stok',
            'subject_type' => CatatanStok::class,
            'subject_id' => $row->id,
            'meta' => [
                'nama_item' => $row->nama_item,
                'jenis' => $row->jenis,
                'jumlah' => (float) $row->jumlah,
                'satuan' => $row->satuan,
                'tanggal' => (string) $row->tanggal,
            ],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('catatan-stok.index')
            ->with('toast', ['type' => 'success', 'message' => 'Catatan stok berhasil ditambahkan.']);
    }

    public function show(CatatanStok $catatanStok)
    {
        return redirect()->route('catatan-stok.edit', $catatanStok);
    }

    public function edit(CatatanStok $catatanStok): View
    {
        return view('catatan-stok.edit', [
            'catatanStok' => $catatanStok,
            'jenisList' => self::JENIS,
        ]);
    }

    public function update(Request $request, CatatanStok $catatanStok)
    {
        $data = $request->validate([
            'nama_item' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'string', 'in:'.implode(',', self::JENIS)],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
        ]);

        $catatanStok->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update catatan stok',
            'subject_type' => CatatanStok::class,
            'subject_id' => $catatanStok->id,
            'meta' => [
                'nama_item' => $catatanStok->nama_item,
                'jenis' => $catatanStok->jenis,
                'jumlah' => (float) $catatanStok->jumlah,
                'satuan' => $catatanStok->satuan,
                'tanggal' => (string) $catatanStok->tanggal,
            ],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('catatan-stok.index')
            ->with('toast', ['type' => 'success', 'message' => 'Catatan stok berhasil diperbarui.']);
    }

    public function destroy(CatatanStok $catatanStok)
    {
        $id = $catatanStok->id;
        $catatanStok->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus catatan stok',
            'subject_type' => CatatanStok::class,
            'subject_id' => $id,
            'meta' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('catatan-stok.index')
            ->with('toast', ['type' => 'success', 'message' => 'Catatan stok berhasil dihapus.']);
    }
}

