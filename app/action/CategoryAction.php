<?php
session_start();

define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/app/controller/CategoryController.php';

$categoryController = new CategoryController();

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'create':
        $categoryController->create($_POST);
        break;

    case 'update':
        $id = (int)($_GET['id'] ?? 0);
        $categoryController->update($_POST, $id);
        break;

    case 'delete':
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            header("Location: /app/action/CategoryAction.php?action=list&msg=No%20id");
            exit;
        }
        $categoryController->delete($id);
        break;

    case 'list':
    default:
        $_SESSION['activeNav'] = 'category_list';
        $categoryController->list();
        break;
}
