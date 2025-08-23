<?php

namespace App\Controllers;

use App\Models\TblEmpsDataModel;
use App\Models\TblEmpsMedsDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;
use App\Models\TblDesignsDataModel;
use App\Libraries\myFuns;
use App\Libraries\DateUtils; // If still used
use CodeIgniter\Controller as BaseController;
use Config\Services;

class Medicals extends BaseController
{
    protected $session;
    protected $empModel;
    protected $medModel;
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
        $this->medModel = new TblEmpsMedsDataModel();
        $this->secModel = new TblStnSecsDataModel();
        $this->subSecModel = new TblStnSubSecsDataModel();
        $this->designModel = new TblDesignsDataModel();
        $this->myFuns = new myFuns();
        $this->validation = Services::validation();
        $this->dateUtils = new DateUtils();
    }
    
    public function index()
    {
        $data['secs'] = $this->secModel->getAllSec('id');
        $data['sub_secs'] = $this->subSecModel->getAllSubSec(null, 'parent_id');

        $from = $this->request->getGet('from');
        $to = $this->request->getGet('to');
        $emp_id = $this->request->getGet('emp_id');
        $sec_id = $this->request->getGet('sec_id');
        $sub_sec_id = $this->request->getGet('sub_sec_id');
        $arrange_by = $this->request->getGet('arrange_by');
        $arrange_order = $this->request->getGet('arrange_order');
        $page = $this->request->getGet('page') ?? 1; 
        $perPage = $this->request->getGet('perPage') ?? 20;

        $filters = [
            'from' => $from,
            'to' => $to,
            'emp_id' => $emp_id,
            'sec_id' => $sec_id,
            'sub_sec_id' => $sub_sec_id,
            'arrange_by' => $arrange_by,
            'arrange_order' => $arrange_order,
        ];

        $data['result'] = $this->medModel->getMedDataSearch($filters, (int)$perPage, (int)$page);

        // Pass filter values for form persistence
        $data['from'] = $from;
        $data['to'] = $to;
        $data['emp_id'] = $emp_id;
        $data['sec_id'] = $sec_id;
        $data['sub_sec_id'] = $sub_sec_id;
        $data['arrange_by'] = $arrange_by;
        $data['arrange_order'] = $arrange_order;

        $data['session'] = $this->session;

        return view('medicals/search', $data);
    }

    public function create()
    {
        $existingMeds = $this->medModel
            ->where('emp_id', $this->request->getPost('emp_id'))
            ->findAll();

        $data = [
            'emp_id'   => $this->request->getPost('emp_id'),
            'start_date'     => $this->request->getPost('start_date'),
            'end_date'       => $this->request->getPost('end_date'),
            'descrp'   => $this->request->getPost('descrp'),
            'remarks'  => $this->request->getPost('remarks'),
            'existingMeds' => $existingMeds,
        ];

        $rules = [
            'emp_id'   => 'required|numeric|is_not_unique[tbl_emps_data.id]',
            'start_date'     => 'required|valid_date',
            'end_date'       => 'required|valid_date|date_greater_than_equal_to[start_date]',
            'descrp'   => 'max_length[255]',
            'remarks'  => 'permit_empty|max_length[255]',
        ];

        if (!$this->validateData($data, $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $dataToInsert = [
            'emp_id'       => $this->request->getPost('emp_id'),
            'start_date'         => $this->request->getPost('start_date'),
            'end_date'           => $this->request->getPost('end_date'),
            'descrp'       => $this->request->getPost('descrp'),
            'remarks'      => $this->request->getPost('remarks'),
            'create_by_id' => $this->session->get('id'),
        ];

        if ($this->medModel->insert($dataToInsert)) {
            return redirect()->to("employees/show/" . $dataToInsert['emp_id'])
                ->with('success', 'Medical record created successfully.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create medical record.');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        if (!$id || !is_numeric($id)) {
            return redirect()->back()->with('error', 'Invalid medical record ID for update.');
        }

        $medical = $this->medModel->find($id);
        if (!$medical) {
            return redirect()->back()->with('error', 'Medical record not found.');
        }

        $existingMeds = $this->medModel
            ->where('emp_id', $this->request->getPost('emp_id'))
            ->findAll();

        $data = [
            'id'       => $id,
            'emp_id'   => $this->request->getPost('emp_id'),
            'start_date'     => $this->request->getPost('start_date'),
            'end_date'       => $this->request->getPost('end_date'),
            'descrp'   => $this->request->getPost('descrp'),
            'remarks'  => $this->request->getPost('remarks'),
            'existingMeds' => $existingMeds,
        ];

        $rules = [
            'emp_id'   => 'required|numeric|is_not_unique[tbl_emps_data.id]',
            'start_date'     => 'required|valid_date',
            'end_date'       => 'required|valid_date|date_greater_than_equal_to[start_date]',
            'descrp'   => 'max_length[255]',
            'remarks'  => 'permit_empty|max_length[255]',
        ];

        if (!$this->validateData($data, $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $dataToUpdate = [
            'emp_id'   => $this->request->getPost('emp_id'),
            'start_date'     => $this->request->getPost('start_date'),
            'end_date'       => $this->request->getPost('end_date'),
            'descrp'   => $this->request->getPost('descrp'),
            'remarks'  => $this->request->getPost('remarks'),
        ];

        if ($this->medModel->update($id, $dataToUpdate)) {
            return redirect()->to("employees/show/" . $dataToUpdate['emp_id'])
                ->with('success', 'Medical record updated successfully.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update medical record.');
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        if (!$id || !is_numeric($id)) {
            return redirect()->back()
                ->with('error', 'Invalid medical record ID.');
        }
        $medical = $this->medModel->find($id);

        if (!$medical) {
            return redirect()->back()
                ->with('error', 'Medical record not found.');
        }

        $emp_id = $medical->emp_id;

        if ($this->medModel->delete($id)) {
            return redirect()->to("employees/show/" . $emp_id)
                ->with('success', 'Medical record deleted successfully.');
        }

        return redirect()->back()
            ->with('error', 'Failed to delete medical record.');
    }
}
