<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerAddressModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\OrderStatusHistoryModel;
use App\Models\PaymentModel;
use App\Models\StoreSettingModel;
use App\Models\UserModel;
use App\Services\OrderService;
use App\Services\OrderStatusService;

class OrderController extends BaseController
{
    protected OrderModel $orderModel;
    protected OrderItemModel $itemModel;
    protected OrderStatusService $statusService;
    protected OrderService $orderService;

    public function __construct()
    {
        $this->orderModel    = new OrderModel();
        $this->itemModel     = new OrderItemModel();
        $this->statusService = new OrderStatusService();
        $this->orderService  = new OrderService();
    }

    public function index()
    {
        $status         = $this->request->getGet('status');
        $paymentStatus  = $this->request->getGet('payment_status');
        $handoverMethod = $this->request->getGet('handover_method');
        $startDate      = $this->request->getGet('start_date');
        $endDate        = $this->request->getGet('end_date');
        $search         = trim($this->request->getGet('search') ?? '');

        $builder = $this->orderModel
            ->select('orders.*, users.name as customer_name, users.phone as customer_phone, users.email as customer_email')
            ->join('users', 'users.id = orders.user_id');

        if (! empty($status)) {
            $builder->where('orders.order_status', $status);
        }

        if (! empty($paymentStatus)) {
            $builder->where('orders.payment_status', $paymentStatus);
        }

        if (! empty($handoverMethod)) {
            $builder->where('orders.handover_method', $handoverMethod);
        }

        if (! empty($startDate)) {
            $builder->where('orders.created_at >=', $startDate . ' 00:00:00');
        }

        if (! empty($endDate)) {
            $builder->where('orders.created_at <=', $endDate . ' 23:59:59');
        }

        if (! empty($search)) {
            $builder->groupStart()
                ->like('orders.order_number', $search)
                ->orLike('users.name', $search)
                ->orLike('users.phone', $search)
                ->groupEnd();
        }

        $orders = $builder->orderBy('orders.id', 'DESC')->paginate(15);
        $pager  = $this->orderModel->pager;

        return view('admin/orders/index', [
            'orders'         => $orders,
            'pager'          => $pager,
            'status'         => $status,
            'paymentStatus'  => $paymentStatus,
            'handoverMethod' => $handoverMethod,
            'startDate'      => $startDate,
            'endDate'        => $endDate,
            'search'         => $search,
        ]);
    }

    public function show($id)
    {
        $order = $this->orderModel
            ->select('orders.*, users.name as customer_name, users.phone as customer_phone, users.email as customer_email')
            ->join('users', 'users.id = orders.user_id')
            ->where('orders.id', $id)
            ->first();

        if (! $order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $items         = $this->itemModel->getByOrder($id);
        $historyModel  = new OrderStatusHistoryModel();
        $histories     = $historyModel->getByOrder($id);
        $paymentModel  = new PaymentModel();
        $payments      = $paymentModel->where('order_id', $id)->orderBy('id', 'DESC')->findAll();
        $addressModel  = new CustomerAddressModel();
        $address       = $order['address_id'] ? $addressModel->find($order['address_id']) : null;
        $nextStatuses  = $this->statusService->getNextAllowedStatuses($order['order_status']);

        return view('admin/orders/show', [
            'order'        => $order,
            'items'        => $items,
            'histories'    => $histories,
            'payments'     => $payments,
            'address'      => $address,
            'nextStatuses' => $nextStatuses,
            'statusService'=> $this->statusService,
        ]);
    }

    public function updateStatus($id)
    {
        $newStatus = $this->request->getPost('status');
        $note      = trim($this->request->getPost('note') ?? '');
        $adminId   = (int) session()->get('user_id');

        $result = $this->statusService->transition($id, $newStatus, $adminId, $note);

        if (! $result['success']) {
            return redirect()->back()->with('error', $result['error']);
        }

        return redirect()->back()->with('success', $result['message']);
    }

    public function updateWeight($id)
    {
        $actualWeight = (float) $this->request->getPost('actual_weight');
        $adminId      = (int) session()->get('user_id');

        $result = $this->orderService->updateActualWeight($id, $actualWeight, $adminId);

        if (! $result['success']) {
            return redirect()->back()->with('error', $result['error']);
        }

        return redirect()->back()->with('success', $result['message']);
    }

    public function addNote($id)
    {
        $internalNote = trim($this->request->getPost('internal_note') ?? '');
        $this->orderModel->update($id, ['internal_note' => $internalNote]);

        return redirect()->back()->with('success', 'Catatan internal berhasil disimpan.');
    }

    public function printReceipt($id)
    {
        $order = $this->orderModel
            ->select('orders.*, users.name as customer_name, users.phone as customer_phone, users.email as customer_email')
            ->join('users', 'users.id = orders.user_id')
            ->where('orders.id', $id)
            ->first();

        if (! $order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $items        = $this->itemModel->getByOrder($id);
        $addressModel = new CustomerAddressModel();
        $address      = $order['address_id'] ? $addressModel->find($order['address_id']) : null;
        $settingModel = new StoreSettingModel();
        $settings     = $settingModel->getAllSettings();

        return view('customer/orders/receipt', [
            'order'    => $order,
            'items'    => $items,
            'address'  => $address,
            'settings' => $settings,
            'user'     => [
                'name'  => $order['customer_name'],
                'phone' => $order['customer_phone'],
                'email' => $order['customer_email'],
            ],
        ]);
    }
}
