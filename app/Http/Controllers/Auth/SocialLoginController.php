<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $exception) {
            Log::error('Google OAuth callback error: ' . $exception->getMessage());
            return redirect()->route('login')->with('error', 'No se pudo iniciar sesión con Google. Intenta de nuevo.');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Usuario Google',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Str::random(24),
                'email_verified_at' => now(),
            ]);
        } elseif (! $user->google_id) {
            $user->update(['google_id' => $googleUser->getId()]);
        }

        Auth::login($user, true);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
