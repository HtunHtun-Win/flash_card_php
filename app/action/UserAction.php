<?php
session_start();

define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/app/controller/UserController.php';

$userController = new UserController();

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'create':
        $userController->create($_POST);
        break;

    case 'update':
        $id = (int)($_GET['id'] ?? 0);
        $userController->update($_POST, $id);
        break;

    case 'delete':
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            header("Location: /app/action/UserAction.php?action=list&msg=No%20id");
            exit;
        }
        $userController->delete($id);
        break;

    case 'list':
    default:
        $_SESSION['activeNav'] = 'user_list';
        $userController->list();
        break;
}
