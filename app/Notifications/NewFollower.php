<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;

class NewFollower extends Notification
{
    use Queueable;

    public function __construct(private $followerId)
    {
        //
    }

    public function toArray(object $notifiable): array
    {
        return [
            'follower_id' => $this->followerId,
        ];
    }
    
    public static function typeView(): string
    {
        return 'new_follower';
    }

    public static function text(User $follower)
    {
        $text = $follower->full_name;
        
        return $text .= ' followed you! :)';
    }
}
