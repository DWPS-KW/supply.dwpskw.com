<?php

namespace App\Libraries;

use DateTime;
use Exception; // It's good practice to import Exception if you're throwing it

class DateUtils
{
    public function calInteractDays($period_1_begin = null, $period_1_end = null, $period_2_start = null, $peroid_2_end = null)
    {
        try {
            $datetimeStart1 = new DateTime($period_1_begin);
            $datetimeEnd1 = new DateTime($period_1_end);

            $datetimeStart2 = new DateTime($period_2_start);
            $datetimeEnd2 = new DateTime($peroid_2_end);
        } catch (Exception $e) {
            // Handle invalid date string gracefully, e.g., log it or return 0
            // For now, re-throwing to indicate a problem with the input date format
            throw new Exception("Invalid date format in calInteractDays: " . $e->getMessage());
        }


        if ($datetimeStart1 < $datetimeEnd2 && $datetimeEnd1 > $datetimeStart2) {
            // Calculate the overlap start and end
            $overlapStart = max($datetimeStart1, $datetimeStart2);
            $overlapEnd = min($datetimeEnd1, $datetimeEnd2);

            // Calculate the difference and add 1 for inclusive days
            return $overlapEnd->diff($overlapStart)->days + 1;
        } else {
            return 0;
        }
    }

    public function getDateDiffInDays(string $startDate, string $endDate): int
    {
        try {
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
        } catch (Exception $e) {
            throw new Exception("Invalid date format provided for date difference calculation: " . $e->getMessage());
        }

        // Ensure start date is not after end date for meaningful duration
        if ($start > $end) {
            return 0; // Or throw an exception, depending on desired behavior for invalid ranges
        }

        $interval = $start->diff($end);
        // Add 1 to make it inclusive (e.g., Jan 1 to Jan 1 is 1 day)
        return $interval->days + 1;
    }
}