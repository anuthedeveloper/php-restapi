<?php
// controllers/UserController.php
namespace Controllers;

use Models\User;

class UserController extends BaseController {
    
    public function getUser(int $id) 
    {
        $user = User::find($id);
        $user ? $this->jsonResponse($user) : $this->jsonResponse(['error' => 'User not found'], 404);
    }

    public function createUser(array $data) 
    {
        $this->validateInput($data, ['name', 'email', 'password']);
        
        $name = $this->sanitize($data['name']);
        $email = $this->sanitize($data['email']);
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        User::create(['name' => $name, 'email' => $email, 'password' => $password]);
        $this->jsonResponse(['message' => 'User created'], 201);
    }
}
