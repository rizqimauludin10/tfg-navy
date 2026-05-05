<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UnitHistoryModel;
use App\Models\UnitModel;

class HistoryController extends BaseController
{
    protected $historyModel;
    protected $unitModel;

    public function __construct()
    {
        $this->historyModel = new UnitHistoryModel();
        $this->unitModel    = new UnitModel();
    }

    public function index()
    {
        $data = [
            'title' => 'History',
            'units' => $this->unitModel->getAllWithType(),
        ];
        return view('history/index', $data);
    }

    // API — ambil histori dengan filter
    public function getHistory()
    {
        $unitId = $this->request->getGet('unit_id');
        $limit  = $this->request->getGet('limit') ?? 50;
        $date   = $this->request->getGet('date');

        $builder = $this->historyModel
        ->select('unit_history.*, units.name as unit_name,
                unit_types.name as type_name,
                unit_types.color,                    
                users.name as moved_by_name')
        ->join('units',      'units.id = unit_history.unit_id',    'left')
        ->join('unit_types', 'unit_types.id = units.unit_type_id', 'left')
        ->join('users',      'users.id = unit_history.moved_by',   'left')
        ->orderBy('unit_history.timestamp', 'DESC');

        if ($unitId) $builder->where('unit_history.unit_id', $unitId);
        if ($date)   $builder->where('DATE(unit_history.timestamp)', $date);

        $history = $builder->findAll($limit);

        return $this->response->setJSON([
            'status'  => 'ok',
            'history' => $history,
        ]);
    }
}