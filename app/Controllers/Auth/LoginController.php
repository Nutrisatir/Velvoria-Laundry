<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            $role = session()->get('user_role');
            return redirect()->to(match ($role) { 'admin' => '/admin/dashboard', 'customer' => '/customer/dashboard', default => '/' });
        }

        return view('auth/login');
    }

    public function attempt()
    {
        $rules = [
            'login'    => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $login    = trim($this->request->getPost('login'));
        $password = $this->request->getPost('password');

        $userModel = new UserModel();

        // Check if email or phone
        $user = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? $userModel->findByEmail($login)
            : $userModel->findByPhone($login);

        if (! $user) {
            return redirect()->back()->withInput()->with('error', 'Email/No. HP atau password tidak cocok.');
        }

        if ($user['status'] !== 'active') {
            return redirect()->back()->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
        }

        if (! password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Email/No. HP atau password tidak cocok.');
        }

        // Regenerate session ID for security
        session()->regenerate(true);

        session()->set([
            'user_id'     => (int) $user['id'],
            'user_name'   => $user['name'],
            'user_email'  => $user['email'],
            'user_role'   => $user['role'],
            'user_avatar' => $user['avatar'] ?? null,
            'logged_in'   => true,
        ]);

        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/dashboard')->with('success', "Selamat datang kembali, {$user['name']}!");
        }
        if ($user['role'] === 'staff') {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Akses staf belum diaktifkan. Hubungi administrator.');
        }

        return redirect()->to('/customer/dashboard')->with('success', "Selamat datang kembali, {$user['name']}!");
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}
