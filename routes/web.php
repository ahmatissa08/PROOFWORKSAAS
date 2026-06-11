<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\App\ClientController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\IntegrationController;
use App\Http\Controllers\App\ProjectController;
use App\Http\Controllers\App\ReportController;
use App\Http\Controllers\App\SettingsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Billing\BillingController;
use App\Http\Controllers\DemoController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════════════════
// PUBLIC ROUTES
// ═══════════════════════════════════════════════════════════════

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('landing');
})->name('home');

// Static pages
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');
Route::view('/roadmap', 'roadmap')->name('roadmap');
Route::view('/changelog', 'changelog')->name('changelog');
Route::view('/security', 'security')->name('security');

// Demo
Route::get('/demo', [DemoController::class, 'index'])->name('demo');
Route::post('/demo/generate', [DemoController::class, 'generate'])->name('demo.generate');

// Public report (no auth, lightly throttled)
Route::get('/r/{token}', [ReportController::class, 'publicView'])
    ->middleware('throttle:60,1')
    ->name('reports.public');

// Stripe webhook (no auth, no CSRF)
Route::post('/stripe/webhook', [BillingController::class, 'webhook'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('stripe.webhook');

// Contact form
Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:120',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:100',
        'message' => 'required|string|max:5000',
        'website' => 'nullable|string|max:255',
    ]);

    if (! empty($validated['website'])) {
        return response()->json(['message' => 'Message sent!'], 200);
    }

    $to = config('proofwork.admin_email') ?: config('mail.from.address');

    if ($to) {
        $bodyLines = [
            "Name: {$validated['name']}",
            "Email: {$validated['email']}",
            "Subject: {$validated['subject']}",
            "Website: ".($validated['website'] ?? '-'),
            '',
            $validated['message'],
        ];

        try {
            Mail::raw(implode("\n", $bodyLines), function ($mail) use ($to, $validated) {
                $mail->to($to)
                    ->subject('[ProofWork contact] '.$validated['subject']);
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }

    return response()->json(['message' => 'Thank you! We will respond within 24 hours.'], 200);
})->name('contact.store');

// ═══════════════════════════════════════════════════════════════
// GUEST ONLY
// ═══════════════════════════════════════════════════════════════

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// ═══════════════════════════════════════════════════════════════
// OAUTH
// ═══════════════════════════════════════════════════════════════

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

// ═══════════════════════════════════════════════════════════════
// AUTH (verification routes)
// ═══════════════════════════════════════════════════════════════

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/email/verify', function () {
        if (request()->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('onboarding');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (Throwable $e) {
            report($e);

            return back()->with('warning', 'Verification email could not be sent. Please try again in a moment or contact support.');
        }

        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});

// ═══════════════════════════════════════════════════════════════
// APP (auth + verified)
// ═══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/onboarding', [DashboardController::class, 'onboarding'])->name('onboarding');

    // Projects
    Route::resource('projects', ProjectController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::patch('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::post('/projects/{project}/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/reports/{report}/send', [ReportController::class, 'send'])->name('reports.send');
    Route::post('/reports/{report}/entries', [ReportController::class, 'addEntry'])->name('reports.entries.add');
    Route::delete('/reports/{report}/entries/{entry}', [ReportController::class, 'deleteEntry'])->name('reports.entries.delete');
    Route::get('/reports/{report}/download', [ReportController::class, 'downloadPdf'])->name('reports.download');

    // Clients
    Route::resource('clients', ClientController::class);

    // Integrations
    Route::get('/integrations', [IntegrationController::class, 'index'])->name('integrations.index');
    Route::get('/integrations/connect/{provider}', [IntegrationController::class, 'connect'])->name('integrations.connect');
    Route::get('/integrations/callback/{provider}', [IntegrationController::class, 'callback'])->name('integrations.callback');
    Route::patch('/integrations/{integration}/resource', [IntegrationController::class, 'updateResource'])->name('integrations.resource.update');
    Route::delete('/integrations/{integration}', [IntegrationController::class, 'destroy'])->name('integrations.destroy');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::patch('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::patch('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');

    // Billing
    Route::get('/billing/plans', [BillingController::class, 'plans'])->name('billing.plans');
    Route::post('/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');
    Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
    Route::get('/billing/manage', [BillingController::class, 'manage'])->name('billing.manage');
});

// ═══════════════════════════════════════════════════════════════
// ADMIN
// ═══════════════════════════════════════════════════════════════

Route::get('/admin', function () {
    if (session('proofwork_admin')) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('admin.login');
});

Route::get('/admin/login', function () {
    if (session('proofwork_admin')) {
        return redirect()->route('admin.dashboard');
    }

    return view('admin.login');
})->name('admin.login');

Route::post('/admin/login', function (Request $request) {
    $request->validate([
        'admin_password' => ['required', 'string', 'max:255'],
    ]);

    $adminPassword = (string) config('proofwork.admin_password');
    $input = (string) $request->admin_password;

    $isHashed = str_starts_with($adminPassword, '$2y$');
    $valid = $isHashed
        ? Hash::check($input, $adminPassword)
        : (filled($adminPassword) && hash_equals($adminPassword, $input));

    if ($valid) {
        $request->session()->regenerate();
        $request->session()->put('proofwork_admin', true);

        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors(['admin_password' => 'Invalid password']);
})->middleware('throttle:5,1')->name('admin.authenticate');

Route::prefix('admin')->name('admin.')->middleware([AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'userShow'])->name('users.show');
    Route::post('/users/{user}/plan', [AdminController::class, 'userChangePlan'])->name('users.plan');
    Route::delete('/users/{user}', [AdminController::class, 'userDelete'])->name('users.delete');
    Route::post('/users/{user}/impersonate', [AdminController::class, 'impersonate'])->name('users.impersonate');
    Route::get('/projects', [AdminController::class, 'projects'])->name('projects');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/broadcast', [AdminController::class, 'broadcastForm'])->name('broadcast');
    Route::post('/broadcast', [AdminController::class, 'broadcastSend'])->name('broadcast.send');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::get('/stop-impersonating', [AdminController::class, 'stopImpersonating'])->name('stop-impersonating');
    Route::post('/logout', function (Request $request) {
        $request->session()->forget(['proofwork_admin', 'admin_impersonating']);
        $request->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});
