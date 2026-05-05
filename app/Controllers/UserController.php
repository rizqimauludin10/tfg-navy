<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Halaman daftar user
    public function index()
    {
        $data = [
            'title' => 'Users',
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('admin/users', $data);
    }

    // Tambah user
    public function store()
    {
        $name     = $this->request->getPost('name');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        // Validasi
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            return redirect()->back()->with('error', 'Semua field wajib diisi.');
        }

        // Cek email duplikat
        if ($this->userModel->findByEmail($email)) {
            return redirect()->back()->with('error', 'Email sudah digunakan.');
        }

        $this->userModel->insert([
            'name'       => $name,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'role'       => $role,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'User "' . $name . '" berhasil ditambahkan!');
    }

    // Update user
    public function update($id)
    {
        $name     = $this->request->getPost('name');
        $email    = $this->request->getPost('email');
        $role     = $this->request->getPost('role');
        $password = $this->request->getPost('password');

        // Cek email duplikat (kecuali milik sendiri)
        $existing = $this->userModel->findByEmail($email);
        if ($existing && $existing['id'] != $id) {
            return redirect()->back()->with('error', 'Email sudah digunakan user lain.');
        }

        $updateData = [
            'name'  => $name,
            'email' => $email,
            'role'  => $role,
        ];

        // Update password hanya kalau diisi
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $updateData);
        return redirect()->back()->with('success', 'User berhasil diupdate!');
    }

    // Hapus user
    public function delete($id)
    {
        // Tidak boleh hapus diri sendiri
        if ($id == session()->get('user_id')) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $this->userModel->delete($id);
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    // Reset password
    public function resetPassword($id)
    {
        $newPassword = $this->request->getPost('new_password');

        if (empty($newPassword) || strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.');
        }

        $this->userModel->update($id, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return redirect()->back()->with('success', 'Password berhasil direset!');
    }
}