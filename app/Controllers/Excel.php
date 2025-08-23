<?php

namespace App\Controllers;

use App\Models\TblEmpsDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;
use App\Models\TblDesignsDataModel;
use App\Models\TblEmpsFdaysDataModel;
use App\Models\TblEmpsLeavesDataModel;
use App\Models\TblEmpsMedsDataModel;
use App\Models\TblHolidaysDataModel;
use App\Libraries\MyFuns;

class Excel extends BaseController
{
    protected $session;
    protected $empModel;
    protected $secModel;
    protected $subSecModel;
    protected $designModel;
    protected $fdayModel;
    protected $leaveModel;
    protected $medModel;
    protected $holidayModel;
    protected $myFuns;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->empModel = new TblEmpsDataModel();
        $this->secModel = new TblStnSecsDataModel();
        $this->subSecModel = new TblStnSubSecsDataModel();
        $this->designModel = new TblDesignsDataModel();
        $this->fdayModel = new TblEmpsFdaysDataModel();
        $this->leaveModel = new TblEmpsLeavesDataModel();
        $this->medModel = new TblEmpsMedsDataModel();
        $this->holidayModel = new TblHolidaysDataModel();
        $this->myFuns = new MyFuns();
    }

    public function empsSearch()
    {
        $this->session->set('last_url', current_url());
        $data['secs'] = $this->secModel->findAll();
        $data['sub_secs'] = $this->subSecModel->findAll();

        $data['designs'] = $this->designModel->findAll();

        $data['view'] = $this->request->getGet('view');
        $data['design_id'] = $this->request->getGet('design_id');
        $data['sec_id'] = $this->request->getGet('sec_id');
        $data['sub_sec_id'] = $this->request->getGet('sub_sec_id');
        $data['nation'] = $this->request->getGet('nation');
        $data['pay'] = $this->request->getGet('pay');
        $data['ot'] = $this->request->getGet('ot');
        $data['join_date_from'] = $this->request->getGet('join_date_from');
        $data['join_date_to'] = $this->request->getGet('join_date_to');
        $data['edu_cert'] = $this->request->getGet('edu_cert');
        $data['active'] = $this->request->getGet('active');
        $data['permanent'] = $this->request->getGet('permanent');

        $data['result'] = $this->empModel->getEmpDataSearch(
            $data['design_id'],
            $data['sec_id'],
            $data['sub_sec_id'],
            $data['nation'],
            $data['pay'],
            $data['ot'],
            $data['join_date_from'],
            $data['join_date_to'],
            $data['edu_cert'],
            $data['active'],
            $data['permanent']
        );

        $data['session'] = $this->session;
        $data['empModel'] = $this->empModel;
        $data['secModel'] = $this->secModel;
        $data['subSecModel'] = $this->subSecModel;
        $data['leaveModel'] = $this->leaveModel;
        $data['medModel'] = $this->medModel;
        $data['fdayModel'] = $this->fdayModel;
        $data['designModel'] = $this->designModel;
        $data['myFuns'] = $this->myFuns;

        return view('excel_empsSearch', $data);
    }

    public function permissions()
    {
        $this->session->set('last_url', current_url());

        $data['secs'] = $this->secModel->findAll();
        $data['sub_secs'] = $this->subSecModel->findAll();

        $data['from'] = $this->request->getGet('from');
        $data['to'] = $this->request->getGet('to');
        $data['arrange_by'] = $this->request->getGet('arrange_by');
        $data['arrange_order'] = $this->request->getGet('arrange_order');
        $data['sec_id'] = $this->request->getGet('sec_id');
        $data['sub_sec_id'] = $this->request->getGet('sub_sec_id');

        $data['result'] = $this->fdayModel->getPermDataSearch(
            $data['from'],
            $data['to'],
            $data['sec_id'],
            $data['sub_sec_id'],
            $data['arrange_by'],
            $data['arrange_order']
        );

        $data['session'] = $this->session;
        $data['empModel'] = $this->empModel;
        $data['secModel'] = $this->secModel;
        $data['subSecModel'] = $this->subSecModel;
        $data['leaveModel'] = $this->leaveModel;
        $data['medModel'] = $this->medModel;
        $data['fdayModel'] = $this->fdayModel;
        $data['designModel'] = $this->designModel;
        $data['myFuns'] = $this->myFuns;

        return view('excel_permsSearch', $data);
    }

    public function leaves()
    {
        $this->session->set('last_url', current_url());

        $data['secs'] = $this->secModel->findAll();
        $data['sub_secs'] = $this->subSecModel->findAll();

        $data['from'] = $this->request->getGet('from');
        $data['to'] = $this->request->getGet('to');
        $data['sec_id'] = $this->request->getGet('sec_id');
        $data['sub_sec_id'] = $this->request->getGet('sub_sec_id');
        $data['arrange_by'] = $this->request->getGet('arrange_by');
        $data['arrange_order'] = $this->request->getGet('arrange_order');

        $data['result'] = $this->leaveModel->getLeaveDataSearch(
            $data['from'],
            $data['to'],
            $data['sec_id'],
            $data['sub_sec_id'],
            $data['arrange_by'],
            $data['arrange_order']
        );

        $data['session'] = $this->session;
        $data['empModel'] = $this->empModel;
        $data['secModel'] = $this->secModel;
        $data['subSecModel'] = $this->subSecModel;
        $data['leaveModel'] = $this->leaveModel;
        $data['medModel'] = $this->medModel;
        $data['fdayModel'] = $this->fdayModel;
        $data['designModel'] = $this->designModel;
        $data['myFuns'] = $this->myFuns;

        return view('excel_leavesSearch', $data);
    }

    public function medicals()
    {
        $this->session->set('last_url', current_url());

        $data['secs'] = $this->secModel->findAll();
        $data['sub_secs'] = $this->subSecModel->findAll();

        $data['from'] = $this->request->getGet('from');
        $data['to'] = $this->request->getGet('to');
        $data['sec_id'] = $this->request->getGet('sec_id');
        $data['sub_sec_id'] = $this->request->getGet('sub_sec_id');
        $data['arrange_by'] = $this->request->getGet('arrange_by');
        $data['arrange_order'] = $this->request->getGet('arrange_order');

        $data['result'] = $this->medModel->getMedicalDataSearch(
            $data['from'],
            $data['to'],
            $data['sec_id'],
            $data['sub_sec_id'],
            $data['arrange_by'],
            $data['arrange_order']
        );

        $data['session'] = $this->session;
        $data['empModel'] = $this->empModel;
        $data['secModel'] = $this->secModel;
        $data['subSecModel'] = $this->subSecModel;
        $data['leaveModel'] = $this->leaveModel;
        $data['medModel'] = $this->medModel;
        $data['fdayModel'] = $this->fdayModel;
        $data['designModel'] = $this->designModel;
        $data['myFuns'] = $this->myFuns;
        
        
        return view('excel_medsSearch', $data);
    }
}
