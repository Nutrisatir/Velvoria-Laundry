<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'category_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => false,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'unit' => [
                'type'       => 'ENUM',
                'constraint' => ['kg', 'pcs', 'pasang'],
                'default'    => 'kg',
                'null'       => false,
            ],
            'price' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'minimum_quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '1.00',
                'null'       => false,
            ],
            'estimated_hours' => [
                'type'    => 'INT',
                'default' => 48,
                'null'    => false,
            ],
            'is_express' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'sort_order' => [
                'type'    => 'INT',
                'default' => 0,
                'null'    => false,
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
        $this->forge->addUniqueKey('code');
        $this->forge->addForeignKey('category_id', 'service_categories', 'id', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('services', false, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('services', true);
    }
}
