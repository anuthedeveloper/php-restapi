<?php
// migrations/2024_11_11_211244_create_files_table.php
namespace Migrations;

use App\Schemas\MigrationInterface;
use App\Schemas\Schema;

class CreateFilesTable implements MigrationInterface {
    public static function up() {
        Schema::createTable('files', [
            'id' => 'INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'filename' => 'VARCHAR(255) NOT NULL',
            'mime_type' => 'VARCHAR(100) NOT NULL',
            'data' => 'LONGBLOB NOT NULL',
            'uploaded_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
    }

    public static function down() {
        Schema::dropTable('files');
    }
}
