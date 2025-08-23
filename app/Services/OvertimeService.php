<?php

namespace App\Services;

use App\Models\TblAttendsDataModel;
use App\Models\TblEmpsDataModel;
use App\Models\TblHolidaysDataModel;
use App\Models\TblEmpsLeavesDataModel;
use App\Models\TblEmpsFdaysDataModel;
use App\Models\TblEmpsMedsDataModel;

class OvertimeService
{
    protected $attendModel;
    protected $empModel;
    protected $holidayModel;
    protected $leaveModel;
    protected $fdayModel;
    protected $medModel;

    public function __construct()
    {
        $this->attendModel = new TblAttendsDataModel();
        $this->empModel = new TblEmpsDataModel();
        $this->holidayModel = new TblHolidaysDataModel();
        $this->leaveModel = new TblEmpsLeavesDataModel();
        $this->fdayModel = new TblEmpsFdaysDataModel();
        $this->medModel = new TblEmpsMedsDataModel();
    }

    public function calculateOvertimeForEmp($emp_id, $month, $year, $fp_type = null)
    {
        $attendances = $this->attendModel->getMonthlyAttendance($emp_id, $month, $year);
        $standard_daily_hours = 8; // This can be dynamic based on policy or employee type

        $total_overtime = 0;

        foreach ($attendances as $attendance) {
            $date = $attendance['attend_date'];
            $in_time = strtotime($attendance['in_time']);
            $out_time = strtotime($attendance['out_time']);

            if (!$in_time || !$out_time) continue;

            $worked_hours = ($out_time - $in_time) / 3600;
            
            // Skip weekends, holidays, leaves, fdays, meds
            if ($this->isHoliday($date) ||
                $this->isLeave($emp_id, $date) ||
                $this->isFreeDay($emp_id, $date) ||
                $this->isMedicalDay($emp_id, $date)) {
                continue;
            }

            if ($worked_hours > $standard_daily_hours) {
                $total_overtime += $worked_hours - $standard_daily_hours;
            }
        }

        return round($total_overtime, 2);
    }

    protected function isHoliday($date)
    {
        return $this->holidayModel->where('date', $date)->countAllResults() > 0;
    }

    protected function isLeave($emp_id, $date)
    {
        return $this->leaveModel
            ->where('emp_id', $emp_id)
            ->where('leave_from <=', $date)
            ->where('leave_to >=', $date)
            ->countAllResults() > 0;
    }

    protected function isFreeDay($emp_id, $date)
    {
        return $this->fdayModel->where('emp_id', $emp_id)->where('f_date', $date)->countAllResults() > 0;
    }

    protected function isMedicalDay($emp_id, $date)
    {
        return $this->medModel->where('emp_id', $emp_id)->where('med_date', $date)->countAllResults() > 0;
    }
}
