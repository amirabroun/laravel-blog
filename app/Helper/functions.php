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

if (!function_exists('replacePersianWordsWithNumbers')) {
    function replacePersianWordsWithNumbers($text)
    {
        $persianNumbers = [
            'یک '  => '1 ',
            'دو '  => '2 ',
            'سه '  => '3 ',
            'چهار '  => '4 ',
            'پنج '  => '5 ',
            'شش '  => '6 ',
            'هفت '  => '7 ',
            'هشت '  => '8 ',
            'نه '  => '9 ',
            'ده '  => '10 ',
            'یازده '  => '11 ',
            'دوازده '  => '12 ',
            'سیزده '  => '13 ',
            'چهارده '  => '14 ',
            'پانزده '  => '15 ',
            'شانزده '  => '16 ',
            'هفده '  => '17 ',
            'هجده '  => '18 ',
            'نوزده '  => '19 ',
            'بیست '  => '20 ',
            'بیست و یک '  => '21 ',
            'بیست و دو '  => '22 ',
            'بیست و سه '  => '23 ',
            'بیست و چهار '  => '24 ',
            'بیست و پنج '  => '25 ',
            'بیست و شش '  => '26 ',
            'بیست و هفت '  => '27 ',
            'بیست و هشت '  => '28 ',
            'بیست و نه '  => '29 ',
            'سی '  => '30 ',
            'سی و یک '  => '31 ',
            'سی و دو '  => '32 ',
            'سی و سه '  => '33 ',
            'سی و چهار '  => '34 ',
            'سی و پنج '  => '35 ',
        ];

        foreach ($persianNumbers as $word => $num) {
            // جایگزینی فقط در صورتی که بعد از عدد حرف فارسی نباشد
            $text = preg_replace('/\b' . preg_quote($word, '/') . '\b/u', $num, $text);
        }

        return $text;
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
