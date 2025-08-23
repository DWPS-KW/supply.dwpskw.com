<?php

namespace App\Controllers;

use App\Models\TblEmpsDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;
use App\Models\TblEmpsLeavesDataModel;
use App\Models\TblEmpsMedsDataModel;
use App\Models\TblEmpsFdaysDataModel;
use App\Models\TblDesignsDataModel;
use App\Libraries\myFuns;
use Config\Services;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Employees extends BaseController
{
    protected $session;
    protected $empModel;
    protected $secModel;
    protected $subSecModel;
    protected $leaveModel;
    protected $medModel;
    protected $fdayModel;
    protected $designModel;
    protected $myFuns;
    protected $validation;

    public function __construct()
    {
        $this->session = Services::session();
        $this->empModel = new TblEmpsDataModel();
        $this->secModel = new TblStnSecsDataModel();
        $this->subSecModel = new TblStnSubSecsDataModel();
        $this->leaveModel = new TblEmpsLeavesDataModel();
        $this->medModel = new TblEmpsMedsDataModel();
        $this->fdayModel = new TblEmpsFdaysDataModel();
        $this->designModel = new TblDesignsDataModel();
        $this->myFuns = new myFuns();
        $this->validation = Services::validation();
    }

    private function getEmployeePhoto($row) {

        $allowPhoto = $this->session->get('allow_photo');
        
        // Default photo if photo missing or not allowed
        if (empty($row->photo) || $allowPhoto == 'none') {
            if ($row->gender === 'male') {
            return 'assets/images/male_avatar.jpg';
            } elseif ($row->gender === 'female') {
            return  'assets/images/female_avatar.jpg';
            }
        }

        if ($allowPhoto == 'all') {
            return $row->photo;
        } elseif ($allowPhoto == 'male' && $row->gender === 'male') {
            return $row->photo;
        } elseif ($allowPhoto == 'female' && $row->gender === 'female') {
            return $row->photo;
        }

        // Fallback to avatars
        if ($row->gender === 'male') {
            return 'assets/images/male_avatar.jpg';
        } elseif ($row->gender === 'female') {
            return 'assets/images/female_avatar.jpg';
        } else {
            return 'assets/images/anonymous_avatar.jpg';
        }

    }

    public function index()
    {
        $search_text = $this->request->getGet('search_text') ?? '';
        $search_in = $this->request->getGet('search_in') ?? 'undefined';
        $search_at = $this->request->getGet('search_at') ?? 'undefined';
        $sort_by = $this->request->getGet('sort_by') ?? 'id';
        $order = $this->request->getGet('order') ?? 'ASC';
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;

        $sessionType = session()->get('type');
        $sessionSecId = session()->get('sec_id');
        $sessionSubSecId = session()->get('sub_sec_id');

        // Prepare filters based on session type
        $filters = [];

        if ($sessionType === 'depart') {
            $filters['sec_id'] = $sessionSecId;
        } elseif ($sessionType === 'sub') {
            $filters['sec_id'] = $sessionSecId;
            $filters['sub_sec_id'] = $sessionSubSecId;
        }

        // Fetch data (assume getAllDataSearch supports pagination)
        $result = $this->empModel->getAllDataSearch($search_text, $search_in, $search_at, $sort_by, $order, $perPage, $page, $filters);

        if ($this->request->isAJAX()) {
            $empsData = [];
            foreach ($result['items'] as $row) {

                $empsData[] = [
                    'id' => $row->id,
                    'name_english' => $row->name_english,
                    'photo' => $this->getEmployeePhoto($row),
                    'design_name' => $row->design_name ?? '',
                    'file_no' => $row->file_no,
                    'sec_id' => $row->sec_id ?? '',
                    'sec_name_arabic' => $row->sec_name_arabic ?? '',
                    'sec_name_english' => $row->sec_name_english ?? '',
                    'sub_sec_name_arabic' => $row->sub_sec_name_arabic ?? '',
                    'sub_sec_name_english' => $row->sub_sec_name_english ?? '',
                ];
            }

            return $this->response->setJSON([
                'emps' => $empsData,
                'hasMore' => $result['hasMore'],
                'total' => $result['total']
            ]);
        }

        // Normal request (page load)
        $secs = $this->secModel->findAll();
        $sub_secs = $this->subSecModel->findAll();
        $data['pageTitle'] = 'قائمة الموظفين';

        return view('employees/index', [
            'result' => $result,
            'secs' => $secs,
            'sub_secs' => $sub_secs,
            'session' => $this->session,
            'empModel' => $this->empModel,
            'secModel' => $this->secModel,
            'subSecModel' => $this->subSecModel,
            'leaveModel' => $this->leaveModel,
            'medModel' => $this->medModel,
            'fdayModel' => $this->fdayModel,
            'designModel' => $this->designModel,
            'myFuns' => $this->myFuns,
            'search_text' => $search_text,
            'search_in' => $search_in,
            'search_at' => $search_at,
            'sort_by' => $sort_by,
            'order' => $order,
        ]);
    }

    public function ajaxSearch()
    {
        $search_text = $this->request->getGet('search_text') ?? '';
        $search_in = $this->request->getGet('search_in') ?? 'undefined';
        $search_at = $this->request->getGet('search_at') ?? 'undefined';
        $sort_by = $this->request->getGet('sort_by') ?? 'id';
        $order = $this->request->getGet('order') ?? 'ASC';
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;

        $sessionType = session()->get('type');
        $sessionSecId = session()->get('sec_id');
        $sessionSubSecId = session()->get('sub_sec_id');

        // Prepare filters based on session type
        $filters = [];

        if ($sessionType === 'depart') {
            $filters['sec_id'] = $sessionSecId;
        } elseif ($sessionType === 'sub') {
            $filters['sec_id'] = $sessionSecId;
            $filters['sub_sec_id'] = $sessionSubSecId;
        }

        // Now this returns an array with 'items' and pagination info
        $result = $this->empModel->getAllDataSearch($search_text, $search_in, $search_at, $sort_by, $order, $perPage, $page, $filters);

        // prepare maps for designs, secs, sub-secs (same as before)...

        $empsData = [];
        foreach ($result['items'] as $row) {

            $empsData[] = [
                'id' => $row->id,
                'name_english' => $row->name_english,
                'photo' => $this->getEmployeePhoto($row),
                'design_name' => $row->design_name ?? '',
                'file_no' => $row->file_no,
                'sec_id' => $row->sec_id ?? '',
                'sec_name_arabic' => $row->sec_name_arabic ?? '',
                'sec_name_english' => $row->sec_name_english ?? '',
                'sub_sec_name_arabic' => $row->sub_sec_name_arabic ?? '',
                'sub_sec_name_english' => $row->sub_sec_name_english ?? '',
            ];
        }

        return $this->response->setJSON([
            'emps' => $empsData,
            'hasMore' => $result['hasMore'],
            'total' => $result['total']
        ]);
    }

    public function searching()
    {
        $this->session->set('last_url', current_url());

        $data['sections'] = $this->secModel->getAllSec('id');
        $data['sub_sections'] = $this->subSecModel->findAll();
        $data['designations'] = $this->designModel->getAll();

        // Initialize the filters array
        $data['filters'] = [
            'permanent'       => $this->request->getGet('permanent'),
            'sec_id'          => $this->request->getGet('sec_id'),
            'sub_sec_id'      => $this->request->getGet('sub_sec_id'),
            'edu_cert'        => $this->request->getGet('edu_cert'),
            'join_date_from'  => $this->request->getGet('join_date_from'),
            'join_date_to'    => $this->request->getGet('join_date_to'),
            'active'          => $this->request->getGet('active'),
            'nation'          => $this->request->getGet('nation'),
            'has_overtime'    => $this->request->getGet('has_overtime'),
            'design_id'       => $this->request->getGet('design_id'),
            'view'       => $this->request->getGet('view'),
        ];
        $search_results = $this->empModel->getEmpDataSearch($data['filters']);
        foreach ($search_results as $row) {
            $row->photo = $this->getEmployeePhoto($row);
        }
        // Pass the whole filters array
        $data['search_results'] = $search_results;
        $data['pageTitle'] = 'بحث عن موظفين';

        return view('employees/search', $data);
    }

    public function exportToExcel()
    {

        $filters = [
            'permanent'    => $this->request->getGet('permanent'),
            'sec_id'     => $this->request->getGet('sec_id'),
            'sub_sec_id'   => $this->request->getGet('sub_sec_id'),
            'edu_cert'    => $this->request->getGet('edu_cert'),
            'join_date_from' => $this->request->getGet('join_date_from'),
            'join_date_to'  => $this->request->getGet('join_date_to'),
            'active'     => $this->request->getGet('active'),
            'nation'     => $this->request->getGet('nation'),
            'has_overtime'  => $this->request->getGet('has_overtime'),
            'design_id'    => $this->request->getGet('design_id'),
        ];

        $employees = $this->empModel->getEmpDataSearch($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Employees Data');
        $sheet->setRightToLeft(true);

        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'رقم الملف');
        $sheet->setCellValue('C1', 'الاسم');
        $sheet->setCellValue('D1', 'الرقم المدني');
        $sheet->setCellValue('E1', 'المسمى');
        $sheet->setCellValue('F1', 'الراتب');
        $sheet->setCellValue('G1', 'المراقبة');
        $sheet->setCellValue('H1', 'القسم');
        $sheet->setCellValue('I1', 'ملاحظات');

        $row = 2;
        $i = 1;

        foreach ($employees as $employee) {
            $sheet->setCellValue('A' . $row, $i++);
            $sheet->setCellValue('B' . $row, $employee->file_no);
            $sheet->setCellValue('C' . $row, $employee->name_english);
            $civil_id = (string)(int)$employee->civil_id;
            $sheet->setCellValueExplicit('D' . $row, $civil_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $row, !empty($employee->design_name) ? $employee->design_name : 'غير محدد');
            $sheet->setCellValue('F' . $row, isset($employee->total_salary) ? number_format($employee->total_salary, 3) : 'غير محدد');
            $sheet->setCellValue('G' . $row, !empty($employee->sec_name_arabic) ? $employee->sec_name_arabic : 'غير محدد');
            $sheet->setCellValue('H' . $row, !empty($employee->sub_sec_name_arabic) ? $employee->sub_sec_name_arabic : 'غير محدد');
            $sheet->setCellValue('I' . $row, $employee->remarks);
            $row++;
        }

        $highestColumn = $sheet->getHighestColumn();
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $filename = 'employees.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    public function show($id)
    {
        $sessionType = session()->get('type');
        $sessionSecId = session()->get('sec_id');
        $sessionSubSecId = session()->get('sub_sec_id');

        $employee = $this->empModel->find($id);

        if (!$employee) {
            return view('404');
        }

        // Access control
        if ($sessionType === 'depart' && $employee->sec_id != $sessionSecId) {
            // Not allowed to view employee from another department
            return view('403'); // or redirect with error message
        }

        if ($sessionType === 'sub' && $employee->sub_sec_id != $sessionSubSecId) {
            // Not allowed to view employee from another sub-section
            return view('403'); // or redirect with error message
        }

        $data = $this->getBaseData();
        $data['pageTitle'] = $employee->name_arabic;
        $employee->photo = $this->getEmployeePhoto($employee);
        $data['row'] = $employee;
        $data['allLeaves'] = $this->leaveModel->where('emp_id', $id)->findAll();
        // $data['allMeds'] = $this->medModel->where('emp_id', $id)->findAll();
        $data['allMeds'] = $this->medModel->getAllforEmp($id, null, null);
        $data['allFdays'] = $this->fdayModel->where('emp_id', $id)->findAll();
        $data['secs'] = $this->secModel->findAll();
        $data['sub_secs'] = $this->subSecModel->findAll();
        $data['pageTitle'] = 'تفاصيل الموظف: ' . $employee->name_arabic;
        // var_dump($data['row']);die;
        return view('employees/show', $data);
    }

    public function upload()
    {
        $emp_id = $this->request->getPost('emp_id');
        $civil_id = $this->request->getPost('civil_id');
        
        if (!$employee = $this->empModel->find($emp_id)) {
            return view('404');
        }

        $file = $this->request->getFile('file');
        
        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'Invalid file uploaded');
        }

        $newName = $civil_id . '.' . $file->getExtension();
        $uploadPath = 'uploads/emps_photos/';
        $fullPath = $uploadPath . $newName;

        // Delete old file if exists
        if (file_exists(FCPATH . $fullPath)) {
            unlink(FCPATH . $fullPath);
        }

        if ($file->move(FCPATH . $uploadPath, $newName)) {
            $this->empModel->update($emp_id, ['photo' => $fullPath]);
            return redirect()->to("employees/update/" . $emp_id);
        }

        return redirect()->back()->with('error', 'Failed to upload photo');
    }

    public function new()
    {
        if (strtolower($this->session->get('type')) !== 'admin') {
            return $this->accessDenied();
        }

        $data = $this->getBaseData();
        $data['secs'] = $this->secModel->findAll();
        $data['sub_secs'] = $this->subSecModel->findAll();
        $data['designs'] = $this->designModel->findAll();
        $data['pageTitle'] = 'إضافة موظف جديد';
        return view('employees/new', $data);
    }

    public function create()
    {
        if (!$this->validateEmployee()) {
            $errors = $this->validation->getErrors();

            return redirect()->back()
                ->withInput()
                ->with('errors', $errors);
        }

        $data = $this->getEmployeeDataFromRequest();

        $this->empModel->transStart();

        try {
            if (!$empId = $this->empModel->insert($data)) {
                throw new \RuntimeException('Failed to create employee record');
            }

            $file = $this->request->getFile('file');

            if ($file && $file->isValid()) {
                $civil_id = $this->request->getPost('civil_id');
                $newName = $civil_id . '.' . $file->getExtension();
                $uploadPath = 'uploads/emps_photos/';
                $fullPath = $uploadPath . $newName;

                if (!is_dir(FCPATH . $uploadPath)) {
                    mkdir(FCPATH . $uploadPath, 0777, true);
                }

                if (file_exists(FCPATH . $fullPath)) {
                    unlink(FCPATH . $fullPath);
                }

                if (!$file->move(FCPATH . $uploadPath, $newName)) {
                    throw new \RuntimeException('Failed to upload photo');
                }

                if (!$this->empModel->update($empId, ['photo' => $fullPath])) {
                    throw new \RuntimeException('Failed to update employee photo');
                }
            }

            $this->empModel->transComplete();
            return redirect()->to("employees/show/" . $empId)
                ->with('success', 'Employee created successfully');

        } catch (\Exception $e) {
            $this->empModel->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!$employee = $this->empModel->find($id)) {
            return view('404');
        }

        if (strtolower($this->session->get('type')) !== 'admin') {
            return $this->accessDenied();
        }

        $data = $this->getBaseData();
        $data['row'] = $employee;
        $data['secs'] = $this->secModel->findAll();
        $data['sub_secs'] = $this->subSecModel->findAll();
        $data['designs'] = $this->designModel->findAll();
        $data['pageTitle'] = 'تعديل بيانات الموظف: ' . $employee->name_arabic;
        return view('employees/edit', $data);
    }

    public function update($emp_id)
    {
        if (!$this->validateEmployee($emp_id)) {
            $errors = $this->validation->getErrors();
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $data = $this->getEmployeeDataFromRequest();

        $this->empModel->transStart();

        try {
            if (!$this->empModel->update($emp_id, $data)) {
                throw new \RuntimeException('Failed to update employee data');
            }

            $file = $this->request->getFile('file');

            if ($file && $file->isValid()) {
                $employee = $this->empModel->find($emp_id);
                if (!$employee) {
                    throw new \RuntimeException('Employee not found');
                }
                $civil_id = $employee->civil_id; // Use existing civil_id for the new filename
                $newName = $civil_id . '.' . $file->getExtension();
                $uploadPath = 'uploads/emps_photos/';
                $fullPath = $uploadPath . $newName;

                if (!is_dir(FCPATH . $uploadPath)) {
                    mkdir(FCPATH . $uploadPath, 0777, true);
                }

                // Delete old photo if it exists and is not the default avatar
                if (!empty($employee->photo) &&
                    !strpos($employee->photo, 'male_avatar.jpg') &&
                    !strpos($employee->photo, 'female_avatar.jpg') &&
                    file_exists(FCPATH . $employee->photo)
                ) {
                    unlink(FCPATH . $employee->photo);
                }

                if (!$file->move(FCPATH . $uploadPath, $newName)) {
                    throw new \RuntimeException('Failed to upload new photo');
                }

                if (!$this->empModel->update($emp_id, ['photo' => $fullPath])) {
                    throw new \RuntimeException('Failed to update employee photo path');
                }
            }

            $this->empModel->transComplete();
            return redirect()->to("employees/show/" . $emp_id)
                ->with('success', 'Employee updated successfully');

        } catch (\Exception $e) {
            $this->empModel->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!$employee = $this->empModel->find($id)) {
            return view('404');
        }

        if ($this->empModel->delete($id)) {
            return redirect()->to('employees')->with('message', 'Employee deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete employee');
    }

    protected function setLastUrl()
    {
        $this->session->set('last_url', current_url());
    }

    protected function getBaseData()
    {
        return [
            'session' => $this->session,
            'empModel' => $this->empModel,
            'secModel' => $this->secModel,
            'subSecModel' => $this->subSecModel,
            'leaveModel' => $this->leaveModel,
            'medModel' => $this->medModel,
            'fdayModel' => $this->fdayModel,
            'designModel' => $this->designModel,
            'myFuns' => $this->myFuns
        ];
    }

    protected function validateEmployee($emp_id = null)
    {
        $validation = \Config\Services::validation(); // Explicitly get the validation service

        $rules = [
            'file_no' => 'required|is_unique[tbl_emps_data.file_no,id,' . $emp_id . ']',
            'civil_id' => 'required|numeric|exact_length[12]|is_unique[tbl_emps_data.civil_id,id,' . $emp_id . ']',
            'name_arabic' => 'required|max_length[100]',
            'name_english' => 'required|max_length[100]',
            'gender' => 'required|in_list[male,female,Other]',
            'mobile' => 'permit_empty|numeric|min_length[8]|max_length[15]',
            'join_date' => 'permit_empty|valid_date',
            'termination_date' => 'permit_empty|valid_date',
            'termination_reason' => 'permit_empty|max_length[255]',
            'design_id' => 'permit_empty|numeric',
            'sec_id' => 'permit_empty|numeric',
            'sub_sec_id' => 'permit_empty|numeric',
            'nation' => 'permit_empty|max_length[50]',
            'birth_date' => 'permit_empty|valid_date',
            'edu_cert' => 'permit_empty|max_length[100]',
            'permanent' => 'permit_empty|in_list[0,1]',
            'photo' => 'permit_empty',
            'experience' => 'permit_empty|max_length[100]',
            'active' => 'permit_empty|in_list[0,1]',
            'remarks' => 'permit_empty|max_length[255]',
            'payroll_category' => 'in_list[mmd,imd]',
            'has_overtime' => 'permit_empty|decimal'
        ];

        return $validation->setRules($rules)->run($this->request->getPost());
    }
    
    protected function getEmployeeDataFromRequest()
    {
        return [
            'file_no' => $this->myFuns->setEmptyToNull($this->request->getPost('file_no')),
            'name_arabic' => $this->request->getPost('name_arabic'),
            'name_english' => $this->request->getPost('name_english'),
            'gender' => $this->myFuns->setEmptyToNull($this->request->getPost('gender')),
            'mobile' => $this->myFuns->setEmptyToNull($this->request->getPost('mobile')),
            'civil_id' => $this->request->getPost('civil_id'),
            'join_date' => $this->request->getPost('join_date'),
            'termination_date' => $this->myFuns->setEmptyToNull($this->request->getPost('termination_date')),
            'termination_reason' => $this->myFuns->setEmptyToNull($this->request->getPost('termination_reason')),
            'design_id' => $this->myFuns->setEmptyToNull($this->request->getPost('design_id')),
            'sec_id' => $this->myFuns->setEmptyToNull($this->request->getPost('sec_id')),
            'sub_sec_id' => $this->myFuns->setEmptyToNull($this->request->getPost('sub_sec_id')),
            'nation' => $this->myFuns->setEmptyToNull($this->request->getPost('nation')),
            'birth_date' => $this->myFuns->setEmptyToNull($this->request->getPost('birth_date')),
            'edu_cert' => $this->myFuns->setEmptyToNull($this->request->getPost('edu_cert')),
            'permanent' => $this->myFuns->setEmptyToNull($this->request->getPost('permanent')),
            'experience' => $this->request->getPost('experience'),
            'active' => $this->request->getPost('active'),
            'remarks' => $this->myFuns->setEmptyToNull($this->request->getPost('remarks')),
            'payroll_category' => $this->myFuns->setEmptyToNull($this->request->getPost('payroll_category')),
            'has_overtime' => $this->myFuns->setEmptyToNull($this->request->getPost('has_overtime')),
        ];
    }

    protected function hasAccess($employee)
    {
        if ($this->session->get('type') == 'admin') {
            return true;
        }

        if( ($this->session->get('type') == 'depart') && $this->session->get('sec') == $employee->sec_id) {
            return true;
        }

        if( ($this->session->get('type') == 'sub') && $this->session->get('sub_sec') == $employee->sub_sec_id) {
            return true;
        }

        return false;
    }

    protected function accessDenied()
    {
        return "NOT ALLOWED, Go Back";
    }

    public function delEmpPhoto($emp_id)
    {
        if (!$employee = $this->empModel->find($emp_id)) {
            return view('404');
        }

        if (file_exists(FCPATH . $employee->photo)) {
            unlink(FCPATH . $employee->photo);
        }

        $this->empModel->update($emp_id, ['photo' => null]);
        return redirect()->to("employees/update/" . $emp_id);
    }

}