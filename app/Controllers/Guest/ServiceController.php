<?php

namespace App\Controllers\Guest;

use App\Controllers\BaseController;
use App\Models\ServiceCategoryModel;
use App\Models\ServiceModel;
use App\Models\StoreSettingModel;

class ServiceController extends BaseController
{
    public function index()
    {
        $categoryModel = new ServiceCategoryModel();
        $serviceModel  = new ServiceModel();

        $categories = $categoryModel->getActive();
        $services   = $serviceModel->getActive();

        $groupedServices = [];
        foreach ($services as $service) {
            $catSlug = $service['category_slug'] ?? 'lainnya';
            $groupedServices[$catSlug][] = $service;
        }

        return view('guest/services', [
            'categories'      => $categories,
            'services'        => $services,
            'groupedServices' => $groupedServices,
        ]);
    }

    public function prices()
    {
        $categoryModel = new ServiceCategoryModel();
        $serviceModel  = new ServiceModel();
        $settingModel  = new StoreSettingModel();

        $categories = $categoryModel->getActive();
        $services   = $serviceModel->getActive();
        $settings   = $settingModel->getAllSettings();

        $groupedServices = [];
        foreach ($services as $service) {
            $catSlug = $service['category_slug'] ?? 'lainnya';
            $groupedServices[$catSlug][] = $service;
        }

        return view('guest/prices', [
            'categories'      => $categories,
            'groupedServices' => $groupedServices,
            'settings'        => $settings,
        ]);
    }
}
