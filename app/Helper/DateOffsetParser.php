<?php

namespace App\Helper;

class DateOffsetParser
{
    private $relativeDayPattern = '/(?P<relative>فردا|پس[\s]?فردا)/u';
    private $numericDayPattern = '/(?P<number>\d{1,2})\s+(روز|هفته|ماه)\s+دیگه/u';
    private $hourPattern = '/\b(صبح|ظهر|عصر|شب|امشب)?\s*(?:ساعت\s*)?(\d{1,2})\b\s*(صبح|ظهر|عصر|شب|امشب)?/u';

    private $weekdays = [
        'یکشنبه' => 0,
        'یک شنبه' => 0,
        '1شنبه' => 0,

        'دوشنبه' => 1,
        'دو شنبه' => 1,
        '2شنبه' => 1,

        'سه‌شنبه' => 2,
        'سه شنبه' => 2,
        'سه‌ شنبه' => 2,
        '3شنبه' => 2,
        '3 شنبه' => 2,

        'چهارشنبه' => 3,
        'چهار شنبه' => 3,
        '4شنبه' => 3,
        '4 شنبه' => 3,

        'پنجشنبه' => 4,
        'پنج شنبه' => 4,
        '5شنبه' => 4,
        '5 شنبه' => 4,

        'جمعه' => 5,

        'شنبه' => 6,
        '7شنبه' => 6,
        '7 شنبه' => 6,
    ];

    private $relativeDays = [
        'فردا' => 1,
        'پس‌فردا' => 2,
        'پسفردا' => 2,
        'پسفردا' => 2,
        'پس فردا' => 2,
    ];

    private function prepareTextForAnalize($text)
    {
        $text = normalizeSpaces($text);
        $text = replacePersianHalfSpace($text);
        $text = changeToEnglish($text);
        $text = replacePersianWordsWithNumbers($text);
        $text = preg_replace('/[،,:؛.!؟"]/u', '', $text);

        return trim($text);
    }

    public function removeTimeFromText($text)
    {
        $text = $this->removeNumericDay($this->prepareTextForAnalize($text));

        $text = $this->removeWeekday($text);

        $text = $this->removeHour($text);

        $text = $this->removeRelativeDay($text);

        return $this->prepareTextForAnalize($text);
    }

    private function removeNumericDay($text)
    {
        return preg_replace($this->numericDayPattern, '', $text);
    }

    private function removeHour($text)
    {
        return preg_replace($this->hourPattern, '', $text);
    }

    private function removeWeekday($text)
    {
        foreach ($this->weekdays as $day => $weekdayNumber) {
            if (preg_match("/\b$day\b/u", $text)) {
                $text = preg_replace("/\b$day\b/u", '', $text);
            }
        }

        return $text;
    }

    private function removeRelativeDay($text)
    {
        return preg_replace($this->relativeDayPattern, '', $text);
    }

    /**
     * If time is not given return false
     */
    public function resolveDateTimeFromText($text)
    {
        $text = $this->removeNumericDay($this->prepareTextForAnalize($text));

        $daysToAdd = 0;
        $hourToAdd = $this->extractHour($text);

        if ($weekdayOffset = $this->extractWeekday($text)) {
            $daysToAdd += $weekdayOffset;
        }

        if ($relativeOffset = $this->extractRelativeDay($text)) {
            $daysToAdd += $relativeOffset;
        }

        if ($numberOffset = $this->extractNumericDay($text)) {
            $daysToAdd += $numberOffset;
        }

        if ($daysToAdd == 0 && $hourToAdd == 0) {
            return false;
        }

        return now()->addDays($daysToAdd)->setTime($hourToAdd ?: 8, 0);
    }

    private function extractHour($text)
    {
        if (!preg_match($this->hourPattern, $text, $matches)) {
            return 0;
        }

        $hour = (int) $matches[2];

        foreach ($matches as $match) {
            if ($match === 'عصر' || $match === 'شب' || $match === 'امشب') {
                if ($hour < 12) {
                    $hour += 12;
                }

                break;
            }
        }

        return $hour;
    }

    private function extractWeekday($text)
    {
        foreach ($this->weekdays as $day => $weekdayNumber) {
            $normalizedDay = str_replace(['‌', ' '], '\s*', $day);
            if (preg_match("/\b$normalizedDay\b/u", $text)) {
                $diff = $weekdayNumber - now()->dayOfWeek;

                if ($diff < 0) {
                    $diff += 7;
                }

                return $diff;
            }
        }

        return 0;
    }

    private function extractRelativeDay($text)
    {
        if (!preg_match($this->relativeDayPattern, $text, $matches)) {
            return 0;
        }

        return $this->relativeDays[$matches['relative']] ?? 0;
    }

    private function extractNumericDay($text)
    {
        if (!preg_match($this->numericDayPattern, $text, $matches)) {
            return 0;
        }

        $number = $matches['number'];
        $unit = $matches[2];

        if ($unit === 'هفته') {
            return (int) $number * 7;
        }

        if ($unit === 'ماه') {
            return (int) $number * 30;
        }

        return (int) $number;
    }
}
