<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminActivityLogModel;
use App\Models\UserModel;

class StaffController extends BaseController
{
    protected UserModel $userModel;
    protected AdminActivityLogModel $activityLog;

    public function __construct()
    {
        $this->userModel   = new UserModel();
        $this->activityLog = new AdminActivityLogModel();
    }

    public function index()
    {
        $staff = $this->userModel
            ->whereIn('role', ['admin', 'staff'])
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('admin/staff/index', [
            'staff' => $staff,
        ]);
    }

    public function store()
    {
        $rules = [
            'name'     => 'required|min_length[2]|max_length[120]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'phone'    => 'required|min_length[9]|max_length[25]',
            'role'     => 'required|in_list[admin,staff]',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $adminId = (int) session()->get('user_id');

        $data = [
            'name'              => trim($this->request->getPost('name')),
            'email'             => strtolower(trim($this->request->getPost('email'))),
            'phone'             => trim($this->request->getPost('phone')),
            'role'              => $this->request->getPost('role'),
            'password_hash'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status'            => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ];

        $id = $this->userModel->insert($data, true);
        $this->activityLog->log($adminId, 'create_staff', 'users', $id, "Menambahkan akun staf baru: {$data['name']} ({$data['role']})");

        return redirect()->to('/admin/staff')->with('success', 'Akun staf berhasil dibuat.');
    }

    public function toggleStatus($id)
    {
        $currentUserId = (int) session()->get('user_id');
        if ((int) $id === $currentUserId) {
            return redirect()->to('/admin/staff')->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/admin/staff')->with('error', 'Akun tidak ditemukan.');
        }

        $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
        $this->userModel->update($id, ['status' => $newStatus]);

        $this->activityLog->log($currentUserId, 'toggle_staff_status', 'users', $id, "Mengubah status akun {$user['name']} menjadi {$newStatus}");

        return redirect()->to('/admin/staff')->with('success', "Status akun {$user['name']} berhasil diubah.");
    }
}
