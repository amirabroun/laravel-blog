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
        if (!auth()->check()) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.unauthorized'),
            ];
        }

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('auth.successfully_auth'),
            'data' => ['user' => UserResource::make(auth()->user())],
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        $user = User::query()->where('username', $request->username)->first();
        if (!$user) {
            return [
                'status' => self::HTTP_STATUS_CODE['not_found'],
                'message' => __('auth.user_not_found.username'),
            ];
        }

        if (!Hash::check($request->password, $user->password)) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.password'),
            ];
        }

        $user->token = $user->createToken($user->username)->plainTextToken;
        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('auth.login'),
            'data' => ['user' => new UserResource($user)],
        ];
    }

    public function register(Request $request)
    {
        $user = $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|confirmed',
        ]);

        auth()->login($user = new User($user));

        if (!$user->save()) {
            return [
                'status' => self::HTTP_STATUS_CODE['server_error'],
                'message' => __('app.server_error'),
            ];
        };

        $user->token = $user->createToken($user->username)->plainTextToken;

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('auth.register'),
            'data' => ['user' => new UserResource($user)],
        ];
    }

    public function logout()
    {
        if (!auth()->user()->tokens()->delete()) {
            return [
                'status' => self::HTTP_STATUS_CODE['server_error'],
                'message' => __('app.server_error'),
            ];
        };

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' =>  __('auth.logout')
        ];
    }
}
