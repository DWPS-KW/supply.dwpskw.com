<?php

namespace App\Controllers;

use App\Models\TblEmpsDataModel;
use App\Models\TblEmpsLeavesDataModel;
use App\Models\TblDesignsDataModel;
use App\Models\TblHolidaysDataModel;
use App\Models\TblEmpsFdaysDataModel;
use App\Models\TblEmpsMedsDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;
use App\Models\TblAttendsDataModel;
use App\Models\TblAttendsDataBeforeModel;
use App\Models\TblAttendsCoverlistModel;
use App\Models\TblAttendsCoverlistOtModel;

use App\Libraries\MyFuns;
use App\Libraries\DateUtils;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Attendance extends BaseController
{
    protected $session;
    protected $empModel;
    protected $secModel;
    protected $subSecModel;
    protected $designModel;
    protected $attendModel;
    protected $myFuns;
    protected $attendBeforeModel;
    protected $coverlistModel;
    protected $coverlistOtModel;
    protected $fdayModel;
    protected $medModel;
    protected $holidayModel;
    protected $leaveModel;

    public function __construct()
    {
        $this->session = session();
        $this->empModel = new TblEmpsDataModel();
        $this->secModel = new TblStnSecsDataModel();
        $this->subSecModel = new TblStnSubSecsDataModel();
        $this->designModel = new TblDesignsDataModel();
        $this->fdayModel = new TblEmpsFdaysDataModel();
        $this->medModel = new TblEmpsMedsDataModel();
        $this->holidayModel = new TblHolidaysDataModel();
        $this->leaveModel = new TblEmpsLeavesDataModel();
        $this->attendModel = new TblAttendsDataModel();
        $this->myFuns = new MyFuns();
        $this->coverlistModel = new TblAttendsCoverlistModel();
        $this->coverlistOtModel = new TblAttendsCoverlistOtModel();
    }

    public function index() {

        $this->session->set('last_url', current_url());
        $data['secs'] = $this->secModel->findAll();
        $data['sub_secs'] = $this->subSecModel->findAll();
        $data['designs'] = $this->designModel->getAll();

        $data['empModel'] = $this->empModel;
        $data['secModel'] = $this->secModel;
        $data['subSecModel'] = $this->subSecModel;
        $data['leaveModel'] = $this->leaveModel;
        $data['medModel'] = $this->medModel;
        $data['fdayModel'] = $this->fdayModel;
        $data['designModel'] = $this->designModel;
        $data['attendModel'] = $this->attendModel;
        $data['holidayModel'] = $this->holidayModel;
        $data['myFuns'] = $this->myFuns;

        if (in_array($this->session->get('type'), ['admin', 'depart'])) {
            return view('attends/index', $data);
        } else {
            echo "NOT ALLOWED";
        }
    }

    public function fingerPrintforEmp()
    {
        if ($this->session->get('type') != 'admin') {
            return redirect()->back()->with('error', 'Access denied. You do not have permission to view this report.');
        }
        $filters = [];
        $filters['emp_id'] = $emp_id = $this->myFuns->setEmptyToNull($this->request->getGet('emp_id'));
        $filters['from_month'] = $from_month = $this->myFuns->setEmptyToNull($this->request->getGet('from_month'));
        $filters['from_year'] = $from_year = $this->myFuns->setEmptyToNull($this->request->getGet('from_year'));
        $filters['to_month'] = $to_month = $this->myFuns->setEmptyToNull($this->request->getGet('to_month'));
        $filters['to_year'] = $to_year = $this->myFuns->setEmptyToNull($this->request->getGet('to_year'));
        $filters['db_table'] = $db_table = $this->myFuns->setEmptyToNull($this->request->getGet('db_table')) ?? 'tbl_attends_data';

        if (empty($emp_id) || empty($from_month) || empty($from_year) || empty($to_month) || empty($to_year)) {
            return redirect()->back()->with('error', 'Missing required parameters for fingerprint report (Employee ID, From/To Month/Year).');
        }

        $finger_print_report = $this->attendModel->getFingerPrintForEmpPeriod($filters);

        if (empty($finger_print_report) || (isset($finger_print_report['error']))) {
            $error_message = $finger_print_report['error'] ?? 'No attendance records found for the selected criteria. Please verify the employee and date range.';
            return view('attends/print_fp_emp', ['error' => $error_message, 'pageTitle' => 'Fingerprint Report for Employee']);
        }
        $employee = $this->empModel->getEmpDataById($emp_id);
        $data = [
            'pageTitle' => 'Fingerprint Report for Employee',
            'emp_data' => $employee,
            'finger_print' => $finger_print_report,
            'from_month' => $from_month,
            'from_year' => $from_year,
            'to_month' => $to_month,
            'to_year' => $to_year,
            'company_name' => "M/S. AL-GHANIM INTERNATIONAL GENERAL TRADING CONTRACTING CO.",
            'document_reference' => "MEW / MC / 6062 / 2024 - 2025",
            'myFuns' => $this->myFuns,
            'design_model' => $this->designModel,
            'empModel' => $this->empModel,
            'secModel' => $this->secModel,
            'subSecModel' => $this->subSecModel,
            'leaveModel' => $this->leaveModel,
            'medModel' => $this->medModel,
            'fdayModel' => $this->fdayModel,
            'holidayModel' => $this->holidayModel,
            'attendModel' => $this->attendModel,
            'db_table' => $db_table,
            'filters' => $filters,
        ];
        // var_dump($data['finger_print']);die;
        return view('attends/fingerprint_emp_print', $data);
    }

    public function fingerPrint()
    {
        if ($this->session->get('type') != 'admin') {
            echo "NOT ALLOWED";
            return;
        }

        $month_name = $this->request->getVar('month', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $year = $this->request->getVar('year', FILTER_SANITIZE_NUMBER_INT);
        $sec_id = $this->request->getVar('sec_id', FILTER_SANITIZE_NUMBER_INT);
        $sub_sec_id = $this->request->getVar('sub_sec_id', FILTER_SANITIZE_NUMBER_INT);
        $payroll_category = $this->request->getVar('payroll_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fp_type = $this->request->getVar('fp_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $db_table = $this->request->getVar('db_table', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $month_numeric = !empty($month_name) ? date('n', strtotime($month_name)) : null;
        $from = date('Y-m-01', strtotime("1 $month_name $year"));
        $to = date('Y-m-t', strtotime("1 $month_name $year"));

        if (is_null($month_numeric) || is_null($year) || empty($month_numeric) || empty($year)) {
            log_message('error', 'fingerPrint() - Missing month or year after initial parsing.');
            return redirect()->back()->with('error', 'الرجاء اختيار الشهر والسنة.');
        } else {

            $filters = [
                'month' => $month_name,
                'year' => $year,
                'sec_id' => $sec_id,
                'sub_sec_id' => $sub_sec_id,
                'payroll_category' => $payroll_category,
                'fp_type' => $fp_type,
                'db_table' => $db_table,
                'active' => 1, // Only active employees

            ];
            if($fp_type === 'overtime'){
                $filters['has_overtime'] = 1;
            }
            $holidays_raw = $this->holidayModel->getHolidaysForRange($from, $to);
            $holidays_by_date = [];
            foreach ($holidays_raw as $holiday) {
                $h_start_ts = strtotime($holiday->start_date);
                $h_end_ts = strtotime($holiday->end_date);
                for ($d_ts = $h_start_ts; $d_ts <= $h_end_ts; $d_ts = strtotime('+1 day', $d_ts)) {
                    $holidays_by_date[date('Y-m-d', $d_ts)] = true;
                }
            }
            $data['holidays_by_date'] = $holidays_by_date;

            $finger_print = $this->attendModel->getFingerPrint($filters);

            $data['pageTitle'] = "Fingerprint Report";
            $data['month'] = $month_name . " " . $year;
            $data['year'] = $year;
            $data['month_numeric'] = $month_numeric;
            $data['from'] = $from;
            $data['to'] = $to;
            $data['db_table'] = $db_table;
            $data['sec'] = $this->secModel->find($sec_id);
            $data['sub_sec'] = $this->subSecModel->find($sub_sec_id);
            $data['designs'] = $this->designModel->getAll();
            $data['attendModel'] = $this->attendModel;
            $data['myFuns'] = $this->myFuns;
            $data['leaveModel'] = $this->leaveModel;
            $data['medModel'] = $this->medModel;
            $data['fdayModel'] = $this->fdayModel;
            $data['holidayModel'] = $this->holidayModel;

            if (empty($finger_print)) {
                return view('attends/fingerprint_print', ['error' => 'No attendance records found for the selected criteria.', 'pageTitle' => $data['pageTitle']]);
            }
            $data['finger_print'] = $finger_print;

            if ($fp_type === 'overtime') {
                return view('attends/fingerprint_ot_print', $data);
            } else {
                return view('attends/fingerprint_print', $data);
            }
        }
    }

    public function monthlyCoverList_form()
    {
        if ($this->session->get('type') != 'admin') {
            echo "NOT ALLOWED";
            return;
        }

        $month_name = $this->request->getVar('month', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $year = $this->request->getVar('year', FILTER_SANITIZE_NUMBER_INT);
        $sec_id = $this->request->getVar('sec_id', FILTER_SANITIZE_NUMBER_INT);
        $sub_sec_id = $this->request->getVar('sub_sec_id', FILTER_SANITIZE_NUMBER_INT);
        $payroll_category = $this->request->getVar('payroll_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fp_type = $this->request->getVar('fp_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $db_table = $this->request->getVar('db_table', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $month_numeric = !empty($month_name) ? date('n', strtotime($month_name)) : null;
        $query = $this->request->getServer('QUERY_STRING');

        if (is_null($month_numeric) || is_null($year) || empty($month_numeric) || empty($year)) {

            return redirect()->back()->with('error', 'الرجاء اختيار الشهر والسنة.');
        } else {

            if($db_table === 'before'){
                    return redirect()->to(base_url('attendance/monthlyCoverList?' . $query));
            }elseif($db_table === 'after'){

                $filters = [
                    'month' => $month_name,
                    'year' => $year,
                    'sec_id' => $sec_id,
                    'sub_sec_id' => $sub_sec_id,
                    'payroll_category' => $payroll_category,
                    'fp_type' => $fp_type,
                    'db_table' => $db_table,
                    'active' => 1, // Only active employees
                ];

                if($fp_type === 'overtime'){
                    $filters['has_overtime'] = 1;
                }

                $data['sec'] = ($sec_id == 'all' || is_null($sec_id)) ? "الكل" : ($this->secModel->find($sec_id)->ar_name ?? '');
                $data['sub_sec'] = ($sub_sec_id == 'all' || is_null($sub_sec_id)) ? "الكل" : ($this->subSecModel->find($sub_sec_id)->ar_name ?? '');
                $data['sec_id'] = $sec_id;
                $data['sub_sec_id'] = $sub_sec_id;
                $data['query'] = $this->request->getServer('QUERY_STRING');
                $data['db_table'] = $db_table;
                $data['fp_type'] = $fp_type;

                $ot = ($fp_type == 'basic') ? null : 1;

                $required_month = strtotime("1 $month_name $year");
                $data['month_numeric'] = date('n', $required_month);
                $data['month'] = $month_name;
                $data['year'] = $year;
                $data['required_month'] = $required_month;
                $data['from'] = $from = date('Y-m-01', $required_month);
                $data['to'] = $to = date('Y-m-t', $required_month);
                $data['month_name'] = $month_name = date('F', $required_month);
                $data['year_name'] = $year_name = date('Y', $required_month);
                $data['month_year_ar'] = $this->myFuns->getMonthName($data['month_numeric'], 'ar') . " " . $year_name;
                $data['month_year_en'] = $this->myFuns->getMonthName($data['month_numeric'], 'en') . " " . $year_name;
                $data['query'] = $query;

                $data['empModel'] = $this->empModel;
                $data['secModel'] = $this->secModel;
                $data['subSecModel'] = $this->subSecModel;
                $data['leaveModel'] = $this->leaveModel;
                $data['medModel'] = $this->medModel;
                $data['fdayModel'] = $this->fdayModel;
                $data['designModel'] = $this->designModel;
                $data['attendModel'] = $this->attendModel;
                $data['holidayModel'] = $this->holidayModel;
                $data['coverlistModel'] = $this->coverlistModel;
                $data['coverlistOtModel'] = $this->coverlistOtModel;
                $data['myFuns'] = $this->myFuns;

            $data['attends'] = $attends = $this->attendModel->getFingerPrint($filters);

                if($fp_type === 'basic'){
                    // var_dump($attends);die;
                    return view('attends/coverlist_form', $data);
                }elseif ($fp_type === 'overtime'){
                    return view('attends/coverlist_ot_form', $data);
                }else{
                    echo "Invalid FingerPrint Type";
                }
            }

        }
    }

    public function monthlyCoverList_save()
    {
        if ($this->session->get('type') != 'admin') {
            echo "NOT ALLOWED";
            return;
        }

        $month = $this->request->getPost('month');
        $year = $this->request->getPost('year');
        $sec_id = $this->request->getPost('sec_id');
        $sub_sec_id = $this->request->getPost('sub_sec_id');
        $payroll_category = $this->request->getPost('payroll_category');
        $db_table = $this->request->getPost('db_table');
        $fp_type = $this->request->getPost('fp_type');
        $no_of_rows = $this->request->getPost('no_of_rows');
        $query = $this->request->getServer('QUERY_STRING');

        if (empty($no_of_rows)) {
            echo "No rows to save.";
            return;
        }

        for ($i = 1; $i <= $no_of_rows; $i++) {

            $emp_id = $this->request->getPost("emp_id_$i");
            if (empty($emp_id)) continue;
            $manual_wd = $this->request->getPost("manual_wd_$i");
            $manual_meds = $this->request->getPost("manual_meds_$i");
            $manual_absent = $this->request->getPost("manual_absent_$i");

            $manual_leaves_input = $this->request->getPost("manual_leaves_$i");

            if (strtolower(trim($manual_leaves_input)) == 'full leave') {
                $manual_leaves = 26;
            } else {
                $manual_leaves = $manual_leaves_input;
            }

            $dataToSave = [
                'emp_id' => $emp_id,
                'month' => $month,
                'year' => $year,
                'working_days' => $manual_wd,
                'med_days' => $manual_meds,
                'absent_days' => $manual_absent,
                'leave_days' => $manual_leaves,
            ];

            $existing = $this->coverlistModel
                            ->where('emp_id', $emp_id)
                            ->where('month', $month)
                            ->where('year', $year)
                            ->first();

            if ($existing) {
                $this->coverlistModel->update($existing->id, $dataToSave);
            } else {
                $this->coverlistModel->insert($dataToSave);
            }
        }

        return redirect()->to(base_url('attendance/monthlyCoverList?' . $query))->with('success', 'Saved successfully');
    }

    public function monthlyCoverList_ot_save()
    {
        if ($this->session->get('type') != 'admin') {
            echo "NOT ALLOWED";
            return;
        }

        $month = $this->request->getPost('month');
        $year = $this->request->getPost('year');
        $db_table = $this->request->getPost('db_table');
        $no_of_rows = $this->request->getPost('no_of_rows');
        $query = $this->request->getServer('QUERY_STRING');

        for ($i = 1; $i <= $no_of_rows; $i++) {
            $emp_id = $this->request->getPost("emp_id_$i");
            if (empty($emp_id)) continue;
            $normal_ot = $this->myFuns->setEmptyToNull($this->request->getPost("manual_not_" . $i));
            $friday_ot = $this->myFuns->setEmptyToNull($this->request->getPost("manual_fot_" . $i));
            $holiday_ot = $this->myFuns->setEmptyToNull($this->request->getPost("manual_hot_" . $i));

            $dataToSave = [
                'emp_id' => $emp_id,
                'month' => $month,
                'year' => $year,
                'normal_ot' => $normal_ot,
                'friday_ot' => $friday_ot,
                'holiday_ot' => $holiday_ot
            ];

            $existing = $this->coverlistOtModel
                            ->where('emp_id', $emp_id)
                            ->where('month', $month)
                            ->where('year', $year)
                            ->first();

            if ($existing) {
                $this->coverlistOtModel->update($existing->id, $dataToSave);
            } else {
                $this->coverlistOtModel->insert($dataToSave);
            }
        }
        return redirect()->to(base_url('attendance/monthlyCoverList?' . $query))->with('success', 'Saved successfully');
    }

    public function monthlyCoverList()
    {
        if ($this->session->get('type') != 'admin') {
            echo "NOT ALLOWED";
            return;
        }

        $month_name = $this->request->getVar('month', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $year = $this->request->getVar('year', FILTER_SANITIZE_NUMBER_INT);
        $sec_id = $this->request->getVar('sec_id', FILTER_SANITIZE_NUMBER_INT);
        $sub_sec_id = $this->request->getVar('sub_sec_id', FILTER_SANITIZE_NUMBER_INT);
        $payroll_category = $this->request->getVar('payroll_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fp_type = $this->request->getVar('fp_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $db_table = $this->request->getVar('db_table', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $month_numeric = !empty($month_name) ? date('n', strtotime($month_name)) : null;

        if (is_null($month_numeric) || is_null($year) || empty($month_numeric) || empty($year)) {

            return redirect()->back()->with('error', 'الرجاء اختيار الشهر والسنة.');
        } else {

            $filters = [
                'month' => $month_name,
                'year' => $year,
                'sec_id' => $sec_id,
                'sub_sec_id' => $sub_sec_id,
                'payroll_category' => $payroll_category,
                'fp_type' => $fp_type,
                'db_table' => $db_table,
                'active' => 1, // Only active employees
            ];
            if($fp_type === 'overtime'){
                $filters['has_overtime'] = 1;
            }
            $data['sec'] = ($sec_id == 'all' || is_null($sec_id)) ? "الكل" : ($this->secModel->find($sec_id)->ar_name ?? '');
            $data['sub_sec'] = ($sub_sec_id == 'all' || is_null($sub_sec_id)) ? "الكل" : ($this->subSecModel->find($sub_sec_id)->ar_name ?? '');
            $data['sec_id'] = $sec_id;
            $data['sub_sec_id'] = $sub_sec_id;
            $data['query'] = $this->request->getServer('QUERY_STRING');
            $data['db_table'] = $db_table;
            $data['fp_type'] = $fp_type;

            $ot = ($fp_type == 'basic') ? null : 1;

            $required_month = strtotime("1 $month_name $year");
            $data['month_numeric'] = date('n', $required_month);
            $data['month'] = $month_name;
            $data['year'] = $year;
            $data['required_month'] = $required_month;
            $data['from'] = $from = date('Y-m-01', $required_month);
            $data['to'] = $to = date('Y-m-t', $required_month);
            $data['month_name'] = $month_name = date('F', $required_month);
            $data['year_name'] = $year_name = date('Y', $required_month);
            $data['month_year_ar'] = $this->myFuns->getMonthName($month_name, 'ar') . " " . $year_name;
            $data['month_year_en'] = $month_name . " " . $year_name;
            $data['payroll_category'] = $payroll_category;
            $data['empModel'] = $this->empModel;
            $data['secModel'] = $this->secModel;
            $data['subSecModel'] = $this->subSecModel;
            $data['leaveModel'] = $this->leaveModel;
            $data['medModel'] = $this->medModel;
            $data['fdayModel'] = $this->fdayModel;
            $data['designModel'] = $this->designModel;
            $data['attendModel'] = $this->attendModel;
            $data['holidayModel'] = $this->holidayModel;
            $data['coverlistModel'] = $this->coverlistModel;
            $data['coverlistOtModel'] = $this->coverlistOtModel;
            $data['myFuns'] = $this->myFuns;
            $data['attends'] = $attends = $this->attendModel->getFingerPrint($filters);
            $data['query'] = $query = $this->request->getServer('QUERY_STRING');

            if ($fp_type == "basic") {

                if ($db_table == "before") {
                    return view('attends/coverlist_before_print', $data);
                }
                elseif ($db_table == "after") {
                    foreach($attends as $emp){
                        $cover_data = $this->coverlistModel->getDataByEmpMonthYear($emp->id, $month_name, $year);
                        $emp->coverlist_working_days = $cover_data->working_days ?? null;
                        $emp->coverlist_med_days = $cover_data->med_days ?? null;
                        $emp->coverlist_absent_days = $cover_data->absent_days ?? null;
                        $emp->coverlist_leave_days = $cover_data->leave_days ?? null;
                    }
                    return view('attends/coverlist_print', $data);
                }
            } elseif ($fp_type == "overtime") {
                if ($db_table == "before") {
                    return view('attends/coverlist_ot_before_print', $data);
                } elseif ($db_table == "after") {

                    foreach($attends as $emp){
                        $coverlist_ot_record = $this->coverlistOtModel
                                                    ->where('emp_id', $emp->id)
                                                    ->where('month', $month_name)
                                                    ->where('year', $year)
                                                    ->first();

                        $emp->coverlist_normal_ot = $coverlist_ot_record->normal_ot ?? null;
                        $emp->coverlist_friday_ot = $coverlist_ot_record->friday_ot ?? null;
                        $emp->coverlist_holiday_ot = $coverlist_ot_record->holiday_ot ?? null;
                    }

                    return view('attends/coverlist_ot_print', $data);
                }
            } else {
                echo "Invalid Fingerprint Type";
            }
        }
    }


    public function exportToExcel()
    {
        if ($this->session->get('type') != 'admin') {
            return redirect()->back()->with('error', 'Access denied.');
        }

        // Get input
        $month_name = $this->request->getVar('month', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $year = $this->request->getVar('year', FILTER_SANITIZE_NUMBER_INT);
        $sec_id = $this->request->getVar('sec_id', FILTER_SANITIZE_NUMBER_INT);
        $sub_sec_id = $this->request->getVar('sub_sec_id', FILTER_SANITIZE_NUMBER_INT);
        $payroll_category = $this->request->getVar('payroll_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fp_type = $this->request->getVar('fp_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $db_table = $this->request->getVar('db_table', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $month_numeric = date('n', strtotime("1 $month_name $year"));

        if (empty($month_name) || empty($year)) {
            return redirect()->back()->with('error', 'Missing month or year for Excel export.');
        }

        $filters = [
            'month' => $month_name,
            'year' => $year,
            'sec_id' => $sec_id,
            'sub_sec_id' => $sub_sec_id,
            'payroll_category' => $payroll_category,
            'fp_type' => $fp_type,
            'db_table' => $db_table,
            'active' => 1, // Only active employees

        ];

            $data['attends'] = $attends = $this->attendModel->getFingerPrint($filters);
        if (empty($attends)) {
            return redirect()->back()->with('error', 'No attendance data found to export.');
        }


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($fp_type === 'basic') {
            if ($db_table === 'before') {
                $filenamePrefix = "Monthly_Coverlist_Before";
            } elseif ($db_table === 'after') {
                $filenamePrefix = "Monthly_Coverlist_After";
                foreach ($attends as $emp) {
                    $cover_data = $this->coverlistModel->getDataByEmpMonthYear($emp->id, $month_name, $year);
                    $emp->working_days = $cover_data->working_days ?? null;
                    $emp->med_days = $cover_data->med_days ?? null;
                    $emp->absent_days = $cover_data->absent_days ?? null;
                    $emp->leave_days = $cover_data->leave_days ?? null;
                }
            } else {
                return redirect()->back()->with('error', 'Invalid db_table for basic.');
            }
            $headers = ["S.N", "Name", "Civil ID", "Category Craft", "Working Days", "Medical Days", "Absent Days", "Leave Days", "Leave From", "Leave To"];
        } elseif ($fp_type === 'overtime') {
            if ($db_table === 'before') {
                $filenamePrefix = "Overtime_Before";
            } elseif ($db_table === 'after') {
                $filenamePrefix = "Overtime_After";
                foreach ($attends as $emp) {
                    $cover_data = $this->coverlistOtModel->getDataByEmpMonthYear($emp->id, $month_name, $year);
                    $emp->coverlist_normal_ot = $cover_data->normal_ot ?? null;
                    $emp->coverlist_friday_ot = $cover_data->friday_ot ?? null;
                    $emp->coverlist_holiday_ot = $cover_data->holiday_ot ?? null;
                }
            } else {
                return redirect()->back()->with('error', 'Invalid db_table for overtime.');
            }
            $headers = ["S.N", "Name", "Civil ID", "Category Craft", "Normal OT", "Friday OT", "Holiday OT"];
        } else {
            return redirect()->back()->with('error', 'Only basic and overtime exports are supported.');
        }

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $row = 2;
        $sn = 1;

        foreach ($attends as $emp) {
            $attend = is_array($emp->attend) ? $emp->attend : [];

            $sheet->setCellValue("A$row", $sn++);
            $sheet->setCellValue("B$row", ucwords(strtolower($emp->name_english ?? '')));
            $sheet->setCellValueExplicit("C$row", $emp->civil_id ?? '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("D$row", ucwords(strtolower($emp->design_name ?? '')));

            if ($fp_type === 'basic') {
                $val = ($db_table === 'before' ? $attend['calculated_working_days'] ?? '' : $emp->working_days ?? '');
                $sheet->setCellValue("E$row", ($val === '0' || $val === 0) ? '' : $val);

                $val = ($db_table === 'before' ? count($attend['meds_days_without_fridays_list'] ?? []) : $emp->med_days ?? '');
                $sheet->setCellValue("F$row", ($val === '0' || $val === 0) ? '' : $val);

                $val = ($db_table === 'before' ? count($attend['real_absent_days_list'] ?? []) : $emp->absent_days ?? '');
                $sheet->setCellValue("G$row", ($val === '0' || $val === 0) ? '' : $val);

                $val = ($db_table === 'before' ? $attend['leaves_days_without_fridays'] ?? '' : $emp->leave_days ?? '');
                $sheet->setCellValue("H$row", ($val === '0' || $val === 0) ? '' : $val);

                $sheet->setCellValue("I$row", ''); // Leave From
                $sheet->setCellValue("J$row", ''); // Leave To
            }

            elseif ($fp_type === 'overtime') {
                $normal_ot = ($db_table === 'before') ? ($attend['normal_ot'] ?? '') : ($emp->coverlist_normal_ot ?? '');
                $friday_ot = ($db_table === 'before') ? ($attend['friday_ot'] ?? '') : ($emp->coverlist_friday_ot ?? '');
                $holiday_ot = ($db_table === 'before') ? ($attend['holiday_ot'] ?? '') : ($emp->coverlist_holiday_ot ?? '');

                $sheet->setCellValue("E$row", ($normal_ot !== '00:00' && $normal_ot !== null && $normal_ot !== '') ? $normal_ot : '');
                $sheet->setCellValue("F$row", ($friday_ot !== '00:00' && $friday_ot !== null && $friday_ot !== '') ? $friday_ot : '');
                $sheet->setCellValue("G$row", ($holiday_ot !== '00:00' && $holiday_ot !== null && $holiday_ot !== '') ? $holiday_ot : '');
            }

            $row++;
        }
                $fullDataRange = 'A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow();

        // Define a simple style for all borders (you can reuse parts of your $style or define a new one)
        $allCellsBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN, // Apply thin border
                    'color' => ['rgb' => '000000'], // Black border color
                ],
            ],
        ];
        $style = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'D9E1F2']],
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '000000']]
        ];
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($style);
        $sheet->getStyle($fullDataRange)->applyFromArray($allCellsBorderStyle);

        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $month_en = $this->myFuns->getMonthName(date('n', strtotime("1 $month_name $year")), 'en');
        $filename = "{$filenamePrefix}_{$month_en}_{$year}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . urlencode($filename) . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }


}
