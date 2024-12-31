<?php
// models/User.php
namespace App\Models;

class User extends BaseModel {
    protected static string $table = "users";

    public static function authenticate(string $email, string $password) : ?object
    {
        $user = self::findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return null;
    }

    public static function findById(int $id) : object
    {
        self::initializeDb();
        $sql = "SELECT * FROM ".self::$table." WHERE id = :id LIMIT 1"; 
        $user = self::$db->get_row($sql, ['id' => $id]);
        return $user ?: null;
    }

    public static function find(array $params) : object
    {
        self::initializeDb();
        if (!empty($params) && is_array($params)) {
            $conditions = [];
            foreach ($params as $field => $value) {
                $conditions[] = "{$field} = '{$value}'";
            }
            $whereClause = " WHERE " . implode(' AND ', $conditions);
        }
        $sql = "SELECT * FROM ".self::$table . $whereClause." LIMIT 1"; 
        $user = self::$db->get_row($sql);
        return $user ?: null;
    }

    public static function findAll(): array 
    {
        self::initializeDb();
        $users = self::$db->query("SELECT * FROM ". self::$table);
        return $users;
    }

    public static function findByEmail(string $email) : ?object
    {
        self::initializeDb();
        $sql = "SELECT * FROM ".self::$table." WHERE email = :email LIMIT 1"; 
        $user = self::$db->get_row($sql, ['email' => $email]);
        return $user ?: null;
    }

    public static function create(array $data)
    {
        self::initializeDb();
        $user = self::findByEmail($data['email']);
        if ( $user ) {
            throw new \Exception("User with email {$data['email']} already exists.");
        }

        $bindValue = [
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'password' => hashPassword($data['password'])
        ];
        self::$db->insert(self::$table, $bindValue);
        return self::findById(self::$db->lastInsertId());
    }

    public static function update($id, array $data) : int
    {
        self::initializeDb();

        $fields = [];
        $whereClause = ['id' => $id];

        if (isset($data['fullname'])) {
            $fields[] = 'fullname =  ' . $data['fullname'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = '. $data['email'];
        }
        if (isset($data['password'])) {
            $fields[] = 'password = ' . hashPassword($data['password']);
        }

        return self::$db->update(self::$table, $fields, $whereClause);
    }

    public static function delete(int $id) : int
    {
        self::initializeDb();
        return self::$db->delete(self::$table, ['id' => $id]);
    }

    public function createResource(array $data) : int
    {
        self::initializeDb();
        $result = self::$db->insert(self::$table, $data);

        if ($result) {
            $socket = fsockopen('localhost', 8080, $errono, $errstr, 5);

            if ($socket) {
                $message = json_encode(['action' => 'create', 'data' => $data]);
                fwrite($socket, $message);
                fclose($socket);
            }
        }
        return $result;
    }

}
