<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function show(int $id)
    {
        if (!$user = User::query()->find($id)) {
            abort(404);
        }

        return view('auth.profile')->with('user', $user);
    }

    public function index()
    {
        $users = User::query()->select(['id', 'first_name', 'last_name', 'email', 'created_at'])->get();

        return view('usersList')->with(compact('users'));
    }

    public function edit(int $id)
    {
        $user = User::query()->find($id);

        return view('auth.editProfile')->with('user', $user);
    }

    public function update(Request $request, int $id)
    {
        $user = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => ['email', Rule::unique('users', 'email')->ignore($id)],
        ]);

        User::query()->where('id', $id)->update($user);

        return view('auth.profile')->with('user', User::query()->find($id));
    }

    public function login(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($user)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('posts.index');
    }

    public function register(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        User::create($user);

        auth()->attempt($user);

        $request->session()->regenerate();

        return redirect()->route('posts.index');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('posts.index');
    }
}
