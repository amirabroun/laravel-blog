<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->resource->toArray();

        $data['avatar'] = $this->resource->avatar;

        if (auth()->check() && auth()->id() != $this->id) {
            $data = $this->appendFollowStatus($data);
        }

        return $data;
    }

    function appendFollowStatus($data)
    {
        $following = app()->auth_followings->get($this->id);

        $data['auth_followed_at'] = $following == null ? null : $following['auth_followed_at'];
        $data['follow_accepted_at'] = $following == null ? null : $following['follow_accepted_at'];

        return $data;
    }
}
