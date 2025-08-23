<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Exceptions\DatabaseException;
use Config\Services;

class TblStnSubSecsDataModel extends Model
{
    protected $table = 'tbl_stn_sub_secs_data';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ar_name',
        'en_name',
        'parent_id',
        'phone',
        'head_id',
        'descrp',
        'remarks',
        'create_by_id',
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    private ?\CodeIgniter\Session\Session $session;
    private ?\App\Libraries\MyFuns $myFuns;
    private ?TblEmpsDataModel $empModel;

    public function __construct()
    {
        parent::__construct();
        $this->session = Services::session();
        $this->myFuns = new \App\Libraries\MyFuns();
        $this->empModel = new TblEmpsDataModel();
    }


    public function ifSubSecExist(?int $id = null): bool
    {
        return $this->where('id', $id)->countAllResults() > 0;
    }

    public function getSubSecById(?int $id = null): ?object
    {
        return $this->find($id);
    }

    public function getAllSubSec(?int $parent_id = null, ?string $sortBy = null, ?string $order = null): array
    {
        $builder = $this->builder();

        if ($sortBy !== null) {
            $builder->orderBy($sortBy, $order ?? 'ASC'); // Default to ASC if order is null
        } else {
            $builder->orderBy('parent_id', 'ASC'); // Default to ASC if sortBy is null
        }

        if ($parent_id !== null) {
            $builder->where('parent_id', $parent_id);
        }

        return $builder->get()->getResult();
    }

    public function checkDuplicate(?int $parent_id = null, ?string $name = null): bool
    {
        return $this->where('parent_id', $parent_id)
            ->where('ar_name', $name)
            ->orWhere('en_name', $name)
            ->countAllResults() > 0;
    }

    public function createSubSec(): bool|string
    {
        $data = [
            'ar_name' => $this->request->getPost('ar_name'),
            'parent_id' => $this->request->getPost('parent_id'),
            'en_name' => $this->myFuns->setEmptyToNull($this->request->getPost('en_name')),
            'phone' => $this->myFuns->setEmptyToNull($this->request->getPost('phone')),
            'head_id' => $this->myFuns->setEmptyToNull($this->request->getPost('head_id')),
            'descrp' => $this->myFuns->setEmptyToNull($this->request->getPost('descrp')),
            'remarks' => $this->myFuns->setEmptyToNull($this->request->getPost('remarks')),
            'create_by_id' => $this->session->get('id'),
        ];

        if ($this->checkDuplicate($data['parent_id'], $data['ar_name'])) {
            return "(Duplicate Entry Sub Section Name, check your data before Insert)";
        }

        try {
            return $this->insert($data);
        } catch (DatabaseException $e) {
            log_message('error', 'Database error in createSubSec: ' . $e->getMessage());
            return false;
        }
    }

    public function updateSubSec(): bool
    {
        $subSecId = $this->request->getPost('sub_sec_id');
        $data = [
            'ar_name' => $this->request->getPost('ar_name'),
            'parent_id' => $this->request->getPost('parent_id'),
            'en_name' => $this->myFuns->setEmptyToNull($this->request->getPost('en_name')),
            'phone' => $this->myFuns->setEmptyToNull($this->request->getPost('phone')),
            'head_id' => $this->myFuns->setEmptyToNull($this->request->getPost('head_id')),
            'descrp' => $this->myFuns->setEmptyToNull($this->request->getPost('descrp')),
            'remarks' => $this->myFuns->setEmptyToNull($this->request->getPost('remarks')),
            'create_by_id' => $this->session->get('id'),
        ];

        try {
            return $this->update($subSecId, $data);
        } catch (DatabaseException $e) {
            log_message('error', 'Database error in updateSubSec: ' . $e->getMessage());
            return false;
        }
    }

    public function delSubSec(?int $id = null): int|bool
    {
        $subSec = $this->getSubSecById($id);

        try {
            if ($this->delete($id)) {
                if ($this->empModel->updateWhere(['sub_sec_id' => $id], ['sub_sec_id' => null])) {
                    return $subSec->parent_id;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (DatabaseException $e) {
            log_message('error', 'Database error in delSubSec: ' . $e->getMessage());
            return false;
        }
    }
}