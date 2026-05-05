<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitHistoryModel extends Model
{
    protected $table         = 'unit_history';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'unit_id', 'pos_x', 'pos_y',
        'moved_by', 'timestamp'
    ];
    protected $useTimestamps = false;
}