<?php
// interfaces/ModelInterface.php
namespace Interfaces;

interface ModelInterface {
    public static function find(int $id);
    public static function create(array $data);
    public static function findAll(): array;
}
