<?php
require_once ROOT_PATH . '/app/config/Database.php';
class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($data)
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO users(name,email,password) VALUES (:name, :email, :password)"
            );
            return $stmt->execute(['name' => $data['name'], 'email' => $data['email'], 'password' => md5($data['password'])]);
        } catch (Exception $e) {
            die("Database Error: " . $e->getMessage());
        }
    }

    public function login($email, $password)
    {
        try {
            $hashedPassword = md5($password);
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
            $stmt->execute(['email' => $email, 'password' => $hashedPassword]);
            return $stmt->fetchObject();
        } catch (Exception $e) {
            die("Database Error: " . $e->getMessage());
        }
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function countAll()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM users");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cnt'] ?? 0;
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT id,name,email,role,created_at FROM users ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT id,name,email,role,created_at FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject();
    }

    public function update($id, $data)
    {
        $params = ['name' => $data['name'], 'email' => $data['email'], 'role' => $data['role'], 'id' => $id];

        if (!empty($data['password'])) {
            $params['password'] = md5($data['password']);
            $sql = "UPDATE users SET name=:name,email=:email,role=:role,password=:password WHERE id=:id";
        } else {
            $sql = "UPDATE users SET name=:name,email=:email,role=:role WHERE id=:id";
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id=:id");
        return $stmt->execute(['id' => $id]);
    }
}
