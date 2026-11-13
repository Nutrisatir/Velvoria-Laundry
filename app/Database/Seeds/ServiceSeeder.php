<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $kiloanCat = $this->db->table('service_categories')->where('slug', 'kiloan')->get()->getRow();
        $satuanCat = $this->db->table('service_categories')->where('slug', 'satuan')->get()->getRow();

        $kiloanId = $kiloanCat ? $kiloanCat->id : 1;
        $satuanId = $satuanCat ? $satuanCat->id : 2;

        $services = [
            // Kiloan Services
            [
                'category_id'      => $kiloanId,
                'code'             => 'KG-REG',
                'name'             => 'Cuci Kering Lipat Reguler',
                'description'      => 'Pencucian higienis dengan detergen ramah kain, pengeringan mesin, dan lipat rapi.',
                'unit'             => 'kg',
                'price'            => 7000,
                'minimum_quantity' => 2.00,
                'estimated_hours'  => 48,
                'is_express'       => 0,
                'is_active'        => 1,
                'sort_order'       => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'      => $kiloanId,
                'code'             => 'KG-EXP',
                'name'             => 'Cuci Kering Lipat Express',
                'description'      => 'Layanan kilat 24 jam selesai. Cuci, kering, dan lipat rapi tanpa kompromi kebersihan.',
                'unit'             => 'kg',
                'price'            => 12000,
                'minimum_quantity' => 2.00,
                'estimated_hours'  => 24,
                'is_express'       => 1,
                'sort_order'       => 2,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'      => $kiloanId,
                'code'             => 'KG-SET',
                'name'             => 'Cuci Setrika Reguler',
                'description'      => 'Paket lengkap cuci, kering, dan setrika uap halus harum siap pakai.',
                'unit'             => 'kg',
                'price'            => 9000,
                'minimum_quantity' => 2.00,
                'estimated_hours'  => 48,
                'is_express'       => 0,
                'sort_order'       => 3,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'      => $kiloanId,
                'code'             => 'KG-SETX',
                'name'             => 'Cuci Setrika Express',
                'description'      => 'Paket cuci setrika cepat dalam 24 jam dengan setrika uap presisi tinggi.',
                'unit'             => 'kg',
                'price'            => 15000,
                'minimum_quantity' => 2.00,
                'estimated_hours'  => 24,
                'is_express'       => 1,
                'sort_order'       => 4,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'      => $kiloanId,
                'code'             => 'KG-SEL',
                'name'             => 'Setrika Saja',
                'description'      => 'Khusus pakaian yang sudah dicuci bersih di rumah. Disetrika uap rapi dan harum.',
                'unit'             => 'kg',
                'price'            => 6000,
                'minimum_quantity' => 2.00,
                'estimated_hours'  => 24,
                'is_express'       => 0,
                'sort_order'       => 5,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],

            // Satuan Services
            [
                'category_id'      => $satuanId,
                'code'             => 'ST-KEM',
                'name'             => 'Kemeja',
                'description'      => 'Perawatan kemeja kerja/formal dengan setrika kerah dan lengan tegak sempurna.',
                'unit'             => 'pcs',
                'price'            => 8000,
                'minimum_quantity' => 1.00,
                'estimated_hours'  => 48,
                'is_express'       => 0,
                'sort_order'       => 6,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'      => $satuanId,
                'code'             => 'ST-JKT',
                'name'             => 'Jaket',
                'description'      => 'Pencucian bahan jaket tebal, parasut, denim, atau rajut dengan formula lembut.',
                'unit'             => 'pcs',
                'price'            => 18000,
                'minimum_quantity' => 1.00,
                'estimated_hours'  => 72,
                'is_express'       => 0,
                'sort_order'       => 7,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'      => $satuanId,
                'code'             => 'ST-SPT',
                'name'             => 'Sepatu',
                'description'      => 'Deep clean sepatu sneakers, canvas, atau kulit sintetis dengan sikat khusus.',
                'unit'             => 'pasang',
                'price'            => 30000,
                'minimum_quantity' => 1.00,
                'estimated_hours'  => 72,
                'is_express'       => 0,
                'sort_order'       => 8,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'      => $satuanId,
                'code'             => 'ST-BDG',
                'name'             => 'Bed Cover',
                'description'      => 'Pencucian bed cover ukuran single hingga king size bebas tungau dan debu.',
                'unit'             => 'pcs',
                'price'            => 25000,
                'minimum_quantity' => 1.00,
                'estimated_hours'  => 72,
                'is_express'       => 0,
                'sort_order'       => 9,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'      => $satuanId,
                'code'             => 'ST-SEL',
                'name'             => 'Selimut',
                'description'      => 'Pembersihan selimut bulu atau katun tebal agar tetap empuk dan wangi menenangkan.',
                'unit'             => 'pcs',
                'price'            => 20000,
                'minimum_quantity' => 1.00,
                'estimated_hours'  => 72,
                'is_express'       => 0,
                'sort_order'       => 10,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'      => $satuanId,
                'code'             => 'ST-GRD',
                'name'             => 'Gorden',
                'description'      => 'Pencucian kain gorden per kg untuk mengangkat debu membandel tanpa merusak serat.',
                'unit'             => 'kg',
                'price'            => 15000,
                'minimum_quantity' => 1.00,
                'estimated_hours'  => 96,
                'is_express'       => 0,
                'sort_order'       => 11,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($services as $srv) {
            $existing = $this->db->table('services')->where('code', $srv['code'])->get()->getRow();
            if (! $existing) {
                $this->db->table('services')->insert($srv);
            }
        }
    }
}
