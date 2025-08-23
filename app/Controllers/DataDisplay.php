<?php

namespace App\Controllers;

use App\Models\TblAdminsDataModel;
use App\Models\TblEmpsDataModel;
use App\Models\TblEmpsFdaysDataModel;
use App\Models\TblEmpsLeavesDataModel;
use App\Models\TblEmpsMedsDataModel;
use App\Models\TblHolidaysDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;

class DataDisplay extends BaseController
{
    protected $adminsModel;
    protected $empModel;
    protected $fdayModel;
    protected $leaveModel;
    protected $medModel;
    protected $holidayModel;
    protected $stnSecsModel;
    protected $stnSubSecsModel;

    public function __construct()
    {
        $this->adminsModel = new TblAdminsDataModel();
        $this->empModel = new TblEmpsDataModel();
        $this->fdayModel = new TblEmpsFdaysDataModel();
        $this->leaveModel = new TblEmpsLeavesDataModel();
        $this->medModel = new TblEmpsMedsDataModel();
        $this->holidayModel = new TblHolidaysDataModel();
        $this->stnSecsModel = new TblStnSecsDataModel();
        $this->stnSubSecsModel = new TblStnSubSecsDataModel();
    }

    public function displayAdmins()
    {
        $admins = $this->adminsModel->findAll();
        var_dump($admins); // Debugging line to check the data
        // return view('data_display/admins', ['admins' => $admins]);
    }

    public function displayEmployees()
    {
        $employees = $this->empModel->findAll();
        var_dump($employees); // Debugging line to check the data
        // return view('data_display/employees', ['employees' => $employees]);
    }

    public function displayFdays()
    {
        $fdays = $this->fdayModel->findAll();
        var_dump($fdays);
        // return view('data_display/fdays', ['fdays' => $fdays]);
    }

    public function displayLeaves()
    {
        $leaves = $this->leaveModel->findAll();
        var_dump($leaves); // Debugging line to check the data
        // return view('data_display/leaves', ['leaves' => $leaves]);
    }

    public function displayMeds()
    {
        $meds = $this->medModel->findAll();
        var_dump($meds); // Debugging line to check the data
        // return view('data_display/meds', ['meds' => $meds]);
    }

    public function displayHolidays()
    {
        $holidays = $this->holidayModel->findAll();
        var_dump($holidays); // Debugging line to check the data
        // return view('data_display/holidays', ['holidays' => $holidays]);
    }

    public function displayStnSecs()
    {
        $sections = $this->stnSecsModel->findAll();
        var_dump($sections); // Debugging line to check the data
        // return view('data_display/stn_secs', ['sections' => $sections]);
    }

    public function displayStnSubSecs()
    {
        $subSections = $this->stnSubSecsModel->findAll();
        var_dump($subSections); // Debugging line to check the data
        // return view('data_display/stn_sub_secs', ['subSections' => $subSections]);
    }
}