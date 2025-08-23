<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Exceptions\DatabaseException;
use Config\Services;

class TblStnSecsDataModel extends Model
{
    protected $table = 'tbl_stn_secs_data';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ar_name',
        'en_name',
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

    public function ifSecExist(?int $id = null): bool
    {
        return $this->where('id', $id)->countAllResults() > 0;
    }

    public function checkDuplicateSec(?string $ar_name = null, ?string $en_name = null): bool
    {
        return $this->where('ar_name', $ar_name)->orWhere('en_name', $en_name)->countAllResults() > 0;
    }

    public function getSecById(?int $id = null): ?object
    {
        return $this->find($id);
    }

    public function getAllSec(?string $sortBy = null, ?string $order = null): array
    {
        if ($sortBy !== null) {
            return $this->orderBy($sortBy, $order ?? 'ASC')->findAll();
        } else {
            return $this->orderBy('ar_name', 'ASC')->findAll();
        }
    }

    public function createSec(): int|bool
    {
        $data = [
            'ar_name' => $this->request->getPost('ar_name'),
            'en_name' => $this->myFuns->setEmptyToNull($this->request->getPost('en_name')),
            'phone' => $this->myFuns->setEmptyToNull($this->request->getPost('phone')),
            'head_id' => $this->myFuns->setEmptyToNull($this->request->getPost('head_id')),
            'descrp' => $this->myFuns->setEmptyToNull($this->request->getPost('descrp')),
            'remarks' => $this->myFuns->setEmptyToNull($this->request->getPost('remarks')),
            'create_by_id' => $this->session->get('id'),
        ];

        if ($this->checkDuplicateSec($data['ar_name'], $data['en_name'])) {
            return false;
        }

        try {
            if ($this->insert($data)) {
                return $this->insertID();
            } else {
                return false;
            }
        } catch (DatabaseException $e) {
            log_message('error', 'Database error in createSec: ' . $e->getMessage());
            return false;
        }
    }

    public function updateSec(): bool
    {
        $secId = $this->request->getPost('sec_id');
        $data = [
            'ar_name' => $this->request->getPost('ar_name'),
            'en_name' => $this->myFuns->setEmptyToNull($this->request->getPost('en_name')),
            'phone' => $this->myFuns->setEmptyToNull($this->request->getPost('phone')),
            'head_id' => $this->myFuns->setEmptyToNull($this->request->getPost('head_id')),
            'descrp' => $this->myFuns->setEmptyToNull($this->request->getPost('descrp')),
            'remarks' => $this->myFuns->setEmptyToNull($this->request->getPost('remarks')),
            'create_by_id' => $this->session->get('id'),
        ];

        try {
            return $this->update($secId, $data);
        } catch (DatabaseException $e) {
            log_message('error', 'Database error in updateSec: ' . $e->getMessage());
            return false;
        }
    }

    public function delSec(?int $id = null): bool
    {
        $sec = $this->getSecById($id);

        try {
            if ($this->delete($id)) {
                return $this->empModel->updateWhere(['sec_id' => $id], ['sec_id' => null]);
            } else {
                return false;
            }
        } catch (DatabaseException $e) {
            log_message('error', 'Database error in delSec: ' . $e->getMessage());
            return false;
        }
    }
}
