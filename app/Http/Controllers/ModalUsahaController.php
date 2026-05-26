<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ModalUsaha;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ModalUsahaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $ownerId = (int) $request->query('owner_id', 0);

        $modal = ModalUsaha::query()
            ->with('owner')
            ->when($ownerId > 0, fn ($query) => $query->where('owner_id', $ownerId))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('owner', fn ($o) => $o->where('name', 'like', "%{$q}%"))
                        ->orWhere('catatan', 'like', "%{$q}%");
                });
            })
            ->latest('tanggal')
            ->paginate(12)
            ->withQueryString();

        $owners = Owner::query()->orderBy('name')->get();
        $totals = ModalUsaha::query()
            ->selectRaw('owner_id, COALESCE(SUM(nominal), 0) as total')
            ->groupBy('owner_id')
            ->pluck('total', 'owner_id')
            ->map(fn ($v) => (int) $v)
            ->all();

        $totalAll = array_sum($totals);
        $percent = [];
        foreach ($owners as $owner) {
            $value = (int) ($totals[$owner->id] ?? 0);
            $percent[$owner->id] = $totalAll > 0 ? round(($value / $totalAll) * 100, 2) : 0;
        }

        return view('modal-usaha.index', [
            'modal' => $modal,
            'owners' => $owners,
            'totals' => $totals,
            'totalAll' => $totalAll,
            'percent' => $percent,
            'q' => $q,
            'ownerId' => $ownerId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('modal-usaha.create', [
            'owners' => Owner::query()->orderBy('name')->get(),
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
            'owner_id' => ['required', 'exists:owners,id'],
            'nominal' => ['required', 'integer', 'min:1'],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
        ]);

        $data['created_by'] = Auth::id();

        $modal = ModalUsaha::create($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah modal usaha',
            'subject_type' => ModalUsaha::class,
            'subject_id' => $modal->id,
            'meta' => ['nominal' => (int) $modal->nominal, 'tanggal' => (string) $modal->tanggal],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('modal-usaha.index')
            ->with('toast', ['type' => 'success', 'message' => 'Modal usaha berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(ModalUsaha $modalUsaha)
    {
        return redirect()->route('modal-usaha.edit', $modalUsaha);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModalUsaha $modalUsaha): View
    {
        return view('modal-usaha.edit', [
            'modalUsaha' => $modalUsaha->load('owner'),
            'owners' => Owner::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModalUsaha $modalUsaha)
    {
        $request->merge([
            'nominal' => preg_replace('/\D+/', '', (string) $request->input('nominal', '')),
        ]);

        $data = $request->validate([
            'owner_id' => ['required', 'exists:owners,id'],
            'nominal' => ['required', 'integer', 'min:1'],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
        ]);

        $modalUsaha->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update modal usaha',
            'subject_type' => ModalUsaha::class,
            'subject_id' => $modalUsaha->id,
            'meta' => ['nominal' => (int) $modalUsaha->nominal, 'tanggal' => (string) $modalUsaha->tanggal],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('modal-usaha.index')
            ->with('toast', ['type' => 'success', 'message' => 'Modal usaha berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModalUsaha $modalUsaha)
    {
        $id = $modalUsaha->id;
        $modalUsaha->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus modal usaha',
            'subject_type' => ModalUsaha::class,
            'subject_id' => $id,
            'meta' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('modal-usaha.index')
            ->with('toast', ['type' => 'success', 'message' => 'Modal usaha berhasil dihapus.']);
    }
}
