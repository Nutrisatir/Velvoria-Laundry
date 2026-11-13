<?php

namespace App\Controllers\Guest;

use App\Controllers\BaseController;
use App\Models\StoreSettingModel;

class PageController extends BaseController
{
    protected StoreSettingModel $settingModel;

    public function __construct()
    {
        $this->settingModel = new StoreSettingModel();
    }

    public function howItWorks()
    {
        return view('guest/how-it-works');
    }

    public function about()
    {
        $settings = $this->settingModel->getAllSettings();
        return view('guest/about', ['settings' => $settings]);
    }

    public function contact()
    {
        $settings = $this->settingModel->getAllSettings();
        return view('guest/contact', ['settings' => $settings]);
    }

    public function sendContact()
    {
        $rules = [
            'name'    => 'required|min_length[2]|max_length[100]',
            'email'   => 'required|valid_email',
            'phone'   => 'permit_empty|max_length[20]',
            'message' => 'required|min_length[10]|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah kami terima dan akan segera direspons oleh tim Velvoria.');
    }

    public function faq()
    {
        return view('guest/faq');
    }

    public function terms()
    {
        $settings = $this->settingModel->getAllSettings();
        return view('guest/terms', ['settings' => $settings]);
    }
}
