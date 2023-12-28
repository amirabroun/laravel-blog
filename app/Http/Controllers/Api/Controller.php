<?php

namespace App\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\Model;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const HTTP_STATUS_CODE = [
        'success' => 200,
        'bad_request' => 400,
        'unauthorized' => 401,
        'forbidden' => 403,
        'not_found' => 404,
        'unprocessable_entity' => 422,
        'server_error' => 500,
    ];

    public function __construct()
    {
        request()->bearerToken() == null ?:
            $this->middleware('auth:sanctum');
    }

    protected function setAuthUserFollowStatus(Collection $followables)
    {
        if (!auth()->check()) return $followables;

        $userFollowings = auth()->user()->followingsPivot()->get();

        $followables->map(function (Model $followable) use ($userFollowings) {
            $follow = $userFollowings
                ->where('followable_type', $followable->getMorphClass())
                ->where('followable_id', $followable->getKey())
                ->first();

            $followable
                ->setAttribute('auth_followed_at', $follow?->created_at)
                ->setAttribute('follow_accepted_at', $follow?->accepted_at);
        });

        return $followables;
    }
}
