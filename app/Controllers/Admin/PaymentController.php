<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminActivityLogModel;
use App\Models\OrderModel;
use App\Models\OrderStatusHistoryModel;
use App\Models\PaymentModel;
use App\Models\PaymentNotificationModel;
use App\Services\OrderStatusService;

class PaymentController extends BaseController
{
    protected PaymentModel $paymentModel;
    protected OrderModel $orderModel;
    protected PaymentNotificationModel $notificationModel;
    protected AdminActivityLogModel $activityLog;

    public function __construct()
    {
        $this->paymentModel      = new PaymentModel();
        $this->orderModel        = new OrderModel();
        $this->notificationModel = new PaymentNotificationModel();
        $this->activityLog       = new AdminActivityLogModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $search = trim($this->request->getGet('search') ?? '');

        $builder = $this->paymentModel
            ->select('payments.*, orders.order_number, users.name as customer_name')
            ->join('orders', 'orders.id = payments.order_id')
            ->join('users', 'users.id = orders.user_id');

        if (! empty($status)) {
            $builder->where('payments.payment_status', $status);
        }

        if (! empty($search)) {
            $builder->groupStart()
                ->like('orders.order_number', $search)
                ->orLike('payments.provider_order_id', $search)
                ->orLike('payments.transaction_id', $search)
                ->orLike('users.name', $search)
                ->groupEnd();
        }

        $payments = $builder->orderBy('payments.id', 'DESC')->paginate(15);
        $pager    = $this->paymentModel->pager;

        return view('admin/payments/index', [
            'payments' => $payments,
            'pager'    => $pager,
            'status'   => $status,
            'search'   => $search,
        ]);
    }

    public function show($id)
    {
        $payment = $this->paymentModel
            ->select('payments.*, orders.order_number, orders.grand_total, users.name as customer_name, users.email as customer_email')
            ->join('orders', 'orders.id = payments.order_id')
            ->join('users', 'users.id = orders.user_id')
            ->where('payments.id', $id)
            ->first();

        if (! $payment) {
            return redirect()->to('/admin/payments')->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $notifications = $this->notificationModel
            ->where('payment_id', $id)
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('admin/payments/show', [
            'payment'       => $payment,
            'notifications' => $notifications,
        ]);
    }

    public function markPaid($id)
    {
        $payment = $this->paymentModel->find($id);
        if (! $payment) {
            return redirect()->to('/admin/payments')->with('error', 'Pembayaran tidak ditemukan.');
        }

        $note = trim((string) $this->request->getPost('note'));
        if ($note === '') {
            return redirect()->back()->with('error', 'Catatan dan bukti konfirmasi pembayaran manual wajib diisi.');
        }

        $order = $this->orderModel->find($payment['order_id']);
        if (! $order) {
            return redirect()->back()->with('error', 'Pesanan terkait tidak ditemukan.');
        }
        if ($order['payment_status'] === 'paid') {
            return redirect()->back()->with('error', 'Pesanan ini sudah tercatat lunas.');
        }

        $adminId = (int) session()->get('user_id');
        $db = db_connect();
        $db->transBegin();
        try {
            // Tutup attempt Midtrans yang belum selesai, jangan ubah transaksi provider menjadi tunai.
            if (in_array($payment['payment_status'], ['unpaid', 'pending'], true)) {
                $this->paymentModel->update($payment['id'], [
                    'payment_status' => 'cancelled',
                    'transaction_status' => 'cancel',
                ]);
            }

            $manualProviderOrderId = 'MANUAL-' . $order['order_number'] . '-' . strtoupper(bin2hex(random_bytes(3)));
            $this->paymentModel->insert([
                'order_id' => $order['id'],
                'provider' => 'manual',
                'provider_order_id' => $manualProviderOrderId,
                'transaction_id' => null,
                'payment_type' => 'cash',
                'gross_amount' => (int) $order['grand_total'],
                'transaction_status' => 'settlement',
                'fraud_status' => null,
                'payment_status' => 'paid',
                'paid_at' => date('Y-m-d H:i:s'),
            ]);

            $orderUpdate = ['payment_status' => 'paid'];
            if ($order['order_status'] === OrderStatusService::STATUS_AWAITING_PAYMENT) {
                $orderUpdate['order_status'] = OrderStatusService::STATUS_PENDING_PICKUP;
                (new OrderStatusHistoryModel())->insert([
                    'order_id' => $order['id'],
                    'old_status' => OrderStatusService::STATUS_AWAITING_PAYMENT,
                    'new_status' => OrderStatusService::STATUS_PENDING_PICKUP,
                    'changed_by' => $adminId,
                    'note' => 'Pembayaran tunai/manual diverifikasi admin. ' . $note,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            $this->orderModel->update($order['id'], $orderUpdate);
            $this->activityLog->log($adminId, 'manual_payment_confirmed', 'orders', (int) $order['id'], 'Pembayaran tunai dikonfirmasi. ' . $note);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Database transaction failed.');
            }
            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Manual payment confirmation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Konfirmasi pembayaran manual gagal disimpan.');
        }

        return redirect()->back()->with('success', 'Pembayaran manual berhasil dicatat sebagai transaksi terpisah.');
    }
}
