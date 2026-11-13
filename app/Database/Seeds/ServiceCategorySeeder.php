<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name'        => 'Layanan Kiloan',
                'slug'        => 'kiloan',
                'description' => 'Pencucian pakaian harian dengan hitungan per kilogram. Praktis, bersih, dan wangi.',
                'sort_order'  => 1,
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Layanan Satuan',
                'slug'        => 'satuan',
                'description' => 'Perawatan khusus untuk pakaian formal, jaket, sepatu, selimut, dan bed cover.',
                'sort_order'  => 2,
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($categories as $cat) {
            $existing = $this->db->table('service_categories')->where('slug', $cat['slug'])->get()->getRow();
            if (! $existing) {
                $this->db->table('service_categories')->insert($cat);
            }
        }
    }
}
