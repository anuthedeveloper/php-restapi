<?php
// controllers/UserController.php
namespace Controllers;

use Models\User;
use Helpers\Response;
use Config\JWT;

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

    public function index()
    {
        $users = User::findAll();
        return Response::json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return Response::json(['error' => 'User not found'], 404);
        }
        return Response::json($user);
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
            return Response::json(['error' => 'Missing required fields'], 400);
        }

        $user = User::create($data);
        return Response::json(['message' => 'User created', 'user' => $user], 201);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!User::find($id)) {
            return Response::json(['error' => 'User not found'], 404);
        }

        User::update($id, $data);
        return Response::json(['message' => 'User updated']);
    }

    public function destroy($id)
    {
        if (!User::find($id)) {
            return Response::json(['error' => 'User not found'], 404);
        }

        User::delete($id);
        return Response::json(['message' => 'User deleted']);
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $user = User::findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return Response::json(['error' => 'Invalid credentials'], 401);
        }

        $jwt = JWT::generateToken($user['id']);
        return Response::json(['token' => $jwt]);
    }
}
