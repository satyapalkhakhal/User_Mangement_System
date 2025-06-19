<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
function handleRoutes($pdo, $request) {
    $authController = new AuthController($pdo);
    $userController = new UserController($pdo);

    // Normalize the path by removing .php and base directory
    $normalizedPath = str_replace('/UserMangement/backend/public', '', $request['path']);
    $normalizedPath = str_replace('.php', '', $normalizedPath);
    $normalizedPath = rtrim($normalizedPath, '/');

    switch ($request['method']) {
        case 'POST':
            if ($normalizedPath === '/signup') {
                echo $authController->signup($request['body']);
                exit;
            } elseif ($normalizedPath === '/login') {
                echo $authController->login($request['body']);
                exit;
            }
            break;
    }

    // Routes that require authentication
    switch ($request['method']) {
        case 'GET':
            if ($normalizedPath === '/users') {
                echo $userController->index();
                exit;
            } elseif (preg_match('/^\/users\/(\d+)$/', $normalizedPath, $matches)) {
                echo $userController->show($matches[1]);
                exit;
            }
            break;
            
        case 'POST':
            if ($normalizedPath === '/users') {
                echo $userController->store($request['body']);
                exit;
            }
            break;
            
        case 'PUT':
            if (preg_match('/^\/users\/(\d+)$/', $normalizedPath, $matches)) {
                echo $userController->update($matches[1], $request['body']);
                exit;
            }
            break;
            
        case 'DELETE':
            if (preg_match('/^\/users\/(\d+)$/', $normalizedPath, $matches)) {
                echo $userController->destroy($matches[1]);
                exit;
            }
            break;
    }

    // If no route matches
    Response::error('Endpoint not found', 404);
}