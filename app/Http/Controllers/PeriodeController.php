<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PeriodeController extends Controller
{
    public function index(): View
    {
        $periodes = Periode::query()->latest()->get();
        $activePeriode = Periode::getActivePeriod();

        return view('periode.index', compact('periodes', 'activePeriode'));
    }

    public function create(): View
    {
        return view('periode.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (isset($validated['is_active']) && $validated['is_active']) {
            Periode::query()->update(['is_active' => false]);
        }

        Periode::query()->create($validated);

        return redirect()->route('periode.index')->with('success', 'Periode berhasil dibuat');
    }

    public function edit(Periode $periode): View
    {
        return view('periode.edit', compact('periode'));
    }

    public function update(Request $request, Periode $periode): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (isset($validated['is_active']) && $validated['is_active']) {
            Periode::query()->where('id', '!=', $periode->id)->update(['is_active' => false]);
        }

        $periode->update($validated);

        return redirect()->route('periode.index')->with('success', 'Periode berhasil diperbarui');
    }

    public function destroy(Periode $periode): RedirectResponse
    {
        $periode->delete();

        return redirect()->route('periode.index')->with('success', 'Periode berhasil dihapus');
    }

    public function activate(Periode $periode): RedirectResponse
    {
        Periode::query()->update(['is_active' => false]);
        $periode->update(['is_active' => true]);

        return redirect()->route('periode.index')->with('success', 'Periode berhasil diaktifkan');
    }
}
