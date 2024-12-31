<?php
// controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use Config\JWT;
use Config\Session;
use App\Http\Request;
use App\Helpers\Response;

class AuthController extends Controller {

    protected function validate( array $data ): void
    {
        $errors = validateInput($data, ['email', 'password']);
        if (!empty($errors)) {
            throw new \App\Exceptions\ValidationException($errors);
        }
    }

    public function login(Request $request) 
    {
        $data = $request->all();

        $this->validate($data);

        $user = User::authenticate($data['email'], $data['password']);
        if (!$user) {
            Response::json(['error' => 'Invalid credentials'], 401);
        }

        $token = JWT::generateToken(['user_id' => $user->id]);
        Session::put('user', ['id' => $user->id, 'email' => $user->email]);
        Session::regenerate(); // Prevent session fixation

        Response::json(['token' => $token]);
    }

    public function logout(): void
    {
        Session::destroy();
        Response::json(['message' => 'Logged out successfully'], 200);
    }
    
}
