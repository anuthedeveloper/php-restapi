<?php
// controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Response;
use App\Http\Request;

class UserController extends Controller {
    
    public function getUser(Request $request, int $id) 
    {
        $user = User::findById($id);
        $user ? Response::json(["success" => true, $user]) : Response::json(['error' => 'User not found'], 404);
        
    }

    public function createUser(Request $request) 
    {
        $data = $request->all();
        validateInput($data, ['fullname', 'email', 'password']);
        
        $fullname = sanitize($data['fullname']);
        $email = sanitize($data['email']);
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        User::create(['fullname' => $fullname, 'email' => $email, 'password' => $password]);
        Response::json(['message' => 'User created'], 201);
    }

    public function index()
    {
        $users = User::findAll();
        return Response::json(["success" => true, "users" => $users]);
    }

    public function show($id)
    {
        $user = User::findById($id);
        if (!$user) {
            return Response::json(['error' => 'User not found'], 404);
        }
        return Response::json(['user' => $user]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (!isset($data['fullname']) || !isset($data['email']) || !isset($data['password'])) {
            return Response::json(['error' => 'Missing required fields'], 400);
        }

        $user = User::create($data);
        return Response::json(['message' => 'User created', 'user' => $user], 201);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->all();

        if (!User::findById($id)) {
            return Response::json(['error' => 'User not found'], 404);
        }

        User::update($id, $data);
        return Response::json(['message' => 'User updated']);
    }

    public function destroy($id)
    {
        if (!User::findById($id)) {
            return Response::json(['error' => 'User not found'], 404);
        }

        User::delete($id);
        return Response::json(['message' => 'User deleted']);
    }

}
