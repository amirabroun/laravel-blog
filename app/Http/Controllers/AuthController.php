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
        return view('usersList')->with('users', User::all());
    }

    public function edit(int $id)
    {
        $user = User::query()->find($id);

        if ($this->authUser->id != $id && !$this->authUser->isAdmin()) {
            abort(404);
        }

        return view('auth.editProfile')->with('user', $user);
    }

    public function update(Request $request, int $id)
    {
        $user = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => ['email', Rule::unique('users', 'email')->ignore($id)],
        ]);

        if ($this->authUser->id != $id && !$this->authUser->isAdmin()) {
            abort(404);
        }

        User::query()->where('id', $id)->update($user);

        return view('auth.profile')->with('user', User::query()->find($id));
    }

    public function destroy(int $id)
    {
        if (!$user = User::find($id)) {
            abort(404);
        }

        $user->delete();

        return redirect()->intended(route('users.index'));
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
            'first_name' => 'required|string',
            'last_name' => '',
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
