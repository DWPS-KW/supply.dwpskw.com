<?php

namespace App\Models;

use CodeIgniter\Model;

class TblAttendsCoverlistModel extends Model
{
    protected $table            = 'tbl_attends_coverlist';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'emp_id',
        'month',
        'year',
        'working_days',
        'med_days',
        'absent_days',
        'leave_days',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAllAttendsCoverlist()
    {
        return $this->findAll();
    }

    public function getAttendsCoverlistById($id)
    {
        return $this->find($id);
    }

    public function createAttendsCoverlist($data)
    {
        return $this->insert($data);
    }

    public function updateAttendsCoverlist($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteAttendsCoverlist($id)
    {
        return $this->delete($id);
    }

    public function checkDuplicate(int $emp_id, int $month, int $year): bool
    {
        return $this->where('emp_id', $emp_id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->countAllResults() > 0;
    }

    public function getDataByEmpMonthYear(int $emp_id, $month, int $year): ?object
    {
        return $this->where('emp_id', $emp_id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->first();
    }
}
