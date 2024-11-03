<?php
// models/BaseModel.php
namespace Models;

use Config\Database;
use Interfaces\ModelInterface;

abstract class BaseModel implements ModelInterface {
    protected string $table;

    protected static function getDB() 
    {
        return Database::getConnection();
    }
}
