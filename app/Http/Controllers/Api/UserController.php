<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserResumeRequest;
use App\Http\Resources\{UserProfileResource, UserCollection, UserResource};

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->paginate($perPage = 20);
        $countOfUsers = User::query()->count();
        $lastPage = ceil($countOfUsers / $perPage);

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('user.users'),
            'data' => [
                'lastPage' => $lastPage,
                'users' => new UserCollection($users)
            ],
        ];
    }

    public function show(string $uuid)
    {
        $user = User::uuid($uuid)->with(['resume', 'media'])->first();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('user.profile'),
            'data' => new UserProfileResource($user),
        ];
    }

    public function updateUserProfile(Request $request, string $uuid)
    {
        $data = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'required|email',
        ]);

        if ($this->authUser->uuid != $uuid) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.no_update_access')
            ];
        }

        if (!$this->authUser->update($data)) {
            return [
                'status' => self::HTTP_STATUS_CODE['server_error'],
                'message' => __('app.server_error'),
            ];
        }

        return  [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => 'update user profile',
            'data' => ['user' => new UserResource($this->authUser)],
        ];
    }

    public function updateUserResume(UpdateUserResumeRequest $request, $uuid)
    {
        $data = $request->validated();

        if ($this->authUser->uuid != $uuid) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.no_update_access')
            ];
        }

        $status = $this->authUser->resume()->update([
            'summary' => $data['summary'] ?? null,
            'experiences' => $data['experiences'] ?? null,
            'skills' => $data['skills'] ?? null,
            'education' => $data['education'] ?? null,
        ]);

        if (!$status) {
            return  [
                'status' => self::HTTP_STATUS_CODE['server_error'],
                'message' => __('app.server_error'),
            ];
        };

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('user.profile_update_successfully'),
            'data' => ['user' => new UserResource($this->authUser)],
        ];
    }
}
