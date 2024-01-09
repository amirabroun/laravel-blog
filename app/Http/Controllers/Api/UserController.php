<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $user = User::uuid($uuid)
            ->with(['resume', 'media', 'followers'])
            ->first()
            ->append('followed_by_auth_user');

        if (!$user) {
            return [
                'status' => self::HTTP_STATUS_CODE['not_found'],
                'message' => __('auth.user_not_found.uuid'),
            ];
        }

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
            'username' => ['required', 'string', Rule::unique('users')->ignore($uuid, 'uuid')],
        ]);

        if (auth()->user()->uuid != $uuid) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.no_update_access')
            ];
        }

        if (!auth()->user()->update($data)) {
            return [
                'status' => self::HTTP_STATUS_CODE['server_error'],
                'message' => __('app.server_error'),
            ];
        }

        $user = User::uuid($uuid)->with(['resume', 'media'])->first();

        return  [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => 'update user profile',
            'data' => ['user' => new UserProfileResource($user)],
        ];
    }

    public function updateUserResume(UpdateUserResumeRequest $request, $uuid)
    {
        $data = $request->validated();

        $request::validateContact($data);

        if (auth()->user()->uuid != $uuid) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.no_update_access')
            ];
        }

        $status = auth()->user()->resume()->update([
            'summary' => $data['summary'] ?? null,
            'experiences' => $data['experiences'] ?? null,
            'skills' => $data['skills'] ?? null,
            'education' => $data['education'] ?? null,
            'contact' => $data['contact'] ?? null,
        ]);

        if (!$status) {
            return  [
                'status' => self::HTTP_STATUS_CODE['server_error'],
                'message' => __('app.server_error'),
            ];
        };

        $user = User::uuid($uuid)->with(['resume', 'media'])->first();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('user.profile_update_successfully'),
            'data' => ['user' => new UserProfileResource($user)],
        ];
    }

    public function getUserPosts(string $uuid)
    {
        $user = User::uuid($uuid)->with(['posts' => fn ($query) => $query->with('media'), 'media'])->first();

        if (!$user) {
            return [
                'status' => self::HTTP_STATUS_CODE['not_found'],
                'message' => __('auth.user_not_found.uuid'),
            ];
        }

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('user.profile'),
            'data' => ['user' => UserResource::make($user)],
        ];
    }

    public function toggleFollow($uuid)
    {
        auth()->user()->toggleFollow(User::uuid($uuid)->first());

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' =>  null,
        ];
    }
}
