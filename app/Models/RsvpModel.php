<?php

namespace App\Models;

use CodeIgniter\Model;

class RsvpModel extends Model
{
    protected $table            = 'rsvps';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "name",
        "mobile",
        "message",
        "is_pasilungan_attending",
        "is_approved"
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ["beforeInsert"];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function beforeInsert(array $params) {

        if (isset($params['data']['is_pasilungan_attending'])) {
            if ($params['data']['is_pasilungan_attending'] == "true") {
                $params['data']['is_pasilungan_attending'] = true;
            } else {
                $params['data']['is_pasilungan_attending'] = false;
            }
        }

        return $params;
    }
}
