<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null'       => false,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'address_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'handover_method' => [
                'type'       => 'ENUM',
                'constraint' => ['drop_off', 'pickup_delivery'],
                'default'    => 'drop_off',
                'null'       => false,
            ],
            'pickup_schedule' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'delivery_schedule' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'estimated_weight' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'actual_weight' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'subtotal' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'default'  => 0,
                'null'     => false,
            ],
            'discount_amount' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'default'  => 0,
                'null'     => false,
            ],
            'delivery_fee' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'default'  => 0,
                'null'     => false,
            ],
            'additional_fee' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'default'  => 0,
                'null'     => false,
            ],
            'grand_total' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'default'  => 0,
                'null'     => false,
            ],
            'promo_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null'       => true,
            ],
            'order_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'awaiting_payment',
                'null'       => false,
            ],
            'payment_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'unpaid',
                'null'       => false,
            ],
            'customer_note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'internal_note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cancel_reason' => [
                'type' => 'TEXT',
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
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('order_number');
        $this->forge->addKey(['user_id', 'created_at']);
        $this->forge->addKey(['order_status', 'created_at']);
        $this->forge->addKey(['payment_status', 'created_at']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('address_id', 'customer_addresses', 'id', 'SET NULL', 'SET NULL');

        $this->forge->createTable('orders', false, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('orders', true);
    }
}
