<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function index($id)
    {
        return view('auth.profile', [
            'user' => $user = User::query()->find($id),
            'updatePermission' => $this->authUser->id == $user->id || $this->authUser->isAdmin(),
        ]);
    }

    public function getUsers()
    {
        return view('usersList')->with('users', User::all());
    }

    public function updateUser(Request $request, $id)
    {
        $user = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'student_number' => Rule::unique('users', 'student_number')->ignore($id),
            'email' => ['email', Rule::unique('users', 'email')->ignore($id)],
        ]);

        User::query()->where('id', $id)->update($user);

        return view('auth.profile', [
            'user' => User::query()->find($id),
            'updatePermission' => true,
            'updateMessage' => 'User profile updated successfully'
        ]);
    }

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
