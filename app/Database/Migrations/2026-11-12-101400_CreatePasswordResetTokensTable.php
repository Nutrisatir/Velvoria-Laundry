<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePasswordResetTokensTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'token_hash' => ['type' => 'CHAR', 'constraint' => 64, 'null' => false],
            'expires_at' => ['type' => 'DATETIME', 'null' => false],
            'used_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('token_hash');
        $this->forge->addKey(['user_id', 'expires_at']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('password_reset_tokens', false, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('password_reset_tokens', true);
    }
}
