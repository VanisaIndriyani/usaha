<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BarangUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BarangUsahaController extends Controller
{
    private const KATEGORI = [
        'Peralatan',
        'Bahan baku',
        'Furniture',
        'Elektronik',
        'Perintilan lainnya',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $kategori = (string) $request->query('kategori', '');

        $barang = BarangUsaha::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nama_barang', 'like', "%{$q}%")
                    ->orWhere('supplier', 'like', "%{$q}%")
                    ->orWhere('catatan', 'like', "%{$q}%");
            })
            ->when($kategori !== '', fn ($query) => $query->where('kategori', $kategori))
            ->latest('tanggal_beli')
            ->paginate(12)
            ->withQueryString();

        $totalPengeluaran = (int) BarangUsaha::query()
            ->selectRaw('COALESCE(SUM(harga * jumlah), 0) as total')
            ->value('total');

        return view('barang-usaha.index', [
            'barang' => $barang,
            'kategoriList' => self::KATEGORI,
            'q' => $q,
            'kategori' => $kategori,
            'totalPengeluaran' => $totalPengeluaran,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('barang-usaha.create', [
            'kategoriList' => self::KATEGORI,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'in:'.implode(',', self::KATEGORI)],
            'harga' => ['required', 'integer', 'min:0'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'tanggal_beli' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('foto')) {
            $data['foto_path'] = $request->file('foto')->store('barang', 'public');
        }

        $data['created_by'] = Auth::id();
        unset($data['foto']);

        $barang = BarangUsaha::create($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah barang usaha',
            'subject_type' => BarangUsaha::class,
            'subject_id' => $barang->id,
            'meta' => ['nama_barang' => $barang->nama_barang, 'kategori' => $barang->kategori],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('barang-usaha.index')
            ->with('toast', ['type' => 'success', 'message' => 'Barang berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangUsaha $barangUsaha)
    {
        return redirect()->route('barang-usaha.edit', $barangUsaha);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangUsaha $barangUsaha): View
    {
        return view('barang-usaha.edit', [
            'barangUsaha' => $barangUsaha,
            'kategoriList' => self::KATEGORI,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangUsaha $barangUsaha)
    {
        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'in:'.implode(',', self::KATEGORI)],
            'harga' => ['required', 'integer', 'min:0'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'tanggal_beli' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('barang', 'public');
            if ($barangUsaha->foto_path) {
                Storage::disk('public')->delete($barangUsaha->foto_path);
            }
            $data['foto_path'] = $path;
        }

        $barangUsaha->update(collect($data)->except('foto')->all());

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update barang usaha',
            'subject_type' => BarangUsaha::class,
            'subject_id' => $barangUsaha->id,
            'meta' => ['nama_barang' => $barangUsaha->nama_barang, 'kategori' => $barangUsaha->kategori],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('barang-usaha.index')
            ->with('toast', ['type' => 'success', 'message' => 'Barang berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangUsaha $barangUsaha)
    {
        $id = $barangUsaha->id;
        if ($barangUsaha->foto_path) {
            Storage::disk('public')->delete($barangUsaha->foto_path);
        }
        $barangUsaha->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus barang usaha',
            'subject_type' => BarangUsaha::class,
            'subject_id' => $id,
            'meta' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('barang-usaha.index')
            ->with('toast', ['type' => 'success', 'message' => 'Barang berhasil dihapus.']);
    }
}
