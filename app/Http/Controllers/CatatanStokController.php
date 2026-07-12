<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\CatatanStok;
use App\Models\UtangOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CatatanStokController extends Controller
{
    private const JENIS = [
        'Stok',
        'Pembelian',
        'Pemakaian',
        'Penyesuaian',
    ];

    private const SUMBER_DANA = [
        'saldo_usaha' => 'Saldo Usaha',
        'owner' => 'Owner',
        'kasir' => 'Kasir',
    ];

    private const STATUS_UTANG_DEFAULT = 'belum_lunas';

    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $jenis = (string) $request->query('jenis', '');
        $start = $request->date('start');
        $end = $request->date('end');

        $catatan = CatatanStok::query()
            ->with('utangOperasional')
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
            'sumberDanaList' => self::SUMBER_DANA,
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
            'sumberDanaList' => self::SUMBER_DANA,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['created_by'] = Auth::id();

        if ($request->hasFile('bukti')) {
            $data['bukti_path'] = $request->file('bukti')->store('catatan-stok', 'public');
        }

        $row = CatatanStok::create(collect($data)->except('bukti')->all());
        $this->syncUtangOperasional($row);

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
                'nominal' => (int) $row->nominal,
                'sumber_dana' => $row->sumber_dana,
                'tanggal' => (string) $row->tanggal,
            ],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('catatan-stok.index')
            ->with('toast', ['type' => 'success', 'message' => 'Catatan stok berhasil ditambahkan.']);
    }

    public function show(CatatanStok $catatanStok): View
    {
        return view('catatan-stok.show', [
            'catatanStok' => $catatanStok,
            'sumberDanaList' => self::SUMBER_DANA,
        ]);
    }

    public function edit(CatatanStok $catatanStok): View
    {
        return view('catatan-stok.edit', [
            'catatanStok' => $catatanStok,
            'jenisList' => self::JENIS,
            'sumberDanaList' => self::SUMBER_DANA,
        ]);
    }

    public function update(Request $request, CatatanStok $catatanStok)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('catatan-stok', 'public');
            if ($catatanStok->bukti_path) {
                Storage::disk('public')->delete($catatanStok->bukti_path);
            }
            $data['bukti_path'] = $path;
        }

        $catatanStok->update(collect($data)->except('bukti')->all());
        $this->syncUtangOperasional($catatanStok->fresh());

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
                'nominal' => (int) $catatanStok->nominal,
                'sumber_dana' => $catatanStok->sumber_dana,
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
        if ($catatanStok->bukti_path) {
            Storage::disk('public')->delete($catatanStok->bukti_path);
        }
        $this->deleteUtangOperasional($catatanStok);
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

    private function validateData(Request $request): array
    {
        $request->merge([
            'nominal' => preg_replace('/\D+/', '', (string) $request->input('nominal', '0')),
        ]);

        $data = $request->validate([
            'nama_item' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'string', Rule::in(self::JENIS)],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'nominal' => ['nullable', 'integer', 'min:0'],
            'sumber_dana' => ['nullable', 'string', Rule::in(array_keys(self::SUMBER_DANA))],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
            'bukti' => ['nullable', 'image', 'max:4096'],
        ]);

        $isPembelian = $data['jenis'] === 'Pembelian';
        $data['nominal'] = $isPembelian ? (int) ($data['nominal'] ?? 0) : 0;
        $data['sumber_dana'] = $isPembelian ? ($data['sumber_dana'] ?? 'saldo_usaha') : null;

        if ($isPembelian && $data['nominal'] < 1) {
            throw ValidationException::withMessages([
                'nominal' => 'Nominal pembelian wajib diisi.',
            ]);
        }

        return $data;
    }

    private function syncUtangOperasional(CatatanStok $catatanStok): void
    {
        $shouldCreateUtang = $catatanStok->jenis === 'Pembelian'
            && in_array($catatanStok->sumber_dana, ['owner', 'kasir'], true)
            && (int) $catatanStok->nominal > 0;

        if (! $shouldCreateUtang) {
            $this->deleteUtangOperasional($catatanStok);
            return;
        }

        $utang = UtangOperasional::query()->firstOrNew([
            'referensi_type' => CatatanStok::class,
            'referensi_id' => $catatanStok->id,
        ]);

        $utang->fill([
            'pihak' => $catatanStok->sumber_dana,
            'sumber' => 'pembelian_stok',
            'deskripsi' => 'Pembelian stok: '.$catatanStok->nama_item,
            'nominal' => (int) $catatanStok->nominal,
            'tanggal' => $catatanStok->tanggal,
            'catatan' => $catatanStok->catatan,
            'created_by' => $catatanStok->created_by,
        ]);

        if (! $utang->exists) {
            $utang->status = self::STATUS_UTANG_DEFAULT;
        }

        $utang->save();
    }

    private function deleteUtangOperasional(CatatanStok $catatanStok): void
    {
        UtangOperasional::query()
            ->where('referensi_type', CatatanStok::class)
            ->where('referensi_id', $catatanStok->id)
            ->delete();
    }
}
