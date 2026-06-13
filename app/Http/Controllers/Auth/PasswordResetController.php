<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GmailApiEmailService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Throwable;

class PasswordResetController extends Controller
{
    // Show forgot password form
    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    // Send reset link
    public function sendLink(Request $request, GmailApiEmailService $emailService)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => __(Password::INVALID_USER)]);
        }

        try {
            $token = Password::broker()->createToken($user);
            $url = URL::route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);

            $html = view('emails.password-reset', [
                'user' => $user,
                'url' => $url,
            ])->render();

            $emailService->send(
                $user->email,
                'Reset your ProofWork password',
                $html,
                "Reset your ProofWork password:\n\n{$url}\n\nThis link expires soon."
            );
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'email' => 'The reset email could not be sent. Check the mail configuration and try again.',
            ]);
        }

        return back()->with('status', __(Password::RESET_LINK_SENT));
    }

    // Show reset form
    public function resetForm(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // Do the reset
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
