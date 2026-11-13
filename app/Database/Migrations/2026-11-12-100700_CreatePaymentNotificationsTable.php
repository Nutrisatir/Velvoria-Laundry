<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentNotificationsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'payment_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'provider_order_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
                'null'       => false,
            ],
            'notification_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => false,
            ],
            'event_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null'       => false,
            ],
            'payload_json' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'processing_result' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('notification_hash');
        $this->forge->addForeignKey('payment_id', 'payments', 'id', 'SET NULL', 'SET NULL');

        $this->forge->createTable('payment_notifications', false, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('payment_notifications', true);
    }
}
