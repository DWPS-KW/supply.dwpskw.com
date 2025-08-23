<?php

namespace App\Models;

use CodeIgniter\Model;

class TblDesignsDataModel extends Model
{
    protected $table = 'tbl_designs_data';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name',
        'cat',
        'arrange',
        'code',
        'total_salary',
        'basic_salary',
        'profit',
        'other_expenses',
        'qty',
        'descrp',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function ifExist(int $id): bool
    {
        return $this->where('id', $id)->countAllResults() === 1;
    }

    public function getById(int $id): ?object
    {
        return $this->find($id);
    }

    public function getAll(?string $sortBy = null, ?string $order = null): array
    {
        if ($sortBy !== null) {
            return $this->orderBy($sortBy, $order ?? 'ASC')->findAll();
        } else {
            return $this->orderBy('name', 'ASC')->findAll();
        }
    }

    public function getNameById(int $id): ?string
    {
        $result = $this->select('name')->find($id);
        return $result ? $result->name : null;
    }

    public function getDesignNameById(int $id): ?string
    {
        return $this->getNameById($id);
    }

    // Example of how to use the query builder directly if needed.
    // public function updateSectionIds()
    // {
    //     $query = $this->db->table($this->table)->get();
    //     $generalModel = new GeneralModel(); // Instantiate the GeneralModel

    //     foreach ($query->getResult() as $row) {
    //         $sectionId = $generalModel->getSecIdbyName($row->section);

    //         if ($sectionId !== null) { // Check if section ID was found
    //             $data = ['section' => $sectionId];
    //             $updated = $this->db->table('tbl_emps_data')->where('id', $row->id)->update($data);

    //             if ($updated) {
    //                 echo $row->id . " - TRUE <br />";
    //             } else {
    //                 echo $row->id . " - FALSE <br />";
    //             }
    //         } else {
    //             echo $row->id . " - Section name not found: " . $row->section . "<br />";
    //         }
    //     }
    // }
}