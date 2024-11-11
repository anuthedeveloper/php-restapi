<?php
// controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use Config\JWT;
use App\Http\Request;
use App\Helpers\Response;

class AuthController extends Controller {
    public function login(Request $request) {
        // Get all input data
        $data = $request->all();
        $user = User::authenticate($data['email'], $data['password']);

        if ($user) {
            $token = JWT::generateToken(['user_id' => $user['id']]);
            Response::json(['token' => $token]);
        } else {
            Response::json(['error' => 'Invalid credentials'], 401);
        }
    }
}
