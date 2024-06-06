<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChirpController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Chirps/Index', [
            'chirps' => Chirp::with('user:id,name')->latest()->get()
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255'
        ]);
        $request->user()->chirps()->create($validated);
        return redirect(route('chirps.index'));
    }

    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
        $chirp->update($validated);
        return redirect(route('chirps.index'));
    }

    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp);
        $chirp->delete();
        return redirect(route('chirps.index'));
    }
}
