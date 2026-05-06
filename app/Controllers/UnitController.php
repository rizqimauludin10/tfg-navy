<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UnitModel;
use App\Models\UnitHistoryModel;
use App\Models\MapModel;

class UnitController extends BaseController
{
    protected $unitModel;
    protected $historyModel;
    protected $mapModel;

    public function __construct()
    {
        $this->unitModel    = new UnitModel();
        $this->historyModel = new UnitHistoryModel();
        $this->mapModel     = new MapModel();
    }

    // API — ambil semua unit aktif
    public function getAll()
    {
        $map   = $this->mapModel->getActiveMap();
        $mapId = $map ? $map['id'] : null;
        $units = $this->unitModel->getAllWithType($mapId);

        return $this->response->setJSON([
            'status' => 'ok',
            'units'  => $units,
        ]);
    }

    // API — tambah unit baru
    public function store()
    {
        $map = $this->mapModel->getActiveMap();
        if (!$map) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak ada peta aktif.',
            ]);
        }

        $name       = $this->request->getJSON()->name ?? '';
        $typeId     = $this->request->getJSON()->unit_type_id ?? null;

        if (empty($name) || empty($typeId)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Nama dan jenis unsur wajib diisi.',
            ]);
        }

        $id = $this->unitModel->insert([
            'name'         => $name,
            'unit_type_id' => $typeId,
            'pos_x'        => 0.5,
            'pos_y'        => 0.5,
            'map_id'       => $map['id'],
            'status'       => 'active',
            'created_by'   => session()->get('user_id'),
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        // Simpan histori awal
        $this->historyModel->insert([
            'unit_id'   => $id,
            'pos_x'     => 0.5,
            'pos_y'     => 0.5,
            'moved_by'  => session()->get('user_id'),
            'timestamp' => date('Y-m-d H:i:s'),
        ]);

        $unit = $this->unitModel->getAllWithType($map['id']);
        $unit = array_filter($unit, fn($u) => $u['id'] == $id);
        $unit = array_values($unit)[0] ?? null;

        return $this->response->setJSON([
            'status' => 'ok',
            'unit'   => $unit,
        ]);
    }

    // API — update posisi unit (dipanggil saat drag)
    public function updatePosition($id)
    {
        $data = $this->request->getJSON();
        $posX = $data->pos_x ?? null;
        $posY = $data->pos_y ?? null;

        if ($posX === null || $posY === null) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Posisi tidak valid.',
            ]);
        }

        // Update posisi
        $this->unitModel->update($id, [
            'pos_x'      => $posX,
            'pos_y'      => $posY,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Simpan histori
        $this->historyModel->insert([
            'unit_id'   => $id,
            'pos_x'     => $posX,
            'pos_y'     => $posY,
            'moved_by'  => session()->get('user_id'),
            'timestamp' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['status' => 'ok']);
    }

    // API — edit nama unit
    public function update($id)
    {
        $name = $this->request->getJSON()->name ?? '';

        if (empty($name)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Nama tidak boleh kosong.',
            ]);
        }

        $this->unitModel->update($id, ['name' => $name]);

        return $this->response->setJSON(['status' => 'ok']);
    }

    // API — hapus unit
    public function delete($id)
    {
        $this->unitModel->update($id, ['status' => 'inactive']);
        return $this->response->setJSON(['status' => 'ok']);
    }

    // API — ambil histori unit
    public function getHistory()
    {
        $limit   = $this->request->getGet('limit') ?? 20;
        $unitId  = $this->request->getGet('unit_id') ?? null;

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

        $history = $builder->findAll($limit);

        return $this->response->setJSON([
            'status'  => 'ok',
            'history' => $history,
        ]);
    }

    // API — set titik awal unit
public function setStartPoint($id)
{
    $unit = $this->unitModel->find($id);
    if (!$unit) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Unit tidak ditemukan.',
        ]);
    }

    // Hapus histori lama unit ini
    $this->historyModel->where('unit_id', $id)->delete();

    // Simpan posisi saat ini sebagai titik awal
    $this->historyModel->insert([
        'unit_id'   => $id,
        'pos_x'     => $unit['pos_x'],
        'pos_y'     => $unit['pos_y'],
        'moved_by'  => session()->get('user_id'),
        'timestamp' => date('Y-m-d H:i:s'),
    ]);

    // Tandai unit sudah punya titik awal
    $this->unitModel->update($id, ['has_start_point' => 1]);

    return $this->response->setJSON([
        'status'  => 'ok',
        'message' => 'Titik awal berhasil disimpan!',
    ]);
}
}