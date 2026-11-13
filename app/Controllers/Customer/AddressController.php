<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\CustomerAddressModel;

class AddressController extends BaseController
{
    protected CustomerAddressModel $addressModel;

    public function __construct()
    {
        $this->addressModel = new CustomerAddressModel();
    }

    public function index()
    {
        $userId    = (int) session()->get('user_id');
        $addresses = $this->addressModel->getByUser($userId);

        return view('customer/addresses/index', [
            'addresses' => $addresses,
        ]);
    }

    public function create()
    {
        return view('customer/addresses/form', [
            'address' => null,
            'title'   => 'Tambah Alamat Baru',
        ]);
    }

    public function store()
    {
        $userId = (int) session()->get('user_id');

        $rules = [
            'label'          => 'required|min_length[2]|max_length[50]',
            'recipient_name' => 'required|min_length[2]|max_length[120]',
            'phone'          => 'required|min_length[9]|max_length[25]',
            'address'        => 'required|min_length[5]',
            'district'       => 'permit_empty|max_length[100]',
            'city'           => 'permit_empty|max_length[100]',
            'postal_code'    => 'permit_empty|max_length[10]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isPrimary = $this->request->getPost('is_primary') ? 1 : 0;

        // If user has no existing address, make this primary
        $existingCount = $this->addressModel->where('user_id', $userId)->countAllResults();
        if ($existingCount === 0) {
            $isPrimary = 1;
        }

        $data = [
            'user_id'        => $userId,
            'label'          => trim($this->request->getPost('label')),
            'recipient_name' => trim($this->request->getPost('recipient_name')),
            'phone'          => trim($this->request->getPost('phone')),
            'address'        => trim($this->request->getPost('address')),
            'district'       => trim($this->request->getPost('district') ?? ''),
            'city'           => trim($this->request->getPost('city') ?? ''),
            'postal_code'    => trim($this->request->getPost('postal_code') ?? ''),
            'notes'          => trim($this->request->getPost('notes') ?? ''),
            'is_primary'     => $isPrimary,
        ];

        $addressId = $this->addressModel->insert($data, true);

        if ($isPrimary && $addressId) {
            $this->addressModel->setPrimary($addressId, $userId);
        }

        return redirect()->to('/customer/addresses')->with('success', 'Alamat baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userId  = (int) session()->get('user_id');
        $address = $this->addressModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $address) {
            return redirect()->to('/customer/addresses')->with('error', 'Alamat tidak ditemukan.');
        }

        return view('customer/addresses/form', [
            'address' => $address,
            'title'   => 'Ubah Alamat',
        ]);
    }

    public function update($id)
    {
        $userId  = (int) session()->get('user_id');
        $address = $this->addressModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $address) {
            return redirect()->to('/customer/addresses')->with('error', 'Alamat tidak ditemukan.');
        }

        $rules = [
            'label'          => 'required|min_length[2]|max_length[50]',
            'recipient_name' => 'required|min_length[2]|max_length[120]',
            'phone'          => 'required|min_length[9]|max_length[25]',
            'address'        => 'required|min_length[5]',
            'district'       => 'permit_empty|max_length[100]',
            'city'           => 'permit_empty|max_length[100]',
            'postal_code'    => 'permit_empty|max_length[10]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isPrimary = $this->request->getPost('is_primary') ? 1 : 0;

        $data = [
            'label'          => trim($this->request->getPost('label')),
            'recipient_name' => trim($this->request->getPost('recipient_name')),
            'phone'          => trim($this->request->getPost('phone')),
            'address'        => trim($this->request->getPost('address')),
            'district'       => trim($this->request->getPost('district') ?? ''),
            'city'           => trim($this->request->getPost('city') ?? ''),
            'postal_code'    => trim($this->request->getPost('postal_code') ?? ''),
            'notes'          => trim($this->request->getPost('notes') ?? ''),
            'is_primary'     => $isPrimary,
        ];

        $this->addressModel->update($id, $data);

        if ($isPrimary) {
            $this->addressModel->setPrimary($id, $userId);
        }

        return redirect()->to('/customer/addresses')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function delete($id)
    {
        $userId  = (int) session()->get('user_id');
        $address = $this->addressModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $address) {
            return redirect()->to('/customer/addresses')->with('error', 'Alamat tidak ditemukan.');
        }

        $this->addressModel->delete($id);

        return redirect()->to('/customer/addresses')->with('success', 'Alamat berhasil dihapus.');
    }

    public function setPrimary($id)
    {
        $userId  = (int) session()->get('user_id');
        $address = $this->addressModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $address) {
            return redirect()->to('/customer/addresses')->with('error', 'Alamat tidak ditemukan.');
        }

        $this->addressModel->setPrimary($id, $userId);

        return redirect()->to('/customer/addresses')->with('success', 'Alamat utama berhasil diubah.');
    }
}
