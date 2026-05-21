<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): Response|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return Inertia::render('Auth/Login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Check lockout
            if ($user->locked_until && $user->locked_until->isFuture()) {
                $seconds = $user->locked_until->diffInSeconds(now());
                $minutes = ceil($seconds / 60);
                throw ValidationException::withMessages([
                    'email' => "Your account is locked due to too many failed attempts. Please try again in {$minutes} minutes.",
                ]);
            }

            // Check credentials
            if (Hash::check($credentials['password'], $user->password)) {
                // Reset failed attempts
                $user->failed_attempts = 0;
                $user->locked_until = null;
                $user->save();

                // Check for Two-Factor Authentication
                if ($user->google2fa_secret) {
                    $request->session()->put('auth.2fa.user_id', $user->id);
                    $request->session()->put('auth.2fa.remember', $request->boolean('remember'));
                    
                    activity()
                        ->causedBy($user)
                        ->log('Requested 2FA challenge.');

                    return redirect()->route('login.2fa');
                }

                // Standard login
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                activity()
                    ->causedBy($user)
                    ->log('Logged in successfully.');

                return redirect()->route('admin.dashboard');
            }

            // Increment failed attempts
            $user->failed_attempts += 1;
            if ($user->failed_attempts >= 5) {
                $user->locked_until = now()->addMinutes(15);
            }
            $user->save();

            activity()
                ->causedBy($user)
                ->log("Failed login attempt. (Failed count: {$user->failed_attempts})");
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            activity()
                ->causedBy($user)
                ->log('Logged out successfully.');
        }

        return redirect()->route('login');
    }
}
