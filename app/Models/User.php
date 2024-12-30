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

    public static function find(int $id) : object
    {
        self::initialize();
        $sql = "SELECT * FROM ".self::$table." WHERE id = :id LIMIT 1"; 
        $user = self::$db->get_row($sql, ['id' => $id]);
        return $user;
    }

    public static function findAll(): array 
    {
        self::initialize();
        $users = self::$db->query("SELECT * FROM ". self::$table);
        return $users;
    }

    public static function findByEmail(string $email) : object
    {
        self::initialize();
        $sql = "SELECT * FROM ".self::$table." WHERE email = :email LIMIT 1"; 
        $user = self::$db->get_row($sql, ['email' => $email]);
        return $user;
    }

    public static function create(array $data)
    {
        self::initialize();
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
        return self::find(self::$db->lastInsertId());
    }

    public static function update($id, array $data) : int
    {
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

        self::initialize();
        return self::$db->update(self::$table, $fields, $whereClause);
    }

    public static function delete(int $id) : int
    {
        self::initialize();
        return self::$db->delete(self::$table, ['id' => $id]);
    }

    public function createResource(array $data)
    {
        self::initialize();
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
