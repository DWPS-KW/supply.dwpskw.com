<?php

namespace App\Models;

use CodeIgniter\Model;

class TblCountriesDataModel extends Model
{
    protected $table            = 'tbl_countries_data';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'country_code',
        'country_enName',
        'country_arName',
        'country_enNationality',
        'country_arNationality',
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

    public function getAllCountries()
    {
        return $this->findAll();
    }

    public function getCountryById($id)
    {
        return $this->find($id);
    }

    public function createCountry($data)
    {
        return $this->insert($data);
    }

    public function updateCountry($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteCountry($id)
    {
        return $this->delete($id);
    }
}
//Display
// TblCountriesDataModel CRUD Methods:
// getAllCountries(), getCountryById($id), createCountry($data), updateCountry($id, $data), deleteCountry($id)