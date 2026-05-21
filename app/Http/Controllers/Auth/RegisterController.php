<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Throwable;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plan' => 'free',
            'trial_ends_at' => Carbon::now()->addDays(14),
        ]);

        Auth::login($user);

        try {
            event(new Registered($user));
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('verification.notice')
                ->with('warning', 'Account created, but the verification email could not be sent. Check your mail settings and try again.');
        }

        return redirect()->route('verification.notice');
    }
}
