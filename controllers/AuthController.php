<?php
// controllers/AuthController.php
namespace Controllers;

use Models\User;
use Config\JWT;

class AuthController extends BaseController {
    public function login($data) {
        $user = User::authenticate($data['email'], $data['password']);

        if ($user) {
            $token = JWT::generateToken(['user_id' => $user['id']]);
            $this->jsonResponse(['token' => $token]);
        } else {
            $this->jsonResponse(['error' => 'Invalid credentials'], 401);
        }
    }
}
