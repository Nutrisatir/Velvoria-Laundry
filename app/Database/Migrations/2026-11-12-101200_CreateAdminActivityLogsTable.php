<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminActivityLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'entity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
            ],
            'entity_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'SET NULL');

        $this->forge->createTable('admin_activity_logs', false, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('admin_activity_logs', true);
    }
}
