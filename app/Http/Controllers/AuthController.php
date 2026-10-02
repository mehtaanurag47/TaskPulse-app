<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister()
    {
        // Agar user already logged in hai, to direct dashboard pe bhej do
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('register');
    }

    public function showLogin()
    {
        // Agar user already logged in hai, to login page nahi dikhana
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        User::create([
            'name'     => strip_tags($request->name), // Clean string names inputs
            'email'    => trim(strtolower($request->email)),
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login')
            ->with('success', 'Registration Successful! Please sign in.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email|string',
            'password' => 'required|string'
        ]);

        // Secure conditional normalization checks
        $credentials['email'] = trim(strtolower($credentials['email']));

        // Attempt authentication login engine match
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // Session fixation protection rule
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        // Standard validation error message loop response
        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our system matrix records.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Completely flush current session payloads securely
        $request->session()->invalidate();

        // Refresh and regenerate standard CSRF defense tokens
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}