<?php

namespace App\Services;

use App\Models\TblAttendsDataModel;

class AttendanceService
{
    protected $attendanceModel;

    public function __construct()
    {
        $this->attendanceModel = new TblAttendsDataModel();
    }

    public function getAll()
    {
        return $this->attendanceModel->getAllAttendsData();
    }

    public function getById($id)
    {
        return $this->attendanceModel->getAttendsDataById($id);
    }

    public function create(array $data)
    {
        return $this->attendanceModel->createAttendsData($data);
    }

    public function update($id, array $data)
    {
        return $this->attendanceModel->updateAttendsData($id, $data);
    }

    public function delete($id)
    {
        return $this->attendanceModel->deleteAttendsData($id);
    }

    public function getEmpNOT($emp_id, $from, $to, $db_table = null)
    {
        return $this->attendanceModel->getEmpNOT($emp_id, $from, $to, $db_table);
    }

    public function getEmpFOT($emp_id, $from, $to, $db_table = null)
    {
        return $this->attendanceModel->getEmpFOT($emp_id, $from, $to, $db_table);
    }

    public function getEmpHOT($emp_id, $from, $to, $db_table = null)
    {
        return $this->attendanceModel->getEmpHOT($emp_id, $from, $to, $db_table);
    }

    public function getFPforEmp($emp_id, $from, $to, $db_table = null)
    {
        return $this->attendanceModel->getFPforEmp($emp_id, $from, $to, $db_table);
    }

    public function calEmpAttend($emp_id, $date, $db_table = null)
    {
        return $this->attendanceModel->calEmpAttend($emp_id, $date, $db_table);
    }

    public function prepareAttendForEmp($emp_id, $from_month, $from_year, $to_month, $to_year, $db_table = null, $fp_type = null)
    {
        return $this->attendanceModel->prepareAttendForEmp($emp_id, $from_month, $from_year, $to_month, $to_year, $db_table, $fp_type);
    }
}
