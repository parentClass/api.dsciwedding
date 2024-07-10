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
        "email",
        "message",
        "is_pasilungan_attending",
        "is_approved",
        "is_declined",
        "updated_at"
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
    protected $validationRules      = [
        'email' => 'is_unique[rsvps.email]'
    ];
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

        if (isset($params['data']['message'])) {
            $params['data']['message'] = preg_replace('/\s+/', '<br/>', $params['data']['message']);
        }

        return $params;
    }
}
