<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminActivityLogModel;
use App\Models\ServiceCategoryModel;

class CategoryController extends BaseController
{
    protected ServiceCategoryModel $categoryModel;
    protected AdminActivityLogModel $activityLog;

    public function __construct()
    {
        $this->categoryModel = new ServiceCategoryModel();
        $this->activityLog   = new AdminActivityLogModel();
    }

    public function index()
    {
        $categories = $this->categoryModel->orderBy('sort_order', 'ASC')->findAll();

        return view('admin/categories/index', [
            'categories' => $categories,
        ]);
    }

    public function store()
    {
        $rules = [
            'name'       => 'required|min_length[3]|max_length[100]',
            'slug'       => 'permit_empty|min_length[2]|max_length[120]|is_unique[service_categories.slug]',
            'sort_order' => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim($this->request->getPost('name'));
        $slug = trim($this->request->getPost('slug') ?? '');
        if (empty($slug)) {
            $slug = url_title($name, '-', true);
        }

        $data = [
            'name'        => $name,
            'slug'        => $slug,
            'description' => trim($this->request->getPost('description') ?? ''),
            'sort_order'  => (int) ($this->request->getPost('sort_order') ?? 0),
            'is_active'   => 1,
        ];

        $id = $this->categoryModel->insert($data, true);
        $adminId = (int) session()->get('user_id');
        $this->activityLog->log($adminId, 'create_category', 'service_categories', $id, "Menambahkan kategori: {$name}");

        return redirect()->to('/admin/service-categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update($id)
    {
        $category = $this->categoryModel->find($id);
        if (! $category) {
            return redirect()->to('/admin/service-categories')->with('error', 'Kategori tidak ditemukan.');
        }

        $rules = [
            'name'       => 'required|min_length[3]|max_length[100]',
            'slug'       => "required|min_length[2]|max_length[120]|is_unique[service_categories.slug,id,{$id}]",
            'sort_order' => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'        => trim($this->request->getPost('name')),
            'slug'        => trim($this->request->getPost('slug')),
            'description' => trim($this->request->getPost('description') ?? ''),
            'sort_order'  => (int) ($this->request->getPost('sort_order') ?? 0),
        ];

        $this->categoryModel->update($id, $data);
        $adminId = (int) session()->get('user_id');
        $this->activityLog->log($adminId, 'update_category', 'service_categories', $id, "Memperbarui kategori: {$data['name']}");

        return redirect()->to('/admin/service-categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggleActive($id)
    {
        $category = $this->categoryModel->find($id);
        if (! $category) {
            return redirect()->to('/admin/service-categories')->with('error', 'Kategori tidak ditemukan.');
        }

        $newStatus = $category['is_active'] ? 0 : 1;
        $this->categoryModel->update($id, ['is_active' => $newStatus]);

        return redirect()->to('/admin/service-categories')->with('success', 'Status kategori berhasil diubah.');
    }
}
