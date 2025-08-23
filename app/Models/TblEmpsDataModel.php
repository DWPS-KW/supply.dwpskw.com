<?php

namespace App\Models;

use CodeIgniter\Model;

class TblEmpsDataModel extends Model
{
    protected $table            = 'tbl_emps_data';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'file_no',
        'name_arabic',
        'name_english',
        'gender',
        'mobile',
        'civil_id',
        'join_date',
        'termination_date',
        'termination_reason',
        'design_id',
        'sec_id',
        'sub_sec_id',
        'nation',
        'birth_date',
        'edu_cert',
        'permanent',
        'photo',
        'experience',
        'active',
        'remarks',
        'payroll_category',
        'has_overtime',
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
    public function ifExist($id = null)
    {
        return $this->find($id) !== null;
    }
    protected $designModel;
    protected $secModel;
    protected $subSecModel;

    public function getEmpDataById($emp_id)
    {
        $emp = $this->find($emp_id);
        if (!$emp) {
            return null;
        }

        $designModel = new \App\Models\TblDesignsDataModel();
        $secModel = new \App\Models\TblStnSecsDataModel();
        $subSecModel = new \App\Models\TblStnSubSecsDataModel();

        $emp->design_name = ($emp->design_id) ? ($designModel->find($emp->design_id)->name ?? '') : '';
        $emp->total_salary = ($emp->design_id) ? ($designModel->find($emp->design_id)->total_salary ?? '') : '';
        $emp->sec_name_arabic = ($emp->sec_id) ? ($secModel->find($emp->sec_id)->name_arabic ?? '') : '';
        $emp->sec_name_english = ($emp->sec_id) ? ($secModel->find($emp->sec_id)->name_english ?? '') : '';
        $emp->sub_sec_name_arabic = ($emp->sub_sec_id) ? ($subSecModel->find($emp->sub_sec_id)->name_arabic ?? '') : '';
        $emp->sub_sec_name_english = ($emp->sub_sec_id) ? ($subSecModel->find($emp->sub_sec_id)->name_english ?? '') : '';

        return $emp;
    }

    private function attachArrangeAndSort(array $emps)
    {
        $designModel = new \App\Models\TblDesignsDataModel();
        $secModel = new \App\Models\TblStnSecsDataModel();
        $subSecModel = new \App\Models\TblStnSubSecsDataModel();

        $emps_data = array_map(function($emp) use ($designModel) {
            $emp_array = (array) $emp;
            $design = $designModel->getById($emp->design_id);
            $emp_array['arrange'] = $design->arrange ?? 0;
            return $emp_array;
        }, $emps);

        usort($emps_data, function ($a, $b) {
            return $a['arrange'] === $b['arrange'] ? strcmp($a['civil_id'], $b['civil_id']) : $a['arrange'] - $b['arrange'];
        });

        return array_map(fn($e) => (object) $e, $emps_data);
    }

    public function getAllEmps($filters = [])
    {
        $builder = $this;

        foreach (['sec_id', 'sub_sec_id', 'design_id', 'payroll_category', 'has_overtime', 'active'] as $field) {
            if (!empty($filters[$field]) && $filters[$field] !== 'all') {
                $builder = $builder->where($field, $filters[$field]);
            }
        }

        $emps = $builder->findAll();

            $designModel = new \App\Models\TblDesignsDataModel();
            $secModel = new \App\Models\TblStnSecsDataModel();
            $subSecModel = new \App\Models\TblStnSubSecsDataModel();

        foreach ($emps as &$emp) {
            $emp->design_name = ($emp->design_id) ? ($designModel->find($emp->design_id)->name ?? '') : '';
            $emp->total_salary = ($emp->design_id) ? ($designModel->find($emp->design_id)->total_salary ?? '') : '';
            $emp->sec_name_arabic = ($emp->sec_id) ? ($secModel->find($emp->sec_id)->name_arabic ?? '') : '';
            $emp->sec_name_english = ($emp->sec_id) ? ($secModel->find($emp->sec_id)->name_english ?? '') : '';
            $emp->sub_sec_name_arabic = ($emp->sub_sec_id) ? ($subSecModel->find($emp->sub_sec_id)->name_arabic ?? '') : '';
            $emp->sub_sec_name_english = ($emp->sub_sec_id) ? ($subSecModel->find($emp->sub_sec_id)->name_english ?? '') : '';
        }

        return $this->attachArrangeAndSort($emps);
    }


    public function getEmpBy($col = null, $value = null)
    {
        return $this->where($col, $value)->first();
    }


    // Search employees with filters and sorting
    public function getAllDataSearch($search_text = null, $search_in = null, $search_at = null, $sort_by = 'id', $order = 'ASC', $perPage = 20, $page = 1, $filters = [])
    {
        $page = (int) $page;
        if ($page < 1) {
            $page = 1;
        }
        
        $perPage = (int) $perPage;
        if ($perPage < 1) {
            $perPage = 20; // default per page
        }
        $session = session();
        $builder = $this->where('active', 1);
        $userType = $session->get('type');

        if ($userType !== 'admin') {
            $builder->where('sec_id', $session->get('sec_id'));
            if ($userType !== 'depart') {
                $builder->where('sub_sec_id', $session->get('sub_sec_id'));
            }
        } elseif (!empty($search_at) && $search_at !== 'undefined') {
            $builder->where('sec_id', $search_at);
        }

        if (!empty($search_text)) {
            $builder->groupStart();
            if (empty($search_in) || $search_in === 'undefined') {
                $builder->like('name_arabic', $search_text)
                    ->orLike('name_english', $search_text)
                    ->orLike('civil_id', $search_text)
                    ->orLike('file_no', $search_text)
                    ->orLike('mobile', $search_text);
            } elseif ($search_in === 'name') {
                $builder->like('name_arabic', $search_text)
                    ->orLike('name_english', $search_text);
            } else {
                $builder->like($search_in, $search_text);
            }
            $builder->groupEnd();
        }
            $designModel = new \App\Models\TblDesignsDataModel();
            $secModel = new \App\Models\TblStnSecsDataModel();
            $subSecModel = new \App\Models\TblStnSubSecsDataModel();

        // Fetch all filtered records
        $allEmps = $builder->get()->getResult();
        foreach ($allEmps as $emp) {
            $emp->design_name = ($emp->design_id) ? (($designModel->find($emp->design_id))->name ?? '') : '';
            $emp->total_salary = ($emp->design_id) ? (($designModel->find($emp->design_id))->total_salary ?? '') : '';
            $emp->sec_name_arabic = ($emp->sec_id) ? (($secModel->find($emp->sec_id))->name_arabic ?? '') : '';
            $emp->sec_name_english = ($emp->sec_id) ? (($secModel->find($emp->sec_id))->name_english ?? '') : '';
            $emp->sub_sec_name_arabic = ($emp->sub_sec_id) ? (($subSecModel->find($emp->sub_sec_id))->name_arabic ?? '') : '';
            $emp->sub_sec_name_english = ($emp->sub_sec_id) ? (($subSecModel->find($emp->sub_sec_id))->name_english ?? '') : '';
        }
        $total = count($allEmps);

        // Apply custom PHP sorting
        $sortedEmps = $this->attachArrangeAndSort($allEmps);

        // Apply pagination manually
        $offset = ($page - 1) * $perPage;
        $pagedEmps = array_slice($sortedEmps, $offset, $perPage);

        return [
            'items' => $pagedEmps,
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
            'hasMore' => ($offset + count($pagedEmps)) < $total
        ];
    }

    public function getEmpDataSearch($filters = [])
    {
        $builder = $this;

        $map = [
            'design_id', 'sec_id', 'sub_sec_id', 'nation', 'edu_cert', 
            'payroll_category', 'has_overtime', 'active', 'permanent'
        ];

        foreach ($map as $field) {
                if (array_key_exists($field, $filters) &&
                    $filters[$field] !== '' &&
                    $filters[$field] !== 'undefined' &&
                    $filters[$field] !== 'all') {

                    // Cast numeric filters
                    if (in_array($field, ['active', 'has_overtime', 'permanent'])) {
                        $builder->where($field, (int)$filters[$field]);
                    } else {
                        $builder->where($field, $filters[$field]);
                    }
                }
            }


        if (!empty($filters['join_date_from'])) {
            $builder->where('join_date >=', $filters['join_date_from']);
        }

        if (!empty($filters['join_date_to'])) {
            $builder->where('join_date <=', $filters['join_date_to']);
        }

        $builder->orderBy('file_no');
        $emps = $builder->findAll();

        $designModel = new \App\Models\TblDesignsDataModel();
        $secModel = new \App\Models\TblStnSecsDataModel();
        $subSecModel = new \App\Models\TblStnSubSecsDataModel();


        foreach ($emps as $emp) {
            $emp->design_name = ($emp->design_id) ? (($designModel->find($emp->design_id))->name ?? '') : '';
            $emp->total_salary = ($emp->design_id) ? (($designModel->find($emp->design_id))->total_salary ?? '') : '';
            $emp->sec_name_arabic = ($emp->sec_id) ? (($secModel->find($emp->sec_id))->name_arabic ?? '') : '';
            $emp->sec_name_english = ($emp->sec_id) ? (($secModel->find($emp->sec_id))->name_english ?? '') : '';
            $emp->sub_sec_name_arabic = ($emp->sub_sec_id) ? (($subSecModel->find($emp->sub_sec_id))->name_arabic ?? '') : '';
            $emp->sub_sec_name_english = ($emp->sub_sec_id) ? (($subSecModel->find($emp->sub_sec_id))->name_english ?? '') : '';
        }

        return $this->attachArrangeAndSort($emps);
    }

    public function create($data)
    {
        if ($this->insert($data)) {
            return $this->insertID();
        } else {
            throw new \RuntimeException('Error inserting data');
        }
    }

    public function updateEmp($emp_id, $data)
    {
        $this->update($emp_id, $data);
        return $emp_id;
    }

    public function deleteEmp($emp_id)
    {
        return $this->delete($emp_id);
    }

    public function addEmpPhoto($emp_id, $upload_file_name)
    {
        return $this->update($emp_id, ['photo' => $upload_file_name]);
    }

    public function deleteEmpPhoto($emp_id)
    {
        return $this->update($emp_id, ['photo' => null]);
    }
}
