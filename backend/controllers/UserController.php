<?php
require_once __DIR__ . '/../models/UserData.php';
require_once __DIR__ . '/../utils/Response.php';

class UserController {
    private $userData;

    public function __construct($pdo) {
        $this->userData = new UserData($pdo);
    }

    public function index() {
        $users = $this->userData->getAll();
        return Response::success($users);
    }

    public function show($id) {
        $user = $this->userData->getById($id);
        if (!$user) {
            return Response::error('User not found', 404);
        }
        return Response::success($user);
    }

    public function store($data) {
        // Validate input
        $requiredFields = ['name', 'email', 'phone', 'role'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return Response::error("$field is required", 400);
            }
        }

        // Check if email exists
        $existingUser = $this->userData->getByEmail($data['email']);
        if ($existingUser) {
            return Response::error('Email already exists', 400);
        }
        $id = $this->userData->create($data);
        $user = $this->userData->getById($id);
        return Response::success($user, 201);
    }

    public function update($id, $data) {
        $user = $this->userData->getById($id);
        if (!$user) {
            return Response::error('User not found', 404);
        }

        // Check if new email exists for another user
        if (isset($data['email']) && $data['email'] !== $user['email']) {
            $existingUser = $this->userData->getByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $id) {
                return Response::error('Email already exists for another user', 400);
            }
        }

        $this->userData->update($id, $data);
        $updatedUser = $this->userData->getById($id);
        return Response::success($updatedUser);
    }

    public function destroy($id) {
        $user = $this->userData->getById($id);
        if (!$user) {
            return Response::error('User not found', 404);
        }

        $this->userData->delete($id);
        return Response::success(['message' => 'User deleted successfully']);
    }
}