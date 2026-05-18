<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Auth::user()->projects()
            ->with(['client', 'latestReport'])
            ->withCount(['reports', 'integrations'])
            ->orderByDesc('created_at')
            ->get();
        return view('app.projects.index', compact('projects'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->canCreateProject()) {
            return redirect()->route('billing.plans')
                ->with('upgrade_reason', 'You\'ve reached the project limit on your current plan.');
        }
        $clients = $user->clients()->orderBy('name')->get();
        return view('app.projects.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->canCreateProject()) {
            return redirect()->route('billing.plans');
        }

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:120'],
            'description'      => ['nullable', 'string', 'max:500'],
            'client_id'        => ['nullable', Rule::exists('clients', 'id')->where('user_id', $user->id)],
            'color'            => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'report_frequency' => ['in:weekly,biweekly,monthly'],
            'report_day'       => ['in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'auto_send'        => ['boolean'],
        ]);

        $validated['auto_report'] = true;
        $validated['auto_send'] = $request->boolean('auto_send') && $user->isPro();

        $project = $user->projects()->create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created! Now connect your integrations.');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load([
            'client',
            'integrations',
            'reports' => fn($q) => $q->withCount('entries')->orderByDesc('period_end')->take(10),
        ]);
        return view('app.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $clients = Auth::user()->clients()->orderBy('name')->get();
        return view('app.projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        $user = Auth::user();
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:120'],
            'description'      => ['nullable', 'string', 'max:500'],
            'client_id'        => ['nullable', Rule::exists('clients', 'id')->where('user_id', $user->id)],
            'color'            => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'report_frequency' => ['in:weekly,biweekly,monthly'],
            'report_day'       => ['in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'auto_send'        => ['boolean'],
            'status'           => ['in:active,paused,completed'],
        ]);

        $validated['auto_send'] = $request->boolean('auto_send') && $user->isPro();

        $project->update($validated);
        return redirect()->route('projects.show', $project)->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
