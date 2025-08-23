<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;
use App\Models\TblEmpsDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;
use App\Models\TblDesignsDataModel;
use App\Libraries\MyFuns;

class TblEmpsFdaysDataModel extends Model
{
    protected $table            = 'tbl_emps_fday_data';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;  // Enable soft deletes
    protected $useTimestamps    = true;  // Enable timestamps
    protected $allowedFields    = ['emp_id', 'date', 'remarks', 'create_by_id']; // 'create_date' is removed
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    private $session;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Kuwait');
        $this->session = Services::session();
    }


    public function checkIFDayIsFday(?int $emp_id = null, ?string $date = null): bool
    {
        $emp_fdays = $this->getAllforEmp($emp_id);
        $found = false;

        foreach ($emp_fdays as $fday) {
            if ($date === $fday->date) {
                $found = true;
                break;
            }
        }

        return $found;
    }

    public function checkIFHasPermInMonth(int $emp_id, string $date): bool
    {
        $month = date('Y-m', strtotime($date));
        return $this->where('emp_id', $emp_id)
            ->like('date', $month, 'after')
            ->countAllResults() > 0;
    }

    public function checkIFHasPermInMonthforUpdate(int $id, int $emp_id, string $date): bool
    {
        $month = date('Y-m', strtotime($date));
        return $this->where('emp_id', $emp_id)
            ->where('id !=', $id)
            ->like('date', $month, 'after')
            ->countAllResults() > 0;
    }

    public function getAllforEmp(int $emp_id, ?string $from = null, ?string $to = null): array
    {
        return $this->where('emp_id', $emp_id)
            ->where('date >=', $from ?? date('Y-m-01'))
            ->where('date <=', $to ?? date('Y-m-t'))
            ->findAll();
    }

    /**
     * Fetches F-day records for multiple employees within a specified date range.
     * This method is added to support bulk data loading for performance optimization.
     *
     * @param array $emp_ids An array of employee IDs.
     * @param string $from Start date (Y-m-d).
     * @param string $to End date (Y-m-d).
     * @return array An array of F-day objects.
     */
    public function getFdaysForEmployeesInMonth(array $emp_ids, string $from, string $to): array
    {
        return $this->whereIn('emp_id', $emp_ids)
                    ->where('date >=', $from)
                    ->where('date <=', $to)
                    ->findAll();
    }


    public function getPermDataSearch(array $filters, int $perPage = 20, int $page = 1): array
    {
        $builder = $this->db->table($this->table);

        // Apply 'from' filter if it's a valid date
        if (!empty($filters['from'])) {
            $builder->where('date >=', $filters['from']);
        }

        // Apply 'to' filter if it's a valid date
        if (!empty($filters['to'])) {
            $builder->where('date <=', $filters['to']);
        }

        if (!empty($filters['arrange_by']) && $filters['arrange_by'] === 'date') {
            // Arrange by date
            $builder->orderBy('date', $filters['arrange_order'] ?? 'desc');
        } else {
            // Default arrangement by employee ID
            $builder->orderBy('emp_id', $filters['arrange_order'] ?? 'asc');
        }

        // Fetch initial results
        $query = $builder->get();
        $fDays = $query->getResult();

        $empModel = new TblEmpsDataModel();
        $secModel = new \App\Models\TblStnSecsDataModel();
        $subSecModel = new \App\Models\TblStnSubSecsDataModel();
        $designModel = new \App\Models\TblDesignsDataModel();

        $filteredFdays = [];
        foreach ($fDays as $fDay) {
            $emp = $empModel->find($fDay->emp_id);
            if ($emp) {
                $fDay->emp_file_no = $emp->file_no;
                $fDay->emp_name_arabic = $emp->name_arabic;
                $fDay->emp_name_english = $emp->name_english;
                $fDay->emp_sec_name_arabic = ($emp->sec_id) ? ($secModel->find($emp->sec_id)->name_arabic ?? '') : '';
                $fDay->emp_sec_name_english = ($emp->sec_id) ? ($secModel->find($emp->sec_id)->name_english ?? '') : '';
                $fDay->emp_sub_sec_name_arabic = ($emp->sub_sec_id) ? ($subSecModel->find($emp->sub_sec_id)->name_arabic ?? '') : '';
                $fDay->emp_sub_sec_name_english = ($emp->sub_sec_id) ? ($subSecModel->find($emp->sub_sec_id)->name_english ?? '') : '';
                $fDay->emp_design_name = ($emp->design_id) ? (($designModel->find($emp->design_id))->name ?? '') : '';

                if (empty($filters['sec_id']) || $filters['sec_id'] === 'undefined' || $filters['sec_id'] === 'all' ||
                    empty($filters['sub_sec_id']) || $filters['sub_sec_id'] === 'undefined' || $filters['sub_sec_id'] === 'all' && $emp->sec_id == $filters['sec_id'] ||
                    $emp->sec_id == $filters['sec_id'] && $emp->sub_sec_id == $filters['sub_sec_id']
                ) {
                    $filteredFdays[] = $fDay;
                }
            }
        }


        $total = count($filteredFdays);
        $offset = ($page - 1) * $perPage;
        $pagedFdays = array_slice($filteredFdays, $offset, $perPage);

        return [
            'pagedFdays' => $pagedFdays,
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
            'hasMore' => ($offset + count($pagedFdays)) < $total
        ];
    }
}
