<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\PromotionModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userId     = (int) session()->get('user_id');
        $orderModel = new OrderModel();
        $promoModel = new PromotionModel();

        // Stats
        $activeOrders = $orderModel
            ->where('user_id', $userId)
            ->whereNotIn('order_status', ['completed', 'cancelled', 'rejected'])
            ->countAllResults();

        $awaitingPayment = $orderModel
            ->where('user_id', $userId)
            ->where('payment_status', 'unpaid')
            ->whereNotIn('order_status', ['cancelled', 'rejected'])
            ->countAllResults();

        $completedOrders = $orderModel
            ->where('user_id', $userId)
            ->where('order_status', 'completed')
            ->countAllResults();

        // Recent orders
        $recentOrders = $orderModel
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->findAll(5);

        // Active promotions
        $promotions = $promoModel->getActive();

        return view('customer/dashboard', [
            'activeOrders'    => $activeOrders,
            'awaitingPayment' => $awaitingPayment,
            'completedOrders' => $completedOrders,
            'recentOrders'    => $recentOrders,
            'promotions'      => $promotions,
        ]);
    }
}
