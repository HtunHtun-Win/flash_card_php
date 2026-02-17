<?php

require_once ROOT_PATH . '/app/models/Category.php';

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    public function list()
    {
        $categories = $this->categoryModel->getAll();
        include "../views/home/category_list.php";
        return;
    }

    public function getById($id)
    {
        return $this->categoryModel->getById($id);
    }

    public function create($data)
    {
        if (empty($data['name'])) {
            header("Location: /app/action/CategoryAction.php?action=list&msg=Missing%20name");
            exit;
        }

        $result = $this->categoryModel->create($data);
        if ($result) {
            header("Location: /app/action/CategoryAction.php?action=list&msg=Category%20created");
        } else {
            header("Location: /app/action/CategoryAction.php?action=list&msg=Create%20failed");
        }
        exit;
    }

    public function update($data, $id)
    {
        if (empty($data['name'])) {
            header("Location: /app/action/CategoryAction.php?action=list&msg=Missing%20name");
            exit;
        }

        $result = $this->categoryModel->update($id, $data);
        if ($result) {
            header("Location: /app/action/CategoryAction.php?action=list&msg=Category%20updated");
        } else {
            header("Location: /app/action/CategoryAction.php?action=list&msg=Update%20failed");
        }
        exit;
    }

    public function delete($id)
    {
        $result = $this->categoryModel->delete($id);
        if ($result) {
            header("Location: /app/action/CategoryAction.php?action=list&msg=Category%20deleted");
        } else {
            header("Location: /app/action/CategoryAction.php?action=list&msg=Delete%20failed");
        }
        exit;
    }
}
