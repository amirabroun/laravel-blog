<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification as BaseNotification;

abstract class Notification extends BaseNotification
{
    use Queueable;

    public function via()
    {
        return ['database'];
    }

    abstract public static function typeView(): string;
}
