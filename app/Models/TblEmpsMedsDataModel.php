<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Exceptions\DatabaseException;
use Config\Services;
use CodeIgniter\Libraries\DateUtils;

class TblEmpsMedsDataModel extends Model
{
    protected $table            = 'tbl_emps_meds_data';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'emp_id',
        'start_date',
        'end_date',
        'descrp',
        'remarks',
        'create_by_id',
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'create_date';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    private ?\CodeIgniter\Session\Session $session;
    private ?\App\Libraries\MyFuns $myFuns;
    private ?TblEmpsDataModel $empModel;
    private ?\App\Libraries\DateUtils $dateUtils;
    private string $defaultStartDate;
    private string $defaultEndDate;

    public function __construct()
    {
        parent::__construct();
        $this->session = Services::session();
        $this->myFuns = new \App\Libraries\MyFuns();
        $this->empModel = new TblEmpsDataModel();
        $this->dateUtils = new \App\Libraries\DateUtils();
        $this->defaultStartDate = '1970-01-01';
        $this->defaultEndDate = date('Y-m-d', strtotime('+1 year'));
    }


    public function ifExist(?int $id = null): bool
    {
        return $this->where('id', $id)->countAllResults() > 0;
    }

    public function getById(?int $id = null): ?object
    {
        return $this->find($id);
    }

    public function checkIFDayIsMed(?int $emp_id = null, ?string $date = null): bool
    {
        $empMeds = $this->getAllforEmp($emp_id);
        foreach ($empMeds as $empMed) {
            if ($date >= $empMed->start_date && $date <= $empMed->end_date) {
                return true;
            }
        }
        return false;
    }


    public function getAllforEmp(?int $emp_id = null, ?string $from = null, ?string $to = null): array
    {
        $builder = $this->builder();

        if ($from !== null) {
            $builder->where('end_date >=', $from);
        }

        if ($to !== null) {
            $builder->where('start_date <=', $to);
        }

        $builder->where('emp_id', $emp_id);
        $results = $builder->get()->getResult();

        foreach ($results as $item) {
            $start = new \DateTime($item->start_date);
            $end = new \DateTime($item->end_date);
            $interval = $start->diff($end);
            $item->duration = $interval->days + 1; // +1 to include both start and end
        }

        return $results;
    }

    /**
     * Fetches medical leave records for multiple employees within a specified date range.
     * This method is added to support bulk data loading for performance optimization.
     *
     * @param array $emp_ids An array of employee IDs.
     * @param string $from Start date (Y-m-d).
     * @param string $to End date (Y-m-d).
     * @return array An array of medical leave objects.
     */
    public function getMedsForEmployeesInMonth(array $emp_ids, string $from, string $to): array
    {
        return $this->whereIn('emp_id', $emp_ids)
                    ->where('start_date <=', $to)
                    ->where('end_date >=', $from)
                    ->findAll();
    }


    public function getMedDataSearch(array $filters = [], int $perPage = 20, int $page = 1): array
    {
        $builder = $this->db->table($this->table);

        if (!empty($filters['from'])) {
            $builder->where('end_date >=', $filters['from']);
        }
        if (!empty($filters['to'])) {
            $builder->where('start_date <=', $filters['to']);
        }


        if (!empty($filters['emp_id'])) {
            $builder->where('emp_id', $filters['emp_id']);
        }

        if (!empty($filters['arrange_by']) && !empty($filters['arrange_order'])) {
            $allowedArrangeBy = ['id', 'emp_id', 'start_date', 'end_date', 'descrp', 'remarks'];
            if (in_array($filters['arrange_by'], $allowedArrangeBy)) {
                $builder->orderBy($filters['arrange_by'], $filters['arrange_order']);
            } else {
                $builder->orderBy('start_date', 'asc');
            }

        } else {
            $builder->orderBy('start_date', 'asc');
        }

        $query = $builder->get();
        $meds = $query->getResult();

        $empModel = new TblEmpsDataModel();
        $secModel = new \App\Models\TblStnSecsDataModel();
        $subSecModel = new \App\Models\TblStnSubSecsDataModel();
        $designModel = new \App\Models\TblDesignsDataModel();

        $filteredMeds = [];
        foreach ($meds as $med) {

            $start = new \DateTime($med->start_date);
            $end = new \DateTime($med->end_date);
            $interval = $start->diff($end);
            $med->duration = $interval->days + 1;
            
            $emp = $empModel->find($med->emp_id);
            if ($emp) {

                $med->emp_file_no = $emp->file_no;
                $med->emp_name_arabic = $emp->name_arabic;
                $med->emp_name_english = $emp->name_english;
                $med->emp_sec_name_arabic = ($emp->sec_id) ? ($secModel->find($emp->sec_id)->name_arabic ?? '') : '';
                $med->emp_sec_name_english = ($emp->sec_id) ? ($secModel->find($emp->sec_id)->name_english ?? '') : '';
                $med->emp_sub_sec_name_arabic = ($emp->sub_sec_id) ? ($subSecModel->find($emp->sub_sec_id)->name_arabic ?? '') : '';
                $med->emp_sub_sec_name_english = ($emp->sub_sec_id) ? ($subSecModel->find($emp->sub_sec_id)->name_english ?? '') : '';
                $med->emp_design_name = ($emp->design_id) ? (($designModel->find($emp->design_id))->name ?? '') : '';

                if (empty($filters['sec_id']) || $filters['sec_id'] === 'undefined' || $filters['sec_id'] === 'all' ||
                    (isset($emp->sec_id) && $emp->sec_id == $filters['sec_id'] && (empty($filters['sub_sec_id']) || $filters['sub_sec_id'] === 'undefined' || $filters['sub_sec_id'] === 'all')) ||
                    (isset($emp->sec_id) && $emp->sec_id == $filters['sec_id'] && isset($emp->sub_sec_id) && $emp->sub_sec_id == $filters['sub_sec_id'])
                ) {
                    $filteredMeds[] = $med;
                }
            }
        }

        $total = count($filteredMeds);
        if ($perPage > 0) {
            $offset = ($page - 1) * $perPage;
            $pagedMeds = array_slice($filteredMeds, $offset, $perPage);
        } else {
            $pagedMeds = $filteredMeds;
        }

        return [
            'pagedMeds' => $pagedMeds,
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
            'hasMore' => ($perPage > 0 && ($offset + count($pagedMeds)) < $total),
        ];
    }

}
