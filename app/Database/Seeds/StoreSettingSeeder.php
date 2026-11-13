<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StoreSettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            'store_name'              => 'Velvoria Laundry',
            'store_tagline'           => 'Baju Bersih, Waktu Lebih Luang.',
            'store_address'           => 'Jl. Senopati Raya No. 45, Kebayoran Baru, Jakarta Selatan',
            'store_phone'             => '021-7258901',
            'store_whatsapp'          => '6281234567890',
            'store_email'             => 'halo@velvorialaundry.com',
            'operating_hours'         => 'Senin - Sabtu: 07:00 - 21:00 WIB | Minggu: 08:00 - 18:00 WIB',
            'minimum_weight'          => '2',
            'delivery_fee'            => '5000',
            'free_delivery_threshold' => '50000',
            'cancellation_policy'     => 'Pesanan hanya dapat dibatalkan sebelum memasuki tahap pencucian (washing). Jika cucian sudah diterima di outlet dan dibatalkan, biaya penjemputan tetap berlaku.',
            'timezone'                => 'Asia/Jakarta',
        ];

        foreach ($settings as $key => $val) {
            $existing = $this->db->table('store_settings')->where('setting_key', $key)->get()->getRow();
            if ($existing) {
                $this->db->table('store_settings')->where('setting_key', $key)->update([
                    'setting_value' => $val,
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
            } else {
                $this->db->table('store_settings')->insert([
                    'setting_key'   => $key,
                    'setting_value' => $val,
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
