<?php

use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;
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
    function telegramUserState($telegramUserId, $state = false)
    {
        $key = 'telegram.user_id.state.' . $telegramUserId;

        if (is_null($state)) {
            return telegramCache($key, null);
        }

        if ($state === false) {
            return telegramCache($key);
        }

        telegramCache($key, $state);
    }
}

if (!function_exists('telegramAuthUser')) {
    function telegramAuthUser($telegramUserId, $localId = false)
    {
        $key = 'telegram.user_id.local_id.' . $telegramUserId;

        if (is_null($localId)) {
            return telegramCache($key, null);
        }

        if ($localId === false) {
            return telegramCache($key);
        }

        telegramCache($key, $localId);
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

if (!function_exists('toJalali')) {
    function toJalali(string $date, $format = 'Y/m/d')
    {
        $time = is_int($date) ? $date : strtotime($date);

        $time = Jalalian::forge($time)->format($format);

        return changeToPersian($time);
    }
}

if (!function_exists('changeToPersian')) {
    function changeToPersian($text)
    {
        if (is_null($text) || $text == '') {
            return $text;
        }

        $find = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '%');
        $replace = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٪');

        return Str::replace($find, $replace, $text);
    }
}

if (!function_exists('changeToEnglish')) {
    function changeToEnglish($text)
    {
        if (is_null($text) || $text == '') {
            return $text;
        }

        $find = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٪');
        $replace = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '%');

        return Str::replace($find, $replace, $text);
    }
}

if (!function_exists('diffForHumans')) {
    function diffForHumans(string $date)
    {
        $time = is_int($date) ? $date : strtotime($date);

        $string = Jalalian::forge($time)->ago();

        // If $date for after. for past ago function handle
        if ($time > time()) {
            $string .= 'بعد';
        }

        return changeToPersian($string);
    }
}
