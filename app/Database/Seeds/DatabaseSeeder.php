<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('AdminSeeder');
        $this->call('ServiceCategorySeeder');
        $this->call('ServiceSeeder');
        $this->call('StoreSettingSeeder');

        // Optional demo promo
        $existingPromo = $this->db->table('promotions')->where('code', 'VELVORIA10')->get()->getRow();
        if (! $existingPromo) {
            $this->db->table('promotions')->insert([
                'code'             => 'VELVORIA10',
                'name'             => 'Diskon Pelanggan Baru 10%',
                'discount_type'    => 'percent',
                'discount_value'   => 10,
                'minimum_order'    => 20000,
                'maximum_discount' => 15000,
                'usage_limit'      => 1000,
                'usage_per_user'   => 1,
                'starts_at'        => date('Y-m-d 00:00:00'),
                'ends_at'          => date('Y-m-d 23:59:59', strtotime('+1 year')),
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
