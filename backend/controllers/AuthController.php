<?php
require_once __DIR__ . '/../models/AdminUser.php';
require_once __DIR__ . '/../utils/Response.php';


class AuthController {
    private $adminUser;

    public function __construct($pdo) {
        $this->adminUser = new AdminUser($pdo);
    }

    public function signup($data) {
        // Validate input
        if (empty($data['name']) || empty($data['email']) || empty($data['phone']) || empty($data['password'])) {
            return Response::error('All fields are required', 400);
        }

        // Check if email exists
        if ($this->adminUser->findByEmail($data['email'])) {
            return Response::error('Email already exists', 400);
        }

        // Create user
        if ($this->adminUser->create($data['name'], $data['email'], $data['phone'], $data['password'])) {
            $user = $this->adminUser->findByEmail($data['email']);


            return Response::success([
                'message' => 'User created successfully'
            ], 201);
        }

        return Response::error('Failed to create user', 500);
    }

    public function login($data) {
        
        if (empty($data['email']) || empty($data['password'])) {
            return Response::error('Email and password are required', 400);
        }

        // Find user
        $user = $this->adminUser->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return Response::error('Invalid email or password', 401);
        }

        return Response::success([
            'message' => 'Login successful'
        ]);
    }
}