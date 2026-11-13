<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $email = strtolower(trim((string) env('velvoria.adminEmail', 'admin@velvoria.test')));
        $password = (string) env('velvoria.adminPassword', '');
        $name = trim((string) env('velvoria.adminName', 'Admin Velvoria'));
        $phone = trim((string) env('velvoria.adminPhone', ''));

        if ($password === '' || strlen($password) < 12) {
            throw new RuntimeException('Atur velvoria.adminPassword minimal 12 karakter di .env sebelum menjalankan DatabaseSeeder.');
        }

        $existing = $this->db->table('users')->where('email', $email)->get()->getRow();
        if ($existing) {
            return;
        }

        $this->db->table('users')->insert([
            'name'              => $name,
            'email'             => $email,
            'phone'             => $phone !== '' ? $phone : null,
            'password_hash'     => password_hash($password, PASSWORD_DEFAULT),
            'role'              => 'admin',
            'status'            => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
    }
}
