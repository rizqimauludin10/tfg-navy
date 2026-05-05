<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'       => 'Super Admin',
            'email'      => 'superadmin@tracksys.com',
            'password'   => password_hash('admin@@admin', PASSWORD_DEFAULT),
            'role'       => 'superadmin',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $data = [
            'name'       => 'Anggota',
            'email'      => 'anggota@mail.com',
            'password'   => password_hash('anggota123', PASSWORD_DEFAULT),
            'role'       => 'pelaku',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($data);
    }
}