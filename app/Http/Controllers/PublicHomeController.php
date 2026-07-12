<?php

namespace App\Http\Controllers;

use App\Support\PublicBusinessOverview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicHomeController extends Controller
{
    public function index(Request $request, PublicBusinessOverview $overview): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('welcome', [
            'publicData' => $overview->build((int) ($request->integer('year') ?: now()->year)),
        ]);
    }
}
