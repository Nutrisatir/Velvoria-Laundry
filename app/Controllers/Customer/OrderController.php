<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\CustomerAddressModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\OrderStatusHistoryModel;
use App\Models\PaymentModel;
use App\Models\PromotionModel;
use App\Models\ServiceCategoryModel;
use App\Models\ServiceModel;
use App\Models\StoreSettingModel;
use App\Models\UserModel;
use App\Services\MidtransService;
use App\Services\OrderService;
use App\Services\PricingService;
use App\Services\PromotionService;

class OrderController extends BaseController
{
    protected OrderModel $orderModel;
    protected OrderItemModel $itemModel;
    protected OrderService $orderService;
    protected ?MidtransService $midtransService = null;

    public function __construct()
    {
        $this->orderModel      = new OrderModel();
        $this->itemModel       = new OrderItemModel();
        $this->orderService    = new OrderService();
    }

    /** Load Midtrans only on payment-related actions/pages. */
    private function getMidtransService(): MidtransService
    {
        if ($this->midtransService === null) {
            $this->midtransService = new MidtransService();
        }

        return $this->midtransService;
    }

    public function index()
    {
        $userId = (int) session()->get('user_id');
        $tab    = $this->request->getGet('tab') ?? 'all';
        $search = trim($this->request->getGet('search') ?? '');

        $builder = $this->orderModel->where('user_id', $userId);

        if ($tab === 'unpaid') {
            $builder->where('payment_status', 'unpaid')->whereNotIn('order_status', ['cancelled', 'rejected']);
        } elseif ($tab === 'processing') {
            $builder->whereNotIn('order_status', ['awaiting_payment', 'completed', 'cancelled', 'rejected']);
        } elseif ($tab === 'completed') {
            $builder->where('order_status', 'completed');
        } elseif ($tab === 'cancelled') {
            $builder->whereIn('order_status', ['cancelled', 'rejected']);
        }

        if (! empty($search)) {
            $builder->groupStart()
                ->like('order_number', $search)
                ->orLike('customer_note', $search)
                ->groupEnd();
        }

        $orders = $builder->orderBy('id', 'DESC')->paginate(10);
        $pager  = $this->orderModel->pager;

        return view('customer/orders/index', [
            'orders' => $orders,
            'pager'  => $pager,
            'tab'    => $tab,
            'search' => $search,
        ]);
    }

    public function history()
    {
        $userId = (int) session()->get('user_id');
        $orders = $this->orderModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        return view('customer/orders/history', [
            'orders' => $orders,
            'pager'  => $this->orderModel->pager,
        ]);
    }

    public function create()
    {
        $userId        = (int) session()->get('user_id');
        $categoryModel = new ServiceCategoryModel();
        $serviceModel  = new ServiceModel();
        $addressModel  = new CustomerAddressModel();
        $settingModel  = new StoreSettingModel();

        $categories = $categoryModel->getActive();
        $services   = $serviceModel->getActive();
        $addresses  = $addressModel->getByUser($userId);
        $settings   = $settingModel->getAllSettings();

        $groupedServices = [];
        foreach ($services as $srv) {
            $catSlug = $srv['category_slug'] ?? 'kiloan';
            $groupedServices[$catSlug][] = $srv;
        }

        return view('customer/orders/create', [
            'categories'      => $categories,
            'services'        => $services,
            'groupedServices' => $groupedServices,
            'addresses'       => $addresses,
            'settings'        => $settings,
        ]);
    }

    public function store()
    {
        $userId = (int) session()->get('user_id');

        $rules = [
            'handover_method' => 'required|in_list[drop_off,pickup_delivery]',
            'items'           => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $rawItems = $this->request->getPost('items');
        if (is_string($rawItems)) {
            $items = json_decode($rawItems, true) ?? [];
        } else {
            $items = $rawItems;
        }

        $data = [
            'handover_method'   => $this->request->getPost('handover_method'),
            'address_id'        => $this->request->getPost('address_id'),
            'pickup_schedule'   => $this->request->getPost('pickup_schedule'),
            'delivery_schedule' => $this->request->getPost('delivery_schedule'),
            'estimated_weight'  => $this->request->getPost('estimated_weight'),
            'customer_note'     => $this->request->getPost('customer_note'),
            'promo_code'        => $this->request->getPost('promo_code'),
            'items'             => $items,
        ];

        $result = $this->orderService->createOrder($userId, $data);

        if (! $result['success']) {
            return redirect()->back()->withInput()->with('error', $result['error']);
        }

        $order = $result['order'];

        return redirect()->to('/customer/orders/' . $order['order_number'])->with('success', 'Pesanan Anda berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    public function show($orderNumber)
    {
        $userId = (int) session()->get('user_id');
        $order  = $this->orderModel->findByOrderNumber($orderNumber);

        if (! $order || (int) $order['user_id'] !== $userId) {
            return redirect()->to('/customer/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $items          = $this->itemModel->getByOrder($order['id']);
        $historyModel   = new OrderStatusHistoryModel();
        $histories      = $historyModel->getByOrder($order['id']);
        $paymentModel   = new PaymentModel();
        $payments       = $paymentModel->where('order_id', $order['id'])->orderBy('id', 'DESC')->findAll();
        $addressModel   = new CustomerAddressModel();
        $address        = $order['address_id'] ? $addressModel->find($order['address_id']) : null;
        $midtrans       = $this->getMidtransService();
        $snapClientKey  = $midtrans->getClientKey();
        $snapJsUrl      = $midtrans->getSnapJsUrl();

        return view('customer/orders/show', [
            'order'         => $order,
            'items'         => $items,
            'histories'     => $histories,
            'payments'      => $payments,
            'address'       => $address,
            'snapClientKey' => $snapClientKey,
            'snapJsUrl'     => $snapJsUrl,
        ]);
    }

    public function cancel($orderNumber)
    {
        $userId = (int) session()->get('user_id');
        $order  = $this->orderModel->findByOrderNumber($orderNumber);

        if (! $order || (int) $order['user_id'] !== $userId) {
            return redirect()->to('/customer/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $reason = $this->request->getPost('cancel_reason') ?? 'Dibatalkan oleh pelanggan';
        $result = $this->orderService->cancelOrder($order['id'], $userId, $reason, false);

        if (! $result['success']) {
            return redirect()->back()->with('error', $result['error']);
        }

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function pay($orderNumber)
    {
        $userId = (int) session()->get('user_id');
        $order  = $this->orderModel->findByOrderNumber($orderNumber);

        if (! $order || (int) $order['user_id'] !== $userId) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'error'   => 'Pesanan tidak ditemukan atau bukan milik akun ini.',
            ]);
        }

        if (($order['payment_status'] ?? '') === 'paid') {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'error'   => 'Pesanan ini sudah dibayar.',
            ]);
        }

        if (in_array($order['order_status'] ?? '', ['cancelled', 'rejected', 'completed'], true)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'error'   => 'Pesanan yang dibatalkan, ditolak, atau selesai tidak dapat dibayar.',
            ]);
        }

        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (! is_array($user)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'error'   => 'Data pelanggan tidak ditemukan. Perbarui profil lalu coba kembali.',
            ]);
        }

        try {
            $result = $this->getMidtransService()->createSnapToken($order, $user);
        } catch (\Throwable $e) {
            log_message('error', 'Midtrans pay endpoint: ' . $e->getMessage());
            $result = [
                'success' => false,
                'error'   => 'Layanan pembayaran belum siap. Periksa instalasi SDK dan konfigurasi Midtrans.',
            ];
        }

        if (! ($result['success'] ?? false)) {
            return $this->response->setStatusCode(422)->setJSON($result);
        }

        return $this->response->setJSON($result);
    }

    public function receipt($orderNumber)
    {
        $userId = (int) session()->get('user_id');
        $order  = $this->orderModel->findByOrderNumber($orderNumber);

        if (! $order || (int) $order['user_id'] !== $userId) {
            return redirect()->to('/customer/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $items        = $this->itemModel->getByOrder($order['id']);
        $addressModel = new CustomerAddressModel();
        $address      = $order['address_id'] ? $addressModel->find($order['address_id']) : null;
        $settingModel = new StoreSettingModel();
        $settings     = $settingModel->getAllSettings();
        $userModel    = new UserModel();
        $user         = $userModel->find($userId);

        return view('customer/orders/receipt', [
            'order'    => $order,
            'items'    => $items,
            'address'  => $address,
            'settings' => $settings,
            'user'     => $user,
        ]);
    }
}
