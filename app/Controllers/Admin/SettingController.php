<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminActivityLogModel;
use App\Models\StoreSettingModel;

class SettingController extends BaseController
{
    protected StoreSettingModel $settingModel;
    protected AdminActivityLogModel $activityLog;

    public function __construct()
    {
        $this->settingModel = new StoreSettingModel();
        $this->activityLog  = new AdminActivityLogModel();
    }

    public function index()
    {
        $settings = $this->settingModel->getAllSettings();

        return view('admin/settings/index', [
            'settings' => $settings,
        ]);
    }

    public function update()
    {
        $adminId = (int) session()->get('user_id');

        $allowedKeys = [
            'store_name',
            'store_tagline',
            'store_address',
            'store_phone',
            'store_whatsapp',
            'store_email',
            'operating_hours',
            'minimum_weight',
            'delivery_fee',
            'free_delivery_threshold',
            'cancellation_policy',
            'timezone',
        ];

        foreach ($allowedKeys as $key) {
            $value = $this->request->getPost($key);
            if ($value !== null) {
                $this->settingModel->saveSetting($key, trim($value));
            }
        }

        $this->activityLog->log($adminId, 'update_settings', 'store_settings', null, 'Admin memperbarui pengaturan toko.');

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan toko berhasil disimpan.');
    }
}
