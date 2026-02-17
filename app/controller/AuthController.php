<?php

require_once ROOT_PATH . '/app/models/User.php';

class AuthController
{
    public function checkAuth()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /app/views/auth/login.php");
            exit();
        } else {
            header("Location: /app/action/CardAction.php");
            exit();
        }
    }

    public function login($data)
    {
        $user = new User();
        $result = $user->login($data['email'], $data['password']);

        if ($result) {
            $_SESSION['user'] = $result;
            header("Location: /app/action/CardAction.php");
        } else {
            header("Location: /app/views/auth/login.php?msg=Invalid%20credentials");
        }
    }

    public function register($data)
    {
        $user = new User();

        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];

        if (!(strlen($name) > 4 && strlen($email) > 10 && strlen($password) > 4)) {
            header("Location: /app/views/auth/register.php?msg=All fields must be at least 4 characters!");
            return;
        }

        $existing = $user->findByEmail($email);

        if (!$existing) {
            $result = $user->create($data);

            if ($result) {
                header("Location: /app/views/auth/login.php");
            } else {
                header("Location: /app/views/auth/register.php?msg=Registration%20failed");
            }
        } else {
            header("Location: /app/views/auth/register.php?msg=Email%20already%20exists");
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: /index.php");
    }
}
