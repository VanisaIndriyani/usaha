<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ModalUsaha;
use App\Models\Owner;
use App\Models\ProfitSharing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfitSharingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $items = ProfitSharing::query()->latest('periode_selesai')->paginate(12);

        $owners = Owner::query()->orderBy('id')->limit(2)->get();

        return view('profit-sharing.index', [
            'items' => $items,
            'owners' => $owners,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('profit-sharing.create', [
            'owners' => Owner::query()->orderBy('id')->limit(2)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'periode_mulai' => ['required', 'date'],
            'periode_selesai' => ['required', 'date', 'after_or_equal:periode_mulai'],
            'laba_bersih' => ['required', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $owners = Owner::query()->orderBy('id')->limit(2)->get();
        if ($owners->count() < 2) {
            abort(422, 'Owner belum lengkap. Minimal 2 owner diperlukan.');
        }

        $modalTotals = ModalUsaha::query()
            ->selectRaw('owner_id, COALESCE(SUM(nominal), 0) as total')
            ->whereDate('tanggal', '<=', $data['periode_selesai'])
            ->groupBy('owner_id')
            ->pluck('total', 'owner_id')
            ->map(fn ($v) => (int) $v)
            ->all();

        $ownerA = $owners[0];
        $ownerB = $owners[1];
        $aModal = (int) ($modalTotals[$ownerA->id] ?? 0);
        $bModal = (int) ($modalTotals[$ownerB->id] ?? 0);
        $totalModal = $aModal + $bModal;

        $aPersen = $totalModal > 0 ? round(($aModal / $totalModal) * 100, 2) : 0;
        $bPersen = $totalModal > 0 ? round(($bModal / $totalModal) * 100, 2) : 0;

        $aNominal = $totalModal > 0 ? (int) floor($data['laba_bersih'] * ($aPersen / 100)) : 0;
        $bNominal = (int) $data['laba_bersih'] - $aNominal;

        $profit = ProfitSharing::create([
            'periode_mulai' => $data['periode_mulai'],
            'periode_selesai' => $data['periode_selesai'],
            'total_modal' => $totalModal,
            'laba_bersih' => (int) $data['laba_bersih'],
            'owner_a_nominal' => $aNominal,
            'owner_b_nominal' => $bNominal,
            'owner_a_persen' => $aPersen,
            'owner_b_persen' => $bPersen,
            'catatan' => $data['catatan'] ?? null,
            'created_by' => Auth::id(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Buat profit sharing',
            'subject_type' => ProfitSharing::class,
            'subject_id' => $profit->id,
            'meta' => ['laba_bersih' => (int) $profit->laba_bersih, 'owner_a_persen' => (float) $profit->owner_a_persen, 'owner_b_persen' => (float) $profit->owner_b_persen],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('profit-sharing.index')
            ->with('toast', ['type' => 'success', 'message' => 'Profit sharing berhasil dibuat.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfitSharing $profitSharing)
    {
        return redirect()->route('profit-sharing.edit', $profitSharing);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfitSharing $profitSharing): View
    {
        return view('profit-sharing.edit', [
            'profitSharing' => $profitSharing,
            'owners' => Owner::query()->orderBy('id')->limit(2)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProfitSharing $profitSharing)
    {
        $data = $request->validate([
            'periode_mulai' => ['required', 'date'],
            'periode_selesai' => ['required', 'date', 'after_or_equal:periode_mulai'],
            'laba_bersih' => ['required', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $owners = Owner::query()->orderBy('id')->limit(2)->get();
        $modalTotals = ModalUsaha::query()
            ->selectRaw('owner_id, COALESCE(SUM(nominal), 0) as total')
            ->whereDate('tanggal', '<=', $data['periode_selesai'])
            ->groupBy('owner_id')
            ->pluck('total', 'owner_id')
            ->map(fn ($v) => (int) $v)
            ->all();

        $ownerA = $owners[0] ?? null;
        $ownerB = $owners[1] ?? null;
        if (! $ownerA || ! $ownerB) {
            abort(422, 'Owner belum lengkap. Minimal 2 owner diperlukan.');
        }

        $aModal = (int) ($modalTotals[$ownerA->id] ?? 0);
        $bModal = (int) ($modalTotals[$ownerB->id] ?? 0);
        $totalModal = $aModal + $bModal;

        $aPersen = $totalModal > 0 ? round(($aModal / $totalModal) * 100, 2) : 0;
        $bPersen = $totalModal > 0 ? round(($bModal / $totalModal) * 100, 2) : 0;

        $aNominal = $totalModal > 0 ? (int) floor($data['laba_bersih'] * ($aPersen / 100)) : 0;
        $bNominal = (int) $data['laba_bersih'] - $aNominal;

        $profitSharing->update([
            'periode_mulai' => $data['periode_mulai'],
            'periode_selesai' => $data['periode_selesai'],
            'total_modal' => $totalModal,
            'laba_bersih' => (int) $data['laba_bersih'],
            'owner_a_nominal' => $aNominal,
            'owner_b_nominal' => $bNominal,
            'owner_a_persen' => $aPersen,
            'owner_b_persen' => $bPersen,
            'catatan' => $data['catatan'] ?? null,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update profit sharing',
            'subject_type' => ProfitSharing::class,
            'subject_id' => $profitSharing->id,
            'meta' => ['laba_bersih' => (int) $profitSharing->laba_bersih, 'owner_a_persen' => (float) $profitSharing->owner_a_persen, 'owner_b_persen' => (float) $profitSharing->owner_b_persen],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('profit-sharing.index')
            ->with('toast', ['type' => 'success', 'message' => 'Profit sharing berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfitSharing $profitSharing)
    {
        $id = $profitSharing->id;
        $profitSharing->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus profit sharing',
            'subject_type' => ProfitSharing::class,
            'subject_id' => $id,
            'meta' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('profit-sharing.index')
            ->with('toast', ['type' => 'success', 'message' => 'Profit sharing berhasil dihapus.']);
    }
}
