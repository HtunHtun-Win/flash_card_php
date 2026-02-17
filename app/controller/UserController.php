<?php

require_once ROOT_PATH . '/app/models/User.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function list()
    {
        $users = $this->userModel->getAll();
        include "../views/home/user_list.php";
        return;
    }

    public function getById($id)
    {
        return $this->userModel->getById($id);
    }

    public function create($data)
    {
        // basic validation
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            header("Location: /app/action/UserAction.php?action=list&msg=Missing%20fields");
            exit;
        }

        $result = $this->userModel->create($data);
        if ($result) {
            header("Location: /app/action/UserAction.php?action=list&msg=User%20created");
        } else {
            header("Location: /app/action/UserAction.php?action=list&msg=Create%20failed");
        }
        exit;
    }

    public function update($data, $id)
    {
        if (empty($data['name']) || empty($data['email'])) {
            header("Location: /app/action/UserAction.php?action=list&msg=Missing%20fields");
            exit;
        }

        $result = $this->userModel->update($id, $data);
        if ($result) {
            header("Location: /app/action/UserAction.php?action=list&msg=User%20updated");
        } else {
            header("Location: /app/action/UserAction.php?action=list&msg=Update%20failed");
        }
        exit;
    }

    public function delete($id)
    {
        // prevent admins from accidentally deleting themself
        $current = $_SESSION['user'] ?? null;
        if ($current && $current->id == $id) {
            header("Location: /app/action/UserAction.php?action=list&msg=Cannot%20delete%20yourself");
            exit;
        }

        $result = $this->userModel->delete($id);
        if ($result) {
            header("Location: /app/action/UserAction.php?action=list&msg=User%20deleted");
        } else {
            header("Location: /app/action/UserAction.php?action=list&msg=Delete%20failed");
        }
        exit;
    }
}
