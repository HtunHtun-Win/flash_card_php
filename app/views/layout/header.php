<!DOCTYPE html>
<html>
<?php
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    $user = null;
}
$activeNav = $_SESSION['activeNav'] ?? 'home';
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FlashCard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Custom styles (small improvements for dashboard) -->
    <style>
        :root {
            --accent: #0d6efd;
        }

        .stat-card {
            border-radius: .6rem;
        }

        .card-grid .card {
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .card-grid .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(13, 110, 253, .12);
        }

        .card-desc {
            max-height: 3.6rem;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .toolbar .form-control {
            min-width: 200px;
        }

        .clear-btn {
            cursor: pointer;
        }

        .badge-owner {
            background: #6c757d;
        }

        .no-cards {
            color: #6c757d;
        }
    </style>

    <!-- Custom script placeholder -->
    <script src="/public/js.js" defer></script>
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">Flash Brain<span class="badge bg-white text-primary m-1"><?= isset($_SESSION['user']) ? $_SESSION['user']->name : 'Guest' ?></span></a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/app/action/CardAction.php?action=dashboard" class="nav-link <?= $activeNav == 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
                </li>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']->role == 'admin'): ?>
                    <li class="nav-item">
                        <a href="/app/action/UserAction.php?action=list" class="nav-link <?= $activeNav == 'user_list' ? 'active' : ''; ?>">Users</a>
                    </li>
                    <li class="nav-item">
                        <a href="/app/action/CategoryAction.php?action=list" class="nav-link <?= $activeNav == 'category_list' ? 'active' : ''; ?>">Category</a>
                    </li>
                <?php endif ?>
                <li class="nav-item">
                    <a href="/app/action/CardAction.php" class="nav-link <?= $activeNav == 'home' ? 'active' : ''; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a href="/app/action/CardAction.php?action=mylist" class="nav-link <?= $activeNav == 'mylist' ? 'active' : ''; ?>">MyCard</a>
                </li>
                <li class="nav-item <?= $activeNav == 'about' ? 'active' : ''; ?>">
                    <a href="#" class="nav-link">About</a>
                </li>
                <li class="nav-item">
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="/app/action/AuthAction.php?action=logout" class="nav-link">Logout</a>
                    <?php else: ?>
                        <a href="/views/auth/login" class="nav-link">Login</a>
                    <?php endif ?>
                </li>
            </ul>
        </div>
    </nav>