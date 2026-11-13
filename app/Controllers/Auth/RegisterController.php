<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class RegisterController extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/customer/dashboard');
        }

        return view('auth/register');
    }

    public function store()
    {
        $rules = [
            'name'             => 'required|min_length[2]|max_length[120]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'phone'            => 'required|min_length[9]|max_length[20]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'terms'            => 'required',
        ];

        $messages = [
            'email' => [
                'is_unique' => 'Email ini sudah terdaftar. Silakan login atau gunakan email lain.',
            ],
            'password_confirm' => [
                'matches' => 'Konfirmasi password tidak cocok dengan password.',
            ],
            'terms' => [
                'required' => 'Anda harus menyetujui syarat dan ketentuan layanan.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $userData = [
            'name'              => trim($this->request->getPost('name')),
            'email'             => strtolower(trim($this->request->getPost('email'))),
            'phone'             => trim($this->request->getPost('phone')),
            'password_hash'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'              => 'customer',
            'status'            => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ];

        $userId = $userModel->insert($userData, true);

        if (! $userId) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat akun. Silakan coba lagi.');
        }

        // Auto login
        session()->regenerate(true);
        session()->set([
            'user_id'     => (int) $userId,
            'user_name'   => $userData['name'],
            'user_email'  => $userData['email'],
            'user_role'   => 'customer',
            'user_avatar' => null,
            'logged_in'   => true,
        ]);

        return redirect()->to('/customer/dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di Velvoria Laundry.');
    }
}
