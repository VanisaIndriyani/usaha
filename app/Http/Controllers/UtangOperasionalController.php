<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\UtangOperasional;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UtangOperasionalController extends Controller
{
    private const STATUS = [
        'belum_lunas' => 'Belum Lunas',
        'lunas' => 'Lunas',
    ];

    private const PIHAK = [
        'owner' => 'Owner',
        'kasir' => 'Kasir',
    ];

    public function ownerIndex(Request $request): View
    {
        return $this->indexByPihak($request, 'owner');
    }

    public function kasirIndex(Request $request): View
    {
        return $this->indexByPihak($request, 'kasir');
    }

    public function toggleStatus(Request $request, UtangOperasional $utangOperasional): RedirectResponse
    {
        $utangOperasional->status = $utangOperasional->status === 'lunas' ? 'belum_lunas' : 'lunas';
        $utangOperasional->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update status utang operasional',
            'subject_type' => UtangOperasional::class,
            'subject_id' => $utangOperasional->id,
            'meta' => [
                'pihak' => $utangOperasional->pihak,
                'deskripsi' => $utangOperasional->deskripsi,
                'status' => $utangOperasional->status,
                'nominal' => (int) $utangOperasional->nominal,
            ],
            'ip_address' => $request->ip(),
        ]);

        $route = $utangOperasional->pihak === 'owner' ? 'utang-owner.index' : 'utang-kasir.index';

        return redirect()
            ->route($route)
            ->with('toast', ['type' => 'success', 'message' => 'Status utang berhasil diperbarui.']);
    }

    private function indexByPihak(Request $request, string $pihak): View
    {
        $q = (string) $request->query('q', '');
        $status = (string) $request->query('status', '');
        $start = $request->date('start');
        $end = $request->date('end');

        $query = UtangOperasional::query()
            ->where('pihak', $pihak)
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where('deskripsi', 'like', "%{$q}%")
                    ->orWhere('catatan', 'like', "%{$q}%");
            })
            ->when($status !== '', fn ($qb) => $qb->where('status', $status))
            ->when($start, fn ($qb) => $qb->whereDate('tanggal', '>=', $start))
            ->when($end, fn ($qb) => $qb->whereDate('tanggal', '<=', $end));

        $utang = (clone $query)->latest('tanggal')->paginate(12)->withQueryString();
        $total = (int) (clone $query)->sum('nominal');
        $lunas = (int) (clone $query)->where('status', 'lunas')->sum('nominal');
        $belumLunas = (int) (clone $query)->where('status', 'belum_lunas')->sum('nominal');

        return view('utang-operasional.index', [
            'utang' => $utang,
            'q' => $q,
            'status' => $status,
            'start' => $start?->toDateString(),
            'end' => $end?->toDateString(),
            'statusList' => self::STATUS,
            'pageTitle' => 'Utang '.self::PIHAK[$pihak],
            'pageLabel' => self::PIHAK[$pihak],
            'pihak' => $pihak,
            'total' => $total,
            'lunas' => $lunas,
            'belumLunas' => $belumLunas,
        ]);
    }
}
