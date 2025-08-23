<?php

namespace App\Models;

use CodeIgniter\Model;

class TblAttendsCoverlistOtModel extends Model
{
    protected $table            = 'tbl_attends_coverlist_ot';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'emp_id',
        'month',
        'year',
        'normal_ot',
        'friday_ot',
        'holiday_ot',
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

    public function getAllAttendsCoverlistOt()
    {
        return $this->findAll();
    }

    public function getAttendsCoverlistOtById($id)
    {
        return $this->find($id);
    }

    public function createAttendsCoverlistOt($data)
    {
        return $this->insert($data);
    }

    public function updateAttendsCoverlistOt($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteAttendsCoverlistOt($id)
    {
        return $this->delete($id);
    }

    public function checkDuplicate(int $emp_id, $month, int $year): bool
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
