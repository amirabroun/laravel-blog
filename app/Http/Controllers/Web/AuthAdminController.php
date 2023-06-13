<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;

class AuthAdminController extends AuthController
{
    public function show($fullName)
    {
        $admins =  User::query()->where('is_admin', 1)->get();

        $fullName = explode('-', trim($fullName));

        $currentAdmin = '';
        foreach ($admins as $admin) {
            if (strtolower($admin->first_name) == $fullName[0] && strtolower($admin->last_name) == $fullName[1]) {
                $currentAdmin = $admin;
            }
        }

        if ($currentAdmin == '') {
            abort(404);
        }

        return view('auth.profile', ['user' => $currentAdmin]);
    }

    public function loginPage($secretKey)
    {
        if ($secretKey == config('blog.admin_keys')) {
            return view('auth.login');
        }

        if (!User::query()->where('first_name', $secretKey)->first()) {
            abort(404);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $user = $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        if (User::query()->where('username', $user['username'])->where('is_admin', 1)->count() == 0) {
            abort(404);
        }

        if (!auth()->attempt($user)) {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->route('posts.index');
    }
}
