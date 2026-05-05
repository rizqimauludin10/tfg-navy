<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UnitTypeModel;


class UnitTypeController extends BaseController
{
    protected $unitTypeModel;

    public function __construct()
    {
        $this->unitTypeModel = new UnitTypeModel();
    }

    // Halaman daftar unit types
    public function index()
    {
        $data = [
            'title'      => 'Unit Types',
            'unitTypes'  => $this->unitTypeModel->getAllWithCreator(),
        ];
        return view('admin/unit_types', $data);
    }

    // Tambah unit type
    public function store()
    {
        $name = $this->request->getPost('name');
        $file = $this->request->getFile('icon_file');
        $color = $this->request->getPost('color') ?? '#00cfff';

        if (empty($name)) {
            return redirect()->back()->with('error', 'Nama jenis unsur wajib diisi.');
        }

        $iconPath = null;

        // Upload icon jika ada
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowed = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/jpg'];
            if (!in_array($file->getMimeType(), $allowed)) {
                return redirect()->back()->with('error', 'Format icon harus PNG, JPG, atau SVG.');
            }
            $newName  = $file->getRandomName();
            $file->move(FCPATH . 'assets/uploads/icons', $newName);
            $iconPath = 'assets/uploads/icons/' . $newName;
        }

        $this->unitTypeModel->insert([
            'name'       => $name,
            'icon_path'  => $iconPath,
            'color'      => $color,
            'created_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Jenis unsur "' . $name . '" berhasil ditambahkan!');
    }

    // Update unit type
    public function update($id)
    {
        $name = $this->request->getPost('name');
        $file = $this->request->getFile('icon_file');

        if (empty($name)) {
            return redirect()->back()->with('error', 'Nama jenis unsur wajib diisi.');
        }


        $updateData = [
            'name'  => $name,
            'color' => $this->request->getPost('color') ?? '#00cfff', // ← tambahkan
        ];

        // Ganti icon jika ada file baru
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowed = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/jpg'];
            if (!in_array($file->getMimeType(), $allowed)) {
                return redirect()->back()->with('error', 'Format icon harus PNG, JPG, atau SVG.');
            }

            // Hapus icon lama
            $old = $this->unitTypeModel->find($id);
            if ($old && $old['icon_path'] && file_exists(FCPATH . $old['icon_path'])) {
                unlink(FCPATH . $old['icon_path']);
            }

            $newName  = $file->getRandomName();
            $file->move(FCPATH . 'assets/uploads/icons', $newName);
            $updateData['icon_path'] = 'assets/uploads/icons/' . $newName;
        }

        $this->unitTypeModel->update($id, $updateData);
        return redirect()->back()->with('success', 'Jenis unsur berhasil diupdate!');
    }

    // Hapus unit type
    public function delete($id)
    {
        $type = $this->unitTypeModel->find($id);
        if ($type) {
            if ($type['icon_path'] && file_exists(FCPATH . $type['icon_path'])) {
                unlink(FCPATH . $type['icon_path']);
            }
            $this->unitTypeModel->delete($id);
        }
        return redirect()->back()->with('success', 'Jenis unsur berhasil dihapus.');
    }

    // API — ambil semua unit types (dipanggil JS)
    public function getAll()
    {
        $types = $this->unitTypeModel->findAll();
        return $this->response->setJSON([
            'status' => 'ok',
            'types'  => $types,
        ]);
    }
}