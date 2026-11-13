<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;

class ReportController extends BaseController
{
    protected OrderModel $orderModel;
    protected PaymentModel $paymentModel;
    protected OrderItemModel $itemModel;

    public function __construct()
    {
        $this->orderModel   = new OrderModel();
        $this->paymentModel = new PaymentModel();
        $this->itemModel    = new OrderItemModel();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');
        $status    = $this->request->getGet('status');

        $builder = $this->orderModel
            ->select('orders.*, users.name as customer_name, users.phone as customer_phone')
            ->join('users', 'users.id = orders.user_id')
            ->where('orders.created_at >=', $startDate . ' 00:00:00')
            ->where('orders.created_at <=', $endDate . ' 23:59:59');

        if (! empty($status)) {
            $builder->where('orders.order_status', $status);
        }

        $orders = $builder->orderBy('orders.id', 'DESC')->findAll();

        // Summary calculations
        $totalOrders     = count($orders);
        $totalRevenue    = 0;
        $totalDiscount   = 0;
        $totalDelivery   = 0;
        $completedCount  = 0;
        $cancelledCount  = 0;

        foreach ($orders as $o) {
            if ($o['payment_status'] === 'paid') {
                $totalRevenue += (int) $o['grand_total'];
            }
            $totalDiscount += (int) $o['discount_amount'];
            $totalDelivery += (int) $o['delivery_fee'];

            if ($o['order_status'] === 'completed') {
                $completedCount++;
            } elseif (in_array($o['order_status'], ['cancelled', 'rejected'])) {
                $cancelledCount++;
            }
        }

        return view('admin/reports/index', [
            'orders'         => $orders,
            'startDate'      => $startDate,
            'endDate'        => $endDate,
            'status'         => $status,
            'totalOrders'    => $totalOrders,
            'totalRevenue'   => $totalRevenue,
            'totalDiscount'  => $totalDiscount,
            'totalDelivery'  => $totalDelivery,
            'completedCount' => $completedCount,
            'cancelledCount' => $cancelledCount,
        ]);
    }

    public function exportCsv()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');
        $status    = $this->request->getGet('status');

        $builder = $this->orderModel
            ->select('orders.*, users.name as customer_name, users.phone as customer_phone')
            ->join('users', 'users.id = orders.user_id')
            ->where('orders.created_at >=', $startDate . ' 00:00:00')
            ->where('orders.created_at <=', $endDate . ' 23:59:59');

        if (! empty($status)) {
            $builder->where('orders.order_status', $status);
        }

        $orders = $builder->orderBy('orders.id', 'ASC')->findAll();

        $filename = "Laporan_Transaksi_Velvoria_{$startDate}_sd_{$endDate}.csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM for Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, [
            'No. Pesanan',
            'Tanggal',
            'Pelanggan',
            'No. HP',
            'Metode Penyerahan',
            'Subtotal (Rp)',
            'Diskon (Rp)',
            'Ongkir (Rp)',
            'Total (Rp)',
            'Status Pengerjaan',
            'Status Pembayaran',
        ]);

        foreach ($orders as $order) {
            fputcsv($output, [
                $order['order_number'],
                $order['created_at'],
                $order['customer_name'],
                $order['customer_phone'],
                $order['handover_method'] === 'drop_off' ? 'Antar Sendiri' : 'Antar Jemput',
                $order['subtotal'],
                $order['discount_amount'],
                $order['delivery_fee'],
                $order['grand_total'],
                $order['order_status'],
                $order['payment_status'],
            ]);
        }

        fclose($output);
        exit;
    }
}
