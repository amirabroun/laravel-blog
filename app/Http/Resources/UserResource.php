<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'username' => $this->username,
            'address' => $this->address,
            'is_admin' => $this->is_admin == 1,
            'avatar' => $this->avatar,
            'followers_count' => $this->followers_count,
            'followings_count' => $this->followings_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'followings' => FollowableResource::collection($this->whenLoaded('followings')),
            'followers' => FollowableResource::collection($this->whenLoaded('followers')),
            'media' => $this->media,
        ];

        !$this->token ?: $data['token'] = $this->token;

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
