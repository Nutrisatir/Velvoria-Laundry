<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminActivityLogModel;
use App\Models\CustomerAddressModel;
use App\Models\OrderModel;
use App\Models\UserModel;

class CustomerController extends BaseController
{
    protected UserModel $userModel;
    protected OrderModel $orderModel;
    protected CustomerAddressModel $addressModel;
    protected AdminActivityLogModel $activityLog;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->orderModel   = new OrderModel();
        $this->addressModel = new CustomerAddressModel();
        $this->activityLog  = new AdminActivityLogModel();
    }

    public function index()
    {
        $search = trim($this->request->getGet('search') ?? '');

        $builder = $this->userModel
            ->select('users.*, COUNT(orders.id) as total_orders, COALESCE(SUM(orders.grand_total), 0) as total_spend')
            ->join('orders', 'orders.user_id = users.id AND orders.payment_status = "paid"', 'left')
            ->where('users.role', 'customer')
            ->groupBy('users.id');

        if (! empty($search)) {
            $builder->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->orLike('users.phone', $search)
                ->groupEnd();
        }

        $customers = $builder->orderBy('users.id', 'DESC')->paginate(15);
        $pager     = $this->userModel->pager;

        return view('admin/customers/index', [
            'customers' => $customers,
            'pager'     => $pager,
            'search'    => $search,
        ]);
    }

    public function show($id)
    {
        $customer = $this->userModel->where('role', 'customer')->find($id);
        if (! $customer) {
            return redirect()->to('/admin/customers')->with('error', 'Pelanggan tidak ditemukan.');
        }

        $orders    = $this->orderModel->where('user_id', $id)->orderBy('id', 'DESC')->findAll();
        $addresses = $this->addressModel->getByUser($id);

        $totalSpent = $this->orderModel
            ->selectSum('grand_total')
            ->where('user_id', $id)
            ->where('payment_status', 'paid')
            ->first()['grand_total'] ?? 0;

        return view('admin/customers/show', [
            'customer'   => $customer,
            'orders'     => $orders,
            'addresses'  => $addresses,
            'totalSpent' => (int) $totalSpent,
        ]);
    }

    public function toggleStatus($id)
    {
        $customer = $this->userModel->where('role', 'customer')->find($id);
        if (! $customer) {
            return redirect()->to('/admin/customers')->with('error', 'Pelanggan tidak ditemukan.');
        }

        $newStatus = $customer['status'] === 'active' ? 'blocked' : 'active';
        $reason    = trim($this->request->getPost('reason') ?? 'Perubahan status oleh admin');

        $this->userModel->update($id, ['status' => $newStatus]);

        $adminId = (int) session()->get('user_id');
        $this->activityLog->log($adminId, 'toggle_user_status', 'users', $id, "Status pelanggan {$customer['name']} diubah menjadi {$newStatus}. Alasan: {$reason}");

        return redirect()->back()->with('success', "Status akun pelanggan {$customer['name']} berhasil diubah menjadi {$newStatus}.");
    }
}
