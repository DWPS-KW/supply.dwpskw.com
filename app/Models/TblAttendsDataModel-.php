<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\TblEmpsDataModel;
use App\Models\TblEmpsLeavesDataModel;
use App\Models\TblDesignsDataModel;
use App\Models\TblHolidaysDataModel;
use App\Models\TblEmpsFdaysDataModel;
use App\Models\TblEmpsMedsDataModel;
use App\Libraries\MyFuns;
use App\Libraries\DateUtils;

class TblAttendsDataModel extends Model
{
    protected $table            = 'tbl_attends_data';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ac_no',
        'date',
        'on_dutty',
        'off_dutty',
        'clock_in',
        'clock_out',
        'late',
        'early',
        'absent',
        'clock_in_out_time',
        'week',
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    protected $empModel;
    protected $leaveModel;
    protected $medModel;
    protected $fdayModel;
    protected $holidayModel;
    protected $dateUtils;
    protected $myFuns;
    protected $month_working_days = 26;
    public $clockIn = '07:30:00';
    public $clockOut = '15:00:00';

    public function __construct()
    {
        parent::__construct();
        $this->empModel = new TblEmpsDataModel();
        $this->leaveModel = new TblEmpsLeavesDataModel();
        $this->medModel = new TblEmpsMedsDataModel();
        $this->fdayModel = new TblEmpsFdaysDataModel();
        $this->holidayModel = new TblHolidaysDataModel();
        $this->dateUtils = new DateUtils();
        $this->myFuns = new MyFuns();
        date_default_timezone_set('Asia/Kuwait');
    }

    private function calculateDailyOvertime(object $fp_record, array $holidays_by_date): object
    {
        $fp_record->daily_normal_ot = "00:00:00";
        $fp_record->daily_friday_ot = "00:00:00";
        $fp_record->daily_holiday_ot = "00:00:00";
        $fp_record->ot_type = null;
        $fp_record->found_ot_daily = false;

        if (empty($fp_record->clock_in) || empty($fp_record->clock_out)) {
            return $fp_record;
        }

        $clock_in_ts = strtotime($fp_record->date . ' ' . $fp_record->clock_in);
        $clock_out_ts = strtotime($fp_record->date . ' ' . $fp_record->clock_out);
        
        if ($clock_out_ts < $clock_in_ts) {
            return $fp_record;
        }

        $isHolidayForThisDate = $this->isHoliday($fp_record->date, $holidays_by_date);
        $isFridayForThisDate = $this->isFriday($fp_record->date);

        if ($isHolidayForThisDate) {
            $diff_seconds = $clock_out_ts - $clock_in_ts;
            if ($diff_seconds > 0) {
                $fp_record->daily_holiday_ot = DateUtils::secondsToHMS($diff_seconds);
                $fp_record->found_ot_daily = true;
                $fp_record->ot_type = "Holiday OT";
            }
        } elseif ($isFridayForThisDate) {
            $diff_seconds = $clock_out_ts - $clock_in_ts;
            if ($diff_seconds > 0) {
                $fp_record->daily_friday_ot = DateUtils::secondsToHMS($diff_seconds);
                $fp_record->found_ot_daily = true;
                $fp_record->ot_type = "Friday OT";
            }
        } else {
            $default_off_dutty_ts = strtotime($fp_record->date . ' ' . $this->clockOut);
            $diff_after_offduty = max(0, $clock_out_ts - $default_off_dutty_ts);
            $diff_before_onduty = 0; 
            $total_daily_normal_ot_seconds = $diff_after_offduty + $diff_before_onduty;

            if ($total_daily_normal_ot_seconds > 0) {
                $fp_record->daily_normal_ot = DateUtils::secondsToHMS($total_daily_normal_ot_seconds);
                $fp_record->found_ot_daily = true;
                $fp_record->ot_type = "Normal OT";
            }
        }

        return $fp_record;
    }
    
    public function getFingerPrintForEmpPeriod($filters): array
    {
        $emp_id = $filters['emp_id'];
        $from_month = $filters['from_month'];
        $from_year = $filters['from_year'];
        $to_month = $filters['to_month'];
        $to_year = $filters['to_year'];
        $db_table = $filters['db_table'];

        $emp_data = $this->empModel->getEmpDataById($emp_id);
        if (empty($emp_data) || empty($emp_data->file_no)) {
            return [];
        }

        $emp_file_no = $emp_data->file_no;

        $start_period_ts = strtotime("1 $from_month $from_year");
        $end_period_ts = strtotime("1 $to_month $to_year");
        $results = [];

        for ($current_month_ts = $start_period_ts; $current_month_ts <= $end_period_ts; $current_month_ts = strtotime('+1 month', $current_month_ts)) {
            $month = date('F', $current_month_ts);
            $year = date('Y', $current_month_ts);

            $from_date = date('Y-m-01', $current_month_ts);
            $to_date = date('Y-m-t', $current_month_ts);

            $holidays_raw = $this->holidayModel->getHolidaysForRange($from_date, $to_date);
            $holidays_by_date = [];
            foreach ($holidays_raw as $holiday) {
                $h_start_ts = strtotime($holiday->start_date);
                $h_end_ts = strtotime($holiday->end_date);
                for ($d_ts = $h_start_ts; $d_ts <= $h_end_ts; $d_ts = strtotime('+1 day', $d_ts)) {
                    $holidays_by_date[date('Y-m-d', $d_ts)] = true;
                }
            }

            $db_table_name = ($db_table === "before") ? "tbl_attends_data_before" : "tbl_attends_data";
            $daily_records_raw = $this->db->table($db_table_name)
                ->where('ac_no', $emp_file_no)
                ->where('date >=', $from_date)
                ->where('date <=', $to_date)
                ->orderBy('date', 'ASC')
                ->get()->getResult();

            $daily_records_with_ot = [];
            $processed_daily_attends_by_date = [];
            foreach ($daily_records_raw as $fp_record) {
                $processed_fp_record = clone $fp_record;
                $processed_fp_record = $this->calculateDailyOvertime($processed_fp_record, $holidays_by_date);
                $daily_records_with_ot[] = $processed_fp_record;
                $processed_daily_attends_by_date[$processed_fp_record->date] = $processed_fp_record;
            }

            $preloaded_data_for_monthly_calc = [
                'holidays_by_date' => $holidays_by_date,
                'meds_by_emp_date' => [],
                'fday_by_emp_date' => [],
                'leaves_by_emp' => [],
                'leaves_by_emp_date' => [],
                'all_attends_for_month_by_ac_no_date' => [$emp_file_no => $processed_daily_attends_by_date],
            ];

            $monthly_attend_data = $this->getAttendForEmp([
                'emp_id' => $emp_id,
                'month' => $month,
                'year' => $year,
                'db_table' => $db_table
            ], $preloaded_data_for_monthly_calc);

            $employee_monthly_report_block = (object)[
                'emp_id' => $emp_id,
                'file_no' => $emp_data->file_no,
                'name_english' => $emp_data->name_english,
                'name_arabic' => $emp_data->name_arabic,
                'civil_id' => $emp_data->civil_id,
                'design_name' => $emp_data->design_name,
                'month' => $month,
                'year' => $year,
                'fingerPrint' => $daily_records_with_ot,
                'attend' => $monthly_attend_data,
            ];
            $results[] = $employee_monthly_report_block;
        }

        return $results;
    }

    public function getFingerPrint($filters): array
    {
        $month = $filters['month'];
        $year = $filters['year'];
        $db_table = $filters['db_table'];
        $fp_type = $filters['fp_type'] ?? 'basic';

        $required_month_timestamp = strtotime("1 $month $year");
        $from_date = date('Y-m-01', $required_month_timestamp);
        $to_date = date('Y-m-t', $required_month_timestamp);

        $emps = $this->empModel->getAllEmps($filters);
        if (empty($emps)) {
            return [];
        }

        $all_emp_ids = array_column($emps, 'id');
        $all_ac_nos = array_column($emps, 'file_no');
        $all_ac_nos = array_filter($all_ac_nos);

        if (empty($all_ac_nos)) {
            foreach ($emps as $emp) {
                $emp->fingerPrint = [];
                $emp->attend = $this->getEmptyAttendData($emp->id, $month, $year);
            }
            return $emps;
        }

        $db_table_name = ($db_table === "before") ? "tbl_attends_data_before" : "tbl_attends_data";

        $all_raw_attends_query_results = $this->db->table($db_table_name)
                                    ->whereIn('ac_no', $all_ac_nos)
                                    ->where('date >=', $from_date)
                                    ->where('date <=', $to_date)
                                    ->orderBy('ac_no', 'ASC')
                                    ->orderBy('date', 'ASC')
                                    ->get()->getResult();
        
        $holidays_raw = $this->holidayModel->getHolidaysForRange($from_date, $to_date);
        $holidays_by_date = [];
        foreach ($holidays_raw as $holiday) {
            $h_start_ts = strtotime($holiday->start_date);
            $h_end_ts = strtotime($holiday->end_date);
            for ($d_ts = $h_start_ts; $d_ts <= $h_end_ts; $d_ts = strtotime('+1 day', $d_ts)) {
                $date_str = date('Y-m-d', $d_ts);
                $holidays_by_date[$date_str] = true;
            }
        }

        $processed_daily_attends_by_ac_no_date = [];
        foreach ($all_raw_attends_query_results as $raw_att_record) {
            $processed_att_record = clone $raw_att_record;
            $processed_att_record = $this->calculateDailyOvertime($processed_att_record, $holidays_by_date);
            $processed_daily_attends_by_ac_no_date[$processed_att_record->ac_no][$processed_att_record->date] = $processed_att_record;
        }

        $meds_raw = $this->medModel->getMedsForEmployeesInMonth($all_emp_ids, $from_date, $to_date);
        $meds_by_emp_date = [];
        foreach ($meds_raw as $med) {
            $start_ts = strtotime($med->start_date);
            $end_ts = strtotime($med->end_date);
            for ($d_ts = $start_ts; $d_ts <= $end_ts; $d_ts = strtotime('+1 day', $d_ts)) {
                $date_str = date('Y-m-d', $d_ts);
                $meds_by_emp_date[$med->emp_id][$date_str] = true;
            }
        }

        $fdays_raw = $this->fdayModel->getFdaysForEmployeesInMonth($all_emp_ids, $from_date, $to_date);
        $fday_by_emp_date = [];
        foreach ($fdays_raw as $fday) {
            $date_str = date('Y-m-d', strtotime($fday->date));
            $fday_by_emp_date[$fday->emp_id][$date_str] = true;
        }

        $leaves_raw = $this->leaveModel->getLeavesForEmployeesInMonth($all_emp_ids, $from_date, $to_date);
        $leaves_by_emp = [];
        $leaves_by_emp_date = [];
        foreach ($leaves_raw as $leave) {
            $leaves_by_emp[$leave->emp_id][] = $leave;
            $start_ts = strtotime($leave->begin);
            $end_ts = strtotime($leave->end);
            for ($d_ts = $start_ts; $d_ts <= $end_ts; $d_ts = strtotime('+1 day', $d_ts)) {
                $date_str = date('Y-m-d', $d_ts);
                $leaves_by_emp_date[$leave->emp_id][$date_str] = true;
            }
        }

        $preloaded_data = [
            'holidays_by_date' => $holidays_by_date,
            'meds_by_emp_date' => $meds_by_emp_date,
            'fday_by_emp_date' => $fday_by_emp_date,
            'leaves_by_emp' => $leaves_by_emp,
            'leaves_by_emp_date' => $leaves_by_emp_date,
            'all_attends_for_month_by_ac_no_date' => $processed_daily_attends_by_ac_no_date,
        ];

        $filtered_and_processed_emps = [];
        foreach ($emps as $emp) {
            $emp->fingerPrint = $processed_daily_attends_by_ac_no_date[$emp->file_no] ?? [];
            $emp->attend = $this->getAttendForEmp([
                'emp_id' => $emp->id,
                'month' => $month,
                'year' => $year,
                'db_table' => $db_table
            ], $preloaded_data);

            if ($fp_type === 'overtime') {
                $has_any_daily_ot = false;
                foreach ($emp->fingerPrint as $daily_fp) {
                    if (isset($daily_fp->found_ot_daily) && $daily_fp->found_ot_daily) {
                        $has_any_daily_ot = true;
                        break;
                    }
                }
                if ($has_any_daily_ot ||
                    (isset($emp->attend['normal_ot']) && $emp->attend['normal_ot'] !== "00:00" && DateUtils::hmsToSeconds($emp->attend['normal_ot']) > 0) ||
                    (isset($emp->attend['friday_ot']) && $emp->attend['friday_ot'] !== "00:00" && DateUtils::hmsToSeconds($emp->attend['friday_ot']) > 0) ||
                    (isset($emp->attend['holiday_ot']) && $emp->attend['holiday_ot'] !== "00:00" && DateUtils::hmsToSeconds($emp->attend['holiday_ot']) > 0)) {
                    $filtered_and_processed_emps[] = $emp;
                }
            } else {
                $filtered_and_processed_emps[] = $emp;
            }
        }
        return $filtered_and_processed_emps;
    }

    private function getEmptyAttendData(int $emp_id, string $month, int $year): array
    {
        return [
            'emp_id' => $emp_id,
            'emp_file_no' => $this->empModel->find($emp_id)->file_no ?? null,
            'month' => $month,
            'year' => $year,
            'from' => date('Y-m-01', strtotime("1 $month $year")),
            'to' => date('Y-m-t', strtotime("1 $month $year")),
            'calculated_working_days' => 0,
            'present_days_list' => [], 'present_days' => 0,
            'absent_days_list' => [], 'absent_days' => 0,
            'real_absent_days_list' => [], 'real_absent_days' => 0,
            'leaves_days_without_fridays_list' => [], 'leaves_days_without_fridays' => 0,
            'meds_days_without_fridays_list' => [], 'meds_days_without_fridays' => 0,
            'fday_days_without_fridays_list' => [], 'fday_days_without_fridays' => 0,
            'fridays_present_list' => [], 'fridays_present' => 0,
            'holidays_present_list' => [], 'holidays_present' => 0,
            'leaves_days_list' => [], 'leaves_days' => 0,
            'calculated_leaves' => 0,
            'late_in' => '00:00',
            'early_out' => '00:00',
            'has_leave' => false, 'has_med' => false, 'has_fday' => false,
            'normal_ot' => '00:00',
            'friday_ot' => '00:00',
            'holiday_ot' => '00:00',
        ];
    }

    public function getAttendForEmp($filters, $preloaded_data): array
    {
        $emp_id = $filters['emp_id'];
        $month = $filters['month'];
        $year = $filters['year'];
        $db_table = $filters['db_table'];

        $emp_file_no = $this->empModel->find($emp_id)->file_no;

        $required_month = strtotime("1 $month $year");
        $from = date('Y-m-01', $required_month);
        $to = date('Y-m-t', $required_month);

        $holidays_by_date = $preloaded_data['holidays_by_date'];
        $meds_by_emp_date = $preloaded_data['meds_by_emp_date'];
        $fday_by_emp_date = $preloaded_data['fday_by_emp_date'];
        $leaves_by_emp_date = $preloaded_data['leaves_by_emp_date'];
        $processed_daily_attends_for_emp = $preloaded_data['all_attends_for_month_by_ac_no_date'][$emp_file_no] ?? [];

        $present_days_list = [];
        $absent_days_list = [];
        $real_absent_days_list = [];
        $leaves_days_without_fridays_list = [];
        $meds_days_without_fridays_list = [];
        $fday_days_without_fridays_list = [];

        $has_leave = false;
        $has_med = false;
        $has_fday = false;

        $leaves_days_list_raw = $preloaded_data['leaves_by_emp'][$emp_id] ?? [];
        if (!empty($leaves_days_list_raw)) {
            $has_leave = true;
            foreach ($leaves_days_list_raw as $leave_record) {
                $leave_start_ts = strtotime($leave_record->begin);
                $leave_end_ts = strtotime($leave_record->end);
                $current_month_start_ts = strtotime($from);
                $current_month_end_ts = strtotime($to);
                $effective_leave_start_ts = max($leave_start_ts, $current_month_start_ts);
                $effective_leave_end_ts = min($leave_end_ts, $current_month_end_ts);

                for ($current_day_ts_loop = $effective_leave_start_ts; $current_day_ts_loop <= $effective_leave_end_ts; $current_day_ts_loop = strtotime('+1 day', $current_day_ts_loop)) {
                    $date_str_loop = date('Y-m-d', $current_day_ts_loop);
                    if (!$this->isFriday($date_str_loop)) {
                        $daily_attend_record = $processed_daily_attends_for_emp[$date_str_loop] ?? null;
                        if (!$daily_attend_record || (isset($daily_attend_record->absent) && $daily_attend_record->absent === 'TRUE')) {
                            $leaves_days_without_fridays_list[] = $date_str_loop;
                        }
                    }
                }
            }
        }
        $leaves_days_without_fridays_list = array_unique($leaves_days_without_fridays_list);
        sort($leaves_days_without_fridays_list);

        $total_month_working_days = 0;
        for ($d = strtotime($from); $d <= strtotime($to); $d = strtotime('+1 day', $d)) {
            if (!$this->isFriday(date('Y-m-d', $d))) {
                $total_month_working_days++;
            }
        }

        if ($has_leave && count($leaves_days_without_fridays_list) >= $total_month_working_days && $total_month_working_days > 0) {
            return $this->getEmptyAttendData($emp_id, $month, $year);
        }
        
        $current_day_ts = strtotime($from);
        while ($current_day_ts <= strtotime($to)) {
            $date_str = date('Y-m-d', $current_day_ts);
            $isFriday = $this->isFriday($date_str);
            $isHoliday = $this->isHoliday($date_str, $holidays_by_date);
            $isMed = $this->isMedDay($emp_id, $date_str, $meds_by_emp_date);
            $isFday = $this->isFdayDay($emp_id, $date_str, $fday_by_emp_date);
            $isLeave = isset($leaves_by_emp_date[$emp_id][$date_str]);

            $attend_data_for_day = $processed_daily_attends_for_emp[$date_str] ?? null;

            if (!$attend_data_for_day || (isset($attend_data_for_day->absent) && $attend_data_for_day->absent === 'TRUE')) {
                $absent_days_list[] = $date_str;
                if (!$isFriday) {
                    if ($isMed) {
                        $meds_days_without_fridays_list[] = $date_str;
                        $has_med = true;
                    }
                    if ($isFday) {
                        $fday_days_without_fridays_list[] = $date_str;
                        $has_fday = true;
                    }
                    if (!$isHoliday && !$isMed && !$isFday && !$isLeave) {
                        $real_absent_days_list[] = $date_str;
                    }
                }
            } else {
                $present_days_list[] = $date_str;
            }
            $current_day_ts = strtotime('+1 day', $current_day_ts);
        }

        $final_calculated_working_days = ($this->month_working_days - count($real_absent_days_list) - count($meds_days_without_fridays_list));
        // if ($final_calculated_working_days === $total_month_working_days) {
        //     $final_calculated_working_days = 'Full Month';
        // }
        
        $emp_normal_ot = $this->getEmpNOT($emp_id, $from, $to, $db_table, $processed_daily_attends_for_emp, $holidays_by_date);
        $emp_friday_ot = $this->getEmpFOT($emp_id, $from, $to, $db_table, $processed_daily_attends_for_emp, $holidays_by_date);
        $emp_holiday_ot = $this->getEmpHOT($emp_id, $from, $to, $db_table, $processed_daily_attends_for_emp, $holidays_by_date);

        return [
            'emp_id' => $emp_id,
            'emp_file_no' => $emp_file_no,
            'month' => $month,
            'year' => $year,
            'from' => $from,
            'to' => $to,
            'calculated_working_days' => $final_calculated_working_days,
            'present_days_list' => $present_days_list,
            'present_days' => count($present_days_list),
            'absent_days_list' => array_unique($absent_days_list),
            'absent_days' => count(array_unique($absent_days_list)),
            'real_absent_days_list' => array_unique($real_absent_days_list),
            'real_absent_days' => count(array_unique($real_absent_days_list)),
            'leaves_days_without_fridays_list' => $leaves_days_without_fridays_list,
            'leaves_days_without_fridays' => count($leaves_days_without_fridays_list),
            'meds_days_without_fridays_list' => array_unique($meds_days_without_fridays_list),
            'meds_days_without_fridays' => count(array_unique($meds_days_without_fridays_list)),
            'fday_days_without_fridays_list' => array_unique($fday_days_without_fridays_list),
            'fday_days_without_fridays' => count(array_unique($fday_days_without_fridays_list)),
            'fridays_present_list' => [], 'fridays_present' => 0,
            'holidays_present_list' => [], 'holidays_present' => 0,
            'leaves_days_list' => array_values($leaves_days_list_raw),
            'leaves_days' => count(array_unique(array_column($leaves_days_list_raw, 'begin', 'end'))),
            'calculated_leaves' => count($leaves_days_without_fridays_list),
            'late_in' => '00:00',
            'early_out' => '00:00',
            'has_leave' => $has_leave,
            'has_med' => $has_med,
            'has_fday' => $has_fday,
            'normal_ot' => $emp_normal_ot,
            'friday_ot' => $emp_friday_ot,
            'holiday_ot' => $emp_holiday_ot,
        ];
    }

    public function isFriday(string $date): bool
    {
        return date('N', strtotime($date)) === '5';
    }

    private function isHoliday(string $date, array $holidays_by_date): bool
    {
        return isset($holidays_by_date[$date]);
    }

    private function isMedDay(int $emp_id, string $date, array $meds_by_emp_date): bool
    {
        return isset($meds_by_emp_date[$emp_id][$date]);
    }

    private function isFdayDay(int $emp_id, string $date, array $fday_by_emp_date): bool
    {
        return isset($fday_by_emp_date[$emp_id][$date]);
    }

    public function getEmpNOT($emp_id, $from, $to, $db_table, $processed_attends_for_emp, $holidays_by_date): string
    {
        $total_seconds_normal_ot = 0;
        foreach ($processed_attends_for_emp as $date_str => $fp_record) {
            if (isset($fp_record->daily_normal_ot) && $fp_record->daily_normal_ot !== "00:00:00") {
                $total_seconds_normal_ot += DateUtils::hmsToSeconds($fp_record->daily_normal_ot);
            }
            elseif (!empty($fp_record->clock_in) && !empty($fp_record->clock_out)) {
                $isFriday = $this->isFriday($date_str);
                $isHoliday = $this->isHoliday($date_str, $holidays_by_date);

                if (!$isFriday && !$isHoliday) {
                    $clock_out_ts = strtotime($date_str . ' ' . $fp_record->clock_out);
                    $default_off_dutty_ts = strtotime($date_str . ' ' . $this->clockOut);
                    $diff_after_offduty = max(0, $clock_out_ts - $default_off_dutty_ts);
                    $diff_before_onduty = 0; 
                    $total_daily_normal_ot_seconds = $diff_after_offduty + $diff_before_onduty;
                    $total_seconds_normal_ot += $total_daily_normal_ot_seconds;
                }
            }
        }
        return $this->roundOvertimeMinutesToNearestHour($total_seconds_normal_ot);
    }

    public function getEmpFOT($emp_id, $from, $to, $db_table, $processed_attends_for_emp, $holidays_by_date): string
    {
        $total_seconds_friday_ot = 0;
        foreach ($processed_attends_for_emp as $date_str => $fp_record) {
            if (isset($fp_record->daily_friday_ot) && $fp_record->daily_friday_ot !== "00:00:00") {
                $total_seconds_friday_ot += DateUtils::hmsToSeconds($fp_record->daily_friday_ot);
            } elseif (!empty($fp_record->clock_in) && !empty($fp_record->clock_out)) {
                $isFriday = $this->isFriday($date_str);
                $isHoliday = $this->isHoliday($date_str, $holidays_by_date);
                if ($isFriday && !$isHoliday) {
                    $total_seconds_friday_ot += $this->timeDiffInSeconds($date_str . ' ' . $fp_record->clock_in, $date_str . ' ' . $fp_record->clock_out);
                }
            }
        }
        return $this->roundOvertimeMinutesToNearestHour($total_seconds_friday_ot);
    }

    public function getEmpHOT($emp_id, $from, $to, $db_table, $processed_attends_for_emp, $holidays_by_date): string
    {
        $total_seconds_holiday_ot = 0;
        foreach ($processed_attends_for_emp as $date_str => $fp_record) {
            if (isset($fp_record->daily_holiday_ot) && $fp_record->daily_holiday_ot !== "00:00:00") {
                $total_seconds_holiday_ot += DateUtils::hmsToSeconds($fp_record->daily_holiday_ot);
            } elseif (!empty($fp_record->clock_in) || !empty($fp_record->clock_out)) {
                $isHoliday = $this->isHoliday($date_str, $holidays_by_date);
                if ($isHoliday) {
                    $total_seconds_holiday_ot += $this->timeDiffInSeconds($date_str . ' ' . $fp_record->clock_in, $date_str . ' ' . $fp_record->clock_out);
                }
            }
        }
        return $this->roundOvertimeMinutesToNearestHour($total_seconds_holiday_ot);
    }

    private function timeDiffInSeconds(string $datetime_1, string $datetime_2): int
    {
        $ts1 = strtotime($datetime_1);
        $ts2 = strtotime($datetime_2);
        if ($ts1 === false || $ts2 === false) {
            log_message('error', 'timeDiffInSeconds: Invalid datetime string provided - ' . $datetime_1 . ' or ' . $datetime_2);
            return 0;
        }
        return abs($ts2 - $ts1);
    }
    
    private function roundOvertimeMinutesToNearestHour(int $totalSeconds): string
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);

        if ($minutes >= 30) {
            $hours++;
        }
        $minutes = 0;

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
