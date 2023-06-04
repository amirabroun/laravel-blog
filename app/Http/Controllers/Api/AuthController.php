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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::query()->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ['message' => __('auth.password')];
        }

        $token = $user->createToken($user->email);

        return [
            'user' => new UserResource($user),
            'token' => $token->plainTextToken
        ];
    }

    public function register(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        auth()->login($user = new User($user));

        $user->save();

        return [
            'user' => new UserResource($user),
            'token' => $user->createToken($user->email)->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ['message' =>  __('auth.logout')];
    }
}
