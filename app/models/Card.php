<?php
define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/app/config/Database.php';
class Card
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAllBySetId($setId)
    {
        $stmt = $this->db->prepare("SELECT * FROM cards WHERE set_id = ?");
        $stmt->execute([$setId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function create($cardSetId, $question, $answer)
    {
        $stmt = $this->db->prepare("INSERT INTO cards(set_id, question, answer) VALUES(:cid, :q, :a)");
        $stmt->execute([
            'cid' => $cardSetId,
            'q' => $question,
            'a' => $answer
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $cardSetId, $question, $answer)
    {
        $stmt = $this->db->prepare("UPDATE cards SET question=:q, answer=:a WHERE id=:id AND set_id=:cid");
        return $stmt->execute([
            'q' => $question,
            'a' => $answer,
            'id' => $id,
            'cid' => $cardSetId
        ]);
    }

    public function getIdsBySet($cardSetId)
    {
        $stmt = $this->db->prepare("SELECT id FROM cards WHERE set_id=:cid");
        $stmt->execute(['cid' => $cardSetId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function deleteMultiple($ids)
    {
        if (empty($ids)) {
            return false;
        }

        foreach($ids as $id) {
            $stmt = $this->db->prepare(
                "DELETE FROM cards WHERE id = :cid"
            );

            $stmt->execute(['cid' => $id]);
        }
    }

    public function updateAnswer($cardId, $optionId)
    {
        $stmt = $this->db->prepare("UPDATE cards SET answer=:a WHERE id=:id");
        return $stmt->execute([
            'a' => $optionId,
            'id' => $cardId
        ]);
    }
}
