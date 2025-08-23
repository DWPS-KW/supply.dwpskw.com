<?php

namespace App\Controllers;

use App\Models\TblEmpsDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;
use App\Libraries\myFuns;

class StnSec extends BaseController
{
    protected $session;
    protected $empModel;
    protected $secModel;
    protected $subSecModel;
    protected $myFuns;

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor if needed

        $this->session = \Config\Services::session();
        $this->empModel = new TblEmpsDataModel();
        $this->secModel = new TblStnSecsDataModel();
        $this->subSecModel = new TblStnSubSecsDataModel();
        $this->myFuns = new myFuns();

    }

    public function index()
    {
    }

    public function browseLoadSec()
    {
        return view('subSecLoad', $data);
    }


    // view searching results fun
    public function browseLoadSubSecEmpCreate()
    {
        $sec_id = $this->request->getGet("sec_id");
        $sub_sec_id = $this->request->getGet("sub_sec_id");

        $data['sub_secs'] = $this->secModel->getAllSubSec($sec_id);

        $data['sec_id'] = $sec_id;
        $data['sub_sec_id'] = $sub_sec_id;

        return view('subSecLoadEmpCreate', $data);
    }

    // view searching results fun
    public function browseLoadSubSecEmpEdit($sec_id = null, $emp_sub_sec = null)
    {
        $sec_id = $this->request->getGet("sec_id");
        $sub_sec_id = $this->request->getGet("sub_sec_id");

        $data['sub_secs'] = $this->secModel->getAllSubSec($sec_id);

        $data['sec_id'] = $sec_id;
        $data['sub_sec_id'] = $sub_sec_id;

        $data['emp_sub_sec'] = $emp_sub_sec;
        return view('subSecLoadEmpEdit', $data);
    }
}