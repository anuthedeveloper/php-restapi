<?php
// models/BaseModel.php
namespace App\Models;

use Config\Database;
use App\Interfaces\ModelInterface;

abstract class BaseModel implements ModelInterface {
    protected static ?Database $db = null;

    public function __construct()
    {  
        // Initialize the connection only once
        if (is_null(self::$db)) {
            self::$db = Database::getInstance();
            if (self::$db === null) {
                throw new \Exception("Database class instantiation failed.");
            }
        }
    }

    public static function initialize(): void
    {
        if (self::$db === null) {
            // Create an instance of the class to invoke the constructor
            new static();
        }
    }
}
