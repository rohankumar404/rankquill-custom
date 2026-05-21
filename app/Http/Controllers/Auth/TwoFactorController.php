<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorController extends Controller
{
    /**
     * Show the two-factor authentication challenge form.
     */
    public function showChallenge(Request $request): Response|RedirectResponse
    {
        if (! $request->session()->has('auth.2fa.user_id')) {
            return redirect()->route('login');
        }

        return Inertia::render('Auth/TwoFactorChallenge');
    }

    /**
     * Verify the two-factor authentication code.
     */
    public function verify(Request $request): RedirectResponse
    {
        if (! $request->session()->has('auth.2fa.user_id')) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $ip = $request->ip();
        if (RateLimiter::tooManyAttempts('2fa:'.$ip, 5)) {
            throw ValidationException::withMessages([
                'code' => 'Too many 2FA verification attempts. Please try again in a few minutes.',
            ]);
        }

        $userId = $request->session()->get('auth.2fa.user_id');
        $user = User::findOrFail($userId);

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);

        if ($valid) {
            RateLimiter::clear('2fa:'.$ip);

            Auth::login($user, $request->session()->get('auth.2fa.remember', false));
            
            $request->session()->forget(['auth.2fa.user_id', 'auth.2fa.remember']);
            $request->session()->regenerate();

            activity()
                ->causedBy($user)
                ->log('Two-Factor Authentication verified successfully.');

            return redirect()->route('admin.dashboard');
        }

        RateLimiter::hit('2fa:'.$ip);

        throw ValidationException::withMessages([
            'code' => 'The provided two-factor code is invalid.',
        ]);
    }

    /**
     * Initialize 2FA setup by generating a secret and QR code URL.
     */
    public function setup(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->google2fa_secret) {
            return response()->json([
                'error' => 'Two-Factor Authentication is already enabled.',
            ], 422);
        }

        $google2fa = app('pragmarx.google2fa');
        $secret = $google2fa->generateSecretKey();
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name', 'RankQuill'),
            $user->email,
            $secret
        );

        // Generate QR code image URL using Google Charts API
        $qrCodeImageUrl = 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=' . urlencode($qrCodeUrl);

        return response()->json([
            'secret' => $secret,
            'qr_code' => $qrCodeImageUrl,
        ]);
    }

    /**
     * Enable 2FA after verifying a code.
     */
    public function enable(Request $request): JsonResponse
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if ($user->google2fa_secret) {
            return response()->json([
                'error' => 'Two-Factor Authentication is already enabled.',
            ], 422);
        }

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($request->secret, $request->code);

        if ($valid) {
            $user->google2fa_secret = $request->secret;
            $user->save();

            activity()
                ->causedBy($user)
                ->log('Two-Factor Authentication enabled.');

            return response()->json([
                'success' => true,
                'message' => 'Two-Factor Authentication enabled successfully.',
            ]);
        }

        return response()->json([
            'error' => 'The verification code was invalid. Please try scanning again.',
        ], 422);
    }

    /**
     * Disable 2FA.
     */
    public function disable(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if (! $user->google2fa_secret) {
            return response()->json([
                'error' => 'Two-Factor Authentication is not enabled.',
            ], 422);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'The password you entered is incorrect.',
            ], 422);
        }

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);

        if ($valid) {
            $user->google2fa_secret = null;
            $user->save();

            activity()
                ->causedBy($user)
                ->log('Two-Factor Authentication disabled.');

            return response()->json([
                'success' => true,
                'message' => 'Two-Factor Authentication has been disabled.',
            ]);
        }

        return response()->json([
            'error' => 'The verification code was invalid.',
        ], 422);
    }
}
