<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Report;
use App\Models\Client;
use App\Mail\BroadcastMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ── Dashboard
    public function dashboard()
    {
        $stats = [
            'users_total'     => User::count(),
            'users_today'     => User::whereDate('created_at', today())->count(),
            'users_week'      => User::where('created_at', '>=', now()->subDays(7))->count(),
            'users_pro'       => User::where('plan', 'pro')->count(),
            'users_agency'    => User::where('plan', 'agency')->count(),
            'users_free'      => User::where('plan', 'free')->count(),
            'projects_total'  => Project::count(),
            'reports_total'   => Report::count(),
            'reports_sent'    => Report::where('status', 'sent')->count(),
            'clients_total'   => Client::count(),
            'mrr'             => (User::where('plan', 'pro')->count() * 19) + (User::where('plan', 'agency')->count() * 49),
        ];

        // Signups chart last 14 days
        $chartLabels = [];
        $chartData   = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $chartLabels[] = $day->format('d M');
            $chartData[]   = User::whereDate('created_at', $day->toDateString())->count();
        }

        // Recent signups
        $recentUsers = User::orderByDesc('created_at')->take(8)->get();

        return view('admin.dashboard', compact('stats', 'chartLabels', 'chartData', 'recentUsers'));
    }

    // ── Users list
    public function users(Request $request)
    {
        $query = User::withCount(['projects', 'reports', 'clients'])
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }

        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        $users = $query->paginate(30)->withQueryString();

        return view('admin.users', compact('users'));
    }

    // ── User detail
    public function userShow(User $user)
    {
        $user->load(['projects', 'clients', 'integrations', 'reports' => fn($q) => $q->orderByDesc('created_at')->take(10)]);
        return view('admin.user-show', compact('user'));
    }

    // ── Change user plan
    public function userChangePlan(Request $request, User $user)
    {
        $request->validate(['plan' => ['required', 'in:free,pro,agency']]);
        $user->update(['plan' => $request->plan]);
        return back()->with('success', "Plan changed to {$request->plan} for {$user->name}.");
    }

    // ── Delete user
    public function userDelete(User $user)
    {
        $email = $user->email;
        $user->delete();
        return redirect()->route('admin.users')->with('success', "User {$email} deleted.");
    }

    // ── Projects list
    public function projects()
    {
        $projects = Project::with(['user', 'client'])
            ->withCount('reports')
            ->orderByDesc('created_at')
            ->paginate(30);
        return view('admin.projects', compact('projects'));
    }

    // ── Reports list
    public function reports()
    {
        $reports = Report::with(['user', 'project', 'client'])
            ->orderByDesc('created_at')
            ->paginate(30);
        return view('admin.reports', compact('reports'));
    }

    // ── Broadcast email to all users
    public function broadcastForm()
    {
        $count = User::count();
        return view('admin.broadcast', compact('count'));
    }

    public function broadcastSend(Request $request)
    {
        $request->validate([
            'subject'   => ['required', 'string', 'max:200'],
            'body'      => ['required', 'string', 'max:5000'],
            'plan'      => ['nullable', 'in:all,free,pro,agency'],
        ]);

        $query = User::query();
        if ($request->plan && $request->plan !== 'all') {
            $query->where('plan', $request->plan);
        }

        $users  = $query->get();
        $sent   = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new BroadcastMail(
                    broadcastSubject: $request->subject,
                    broadcastBody:    $request->body,
                    recipientName:    $user->name,
                ));
                $sent++;
            } catch (\Throwable $e) {
                Log::error("Admin broadcast failed for {$user->email}: " . $e->getMessage());
                $failed++;
            }
        }

        return back()->with('success', "Sent to {$sent} users." . ($failed ? " {$failed} failed." : ''));
    }

    // ── Settings / config
    public function settings()
    {
        return view('admin.settings');
    }

    // ── Impersonate user (login as them)
    public function impersonate(User $user)
    {
        session(['admin_impersonating' => auth()->id()]);
        auth()->login($user);
        return redirect()->route('dashboard')->with('info', "You are now logged in as {$user->name}.");
    }

    public function stopImpersonating()
    {
        $adminId = session('admin_impersonating');
        if ($adminId) {
            session()->forget('admin_impersonating');
            auth()->loginUsingId($adminId);
        }
        return redirect()->route('admin.dashboard');
    }
}
