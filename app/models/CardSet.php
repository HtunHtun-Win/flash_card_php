<?php
define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/app/config/Database.php';
class CardSet
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM card_sets WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllTitles()
    {
        $stmt = $this->db->prepare(
            "select card_sets.*,(select count(set_id) as total from cards where set_id=card_sets.id GROUP by set_id) as total,users.name as uname,categories.NAME as cname 
            from card_sets,users,categories 
            where card_sets.user_id=users.id and card_sets.category_id=categories.id and card_sets.visibility='public' 
            order by card_sets.id desc"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllTitlesByUser($userId)
    {
        $stmt = $this->db->prepare(
            "select card_sets.*,(select count(set_id) as total from cards where set_id=card_sets.id GROUP by set_id) as total,users.name as uname,categories.NAME as cname 
            from card_sets,users,categories 
            where card_sets.user_id=users.id and card_sets.category_id=categories.id and card_sets.user_id=:uid 
            order by card_sets.id desc"
        );
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function getTitleBySearch($keyword, $cid, $uid)
    {
        $sql = "SELECT f.*,(select count(set_id) as total from cards where set_id=f.id GROUP by set_id) as total, c.name cname, u.name uname
                FROM card_sets f
                JOIN categories c ON f.category_id=c.id
                JOIN users u ON f.user_id=u.id
                WHERE 1=1";

        $params = [];

        if ($uid == 0) $sql .= " AND visibility='public'";

        if ($keyword) {
            $sql .= " AND f.name LIKE :keyword";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        if ($cid != 0) {
            $sql .= " AND category_id=:cid";
            $params[':cid'] = $cid;
        }

        if ($uid != 0) {
            $sql .= " AND u.id=:uid";
            $params[':uid'] = $uid;
        }

        $sql .= " ORDER BY f.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function saveCardSet($data, $userId)
    {
        $db = $this->db;

        try {
            $db->beginTransaction();

            //Insert card_set
            $stmt = $db->prepare("INSERT INTO card_sets(user_id,category_id, name, `desc`, visibility, created_at) VALUES (:user_id,:cat, :name, :desc, :visibility, NOW())");
            $stmt->execute([
                'user_id' => $userId,
                'cat' => $data['cat'],
                'desc' => $data['desc'],
                'visibility' => $data['visibility'],
                'name' => $data['set_name']
            ]);
            $setId = $db->lastInsertId();

            //Loop through all cards
            foreach ($data['cards'] as $cardIndex => $card) {
                $stmtCard = $db->prepare("INSERT INTO cards(set_id, question, created_at) VALUES (:set_id, :question, NOW())");
                $stmtCard->execute([
                    'set_id' => $setId,
                    'question' => $card['question']
                ]);
                $cardId = $db->lastInsertId();

                //Insert options
                $optionIds = [];
                foreach ($card['options'] as $optionText) {
                    $stmtOption = $db->prepare("INSERT INTO card_options(card_id, option_text) VALUES (:card_id, :option_text)");
                    $stmtOption->execute([
                        'card_id' => $cardId,
                        'option_text' => $optionText
                    ]);
                    $optionIds[] = $db->lastInsertId();
                }

                //Update correct answer in cards table
                $correctOptionIndex = (int)$card['answer']; // index 0,1,2...
                if (isset($optionIds[$correctOptionIndex])) {
                    $stmtUpdate = $db->prepare("UPDATE cards SET answer = :answer WHERE id = :card_id");
                    $stmtUpdate->execute([
                        'answer' => $optionIds[$correctOptionIndex],
                        'card_id' => $cardId
                    ]);
                }
            }

            $db->commit();

            // Redirect to list page
            header("Location: /app/action/CardAction.php");
            exit();
        } catch (Exception $e) {
            $db->rollBack();
            die("Error saving card set: " . $e->getMessage());
        }
    }

    public function update($id, $userId, $name, $desc, $categoryId)
    {
        if ($userId == 1) {
            $stmt = $this->db->prepare("UPDATE card_sets SET name=:name, `desc`=:desc, category_id=:cat WHERE id=:id");
            return $stmt->execute([
                'name' => $name,
                'desc' => $desc,
                'cat' => $categoryId,
                'id' => $id
            ]);
        } else {
            $stmt = $this->db->prepare("UPDATE card_sets SET name=:name, `desc`=:desc, category_id=:cat WHERE id=:id AND user_id=:uid");
            return $stmt->execute([
                'name' => $name,
                'desc' => $desc,
                'cat' => $categoryId,
                'id' => $id,
                'uid' => $userId
            ]);
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM card_sets WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countAll()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM card_sets");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cnt'] ?? 0;
    }
}
