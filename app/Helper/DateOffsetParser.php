<?php

namespace App\Helper;

class DateOffsetParser
{
    public static function calculateOffset($text)
    {
        $self = new static;
        
        $text = changeToEnglish($text);

        $daysToAdd = 0;
        $timeToAdd = null;

        if ($weekdayOffset = $self->getWeekdayOffset($text)) {
            $daysToAdd += $weekdayOffset;
        }

        if ($relativeOffset = $self->getRelativeDayOffset($text)) {
            $daysToAdd += $relativeOffset;
        }

        if ($numberOffset = $self->getNumericDayOffset($text)) {
            $daysToAdd += $numberOffset;
        }

        $date = now()->addDays($daysToAdd);

        if ($timeOffset = $self->getTimeOffset($text)) {
            $timeToAdd = $timeOffset;
        }

        if ($timeToAdd) {
            $date = $date->setTime($timeToAdd['hour'], $timeToAdd['minute']);
        }

        return $date;
    }

    private function getTimeOffset($text)
    {
        if (preg_match('/امشب|امروز/u', $text)) {
            $text = str_replace('امشب', 'امروز شب', $text); 
        }

        $patterns = [
            '/(\d{1,2})\s*(?:ساعت|)(?:\s*(صبح|ظهر|عصر|شب))?/u', 
            '/(\d{1,2})\s*(?:[:،])\s*(\d{1,2})/u' 
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $hour = (int) $matches[1];

                if (isset($matches[2]) && ($matches[2] === 'شب' || $matches[2] === 'عصر') && $hour < 12) {
                    $hour += 12;
                }

                $minute = isset($matches[2]) ? (int) $matches[2] : 0;

                return ['hour' => $hour, 'minute' => $minute];
            }
        }

        return null;
    }

    private function getWeekdayOffset($text)
    {
        $weekdays = [
            'شنبه' => 6,
            'یکشنبه' => 0,
            'دوشنبه' => 1,
            'سه‌شنبه' => 2,
            'چهارشنبه' => 3,
            'پنجشنبه' => 4,
            'جمعه' => 5,
        ];

        foreach ($weekdays as $day => $weekdayNumber) {
            if (preg_match("/\b$day\b/u", $text)) {
                $diff = $weekdayNumber - now()->dayOfWeek;
                if ($diff < 0) {
                    $diff += 7;
                }

                if (preg_match('/هفته\s*بعد/', $text)) {
                    $diff += 7;
                } elseif (preg_match('/دو\s*هفته\s*بعد/', $text)) {
                    $diff += 14;
                }

                return $diff;
            }
        }

        return 0;
    }

    private function getRelativeDayOffset($text)
    {
        $pattern = '/(?P<relative>فردا|پس‌فردا|پس‌|امروز|امشب|دیشب)/u';

        if (!preg_match($pattern, $text, $matches)) {
            return 0;
        }

        $relativeDays = [
            'امروز' => 0,
            'امشب' => 0,
            'فردا' => 1,
            'پس‌فردا' => 2,
            'پس‌ فردا' => 2,
        ];

        return $relativeDays[$matches['relative']] ?? 0;
    }

    private function getNumericDayOffset($text)
    {
        $persianNumbers = [
            'یک' => 1,
            'دو' => 2,
            'سه' => 3,
            'چهار' => 4,
            'پنج' => 5,
            'شش' => 6,
            'هفت' => 7,
            'هشت' => 8,
            'نه' => 9,
            'ده' => 10,
            'یازده' => 11,
            'دوازده' => 12,
            'سیزده' => 13,
            'چهارده' => 14,
            'پانزده' => 15,
            'شانزده' => 16,
            'هفده' => 17,
            'هجده' => 18,
            'نوزده' => 19,
            'بیست' => 20,
            'بیست و یک' => 21,
            'بیست و دو' => 22,
            'بیست و سه' => 23,
            'بیست و چهار' => 24,
            'بیست و پنج' => 25,
            'بیست و شش' => 26,
            'بیست و هفت' => 27,
            'بیست و هشت' => 28,
            'بیست و نه' => 29,
            'سی' => 30,
            'سی و یک' => 31,
            'سی و دو' => 32,
            'سی و سه' => 33,
            'سی و چهار' => 34,
            'سی و پنج' => 35
        ];

        $pattern = '/(?P<number>[0-9]{1,2}|' . implode('|', array_keys($persianNumbers)) . ')\s+(روز|هفته)\s+دیگه/u';

        if (preg_match($pattern, $text, $matches)) {
            $number = $matches['number'];
            $unit = $matches[2];

            if ($unit === 'هفته') {
                return is_numeric($number) ? (int) $number * 7 : ($persianNumbers[$number] ?? 0) * 7;
            }

            return is_numeric($number) ? (int) $number : ($persianNumbers[$number] ?? 0);
        }

        return 0;
    }
}
