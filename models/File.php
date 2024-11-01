<?php
// models/File.php
namespace Models;

class File extends BaseModel {
    private $db;

    public static function uploadFile(string $filename, string $mimeType, string $fileData): int 
    {
        $stmt = self::getDB()->prepare("INSERT INTO files (filename, mime_type, data) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $filename);
        $stmt->bindParam(2, $mimeType);
        $stmt->bindParam(3, $fileData, \PDO::PARAM_LOB);
        $stmt->execute();
        return self::getDB()->lastInsertId();
    }

    public static function getFile(int $id) 
    {
        $stmt = self::getDB()->prepare("SELECT filename, mime_type, data FROM files WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function find(int $id) 
    {
        $stmt = self::getDB()->prepare("SELECT filename, mime_type, data FROM files WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create(array $data) { }

    public static function all(): array { 
        return [];
    }

}
