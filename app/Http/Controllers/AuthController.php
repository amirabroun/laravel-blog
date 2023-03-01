<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\RegisterUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function show(string $uuid)
    {
        $user = User::query()->with('skills')->where('uuid', $uuid)->firstOrFail();

        return view('auth.profile', compact('user'));
    }

    public function index()
    {
        $users = User::query()->select(['uuid', 'first_name', 'last_name', 'email', 'created_at'])->get()->keyBy('uuid');

        return view('usersList')->with(compact('users'));
    }

    public function edit(string $uuid)
    {
        $user = User::query()->where('uuid', $uuid)->firstOrFail();

        if (!$this->authUser->profileOwnerOrAdmin($user)) {
            abort(404);
        }

        return view('auth.editProfile', compact('user'));
    }

    public function update(Request $request, string $uuid)
    {
        $user = User::query()->where('uuid', $uuid)->firstOrFail();

        $newUserData = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => ['email', Rule::unique('users', 'email')->ignoreModel($user)],
        ]);

        if (!$this->authUser->profileOwnerOrAdmin($user)) {
            abort(404);
        }

        User::query()->where('uuid', $uuid)->update($newUserData);

        $user = User::query()->where('uuid', $uuid)->firstOrFail();

        return view('auth.profile', compact('user'));
    }

    public function login(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($user)) {
            return back()->withErrors([
                'email' => 'The provuuided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->to(session('previous_url'));
    }

    public function register(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        User::create($user);

        Mail::to($request->user())->queue(new RegisterUser());

        auth()->attempt($user);

        $request->session()->regenerate();

        return redirect()->to(session('previous_url'));
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->intended(session('previous_url'));
    }
}
