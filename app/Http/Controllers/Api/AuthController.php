<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function account()
    {
        return new UserResource($this->authUser);
    }

    public function show(string $uuid)
    {
        $user = User::query()->with(['resume', 'media'])->where('uuid', $uuid)->first();

        $user->setHidden(['id'])->media->setVisible(['uuid', 'collection_name', 'name', 'original_url', 'created_at']);

        return response($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::query()->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'The provuuided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $token = $user->createToken($user->email);

        return [
            'user' => new UserResource($user),
            'token' => $token->plainTextToken
        ];
    }
}
