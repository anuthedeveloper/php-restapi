<?php
// schemas/Schema.php
namespace App\Schemas;

use Config\Database;

// $db = Database::getConnection();  // Get the PDO connection

class Schema {
    public static function createTable(string $tableName, array $columns) {
        $columnDefinitions = array_map(fn($name, $type) => "`$name` $type", array_keys($columns), $columns);
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(', ', $columnDefinitions) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $db = Database::getConnection();
        $db->exec($sql);
        echo "Table `$tableName` created successfully.\n";
    }

    public static function dropTable(string $tableName) {
        $sql = "DROP TABLE IF EXISTS `$tableName`;";
        
        $db = Database::getConnection();
        $db->exec($sql);
        echo "Table `$tableName` dropped successfully.\n";
    }
}
