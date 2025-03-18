<?php

namespace App\Helper;

class DateOffsetParser
{
    public function removeTimeFromText($text)
    {
        $text = replacePersianWordsWithNumbers(changeToEnglish($text));

        $text = $this->extractOrRemoveNumericDay($text, true);

        $text = $this->extractOrRemoveHour($text, true);

        $text = $this->extractOrRemoveWeekday($text, true);

        $text = $this->extractOrRemoveRelativeDay($text, true);

        $text = preg_replace('/[،,:؛.!؟"]/u', '', $text);

        return trim($text);
    }

    /**
     * If time is not given return false
     */
    public function resolveDateTimeFromText($text)
    {
        $text = replacePersianWordsWithNumbers(changeToEnglish($text));

        $daysToAdd = 0;
        $hourToAdd = $this->extractOrRemoveHour($text);

        if ($weekdayOffset = $this->extractOrRemoveWeekday($text)) {
            $daysToAdd += $weekdayOffset;
        }

        if ($relativeOffset = $this->extractOrRemoveRelativeDay($text)) {
            $daysToAdd += $relativeOffset;
        }

        if ($numberOffset = $this->extractOrRemoveNumericDay($text)) {
            $daysToAdd += $numberOffset;
        }

        if ($daysToAdd == 0 && $hourToAdd == 0) {
            return false;
        }

        return now()->addDays($daysToAdd)->setTime($hourToAdd, 0);
    }

    private function extractOrRemoveHour($text, $removeTime = false)
    {
        $pattern = '/\b(صبح|ظهر|عصر|شب|امشب)?\s*(?:ساعت\s*)?(\d{1,2})\b\s*(صبح|ظهر|عصر|شب|امشب)?/u';

        if (!preg_match($pattern, $text, $matches)) {
            return $removeTime ? $text : 0;
        }

        if ($removeTime) {
            return preg_replace($pattern, '', $text);
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

    private function extractOrRemoveWeekday($text, $removeTime = false)
    {
        $weekdays = [
            'یکشنبه' => 0,
            'دوشنبه' => 1,
            'سه‌شنبه' => 2,
            'چهارشنبه' => 3,
            'پنجشنبه' => 4,
            'جمعه' => 5,
            'شنبه' => 6,
        ];

        foreach ($weekdays as $day => $weekdayNumber) {
            if (preg_match("/\b$day\b/u", $text)) {
                $diff = $weekdayNumber - now()->dayOfWeek;

                if ($diff < 0) {
                    $diff += 7;
                }

                if ($removeTime) {
                    return preg_replace("/\b$day\b/u", '', $text);
                }

                return $diff;
            }
        }

        return $removeTime ? $text : 0;
    }

    private function extractOrRemoveRelativeDay($text, $removeTime = false)
    {
        $pattern = '/(?P<relative>فردا|پس[\s]?فردا)/u';

        if (!preg_match($pattern, $text, $matches)) {
            return $removeTime ? $text : 0;
        }

        if ($removeTime) {
            return preg_replace($pattern, '', $text);
        }

        $relativeDays = [
            'فردا' => 1,
            'پس‌فردا' => 2,
            'پس‌ فردا' => 2,
        ];

        return $relativeDays[$matches['relative']] ?? 0;
    }

    private function extractOrRemoveNumericDay($text, $removeTime = false)
    {
        $pattern = '/(?P<number>\d{1,2})\s+(روز|هفته|ماه)\s+دیگه/u';

        if (!preg_match($pattern, $text, $matches)) {
            if ($removeTime) {
                return $text;
            }

            return 0;
        }

        if ($removeTime) {
            $text = preg_replace($pattern, '', $text);

            return $text;
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
