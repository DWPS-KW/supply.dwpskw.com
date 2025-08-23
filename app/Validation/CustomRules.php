<?php

namespace App\Validation;

class CustomRules
{
    public function date_greater_than_equal_to(string $str, string $field, array $data): bool
    {
        if (empty($str) || empty($data[$field])) {
            return true;
        }

        $endDate = strtotime($str);
        $startDate = strtotime($data[$field]);

        if ($endDate === false || $startDate === false) {
            return false;
        }

        return $endDate >= $startDate;
    }

    public function check_date_range_overlap($value, string $params, array $data): bool
    {
        [$startField, $endField, $existingField] = explode(',', $params);
        $currentLeaveId = $data['id'] ?? null;

        if (!isset($data[$startField], $data[$endField], $data[$existingField])) {
            return true;
        }

        $startDate = strtotime($data[$startField]);
        $endDate = strtotime($data[$endField]);
        $existingLeaves = $data[$existingField];

        if ($startDate === false || $endDate === false) {
            return false;
        }

        foreach ($existingLeaves as $leave) {
            if (!empty($currentLeaveId) && $leave->id == $currentLeaveId) {
                continue;
            }

            $existingStart = strtotime($leave->begin);
            $existingEnd = strtotime($leave->end);

            if ($existingStart === false || $existingEnd === false) {
                continue;
            }

            if ($startDate <= $existingEnd && $endDate >= $existingStart) {
                return false;
            }
        }

        return true;
    }

    public function check_medical_date_range_overlap($value, string $params, array $data): bool
    {
        [$dateField, $durationField, $existingMedsField] = explode(',', $params);

        $currentMedId = $data['id'] ?? null;

        if (!isset($data[$dateField], $data[$durationField], $data[$existingMedsField])) {
            return true;
        }

        $newMedStartDate = strtotime($data[$dateField]);
        $newMedDuration = (int)$data[$durationField];
        $newMedEndDate = strtotime($data[$dateField] . ' + ' . ($newMedDuration - 1) . ' days');

        if ($newMedStartDate === false || $newMedEndDate === false) {
            return false;
        }

        $existingMeds = $data[$existingMedsField];

        foreach ($existingMeds as $med) {
            if (!empty($currentMedId) && $med->id == $currentMedId) {
                continue;
            }

            $existingMedStartDate = strtotime($med->date);
            $existingMedDuration = (int)$med->duration;
            $existingMedEndDate = strtotime($med->date . ' + ' . ($existingMedDuration - 1) . ' days');

            if ($existingMedStartDate === false || $existingMedEndDate === false) {
                continue;
            }

            if ($newMedStartDate <= $existingMedEndDate && $newMedEndDate >= $existingMedStartDate) {
                return false;
            }
        }

        return true;
    }
}
