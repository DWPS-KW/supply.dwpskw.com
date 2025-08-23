<?php

namespace App\Libraries;

use DateTime;

class myFuns
{
    public function decorateDate(string $date): string
    {
        return date("d-M-Y", strtotime($date));
    }

    public function decorateDateNo(string $date): string
    {
        return date("d-m-Y", strtotime($date));
    }

    public function decorateTime(string $time): string
    {
        return date('H:i', strtotime($time));
    }

    public function getEvalDegree($evalVal): string
    {
        if (is_null($evalVal)) {
            return '';
        }
        if ($evalVal >= 90) return "ممتاز";
        if ($evalVal >= 80) return "جيد جدا";
        if ($evalVal >= 70) return "جيد";
        if ($evalVal >= 60) return "مقبول";
        return "ضعيف";
    }

    public function cleanFileName(string $file_name): string
    {
        $file_name = preg_replace("/[^a-zA-Z0-9_.-]/", "", $file_name);
        $file_name = preg_replace("/[\\s_]/", "-", $file_name);
        return $file_name;
    }

    public function getMonthName($month, string $lang = 'en'): string
    {
        $months_en_to_num = [
            'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
            'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
            'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
        ];

        $months_ar_to_num = [
            'يناير' => 1, 'فبراير' => 2, 'مارس' => 3, 'أبريل' => 4,
            'مايو' => 5, 'يونيو' => 6, 'يوليو' => 7, 'أغسطس' => 8,
            'سبتمبر' => 9, 'أكتوبر' => 10, 'نوفمبر' => 11, 'ديسمبر' => 12
        ];

        $months = [
            'en' => [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ],
            'ar' => [
                1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
                5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
                9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
            ]
        ];

        $monthNumber = null;

        if (is_int($month)) {
            $monthNumber = $month;
        } elseif (is_string($month)) {
            $lowerCaseMonth = strtolower($month);
            if (isset($months_en_to_num[$lowerCaseMonth])) {
                $monthNumber = $months_en_to_num[$lowerCaseMonth];
            }
            else if (isset($months_ar_to_num[$month])) {
                $monthNumber = $months_ar_to_num[$month];
            }
            else {
                $intMonth = (int) $month;
                if ($intMonth >= 1 && $intMonth <= 12) {
                    $monthNumber = $intMonth;
                }
            }
        }

        $lang = strtolower($lang);

        if ($monthNumber === null || !isset($months[$lang]) || !isset($months[$lang][$monthNumber])) {
            return '';
        }

        return $months[$lang][$monthNumber];
    }

    public function add_time(string $time1, string $time2): string
    {
        $seconds1 = $this->timeToSeconds($time1);
        $seconds2 = $this->timeToSeconds($time2);
        $totalSeconds = $seconds1 + $seconds2;
        return $this->secondsToTime($totalSeconds);
    }

    private function timeToSeconds(string $time): int
    {
        $parts = explode(':', $time);
        $hours = (int)($parts[0] ?? 0);
        $minutes = (int)($parts[1] ?? 0);
        $seconds = (int)($parts[2] ?? 0);
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    public function secondsToTime(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function setEmptyToNull($value)
    {
        return (empty($value) && !is_numeric($value)) ? null : $value;
    }
    public function reverseDateToSeeArabic(?string $date): string
    {
        if ($date === null) {
            return '';
        }
        $dateParts = explode('-', $date);
        if (count($dateParts) === 3) {
            return implode('-', array_reverse($dateParts));
        }
        return $date; // Return original date if format is unexpected
    }
}
