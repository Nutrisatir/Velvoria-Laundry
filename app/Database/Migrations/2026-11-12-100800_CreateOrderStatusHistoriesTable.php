<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderStatusHistoriesTable extends Migration
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
            'old_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'new_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => false,
            ],
            'changed_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('changed_by', 'users', 'id', 'SET NULL', 'SET NULL');

        $this->forge->createTable('order_status_histories', false, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('order_status_histories', true);
    }
}
