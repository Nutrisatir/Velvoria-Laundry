<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'midtrans',
                'null'       => false,
            ],
            'provider_order_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
                'null'       => false,
            ],
            'transaction_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'payment_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'snap_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'gross_amount' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'transaction_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null'       => true,
            ],
            'fraud_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null'       => true,
            ],
            'payment_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'unpaid',
                'null'       => false,
            ],
            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('provider_order_id');
        $this->forge->addKey('transaction_id');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('payments', false, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('payments', true);
    }
}
