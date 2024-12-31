<?php
// interfaces/ModelInterface.php
namespace App\Interfaces;

interface ModelInterface {
    public static function findById(int $id);
    public static function create(array $data);
    public static function findAll(): array;
}
