<?php
require_once ROOT_PATH . '/app/config/Database.php';
class Category
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT id,name,created_at FROM categories ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            echo "DB ERROR";
            return [];
        }
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT id,name,created_at FROM categories WHERE id=:id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO categories(name,created_at) VALUES (:name, NOW())");
        return $stmt->execute(['name' => $data['name']]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE categories SET name = :name WHERE id = :id");
        return $stmt->execute(['name' => $data['name'], 'id' => $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function countAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM categories");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['cnt'] ?? 0;
        } catch (Exception $e) {
            echo "DB ERROR";
            return 0;
        }
    }
}
