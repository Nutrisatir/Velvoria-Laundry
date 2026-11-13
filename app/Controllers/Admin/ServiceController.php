<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminActivityLogModel;
use App\Models\ServiceCategoryModel;
use App\Models\ServiceModel;

class ServiceController extends BaseController
{
    protected ServiceModel $serviceModel;
    protected ServiceCategoryModel $categoryModel;
    protected AdminActivityLogModel $activityLog;

    public function __construct()
    {
        $this->serviceModel  = new ServiceModel();
        $this->categoryModel = new ServiceCategoryModel();
        $this->activityLog   = new AdminActivityLogModel();
    }

    public function index()
    {
        $services   = $this->serviceModel->getActive();
        $categories = $this->categoryModel->getActive();

        return view('admin/services/index', [
            'services'   => $services,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $categories = $this->categoryModel->getActive();

        return view('admin/services/form', [
            'service'    => null,
            'categories' => $categories,
            'title'      => 'Tambah Layanan Baru',
        ]);
    }

    public function store()
    {
        $rules = [
            'category_id'      => 'required|is_natural_no_zero',
            'code'             => 'required|min_length[2]|max_length[30]|is_unique[services.code]',
            'name'             => 'required|min_length[3]|max_length[150]',
            'unit'             => 'required|in_list[kg,pcs,pasang]',
            'price'            => 'required|numeric|greater_than_equal_to[0]',
            'minimum_quantity' => 'required|numeric|greater_than[0]',
            'estimated_hours'  => 'required|is_natural_no_zero',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $adminId = (int) session()->get('user_id');

        $data = [
            'category_id'      => (int) $this->request->getPost('category_id'),
            'code'             => strtoupper(trim($this->request->getPost('code'))),
            'name'             => trim($this->request->getPost('name')),
            'description'      => trim($this->request->getPost('description') ?? ''),
            'unit'             => $this->request->getPost('unit'),
            'price'            => (int) $this->request->getPost('price'),
            'minimum_quantity' => (float) $this->request->getPost('minimum_quantity'),
            'estimated_hours'  => (int) $this->request->getPost('estimated_hours'),
            'is_express'       => $this->request->getPost('is_express') ? 1 : 0,
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order'       => (int) ($this->request->getPost('sort_order') ?? 0),
        ];

        $serviceId = $this->serviceModel->insert($data, true);

        $this->activityLog->log($adminId, 'create_service', 'services', $serviceId, "Menambahkan layanan baru: {$data['name']}");

        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $service = $this->serviceModel->find($id);
        if (! $service) {
            return redirect()->to('/admin/services')->with('error', 'Layanan tidak ditemukan.');
        }

        $categories = $this->categoryModel->getActive();

        return view('admin/services/form', [
            'service'    => $service,
            'categories' => $categories,
            'title'      => 'Ubah Layanan: ' . $service['name'],
        ]);
    }

    public function update($id)
    {
        $service = $this->serviceModel->find($id);
        if (! $service) {
            return redirect()->to('/admin/services')->with('error', 'Layanan tidak ditemukan.');
        }

        $rules = [
            'category_id'      => 'required|is_natural_no_zero',
            'code'             => "required|min_length[2]|max_length[30]|is_unique[services.code,id,{$id}]",
            'name'             => 'required|min_length[3]|max_length[150]',
            'unit'             => 'required|in_list[kg,pcs,pasang]',
            'price'            => 'required|numeric|greater_than_equal_to[0]',
            'minimum_quantity' => 'required|numeric|greater_than[0]',
            'estimated_hours'  => 'required|is_natural_no_zero',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $adminId = (int) session()->get('user_id');

        $data = [
            'category_id'      => (int) $this->request->getPost('category_id'),
            'code'             => strtoupper(trim($this->request->getPost('code'))),
            'name'             => trim($this->request->getPost('name')),
            'description'      => trim($this->request->getPost('description') ?? ''),
            'unit'             => $this->request->getPost('unit'),
            'price'            => (int) $this->request->getPost('price'),
            'minimum_quantity' => (float) $this->request->getPost('minimum_quantity'),
            'estimated_hours'  => (int) $this->request->getPost('estimated_hours'),
            'is_express'       => $this->request->getPost('is_express') ? 1 : 0,
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order'       => (int) ($this->request->getPost('sort_order') ?? 0),
        ];

        $this->serviceModel->update($id, $data);

        $this->activityLog->log($adminId, 'update_service', 'services', $id, "Memperbarui layanan: {$data['name']}");

        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function toggleActive($id)
    {
        $service = $this->serviceModel->find($id);
        if (! $service) {
            return redirect()->to('/admin/services')->with('error', 'Layanan tidak ditemukan.');
        }

        $newStatus = $service['is_active'] ? 0 : 1;
        $this->serviceModel->update($id, ['is_active' => $newStatus]);

        $adminId = (int) session()->get('user_id');
        $label   = $newStatus ? 'mengaktifkan' : 'menonaktifkan';
        $this->activityLog->log($adminId, 'toggle_service', 'services', $id, "Admin {$label} layanan {$service['name']}");

        return redirect()->to('/admin/services')->with('success', "Status layanan {$service['name']} berhasil diubah.");
    }
}
