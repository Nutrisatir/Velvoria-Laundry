<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\PasswordResetTokenModel;
use App\Models\UserModel;
use Throwable;

class PasswordController extends BaseController
{
    public function forgotIndex()
    {
        return view('auth/forgot-password');
    }

    public function sendReset()
    {
        if (! $this->validate(['email' => 'required|valid_email'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $user = (new UserModel())->findByEmail($email);

        // Respon selalu sama agar email terdaftar tidak dapat ditebak dari endpoint ini.
        if ($user && $user['status'] === 'active') {
            $plainToken = bin2hex(random_bytes(32));
            $tokenModel = new PasswordResetTokenModel();
            $tokenModel->where('user_id', $user['id'])->delete();
            $tokenModel->insert([
                'user_id'    => $user['id'],
                'token_hash' => hash('sha256', $plainToken),
                'expires_at' => date('Y-m-d H:i:s', time() + 3600),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $resetUrl = site_url('reset-password/' . $plainToken);
            $emailService = service('email');
            $emailService->setTo($user['email']);
            $emailService->setSubject('Reset Password Velvoria Laundry');
            $emailService->setMailType('html');
            $emailService->setMessage(
                '<p>Halo ' . esc($user['name']) . ',</p>' .
                '<p>Kami menerima permintaan untuk mengatur ulang password akun Velvoria Laundry.</p>' .
                '<p><a href="' . esc($resetUrl) . '">Atur ulang password</a></p>' .
                '<p>Tautan berlaku selama 60 menit. Abaikan email ini jika kamu tidak meminta reset password.</p>'
            );

            try {
                if (! $emailService->send()) {
                    log_message('error', 'Gagal mengirim email reset password: ' . $emailService->printDebugger(['headers']));
                }
            } catch (Throwable $e) {
                log_message('error', 'Email reset password error: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Jika email terdaftar, tautan reset password akan dikirimkan. Periksa kotak masuk dan folder spam.');
    }

    public function resetIndex(string $token)
    {
        $reset = (new PasswordResetTokenModel())->findValidToken($token);
        if (! $reset) {
            return redirect()->to('/forgot-password')->with('error', 'Tautan reset tidak valid atau sudah kedaluwarsa.');
        }

        return view('auth/reset-password', ['token' => $token]);
    }

    public function updatePassword()
    {
        $rules = [
            'token'            => 'required|min_length[32]|max_length[128]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $tokenModel = new PasswordResetTokenModel();
        $token = (string) $this->request->getPost('token');
        $reset = $tokenModel->findValidToken($token);
        if (! $reset) {
            return redirect()->to('/forgot-password')->with('error', 'Tautan reset tidak valid atau sudah kedaluwarsa.');
        }

        $db = db_connect();
        $db->transBegin();
        try {
            (new UserModel())->update($reset['user_id'], [
                'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            ]);
            $tokenModel->update($reset['id'], ['used_at' => date('Y-m-d H:i:s')]);
            $tokenModel->where('user_id', $reset['user_id'])->where('id !=', $reset['id'])->delete();
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Database transaction failed.');
            }
            $db->transCommit();
        } catch (Throwable $e) {
            $db->transRollback();
            log_message('error', 'Password reset failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Password belum dapat diperbarui. Silakan coba lagi.');
        }

        return redirect()->to('/login')->with('success', 'Password berhasil diperbarui. Silakan login menggunakan password baru.');
    }
}
