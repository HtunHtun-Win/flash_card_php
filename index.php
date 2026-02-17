<?php
session_start();

define('ROOT_PATH', __DIR__);

require ROOT_PATH . '/app/controller/AuthController.php';

$authController = new AuthController();

$authController->checkAuth();
