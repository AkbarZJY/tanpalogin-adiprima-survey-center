<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $credentials['login'];
        $password = $credentials['password'];

        // Determine if login is email, username, or NIK
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt login with username or email
        $attempt = Auth::attempt([$fieldType => $loginInput, 'password' => $password], $request->boolean('remember'));

        if (!$attempt) {
            // Fallback attempt with NIK
            $attempt = Auth::attempt(['nik' => $loginInput, 'password' => $password], $request->boolean('remember'));
        }

        if ($attempt) {
            $user = Auth::user();

            // Hanya admin yang perlu login
            if (!$user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'login' => 'Halaman login ini hanya untuk Admin. Pengguna biasa dapat langsung mengisi survei tanpa login.',
                ])->onlyInput('login');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'login' => 'Username, NIK, atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
