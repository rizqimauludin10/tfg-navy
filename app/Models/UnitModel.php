<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table         = 'units';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'name', 'unit_type_id', 'pos_x', 'pos_y',
        'map_id', 'status', 'has_start_point', 'created_by',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;

    public function getAllWithType($mapId = null)
    {
        $builder = $this->select('units.*, unit_types.name as type_name, unit_types.icon_path, unit_types.color, units.has_start_point')
                        ->join('unit_types', 'unit_types.id = units.unit_type_id', 'left')
                        ->where('units.status', 'active');

        if ($mapId) {
            $builder->where('units.map_id', $mapId);
        }

        return $builder->findAll();
    }
}