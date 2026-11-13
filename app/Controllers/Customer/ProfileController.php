<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = (int) session()->get('user_id');
        $user   = $this->userModel->find($userId);

        if (! is_array($user)) {
            return redirect()->to('/login')->with('error', 'Sesi akun tidak ditemukan. Silakan masuk kembali.');
        }

        return view('customer/profile', ['user' => $user]);
    }

    public function update()
    {
        $userId = (int) session()->get('user_id');
        $user   = $this->userModel->find($userId);

        if (! is_array($user)) {
            return redirect()->to('/login')->with('error', 'Sesi akun tidak ditemukan. Silakan masuk kembali.');
        }

        $rules = [
            'name'  => 'required|min_length[2]|max_length[120]',
            'phone' => 'required|min_length[9]|max_length[25]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'  => trim((string) $this->request->getPost('name')),
            'phone' => trim((string) $this->request->getPost('phone')),
        ];

        $avatar = $this->request->getFile('avatar');
        if ($avatar && $avatar->getError() !== UPLOAD_ERR_NO_FILE) {
            if (! $avatar->isValid()) {
                return redirect()->back()->withInput()->with('error', 'Foto profil gagal diunggah. Silakan pilih file lain.');
            }

            $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
            if (! in_array($avatar->getMimeType(), $allowedMime, true) || ! $avatar->isValid() || $avatar->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Foto harus JPG, PNG, atau WEBP dengan ukuran maksimal 2 MB.');
            }

            if (! @getimagesize($avatar->getTempName())) {
                return redirect()->back()->withInput()->with('error', 'File yang dipilih bukan gambar yang valid.');
            }

            $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'avatars';
            if (! is_dir($uploadPath) && ! mkdir($uploadPath, 0755, true) && ! is_dir($uploadPath)) {
                log_message('error', 'Unable to create avatar upload directory: ' . $uploadPath);
                return redirect()->back()->withInput()->with('error', 'Folder penyimpanan foto belum dapat dibuat.');
            }

            $newName = $avatar->getRandomName();
            try {
                $avatar->move($uploadPath, $newName);
            } catch (\Throwable $e) {
                log_message('error', 'Avatar upload failed: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Foto profil tidak berhasil disimpan.');
            }

            $data['avatar'] = $newName;
        }

        if (! $this->userModel->update($userId, $data)) {
            if (isset($data['avatar'])) {
                $newPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . $data['avatar'];
                if (is_file($newPath)) {
                    @unlink($newPath);
                }
            }

            return redirect()->back()->withInput()->with('error', 'Perubahan profil gagal disimpan ke database.');
        }

        // Remove the old picture only after the new one has been saved successfully.
        if (isset($data['avatar']) && ! empty($user['avatar'])) {
            $oldName = basename((string) $user['avatar']);
            $oldPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . $oldName;
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        session()->set([
            'user_name'   => $data['name'],
            'user_avatar' => $data['avatar'] ?? ($user['avatar'] ?? null),
        ]);

        return redirect()->to('/customer/profile')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function changePassword()
    {
        $userId = (int) session()->get('user_id');
        $user   = $this->userModel->find($userId);

        if (! is_array($user)) {
            return redirect()->to('/login')->with('error', 'Sesi akun tidak ditemukan. Silakan masuk kembali.');
        }

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('password_errors', $this->validator->getErrors());
        }

        if (! password_verify((string) $this->request->getPost('current_password'), $user['password_hash'])) {
            return redirect()->back()->with('password_error', 'Password saat ini salah.');
        }

        $this->userModel->update($userId, [
            'password_hash' => password_hash((string) $this->request->getPost('new_password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->back()->with('password_success', 'Password berhasil diperbarui.');
    }
}
