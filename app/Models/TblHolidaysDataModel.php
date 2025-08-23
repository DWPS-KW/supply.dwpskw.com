<?php

namespace App\Models;

use CodeIgniter\Model;

class TblHolidaysDataModel extends Model
{
    protected $table            = 'tbl_holidays_data';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'start_date',
        'end_date',
        'name',
        'descrp',
    ];

    protected $useTimestamps = false;

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

public function getHolidaysForRange($from, $to)
{
    return $this->where('start_date <=', $to)
                ->where('end_date >=', $from)
                ->orderBy('start_date', 'ASC')
                ->findAll();
}


    public function ifExist($id = null)
    {
        return $this->where('id', $id)->countAllResults() === 1;
    }

    public function checkIFDayIsHoliday(string $date): bool
    {
        return $this->where('start_date <=', $date)
                    ->where('end_date >=', $date)
                    ->countAllResults() > 0;
    }

    public function checkDuplicate($start = null, $end = null)
    {
        return $this->where('start_date', $start)
                    ->where('end_date', $end)
                    ->countAllResults() > 0;
    }

    public function checkDuplicateForUpdate($id = null, $start = null, $end = null)
    {
        return $this->where('start_date', $start)
                    ->where('end_date', $end)
                    ->where('id !=', $id)
                    ->countAllResults() > 0;
    }

    public function getById($id = null)
    {
        return $this->where('id', $id)->first();
    }

    public function getAll($from = null, $to = null, $sort = 'asc')
    {
        // Ensure $sort is always a valid string
        $sort = strtolower($sort);
        if ($sort !== 'asc' && $sort !== 'desc') {
            $sort = 'asc';
        }

        if ($from !== null && $to !== null) {
            $this->groupStart()
                 ->where('start_date <=', $to)
                 ->where('end_date >=', $from)
                 ->groupEnd();
        }

        return $this->orderBy('start_date', $sort)->findAll();
    }

    public function getAllHolidays()
    {
        return $this->orderBy('start_date', 'asc')->findAll();
    }

    public function getHolidayById($id)
    {
        return $this->find($id);
    }

    public function createHoliday($data)
    {
        return $this->insert($data);
    }

    public function updateHoliday($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteHoliday($id)
    {
        return $this->delete($id);
    }
}
