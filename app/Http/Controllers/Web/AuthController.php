<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Mail\RegisterUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function show(string $uuid)
    {
        $user = User::query()->with(['resume', 'media'])->where('uuid', $uuid)->firstOrFail();

        return view('auth.profile', compact('user'));
    }

    public function index()
    {
        $users = User::query()->select(['uuid', 'first_name', 'last_name', 'username', 'created_at'])->get()->keyBy('uuid');

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
            'username' => ['string', Rule::unique('users', 'username')->ignoreModel($user)],
        ]);

        if (!$this->authUser->profileOwnerOrAdmin($user)) {
            abort(404);
        }

        User::query()->where('uuid', $uuid)->update($newUserData);

        if ($request->file('avatar', false)) {
            $user->addMediaFromRequest('avatar')->usingFileName(
                $request->file('avatar')->hashName()
            )->toMediaCollection('avatar');
        }

        $user = User::query()->where('uuid', $uuid)->firstOrFail();

        return view('auth.profile', compact('user'));
    }

    public function login(Request $request)
    {
        $user = $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        if (!auth()->attempt($user)) {
            return back()->withErrors([
                'username' => 'The provuuided credentials do not match our records.',
            ])->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function register(Request $request)
    {
        $user = $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|confirmed',
        ]);

        User::create($user);

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

    public function deleteUserAvatar($uuid)
    {
        $user = User::query()->where('uuid', $uuid)->with('media')->firstOrFail();

        if (!$this->authUser->profileOwnerOrAdmin($user)) {
            abort(404);
        }

        if ($user->media->count() == 0) {
            return back()->withErrors(['file' => 'user does not have file']);
        }

        $user->media->where('collection_name', 'avatar')
            ->map(fn ($image) => $image->forceDelete());

        return redirect()->back();
    }
}
