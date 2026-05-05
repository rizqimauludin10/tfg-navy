<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MapModel;

class MapController extends BaseController
{
    protected $mapModel;

    public function __construct()
    {
        $this->mapModel = new MapModel();
    }

    // Halaman Map Settings
    public function index()
    {
        $data = [
            'title'   => 'Map Settings',
            'maps'    => $this->mapModel->orderBy('created_at', 'DESC')->findAll(),
            'active'  => $this->mapModel->getActiveMap(),
        ];
        return view('admin/map_settings', $data);
    }

    // Upload peta baru
    public function upload()
    {
        $file = $this->request->getFile('map_image');
        $name = $this->request->getPost('map_name');

        // Validasi
        if (!$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowed)) {
            return redirect()->back()->with('error', 'Format file harus JPG atau PNG.');
        }

        
        // Non-aktifkan semua peta lama
        $this->mapModel->set('is_active', 0)->where('id >', 0)->update();

        // Simpan file
        $newName = $file->getRandomName();
        $file->move(FCPATH . 'assets/uploads/maps', $newName);

        // Simpan ke DB
        $this->mapModel->insert([
            'name'        => $name ?: 'Peta Operasi',
            'image_path'  => 'assets/uploads/maps/' . $newName,
            'is_active'   => 1,
            'uploaded_by' => session()->get('user_id'),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Peta berhasil diupload!');
    }

    // Set peta aktif
    public function setActive($id)
    {
        $this->mapModel->set('is_active', 0)->where('id >', 0)->update();
        $this->mapModel->update($id, ['is_active' => 1]);
        return redirect()->back()->with('success', 'Peta aktif berhasil diubah!');
    }

    // Hapus peta
    public function delete($id)
    {
        $map = $this->mapModel->find($id);
        if ($map) {
            // Hapus file
            $filePath = FCPATH . $map['image_path'];
            if (file_exists($filePath)) unlink($filePath);
            $this->mapModel->delete($id);
        }
        return redirect()->back()->with('success', 'Peta berhasil dihapus.');
    }

    // API — ambil peta aktif (dipanggil JS)
    public function getActive()
    {
        $map = $this->mapModel->getActiveMap();
        return $this->response->setJSON([
            'status' => 'ok',
            'map'    => $map,
        ]);
    }
}