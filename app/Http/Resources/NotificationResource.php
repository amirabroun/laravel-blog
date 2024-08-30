<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Notifications\NewFollower;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type::typeView(),
            'text' => $this->text,
            'created_at' => $this->created_at,
        ];
    }

    public static function collection($resource, $setText = true)
    {
        $collection = parent::collection($resource);

        return $setText ? self::setText($collection) : $collection;
    }

    public static function setText($unreadNotifications)
    {
        $newUsersFollowersIds = [];
        foreach ($unreadNotifications as $notification) {
            if ($notification->type == NewFollower::class) {
                $newUsersFollowersIds[] = $notification['data']['follower_id'];
            }
        }

        if (count($newUsersFollowersIds)) {
            $newUsersFollowers = User::query()->whereIn('id', $newUsersFollowersIds)->get();

            foreach ($unreadNotifications as $notification) {
                if ($notification->type == NewFollower::class) {
                    $follower = $newUsersFollowers->find($notification['data']['follower_id']);

                    $text = NewFollower::text($follower);

                    $notification->text = $text;
                }
            }
        }

        return $unreadNotifications;
    }
}
