<?php

namespace App\Models;

use CodeIgniter\Model;

class TblAttendsDataBeforeModel extends Model
{
    protected $table            = 'tbl_attends_data_before';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ac_no',
        'date',
        'on_dutty',
        'off_dutty',
        'clock_in',
        'clock_out',
        'late',
        'early',
        'absent',
        'clock_in_out_time',
        'week',
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

    public function getAllAttendsDataBefore()
    {
        return $this->findAll();
    }

    public function getAttendsDataBeforeById($id)
    {
        return $this->find($id);
    }

    public function createAttendsDataBefore($data)
    {
        return $this->insert($data);
    }

    public function updateAttendsDataBefore($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteAttendsDataBefore($id)
    {
        return $this->delete($id);
    }
}
//Display
// TblAttendsDataBeforeModel CRUD Methods:
// getAllAttendsDataBefore(), getAttendsDataBeforeById($id), createAttendsDataBefore($data), updateAttendsDataBefore($id, $data), deleteAttendsDataBefore($id)