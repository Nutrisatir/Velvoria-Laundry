<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ServiceModel;
use App\Models\UserModel;
use App\Services\OrderStatusService;

class DashboardController extends BaseController
{
    public function index()
    {
        $orderModel   = new OrderModel();
        $paymentModel = new PaymentModel();
        $serviceModel = new ServiceModel();
        $userModel    = new UserModel();

        $today = date('Y-m-d');

        // Stat Cards
        $todayOrders = $orderModel
            ->like('created_at', $today)
            ->countAllResults();

        $pendingPickup = $orderModel
            ->where('order_status', OrderStatusService::STATUS_PENDING_PICKUP)
            ->countAllResults();

        $inProcess = $orderModel
            ->whereIn('order_status', [
                OrderStatusService::STATUS_RECEIVED,
                OrderStatusService::STATUS_WASHING,
                OrderStatusService::STATUS_DRYING,
                OrderStatusService::STATUS_IRONING,
            ])
            ->countAllResults();

        $readyOrders = $orderModel
            ->whereIn('order_status', [
                OrderStatusService::STATUS_READY,
                OrderStatusService::STATUS_DELIVERING,
            ])
            ->countAllResults();

        // Revenue today (only paid)
        $todayRevenue = $paymentModel
            ->selectSum('gross_amount')
            ->where('payment_status', 'paid')
            ->like('paid_at', $today)
            ->first()['gross_amount'] ?? 0;

        $unpaidCount = $orderModel
            ->where('payment_status', 'unpaid')
            ->whereNotIn('order_status', ['cancelled', 'rejected'])
            ->countAllResults();

        // 7-day revenue chart data
        $chartLabels  = [];
        $chartRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $dayDate = date('Y-m-d', strtotime("-{$i} days"));
            $dayName = date('d M', strtotime($dayDate));
            $chartLabels[] = $dayName;

            $rev = $paymentModel
                ->selectSum('gross_amount')
                ->where('payment_status', 'paid')
                ->like('paid_at', $dayDate)
                ->first()['gross_amount'] ?? 0;

            $chartRevenue[] = (int) $rev;
        }

        // Status distribution
        $statusCounts = [
            'awaiting_payment' => $orderModel->where('order_status', 'awaiting_payment')->countAllResults(),
            'pending_pickup'   => $orderModel->where('order_status', 'pending_pickup')->countAllResults(),
            'in_process'       => $inProcess,
            'ready'            => $readyOrders,
            'completed'        => $orderModel->where('order_status', 'completed')->countAllResults(),
            'cancelled'        => $orderModel->whereIn('order_status', ['cancelled', 'rejected'])->countAllResults(),
        ];

        // Recent 10 Orders with user
        $recentOrders = $orderModel
            ->select('orders.*, users.name as customer_name, users.phone as customer_phone')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('orders.id', 'DESC')
            ->findAll(10);

        // Recent 5 Payments
        $recentPayments = $paymentModel
            ->select('payments.*, orders.order_number')
            ->join('orders', 'orders.id = payments.order_id')
            ->orderBy('payments.id', 'DESC')
            ->findAll(5);

        return view('admin/dashboard', [
            'todayOrders'     => $todayOrders,
            'pendingPickup'   => $pendingPickup,
            'inProcess'       => $inProcess,
            'readyOrders'     => $readyOrders,
            'todayRevenue'    => (int) $todayRevenue,
            'unpaidCount'     => $unpaidCount,
            'chartLabels'     => json_encode($chartLabels),
            'chartRevenue'    => json_encode($chartRevenue),
            'statusCounts'    => json_encode(array_values($statusCounts)),
            'recentOrders'    => $recentOrders,
            'recentPayments'  => $recentPayments,
        ]);
    }
}
