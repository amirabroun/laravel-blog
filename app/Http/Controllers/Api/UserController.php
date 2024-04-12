<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserResumeRequest;

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
                'users' => UserResource::collection($users)
            ],
        ];
    }

    public function show(string $uuid)
    {
        $user = User::uuid($uuid)
            ->withCount('followers as followers_count')
            ->withCount('followings as followings_count')
            ->with([
                'media',
                'posts' => fn ($query) => $query->latest()->with('media'),
                'followings' => fn ($query) => $query->with('media'),
                'followers' => fn ($query) => $query->with('media'),
            ])->first();

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

    public function updateUserProfile(Request $request, string $uuid)
    {
        $data = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'username' => 'string',
        ]);

        if (auth()->guest() || auth()->user()->uuid != $uuid) {
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

        return  [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => 'user profile updated',
        ];
    }

    public function addUserAvatar(Request $request, string $uuid)
    {
        $request->validate([
            'avatar' => 'required|file|mimetypes:image/jpeg,image/png,image/jpg',
        ]);

        $user = User::query()->where('uuid', $uuid)->with('media')->first();

        if (!$user) {
            return [
                'status' => self::HTTP_STATUS_CODE['not_found'],
                'message' => __('auth.user_not_found.uuid'),
            ];
        }

        if (auth()->guest() || auth()->user()->uuid != $uuid) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.no_update_access')
            ];
        }

        if ($request->file('avatar', false)) {
            $user->addMediaFromRequest('avatar')->usingFileName(
                $request->file('avatar')->hashName()
            )->toMediaCollection('avatar');
        }

        return  [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => 'avatar added',
        ];
    }

    public function deleteUserAvatar($uuid)
    {
        $user = User::query()->where('uuid', $uuid)->with('media')->first();

        if (!$user) {
            return [
                'status' => self::HTTP_STATUS_CODE['not_found'],
                'message' => __('auth.user_not_found.uuid'),
            ];
        }

        if (auth()->guest() || auth()->user()->uuid != $uuid) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.no_update_access')
            ];
        }

        if ($user->media->count() == 0) {
            return [
                'status' => self::HTTP_STATUS_CODE['bad_request'],
                'message' => 'user does not have avatar'
            ];
        }

        $user->media->where('collection_name', 'avatar')
            ->map(fn ($image) => $image->forceDelete());

        return  [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => 'user profile avatar deleted',
        ];
    }

    public function checkUniqueUsername(Request $request)
    {
        $request->validate([
            'username' => Rule::unique('users', 'username')->ignore(auth()->id()),
        ], [
            'username' => 'username is not available :)'
        ]);

        return  [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => 'username is available',
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
            'data' => ['user' => UserResource::make($user)],
        ];
    }

    public function getUserPosts(string $uuid)
    {
        $user = User::uuid($uuid)
            ->withCount('followers as followers_count')
            ->withCount('followings as followings_count')
            ->with([
                'followings' => fn ($query) => $query->inRandomOrder()->with('media')->take(mt_rand(2, 5)),
                'posts' => fn ($query) => $query->latest()->with('media'), 'media'
            ])->first();

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
        $user = User::uuid($uuid)->first();

        app()->auth_followings->get($user->id) == null
            ? auth()->user()->follow($user)
            : auth()->user()->unfollow($user);

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' =>  null,
        ];
    }
}
