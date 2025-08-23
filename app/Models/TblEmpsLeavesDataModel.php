<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Exceptions\DatabaseException;
use App\Models\TblEmpsDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;
use App\Models\TblDesignsDataModel;
use App\Libraries\MyFuns;
use App\Libraries\DateUtils; // Make sure this line is present

class TblEmpsLeavesDataModel extends Model
{
    protected $table = 'tbl_emps_leaves_data';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'emp_id',
        'duration', // Add duration to allowed fields
        'begin',
        'end',
        'soft_copy',
        'remarks',
        'created_at',
        'create_by_id',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $dateUtils; // Declare the property
    protected $default_start_date; // Declare default_start_date property
    protected $default_end_date;   // Declare default_end_date property
    protected $session; // Declare the session property
    protected $myFuns; // Declare the myFuns property
    protected $emp_model; // Declare the emp_model property

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $this->myFuns = new MyFuns();
        $this->emp_model = new TblEmpsDataModel();
        $this->dateUtils = new DateUtils(); // Initialize DateUtils
        $currentYear = date('Y');
        $this->default_start_date = $currentYear . '-01-01';
        $this->default_end_date = $currentYear . '-12-31';
    }

    public function getAllforEmp($emp_id = null, $from = null, $to = null, $sortByDate = null): array
    {
        $builder = $this->builder();
        $builder->where('emp_id', $emp_id);
        if ($from !== null) {
            $builder->where('end >=', $from);
        }
        if ($to !== null) {
            $builder->where('begin <=', $to);
        }
        if ($sortByDate !== null) {
            $builder->orderBy("begin", $sortByDate);
        } else {
            $builder->orderBy("begin", "asc");
        }
        return $builder->get()->getResult();
    }

    public function getLeavesForEmployeesInMonth(array $emp_ids, string $from, string $to): array
    {
        return $this->whereIn('emp_id', $emp_ids)
                    ->where('begin <=', $to)
                    ->where('end >=', $from)
                    ->findAll();
    }


    public function checkIFDayIsLeave($emp_id = null, $date = null): bool
    {
        $emp_leaves = $this->getAllforEmp($emp_id);
        foreach ($emp_leaves as $emp_leave) {
            if (($date >= $emp_leave->begin) && ($date <= $emp_leave->end)) {
                return true;
            }
        }
        return false;
    }

    public function getLeaveDataSearch(array $filters, int $perPage = 20, int $page = 1): array
    {
      $builder = $this->db->table($this->table);

      // Apply filters and ordering...
      if (!empty($filters['from'])) {
        $builder->where('begin >=', $filters['from']);
      }
      if (!empty($filters['to'])) {
        $builder->where('end <=', $filters['to']);
      }
      if (!empty($filters['arrange_by']) && !empty($filters['arrange_order'])) {
        $builder->orderBy($filters['arrange_by'], $filters['arrange_order']);
      } else {
        $builder->orderBy('begin', 'asc'); // Default ordering
      }

      $query = $builder->get();
      $leaves = $query->getResult();

      $empModel = new TblEmpsDataModel();
      $secModel = new \App\Models\TblStnSecsDataModel();
      $subSecModel = new \App\Models\TblStnSubSecsDataModel();
      $designModel = new \App\Models\TblDesignsDataModel();

      $filteredLeaves = [];
      foreach ($leaves as $leave) {
        $emp = $empModel->find($leave->emp_id);
        if ($emp) {
          $leave->emp_file_no = $emp->file_no;
          $leave->emp_name_arabic = $emp->name_arabic;
          $leave->emp_name_english = $emp->name_english;
          $leave->emp_sec_name_arabic = ($emp->sec_id) ? ($secModel->find($emp->sec_id)->name_arabic ?? '') : '';
          $leave->emp_sec_name_english = ($emp->sec_id) ? ($secModel->find($emp->sec_id)->name_english ?? '') : '';
          $leave->emp_sub_sec_name_arabic = ($emp->sub_sec_id) ? ($subSecModel->find($emp->sub_sec_id)->name_arabic ?? '') : '';
          $leave->emp_sub_sec_name_english = ($emp->sub_sec_id) ? ($subSecModel->find($emp->sub_sec_id)->name_english ?? '') : '';
          $leave->emp_design_name = ($emp->design_id) ? (($designModel->find($emp->design_id))->name ?? '') : '';

          if (empty($filters['sec_id']) || $filters['sec_id'] === 'undefined' || $filters['sec_id'] === 'all' ||
            empty($filters['sub_sec_id']) || $filters['sub_sec_id'] === 'undefined' || $filters['sub_sec_id'] === 'all' ||
            ($emp->sec_id == $filters['sec_id'] && (empty($filters['sub_sec_id']) || $filters['sub_sec_id'] === 'undefined' || $filters['sub_sec_id'] === 'all')) ||
            ($emp->sec_id == $filters['sec_id'] && $emp->sub_sec_id == $filters['sub_sec_id'])
          ) {
            $filteredLeaves[] = $leave;
          }
        }
      }

      $total = count($filteredLeaves);
      if ($perPage > 0) {
        $offset = ($page - 1) * $perPage;
        $pagedLeaves = array_slice($filteredLeaves, $offset, $perPage);
      } else {
        $pagedLeaves = $filteredLeaves; // Return all if $perPage is 0
      }

      return [
        'pagedLeaves' => $pagedLeaves,
        'total' => $total,
        'perPage' => $perPage,
        'page' => $page,
        'hasMore' => ($perPage > 0 && ($offset + count($pagedLeaves)) < $total),
      ];
    }

    public function countEmpLeaveDaysNoFri($emp_id, $from, $to){
        $builder = $this->db->table($this->table);
        $builder->select('begin, end');
        $builder->where('emp_id', $emp_id);
        $builder->where('begin <=', $to);
        $builder->where('end >=', $from);
        $query = $builder->get();

        $total_days = 0;
        foreach ($query->getResult() as $leave) {
            $start = max($from, $leave->begin);
            $end = min($to, $leave->end);

            $days = $this->countDaysExcludeFridays($start, $end);
            $total_days += $days;
        }
        return $total_days;
    }

    public function isLeaveCoveringDate(?int $emp_id = null, ?string $date = null): bool
    {
        if (is_null($emp_id) || is_null($date)) {
            return false;
        }

        // Check if any leave record for the employee covers the given date
        return $this->where('emp_id', $emp_id)
                    ->where('begin <=', $date)
                    ->where('end >=', $date)
                    ->countAllResults() > 0;
    }
    
    private function countDaysExcludeFridays($start, $end) {
        $count = 0;
        $current = strtotime($start);
        $end_ts = strtotime($end);

        while ($current <= $end_ts) {
            // WEEKDAY: Monday=0 ... Friday=4 ... Sunday=6
            $weekday = date('w', $current); // 0=Sunday, 5=Friday here!
            // We want to exclude Fridays; date('w') Friday = 5
            if ($weekday != 5) {
                $count++;
            }
            $current = strtotime('+1 day', $current);
        }
        return $count;
    }

    public function isFullLeave($emp_leave_begin, $emp_leave_end, $from, $to){
        // Check if the leave period covers the entire range from $from to $to
        $leaveStart = strtotime($emp_leave_begin);
        $leaveEnd = strtotime($emp_leave_end);
        $rangeStart = strtotime($from);
        $rangeEnd = strtotime($to);

        // If the leave starts before or on the range start and ends after or on the range end, it's a full leave
        return ($leaveStart <= $rangeStart && $leaveEnd >= $rangeEnd);

    }

  }
