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
        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('auth.successfully_auth'),
            'data' => new UserResource($this->authUser)
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::query()->where('email', $request->email)->first();
        if (!$user) {
            return [
                'status' => self::HTTP_STATUS_CODE['not_found'],
                'message' => __('auth.user_not_found.email'),
            ];
        }

        if (!Hash::check($request->password, $user->password)) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.password'),
            ];
        }

        $user->token = $user->createToken($user->email)->plainTextToken;
        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('auth.login'),
            'data' => new UserResource($user),
        ];
    }

    public function register(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        auth()->login($user = new User($user));

        if (!$user->save()) {
            return [
                'status' => self::HTTP_STATUS_CODE['server_error'],
                'message' => __('app.server_error'),
            ];
        };

        $user->token = $user->createToken($user->email)->plainTextToken;

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('auth.register'),
            'data' => new UserResource($user),
        ];
    }

    public function logout(Request $request)
    {
        if (!$request->user()->currentAccessToken()->delete()) {
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
