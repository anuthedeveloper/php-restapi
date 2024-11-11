<?php
// migrations/2024_11_11_211239_create_users_table.php
namespace Migrations;

use App\Schemas\MigrationInterface;
use App\Schemas\Schema;

class CreateUsersTable implements MigrationInterface {
    public static function up() {
        Schema::createTable('users', [
            'id' => 'INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'fullname' => 'VARCHAR(100) NOT NULL',
            'email' => 'VARCHAR(100) NOT NULL UNIQUE',
            'password' => 'VARCHAR(255) NOT NULL',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
    }

    public static function down() {
        Schema::dropTable('users');
    }
}
