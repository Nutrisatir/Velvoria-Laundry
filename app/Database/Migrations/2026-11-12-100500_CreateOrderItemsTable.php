<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemsTable extends Migration
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
            'service_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'service_code_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => false,
            ],
            'service_name_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'unit_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'unit_price' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'line_total' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
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
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'SET NULL', 'SET NULL');

        $this->forge->createTable('order_items', false, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('order_items', true);
    }
}
