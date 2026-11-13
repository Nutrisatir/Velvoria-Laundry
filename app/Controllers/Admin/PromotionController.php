<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminActivityLogModel;
use App\Models\PromotionModel;
use App\Models\PromotionUsageModel;

class PromotionController extends BaseController
{
    protected PromotionModel $promoModel;
    protected PromotionUsageModel $usageModel;
    protected AdminActivityLogModel $activityLog;

    public function __construct()
    {
        $this->promoModel   = new PromotionModel();
        $this->usageModel   = new PromotionUsageModel();
        $this->activityLog  = new AdminActivityLogModel();
    }

    public function index()
    {
        $promotions = $this->promoModel->orderBy('id', 'DESC')->findAll();

        return view('admin/promotions/index', [
            'promotions' => $promotions,
        ]);
    }

    public function create()
    {
        return view('admin/promotions/form', [
            'promo' => null,
            'title' => 'Tambah Promo Baru',
        ]);
    }

    public function store()
    {
        $rules = [
            'code'           => 'required|min_length[3]|max_length[40]|is_unique[promotions.code]',
            'name'           => 'required|min_length[3]|max_length[120]',
            'discount_type'  => 'required|in_list[fixed,percent]',
            'discount_value' => 'required|numeric|greater_than[0]',
            'starts_at'      => 'required',
            'ends_at'        => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $adminId = (int) session()->get('user_id');

        $data = [
            'code'             => strtoupper(trim($this->request->getPost('code'))),
            'name'             => trim($this->request->getPost('name')),
            'discount_type'    => $this->request->getPost('discount_type'),
            'discount_value'   => (int) $this->request->getPost('discount_value'),
            'minimum_order'    => (int) ($this->request->getPost('minimum_order') ?? 0),
            'maximum_discount' => ! empty($this->request->getPost('maximum_discount')) ? (int) $this->request->getPost('maximum_discount') : null,
            'usage_limit'      => ! empty($this->request->getPost('usage_limit')) ? (int) $this->request->getPost('usage_limit') : null,
            'usage_per_user'   => ! empty($this->request->getPost('usage_per_user')) ? (int) $this->request->getPost('usage_per_user') : null,
            'starts_at'        => $this->request->getPost('starts_at'),
            'ends_at'          => $this->request->getPost('ends_at'),
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $id = $this->promoModel->insert($data, true);
        $this->activityLog->log($adminId, 'create_promo', 'promotions', $id, "Menambahkan kode promo: {$data['code']}");

        return redirect()->to('/admin/promotions')->with('success', 'Promo berhasil dibuat.');
    }

    public function edit($id)
    {
        $promo = $this->promoModel->find($id);
        if (! $promo) {
            return redirect()->to('/admin/promotions')->with('error', 'Promo tidak ditemukan.');
        }

        return view('admin/promotions/form', [
            'promo' => $promo,
            'title' => 'Ubah Promo: ' . $promo['code'],
        ]);
    }

    public function update($id)
    {
        $promo = $this->promoModel->find($id);
        if (! $promo) {
            return redirect()->to('/admin/promotions')->with('error', 'Promo tidak ditemukan.');
        }

        $rules = [
            'code'           => "required|min_length[3]|max_length[40]|is_unique[promotions.code,id,{$id}]",
            'name'           => 'required|min_length[3]|max_length[120]',
            'discount_type'  => 'required|in_list[fixed,percent]',
            'discount_value' => 'required|numeric|greater_than[0]',
            'starts_at'      => 'required',
            'ends_at'        => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $adminId = (int) session()->get('user_id');

        $data = [
            'code'             => strtoupper(trim($this->request->getPost('code'))),
            'name'             => trim($this->request->getPost('name')),
            'discount_type'    => $this->request->getPost('discount_type'),
            'discount_value'   => (int) $this->request->getPost('discount_value'),
            'minimum_order'    => (int) ($this->request->getPost('minimum_order') ?? 0),
            'maximum_discount' => ! empty($this->request->getPost('maximum_discount')) ? (int) $this->request->getPost('maximum_discount') : null,
            'usage_limit'      => ! empty($this->request->getPost('usage_limit')) ? (int) $this->request->getPost('usage_limit') : null,
            'usage_per_user'   => ! empty($this->request->getPost('usage_per_user')) ? (int) $this->request->getPost('usage_per_user') : null,
            'starts_at'        => $this->request->getPost('starts_at'),
            'ends_at'          => $this->request->getPost('ends_at'),
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $this->promoModel->update($id, $data);
        $this->activityLog->log($adminId, 'update_promo', 'promotions', $id, "Memperbarui promo: {$data['code']}");

        return redirect()->to('/admin/promotions')->with('success', 'Promo berhasil diperbarui.');
    }

    public function toggleActive($id)
    {
        $promo = $this->promoModel->find($id);
        if (! $promo) {
            return redirect()->to('/admin/promotions')->with('error', 'Promo tidak ditemukan.');
        }

        $newStatus = $promo['is_active'] ? 0 : 1;
        $this->promoModel->update($id, ['is_active' => $newStatus]);

        return redirect()->to('/admin/promotions')->with('success', 'Status promo berhasil diubah.');
    }
}
