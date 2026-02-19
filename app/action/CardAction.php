<?php
session_start();

define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/app/controller/CardController.php';

$cardController = new CardController();
$user = $_SESSION['user'] ?? null;
$userId = $user->id ?? 0;

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'mylist':
        $_SESSION['activeNav'] = 'mylist';

        $keyword = $_GET['q'] ?? "";
        $cid = $_GET['cat'] ?? 0;

        if ($keyword || $cid) {
            $cards = $cardController->getAllBySearch($keyword, $cid, $userId);
        } else {
            $cards = $cardController->getAllById($userId);
        }
        break;

    case 'saveSet':
        $cardController->saveCardSet($_POST, $user->id);
        break;

    case 'update':
        $cardController->update($_POST, $_GET['id'] ?? 0);
        break;

    case 'delete':
        if (!isset($_GET['id'])) {
            header("Location: /index.php?msg=No card ID provided");
            exit;
        }

        $cardId = (int)$_GET['id'];
        $userId = $_SESSION['user']->id ?? 0;

        // Call delete function in controller
        $deleted = $cardController->delete($cardId, $userId);

        if ($deleted) {
            header("Location: /index.php?msg=Card deleted successfully");
        } else {
            header("Location: /index.php?msg=Failed to delete card");
        }
        exit;
        break;

    case 'dashboard':
        $_SESSION['activeNav'] = 'dashboard';
        $cardController->dashboard();
        break;
    
    case 'about':
        $_SESSION['activeNav'] = 'about';
        include ROOT_PATH . '/app/views/home/about.php';
        break;

    default:
        $_SESSION['activeNav'] = 'home';

        $keyword = $_GET['q'] ?? "";
        $cid = $_GET['cat'] ?? 0;

        if ($keyword || $cid) {
            $cards = $cardController->getAllBySearch($keyword, $cid, 0);
        } else {
            $cards = $cardController->getAll();
        }
}
