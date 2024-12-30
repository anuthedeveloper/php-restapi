<?php
// controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use Config\JWT;
use App\Http\Request;
use App\Helpers\Response;

class AuthController extends Controller {

    protected function validate( array $data ): void
    {
        $requiredFields = ['email', 'password'];
        validateInput($data, $requiredFields);
    }

    public function login(Request $request) 
    {
        echo "hit";
        // Get all input data
        $data = $request->all();
        var_dump($data);
        $this->validate($data);
        $user = User::authenticate($data['email'], $data['password']);

        if ($user) {
            $token = JWT::generateToken(['user_id' => $user['id']]);
            Response::json(['token' => $token]);
        } else {
            Response::json(['error' => 'Invalid credentials'], 401);
        }
    }
}
