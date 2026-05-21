<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('app.settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'timezone' => ['required', 'timezone'],
        ]);
        $user->update($validated);

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated.');
    }

    public function updateNotifications(Request $request)
    {
        Auth::user()->update([
            'notification_preferences' => [
                'report_generated' => $request->boolean('report_generated'),
                'report_viewed' => $request->boolean('report_viewed'),
                'weekly_digest' => $request->boolean('weekly_digest'),
            ],
        ]);

        return back()->with('success', 'Notification preferences saved.');
    }
}
