<?php
// models/BaseModel.php
namespace Models;

use Config\Database;
use Interfaces\ModelInterface;
use PDO;

abstract class BaseModel implements ModelInterface {

    protected static ?PDO $db = null;

    public function __construct()
    {
        // Initialize the connection only once
        if (is_null(self::$db)) {
            self::$db = Database::getConnection();
        }
    }
}
