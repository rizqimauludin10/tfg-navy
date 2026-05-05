<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitTypeModel extends Model
{
    protected $table         = 'unit_types';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'name', 'icon_path', 'color', 'created_by',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;

    public function getAllWithCreator()
    {
        return $this->select('unit_types.*, users.name as creator_name')
                    ->join('users', 'users.id = unit_types.created_by', 'left')
                    ->orderBy('unit_types.created_at', 'DESC')
                    ->findAll();
    }
}