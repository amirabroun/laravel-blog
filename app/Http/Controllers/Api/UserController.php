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
        if (!$this->authUser->isAdmin()) {
            return $this->apiResource(null, [
                'status' => self::HTTP_STATUS_CODE['unprocessable_entity'],
                'message' => __('auth.no_access'),
            ]);
        }

        $users = new UserCollection(User::paginate(20));

        return $this->apiResource($users, [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('user.users'),
        ]);
    }

    public function show(string $uuid)
    {
        $user = User::uuid($uuid)->with(['resume', 'media'])->first();

        return $this->apiResource(new UserProfileResource($user), [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('user.profile'),
        ]);
    }

    public function updateUserProfile(Request $request, string $uuid)
    {
        $data = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'required|email',
        ]);

        if ($this->authUser->uuid != $uuid) {
            return $this->apiResource(null, [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.no_update_access')
            ]);
        }

        if (!$this->authUser->update($data)) {
            return $this->apiResource(null,  [
                'status' => self::HTTP_STATUS_CODE['server_error'],
                'message' => __('app.server_error'),
            ]);
        }

        return $this->apiResource(null,  ['status' => self::HTTP_STATUS_CODE['success'], 'message' => 'update user profile']);
    }

    public function updateUserResume(UpdateUserResumeRequest $request, $uuid)
    {
        $data = $request->validated();

        if ($this->authUser->uuid != $uuid) {
            return $this->apiResource(null, [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.no_update_access')
            ]);
        }

        $status = $this->authUser->resume()->update([
            'summary' => $data['summary'] ?? null,
            'experiences' => $data['experiences'] ?? null,
            'skills' => $data['skills'] ?? null,
            'education' => $data['education'] ?? null,
        ]);

        if (!$status) {
            return $this->apiResource(null,  [
                'status' => self::HTTP_STATUS_CODE['server_error'],
                'message' => __('app.server_error'),
            ]);
        };

        return $this->apiResource(new UserResource($this->authUser), [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('user.profile_update_successfully'),
        ]);
    }
}
