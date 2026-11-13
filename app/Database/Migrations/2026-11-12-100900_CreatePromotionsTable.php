<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromotionsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null'       => false,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => false,
            ],
            'discount_type' => [
                'type'       => 'ENUM',
                'constraint' => ['fixed', 'percent'],
                'default'    => 'fixed',
                'null'       => false,
            ],
            'discount_value' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'minimum_order' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'default'  => 0,
                'null'     => false,
            ],
            'maximum_discount' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'usage_limit' => [
                'type' => 'INT',
                'null' => true,
            ],
            'usage_per_user' => [
                'type' => 'INT',
                'null' => true,
            ],
            'starts_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'ends_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
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

        $this->forge->createTable('promotions', false, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('promotions', true);
    }
}
