<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $projects = $user->projects()->with(['client', 'latestReport'])->withCount('reports')->get();
        $recentReports = $user->reports()
            ->with(['project', 'client'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $stats = [
            'projects' => $projects->count(),
            'reports_total' => $user->reports()->count(),
            'reports_sent' => $user->reports()->where('status', 'sent')->count(),
            'clients' => $user->clients()->count(),
            'views_total' => $user->reports()->sum('view_count'),
        ];

        return view('app.dashboard.index', compact('user', 'projects', 'recentReports', 'stats'));
    }

    public function onboarding()
    {
        $user = Auth::user();
        if ($user->projects()->exists()) {
            return redirect()->route('dashboard');
        }

        return view('app.onboarding');
    }
}
