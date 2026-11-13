<?php

namespace App\Controllers\Guest;

use App\Controllers\BaseController;
use App\Models\PromotionModel;
use App\Models\ServiceCategoryModel;
use App\Models\ServiceModel;
use App\Models\StoreSettingModel;

class HomeController extends BaseController
{
    public function index()
    {
        $categoryModel = new ServiceCategoryModel();
        $serviceModel  = new ServiceModel();
        $promoModel    = new PromotionModel();
        $settingModel  = new StoreSettingModel();

        $categories = $categoryModel->getActive();
        $services   = $serviceModel->getActive();
        $promotions = $promoModel->getActive();
        $settings   = $settingModel->getAllSettings();

        // Group services by category slug
        $groupedServices = [];
        foreach ($services as $service) {
            $catSlug = $service['category_slug'] ?? 'lainnya';
            $groupedServices[$catSlug][] = $service;
        }

        return view('guest/home', [
            'categories'      => $categories,
            'services'        => $services,
            'groupedServices' => $groupedServices,
            'promotions'      => $promotions,
            'settings'        => $settings,
        ]);
    }
}
