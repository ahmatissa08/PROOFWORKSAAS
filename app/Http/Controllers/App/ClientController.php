<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Auth::user()->clients()
            ->withCount(['projects', 'reports'])
            ->orderBy('name')->get();
        return view('app.clients.index', compact('clients'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->canCreateClient()) {
            return redirect()->route('billing.plans')
                ->with('upgrade_reason', 'You\'ve reached the client limit on the free plan.');
        }
        return view('app.clients.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->canCreateClient()) {
            return redirect()->route('billing.plans')
                ->with('upgrade_reason', 'You\'ve reached the client limit on the free plan.');
        }

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:120'],
            'email'        => ['nullable', 'email', 'max:255'],
            'company'      => ['nullable', 'string', 'max:120'],
            'avatar_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ]);

        $client = Auth::user()->clients()->create($validated);
        return redirect()->route('clients.show', $client)->with('success', 'Client added!');
    }

    public function show(Client $client)
    {
        $this->authorize('view', $client);
        $client->load([
            'projects',
            'reports' => fn($q) => $q->orderByDesc('created_at')->take(10),
        ]);
        return view('app.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $this->authorize('update', $client);
        return view('app.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:120'],
            'email'        => ['nullable', 'email', 'max:255'],
            'company'      => ['nullable', 'string', 'max:120'],
            'avatar_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ]);
        $client->update($validated);
        return redirect()->route('clients.show', $client)->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client removed.');
    }
}
