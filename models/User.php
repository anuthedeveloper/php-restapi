<?php
// models/User.php
namespace Models;

class User extends BaseModel {
    protected $table = "users";

    public static function authenticate(string $email, string $password) 
    {
        $stmt = self::getDB()->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public static function find(int $id) 
    {
        $stmt = self::getDB()->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function create(array $data) 
    {
        $stmt = self::getDB()->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        return $stmt->execute([$data['name'], $data['email']]);
    }

    public static function all(): array 
    {
        $stmt = self::getDB()->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }
}
