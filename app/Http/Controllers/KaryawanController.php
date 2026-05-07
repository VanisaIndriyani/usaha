<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $jabatan = (string) $request->query('jabatan', '');

        $jabatanList = Karyawan::query()
            ->select('jabatan')
            ->distinct()
            ->orderBy('jabatan')
            ->pluck('jabatan')
            ->filter()
            ->values()
            ->all();

        $karyawan = Karyawan::query()
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where('nama', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('no_hp', 'like', "%{$q}%");
            })
            ->when($jabatan !== '', fn ($qb) => $qb->where('jabatan', $jabatan))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('karyawan.index', [
            'karyawan' => $karyawan,
            'q' => $q,
            'jabatan' => $jabatan,
            'jabatanList' => $jabatanList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:karyawan,email'],
            'no_hp' => ['nullable', 'string', 'max:40'],
            'alamat' => ['nullable', 'string'],
            'jabatan' => ['required', 'string', 'max:255'],
            'gaji_harian' => ['required', 'integer', 'min:0'],
            'tanggal_masuk' => ['required', 'date'],
            'status_kerja' => ['required', 'string', 'in:aktif,nonaktif'],
            'foto' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('foto')) {
            $data['foto_path'] = $request->file('foto')->store('karyawan', 'public');
        }

        $karyawan = Karyawan::create([
            'foto_path' => $data['foto_path'] ?? null,
            'nama' => $data['nama'],
            'email' => $data['email'] ?? null,
            'no_hp' => $data['no_hp'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'jabatan' => $data['jabatan'],
            'gaji_harian' => (int) $data['gaji_harian'],
            'tanggal_masuk' => $data['tanggal_masuk'],
            'status_kerja' => $data['status_kerja'],
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah karyawan',
            'subject_type' => Karyawan::class,
            'subject_id' => $karyawan->id,
            'meta' => ['nama' => $karyawan->nama, 'jabatan' => $karyawan->jabatan],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('karyawan.index')
            ->with('toast', ['type' => 'success', 'message' => 'Karyawan berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        return view('karyawan.show', [
            'karyawan' => $karyawan->load('gaji'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan): View
    {
        return view('karyawan.edit', [
            'karyawan' => $karyawan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('karyawan', 'email')->ignore($karyawan->id)],
            'no_hp' => ['nullable', 'string', 'max:40'],
            'alamat' => ['nullable', 'string'],
            'jabatan' => ['required', 'string', 'max:255'],
            'gaji_harian' => ['required', 'integer', 'min:0'],
            'tanggal_masuk' => ['required', 'date'],
            'status_kerja' => ['required', 'string', 'in:aktif,nonaktif'],
            'foto' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('karyawan', 'public');
            if ($karyawan->foto_path) {
                Storage::disk('public')->delete($karyawan->foto_path);
            }
            $data['foto_path'] = $path;
        }

        $karyawan->update([
            'foto_path' => $data['foto_path'] ?? $karyawan->foto_path,
            'nama' => $data['nama'],
            'email' => $data['email'] ?? null,
            'no_hp' => $data['no_hp'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'jabatan' => $data['jabatan'],
            'gaji_harian' => (int) $data['gaji_harian'],
            'tanggal_masuk' => $data['tanggal_masuk'],
            'status_kerja' => $data['status_kerja'],
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update karyawan',
            'subject_type' => Karyawan::class,
            'subject_id' => $karyawan->id,
            'meta' => ['nama' => $karyawan->nama, 'jabatan' => $karyawan->jabatan],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('karyawan.index')
            ->with('toast', ['type' => 'success', 'message' => 'Karyawan berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        $id = $karyawan->id;
        if ($karyawan->foto_path) {
            Storage::disk('public')->delete($karyawan->foto_path);
        }
        $karyawan->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus karyawan',
            'subject_type' => Karyawan::class,
            'subject_id' => $id,
            'meta' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('karyawan.index')
            ->with('toast', ['type' => 'success', 'message' => 'Karyawan berhasil dihapus.']);
    }
}
