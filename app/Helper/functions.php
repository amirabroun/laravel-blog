<?php

use Illuminate\Support\Facades\Redis;

if (!function_exists('telegramCache')) {
    function telegramCache(string $key, $value = false)
    {
        $key = 'telegram_cache.' . $key;

        if (is_null($value)) {
            return Redis::del($key);
        }

        if ($value === false) {
            return Redis::get($key);
        }

        Redis::set($key, $value);
    }
}

if (!function_exists('telegramUserState')) {
    function telegramUserState(string $telegramUserId, $state = false)
    {
        $key = 'telegram.user_id.' . $telegramUserId;

        if (is_null($state)) {
            return telegramCache($key, null);
        }

        if ($state === false) {
            return telegramCache($key);
        }

        telegramCache($key, $state);
    }
}

if (!function_exists('isCommandMatched')) {
    function isCommandMatched($message, $keywords)
    {
        foreach ($keywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                return true;
            }
        }
    
        return false;
    }
}
