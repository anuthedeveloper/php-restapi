<?php
// models/User.php
namespace Models;
use PDO;

class User extends BaseModel {

    protected static $table = "users";

    public static function authenticate(string $email, string $password) 
    {
        $stmt = self::$db->prepare("SELECT * FROM ". self::$table ." WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public static function find(int $id) 
    {
        $stmt = self::$db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function findAll(): array 
    {
        $stmt = self::$db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByEmail($email)
    {
        $stmt = self::$db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create(array $data)
    {
        $stmt = self::$db->prepare("INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)");
        $stmt->execute([
            ':fullname' => $data['fullname'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
        return self::find(self::$db->lastInsertId());
    }

    public static function update($id, array $data)
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['fullname'])) {
            $fields[] = 'fullname = :fullname';
            $params[':fullname'] = $data['fullname'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params[':email'] = $data['email'];
        }
        if (isset($data['password'])) {
            $fields[] = 'password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
    }

    public static function delete($id)
    {
        $stmt = self::$db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }

}
