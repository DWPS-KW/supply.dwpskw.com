<?php

namespace App\Controllers;

use App\Models\TblEmpsDataModel;
use App\Models\TblEmpsFdaysDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;
use App\Models\TblDesignsDataModel;
use App\Libraries\myFuns;
use CodeIgniter\Controller as BaseController;
use Config\Services;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class Fdays extends BaseController
{
    protected $session;
    protected $empModel;
    protected $fdayModel;
    protected $secModel;
    protected $subSecModel;
    protected $designModel;
    protected $myFuns;
    protected $validation;

    public function __construct()
    {
        $this->session = session();
        $this->empModel = new TblEmpsDataModel();
        $this->fdayModel = new TblEmpsFdaysDataModel();
        $this->secModel = new TblStnSecsDataModel();
        $this->subSecModel = new TblStnSubSecsDataModel();
        $this->designModel = new TblDesignsDataModel();
        $this->myFuns = new myFuns();
        $this->validation = Services::validation();
    }

    public function index()
    {
        $data['sections'] = $this->secModel->getAllSec('id');
        $data['sub_sections'] = $this->subSecModel->findAll();

        $data['filters'] = [
            'from'          => $this->request->getGet('from'),
            'to'            => $this->request->getGet('to'),
            'sec_id'        => $this->request->getGet('sec_id'),
            'sub_sec_id'    => $this->request->getGet('sub_sec_id'),
            'arrange_by'    => $this->request->getGet('arrange_by'),
            'arrange_order' => $this->request->getGet('arrange_order'),
            'view'          => $this->request->getGet('view'),
        ];

        $data['result'] = $this->fdayModel->getPermDataSearch($data['filters']);

        $data['empModel'] = $this->empModel;
        $data['secModel'] = $this->secModel;
        $data['subSecModel'] = $this->subSecModel;
        $data['fdayModel'] = $this->fdayModel;
        $data['designModel'] = $this->designModel;
        $data['pageTitle'] = 'أذونات اليوم الكامل';
        // var_dump($data['result']);die;
        return view('perms/search', $data);
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

        $fDaysResult = $this->fdayModel->getPermDataSearch($filters);
        $fDays = $fDaysResult['pagedFdays'] ?? []; // Access the paged data

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Permissions Data');
        $sheet->setRightToLeft(true);

        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'الأسم');
        $sheet->setCellValue('C1', 'رقم الملف');
        $sheet->setCellValue('D1', 'التاريخ');
        $sheet->setCellValue('E1', 'المراقبة');
        $sheet->setCellValue('F1', 'القسم');
        $sheet->setCellValue('G1', 'ملاحظات');

        $row = 2;
        $i = 1;
        foreach ($fDays as $fDay) {
            $employee = $this->empModel->find($fDay->emp_id); // Fetch employee data
            $sheet->setCellValue('A' . $row, $i++);
            $sheet->setCellValue('B' . $row, $fDay ? $fDay->emp_name_english : 'غير محدد'); // Employee Name
            $sheet->setCellValue('C' . $row, $fDay ? $fDay->emp_file_no : 'غير محدد');   // File Number
            $sheet->setCellValue('D' . $row, $fDay->date);
            $sheet->setCellValue('E' . $row, $fDay ? $fDay->emp_sec_name_arabic ?? 'غير محدد' : 'غير محدد'); // Section Name
            $sheet->setCellValue('F' . $row, $fDay ? $fDay->emp_sub_sec_name_arabic ?? 'غير محدد' : 'غير محدد'); // Subsection Name
            $sheet->setCellValue('G' . $row, $fDay->remarks);
            $row++;
        }

        $highestColumn = $sheet->getHighestColumn();
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'permissions.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    protected function validateFday()
    {
        $rules = [
            'emp_id' => 'required|numeric|is_not_unique[tbl_emps_data.id]',
            'date' => 'required|valid_date',
            'remarks' => 'permit_empty|max_length[255]'
        ];
        
        return $this->validation->setRules($rules)->run($this->request->getPost());
    }

    public function create()
    {
        if (!$this->validateFday()) {
            $errors = $this->validation->getErrors();
            return redirect()->back()
                ->withInput()
                ->with('errors', $errors);
        }

        $emp_id = $this->request->getPost('emp_id');
        $date = $this->request->getPost('date');
        $remarks = $this->request->getPost('remarks');

        if ($this->fdayModel->checkIFHasPermInMonth($emp_id, $date)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Only one full-day permission is allowed per month.');
        }

        $data = [
            'emp_id' => $emp_id,
            'date' => $date,
            'remarks' => $remarks
        ];

        if ($this->fdayModel->insert($data)) {
            return redirect()->to("employees/show/$emp_id")
                ->with('success', 'Full-day permission created successfully.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create full-day permission.');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        
        if (!$this->validateFday()) {
            $errors = $this->validation->getErrors();
            return redirect()->back()
                ->withInput()
                ->with('errors', $errors);
        }

        $emp_id = $this->request->getPost('emp_id');
        $date = $this->request->getPost('date');
        $remarks = $this->request->getPost('remarks');

        if ($this->fdayModel->checkIFHasPermInMonthforUpdate($id, $emp_id, $date)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Only one full-day permission is allowed per month.');
        }

        $data = [
            'emp_id' => $emp_id,
            'date' => $date,
            'remarks' => $remarks
        ];

        if ($this->fdayModel->update($id, $data)) {
            return redirect()->to("employees/show/$emp_id")
                ->with('success', 'Full-day permission updated successfully.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update full-day permission.');
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        if (!$this->fdayModel->ifExist($id)) {
            return redirect()->back()
                ->with('error', 'Full-day permission record not found.');
        }
        $fday = $this->fdayModel->find($id);
        
        if (!$fday) {
            return redirect()->back()
                ->with('error', 'Full-day permission record not found.');
        }

        $emp_id = $fday->emp_id;
        
        if ($this->fdayModel->delete($id)) {
            return redirect()->to("employees/show/$emp_id")
                ->with('success', 'Full-day permission deleted successfully.');
        }

        return redirect()->back()
            ->with('error', 'Failed to delete full-day permission.');
    }
}