<?php

namespace App\Models;

use CodeIgniter\Model;

class MapModel extends Model
{
    protected $table         = 'maps';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'name', 'image_path', 'is_active',
        'uploaded_by', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;

    public function getActiveMap()
    {
        return $this->where('is_active', 1)->first();
    }
}