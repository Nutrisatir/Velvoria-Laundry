<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\StoreSettingModel;

class HelpController extends BaseController
{
    public function index()
    {
        return view('customer/help', [
            'settings' => (new StoreSettingModel())->getAllSettings(),
        ]);
    }
}
