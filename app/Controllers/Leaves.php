<?php

namespace App\Controllers;

use App\Models\TblEmpsDataModel;
use App\Models\TblEmpsLeavesDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;
use App\Models\TblDesignsDataModel;
use App\Libraries\MyFuns;
use App\Libraries\DateUtils;
use CodeIgniter\Controller as BaseController;
use Config\Services;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class Leaves extends BaseController
{
    protected $session;
    protected $empModel;
    protected $leaveModel;
    protected $secModel;
    protected $subSecModel;
    protected $designModel;
    protected $myFuns;
    protected $validation;
    protected $dateUtils;

    public function __construct()
    {
        $this->session = session();
        $this->empModel = new TblEmpsDataModel();
        $this->leaveModel = new TblEmpsLeavesDataModel();
        $this->secModel = new TblStnSecsDataModel();
        $this->subSecModel = new TblStnSubSecsDataModel();
        $this->designModel = new TblDesignsDataModel();
        $this->myFuns = new MyFuns();
        $this->validation = Services::validation();
        $this->dateUtils = new DateUtils();
    }

    public function index()
    {
        $page = $this->request->getGet('page') ? (int)$this->request->getGet('page') : 1;
        $perPage = 20;

        $data['sections'] = $this->secModel->getAllSec('id');
        $data['sub_secs'] = $this->subSecModel->findAll();

        $data['filters'] = [
            'from' => $this->request->getGet('from'),
            'to' => $this->request->getGet('to'),
            'sec_id' => $this->request->getGet('sec_id'),
            'sub_sec_id' => $this->request->getGet('sub_sec_id'),
            'arrange_by' => $this->request->getGet('arrange_by'),
            'arrange_order' => $this->request->getGet('arrange_order'),
        ];

        $data['result'] = $this->leaveModel->getLeaveDataSearch($data['filters'], $perPage, $page);

        $data['sec_id'] = $data['filters']['sec_id'];
        $data['sub_sec_id'] = $data['filters']['sub_sec_id'];
        $data['from'] = $data['filters']['from'];
        $data['to'] = $data['filters']['to'];
        $data['arrange_by'] = $data['filters']['arrange_by'];
        $data['arrange_order'] = $data['filters']['arrange_order'];

        $data['empModel'] = $this->empModel;
        $data['secModel'] = $this->secModel;
        $data['subSecModel'] = $this->subSecModel;
        $data['leaveModel'] = $this->leaveModel;
        $data['designModel'] = $this->designModel;
        $data['pageTitle'] = 'بيانات الإجازات';

        return view('leaves/search', $data);
    }

    public function exportToExcel()
    {
        $filters = [
            'from' => $this->request->getGet('from'),
            'to' => $this->request->getGet('to'),
            'sec_id' => $this->request->getGet('sec_id'),
            'sub_sec_id' => $this->request->getGet('sub_sec_id'),
            'arrange_by' => $this->request->getGet('arrange_by'),
            'arrange_order' => $this->request->getGet('arrange_order'),
        ];

        $leavesResult = $this->leaveModel->getLeaveDataSearch($filters, 0);
        $leaves = $leavesResult['pagedLeaves'] ?? [];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Leaves Data');
        $sheet->setRightToLeft(true);

        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'الأسم');
        $sheet->setCellValue('C1', 'الوظيفة');
        $sheet->setCellValue('D1', 'المدة');
        $sheet->setCellValue('E1', 'من');
        $sheet->setCellValue('F1', 'إلى');
        $sheet->setCellValue('G1', 'المراقبة');
        $sheet->setCellValue('H1', 'القسم');

        $row = 2;
        $i = 1;
        foreach ($leaves as $leave) {
            $sheet->setCellValue('A' . $row, $i++);
            $sheet->setCellValue('B' . $row, $leave ? $leave->emp_name_english : 'غير محدد');
            $sheet->setCellValue('C' . $row, $leave ? $leave->emp_design_name ?? 'غير محدد' : 'غير محدد');
            $sheet->setCellValue('D' . $row, $leave->duration ?? 'غير محدد');
            $sheet->setCellValue('E' . $row, $leave->begin ?? 'غير محدد');
            $sheet->setCellValue('F' . $row, $leave->end ?? 'غير محدد');
            $sheet->setCellValue('G' . $row, $leave ? $leave->emp_sec_name_arabic ?? 'غير محدد' : 'غير محدد');
            $sheet->setCellValue('H' . $row, $leave ? $leave->emp_sub_sec_name_arabic ?? 'غير محدد' : 'غير محدد');
            $row++;
        }

        $highestColumn = $sheet->getHighestColumn();
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'leaves.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    public function create()
    {
        $existingLeaves = $this->leaveModel
            ->where('emp_id', $this->request->getPost('emp_id'))
            ->findAll();

        $data = [
            'emp_id'  => $this->request->getPost('emp_id'),
            'begin'   => $this->request->getPost('begin'),
            'end'     => $this->request->getPost('end'),
            'remarks' => $this->request->getPost('remarks'),
            'existingLeaves' => $existingLeaves,
        ];

        $rules = [
            'emp_id'  => 'required|numeric|is_not_unique[tbl_emps_data.id]',
            'begin'   => 'required|valid_date',
            'end'     => 'required|valid_date|date_greater_than_equal_to[begin]|check_date_range_overlap[begin,end,existingLeaves]',
            'remarks' => 'permit_empty|max_length[255]',
        ];

        $messages = [
            'check_date_range_overlap' => [
                'check_date_range_overlap' => 'The selected leave period overlaps with an existing leave.',
            ],
            'date_greater_than_equal_to' => [
                'date_greater_than_equal_to' => 'The end date must be greater than or equal to the begin date.',
            ]
        ];

        if (!$this->validateData($data, $rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $begin = $this->request->getPost('begin');
        $end = $this->request->getPost('end');
        $duration = $this->dateUtils->getDateDiffInDays($begin, $end);

        $data = [
            'emp_id' => $this->request->getPost('emp_id'),
            'begin' => $begin,
            'end' => $end,
            'duration' => $duration,
            'remarks' => $this->request->getPost('remarks'),
        ];

        if ($this->leaveModel->insert($data)) {
            return redirect()->to("employees/show/" . $data['emp_id'])->with('success', 'Leave recorded successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to record leave.');
        }
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $leave = $this->leaveModel->find($id);
        if (!$leave) {
            return redirect()->back()->with('error', 'Leave record not found.');
        }

        $existingLeaves = $this->leaveModel
            ->where('emp_id', $this->request->getPost('emp_id'))
            ->findAll();

        $data = [
            'id' => $id,
            'emp_id'  => $this->request->getPost('emp_id'),
            'begin'   => $this->request->getPost('begin'),
            'end'     => $this->request->getPost('end'),
            'remarks' => $this->request->getPost('remarks'),
            'existingLeaves' => $existingLeaves,
        ];

        $rules = [
            'emp_id'  => 'required|numeric|is_not_unique[tbl_emps_data.id]',
            'begin'   => 'required|valid_date',
            'end'     => 'required|valid_date|date_greater_than_equal_to[begin]|check_date_range_overlap[begin,end,existingLeaves]',
            'remarks' => 'permit_empty|max_length[255]',
        ];

        $messages = [
            'check_date_range_overlap' => [
                'check_date_range_overlap' => 'The selected leave period overlaps with an existing leave.',
            ],
            'date_greater_than_equal_to' => [
                'date_greater_than_equal_to' => 'The end date must be greater than or equal to the begin date.',
            ]
        ];

        if (!$this->validateData($data, $rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $begin = $this->request->getPost('begin');
        $end = $this->request->getPost('end');
        $duration = $this->dateUtils->getDateDiffInDays($begin, $end);

        $data = [
            'emp_id' => $this->request->getPost('emp_id'),
            'begin' => $begin,
            'end' => $end,
            'duration' => $duration,
            'remarks' => $this->request->getPost('remarks'),
        ];

        if ($this->leaveModel->update($id, $data)) {
            return redirect()->to("employees/show/" . $data['emp_id'])->with('success', 'Leave updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update leave.');
        }
    }
    public function delete()
    {
        $id = $this->request->getPost('id');
        $leave = $this->leaveModel->find($id);

        if ($leave) {
            if ($this->leaveModel->delete($id)) {
                return redirect()->to("employees/show/" . $leave->emp_id)->with('success', 'Leave deleted successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to delete leave.');
            }
        } else {
            return redirect()->back()->with('error', 'Leave record not found.');
        }
    }
}
