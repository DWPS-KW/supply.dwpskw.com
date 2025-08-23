<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Exceptions\DatabaseException;
use Config\Services;

class TblAdminsDataModel extends Model
{
    protected $table            = 'tbl_admins_data';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'username',
        'typing_name',
        'mail',
        'password',
        'allow',
        'type',
        'sec',
        'sub_sec',
        'active',
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    private ?\CodeIgniter\Session\Session $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = Services::session();
    }

    public function login(?string $username = null, ?string $password = null): bool
    {
        $user = $this->where('username', $username)->where('password', $password)->first();

        if ($user) {
            $this->session->set([
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'mail' => $user->mail,
                'allow' => $user->allow,
                'type' => $user->type,
                'sec' => $user->sec,
                'sub_sec' => $user->sub_sec,
                'active' => $user->active,
                'isLoggedIn' => true,
                'lang' => 'ar',
            ]);
            return true;
        } else {
            return false;
        }
    }

    public function logout(): bool
    {
        $this->session->remove([
            'id',
            'name',
            'username',
            'mail',
            'allow',
            'type',
            'sec',
            'sub_sec',
            'active',
            'isLoggedIn',
            'lang',
        ]);
        return true;
    }

    public function ifExist(?int $id = null): bool
    {
        return $this->where('id', $id)->countAllResults() > 0;
    }

    public function getByUsername(?string $username = null): ?object
    {
        return $this->where('username', $username)->first();
    }


    public function getByMail(?string $mail = null): ?object
    {
        return $this->where('mail', $mail)->first();
    }

    public function create(array $data): int|bool
    {
        try {
            if ($this->insert($data)) {
                return $this->insertID();
            } else {
                return false;
            }
        } catch (DatabaseException $e) {
            log_message('error', 'Database error in create: ' . $e->getMessage());
            return false;
        }
    }

    public function update($id = null, $data = null): bool
    {
        try {
            return parent::update($id, $data); //use parent::update to call the base model update function.
        } catch (DatabaseException $e) {
            log_message('error', 'Database error in update: ' . $e->getMessage());
            return false;
        }
    }

    public function del(?int $id = null): bool
    {
        try {
            return $this->delete($id);
        } catch (DatabaseException $e) {
            log_message('error', 'Database error in del: ' . $e->getMessage());
            return false;
        }
    }
}