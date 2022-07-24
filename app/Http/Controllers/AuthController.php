<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($user)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect('/');
    }

    public function register(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        (new User($user))->setPasswordAttribute($user['password'])->save();

        Auth::attempt($user);

        $request->session()->regenerate();

        $user = User::query()->where('email', $user['email'])->first();

        return redirect('/');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
