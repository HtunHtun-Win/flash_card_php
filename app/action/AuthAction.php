<?php
session_start();

define('ROOT_PATH', dirname(__DIR__, 2));

require_once ROOT_PATH . '/app/controller/AuthController.php';

$authController = new AuthController();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        $authController->login($_POST);
        break;

    case 'register':
        $authController->register($_POST);
        break;

    case 'logout':
        $authController->logout();
        break;

    default:
        header("Location: /index.php");
        exit();
}
