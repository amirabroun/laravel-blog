<?php

use Illuminate\Support\Facades\Redis;
use Telegram\Bot\Laravel\Facades\Telegram;

if (!function_exists('telegramCache')) {
    function telegramCache(string $key, $value = false)
    {
        if (!$value) {
            return Redis::get('telegram_cache.' . $key);
        }

        Redis::set('telegram_cache.' . $key, $value);
    }
}


if (!function_exists('telegram')) {
    function telegram()
    {
        return Telegram::bot()->getUpdates();
    }
}
