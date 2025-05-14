<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Rate-limiting
        $key = 'login-attempt:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->route('login')->withErrors([
                'login' => "Too many attempts. Try again in $seconds seconds.",
            ]);
        }

        // Validate input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt authentication
        $credentials = ['username' => $request->username, 'password' => $request->password];
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            RateLimiter::clear($key);
            return redirect()->intended(route('dashboard'));
        }

        // Increment rate-limiter and return error
        RateLimiter::hit($key, 60);
        return redirect()->route('login')->withErrors([
            'login' => 'Invalid username or password.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}