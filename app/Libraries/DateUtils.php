<?php

namespace App\Libraries;

use DateTime;
use Exception;

class DateUtils
{
    /**
     * Counts the number of working days (excluding Friday and Saturday for Kuwait)
     * between two given dates (inclusive).
     *
     * @param string $start_date_str Start date in 'YYYY-MM-DD' format.
     * @param string $end_date_str End date in 'YYYY-MM-DD' format.
     * @return int The number of working days.
     */
    public function countWorkingDays(string $start_date_str, string $end_date_str): int
    {
        $startDate = new \DateTime($start_date_str);
        $endDate = new \DateTime($end_date_str);
        $endDate->modify('+1 day'); // Include the end date in the iteration

        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);

        $count = 0;
        foreach ($dateRange as $date) {
            $day = (int)$date->format('N'); // 1 = Monday, ..., 5 = Friday, 6 = Saturday, 7 = Sunday
            
            // Correctly exclude Friday (5) and Saturday (6) for Kuwait's weekend
            if ($day == 5 || $day == 6) {
                continue; // Skip weekend days
            }
            $count++;
        }

        return $count;
    }

    /**
     * Counts the number of days between two dates, with an option to exclude weekends.
     * Weekends are Saturday (6) and Sunday (7) in this context.
     *
     * @param string $start Start date string.
     * @param string $end End date string.
     * @param bool $excludeWeekends Whether to exclude weekend days.
     * @return int The number of days.
     */
    public static function countDaysBetween(string $start, string $end, bool $excludeWeekends = false): int
    {
        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);
        $endDate->modify('+1 day'); // Include the end date

        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);

        $count = 0;
        foreach ($dateRange as $date) {
            if ($excludeWeekends) {
                $day = (int)$date->format('N'); // 6 = Saturday, 7 = Sunday
                if ($day >= 6) continue;
            }
            $count++;
        }

        return $count;
    }

    /**
     * Formats year, month, and day into a 'YYYY-MM-DD' string.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return string
     */
    public static function formatDate(int $year, int $month, int $day): string
    {
        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    /**
     * Formats month and year into a 'YYYY-MM-01' string.
     *
     * @param int $month
     * @param int $year
     * @return string
     */
    public static function formatDateForMonthName(int $month, int $year): string
    {
        return sprintf('%04d-%02d-01', $year, $month);
    }

    /**
     * Gets the number of days in a given month and year.
     *
     * @param int $month
     * @param int $year
     * @return int
     */
    public static function getDaysInMonth(int $month, int $year): int
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * Converts a total number of seconds into an 'HH:MM:SS' string.
     *
     * @param int $seconds Total seconds.
     * @return string Formatted time string.
     */
    public static function secondsToHMS(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }

    /**
     * Converts a time string (HH:MM or HH:MM:SS) into total seconds.
     *
     * @param string $hms Time string (e.g., '01:30', '26:00', '01:30:45').
     * @return int Total seconds. Returns 0 if the format is invalid or cannot be parsed.
     */
    public static function hmsToSeconds(string $hms): int
    {
        $parts = explode(':', $hms);
        $totalSeconds = 0;

        if (count($parts) === 3) { // Format is HH:MM:SS
            $totalSeconds = (int)$parts[0] * 3600 + (int)$parts[1] * 60 + (int)$parts[2];
        } elseif (count($parts) === 2) { // Format is HH:MM
            $totalSeconds = (int)$parts[0] * 3600 + (int)$parts[1] * 60;
        }
        // If count is not 2 or 3, it will return 0 (initialized value)
        return $totalSeconds;
    }

    /**
     * Converts total seconds into an object with 'h', 'i', 's' properties.
     *
     * @param int $seconds Total seconds.
     * @return object Object with hours, minutes, and seconds.
     */
    public static function secondsToHMSObject(int $seconds): object
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;
        return (object)['h' => $hours, 'i' => $minutes, 's' => $remainingSeconds];
    }

    /**
     * Counts the number of interacting days between two date ranges.
     *
     * @param string $range1_start Start date of the first range.
     * @param string $range1_end End date of the first range.
     * @param string $range2_start Start date of the second range.
     * @param string $range2_end End date of the second range.
     * @return int The number of intersecting days. Returns 0 on error or no overlap.
     */
    public static function countDaysInteract(string $range1_start, string $range1_end, string $range2_start, string $range2_end): int
    {
        try {
            $start1 = new DateTime($range1_start);
            $end1 = new DateTime($range1_end);
            $end1->setTime(23, 59, 59); // Ensure end of day for inclusive comparison

            $start2 = new DateTime($range2_start);
            $end2 = new DateTime($range2_end);
            $end2->setTime(23, 59, 59); // Ensure end of day for inclusive comparison
        } catch (Exception $e) {
            // Log the exception for debugging
            error_log("DateUtils::countDaysInteract error: " . $e->getMessage());
            return 0;
        }

        // Find the latest start date and earliest end date of the intersection
        $latest_start = max($start1, $start2);
        $earliest_end = min($end1, $end2);

        // If latest_start is before earliest_end, there is an overlap
        if ($latest_start < $earliest_end) {
            $interval = $latest_start->diff($earliest_end);
            return $interval->days + 1; // Add 1 to include both start and end days
        }

        return 0; // No overlap
    }

    /**
     * Calculates the difference in days between two dates (inclusive).
     *
     * @param string $startDate Start date string.
     * @param string $endDate End date string.
     * @return int The number of days. Returns 0 on error or if start date is after end date.
     */
    public static function getDateDiffInDays(string $startDate, string $endDate): int
    {
        try {
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
        } catch (Exception $e) {
            // Log the exception for debugging
            error_log("DateUtils::getDateDiffInDays error: " . $e->getMessage());
            return 0;
        }

        if ($start > $end) {
            return 0;
        }

        $interval = $start->diff($end);
        return $interval->days + 1; // Add 1 to include both start and end days
    }
}
