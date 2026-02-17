<?php
require_once ROOT_PATH . '/app/config/Database.php';

class CardOption
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getByCardId($cardId)
    {
        $stmt = $this->db->prepare("SELECT * FROM card_options WHERE card_id = ?");
        $stmt->execute([$cardId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function replaceOptions($cardId, $options)
    {
        // Delete old
        $stmt = $this->db->prepare("DELETE FROM card_options WHERE card_id=:cid");
        $stmt->execute(['cid' => $cardId]);

        // Insert new
        $stmt = $this->db->prepare("INSERT INTO card_options(card_id, option_text) VALUES(:cid, :opt)");
        foreach ($options as $opt) {
            if (trim($opt) !== '') {
                $stmt->execute(['cid' => $cardId, 'opt' => $opt]);
            }
        }
    }

    public function addOption($cardId, $text)
    {
        $stmt = $this->db->prepare("INSERT INTO card_options(card_id, option_text) VALUES(:cid, :opt)");
        $stmt->execute(['cid' => $cardId, 'opt' => $text]);
        return $this->db->lastInsertId(); // return ID for foreign key
    }

    public function deleteByCards(array $cardIds)
    {
        if (empty($cardIds)) {
            return false;
        }

        foreach($cardIds as $cardId) {
            $stmt = $this->db->prepare(
                "DELETE FROM card_options WHERE card_id = :cid"
            );

            $stmt->execute(['cid' => $cardId]);
        }
    }

    // Replace old options and return their IDs
    public function replaceOptionsAndReturnIds($cardId, $options)
    {
        // Delete old options
        $stmt = $this->db->prepare("DELETE FROM card_options WHERE card_id=:cid");
        $stmt->execute(['cid' => $cardId]);

        // Insert new options
        $stmt = $this->db->prepare("INSERT INTO card_options(card_id, option_text) VALUES(:cid, :opt)");
        $optionIds = [];
        foreach ($options as $opt) {
            if (trim($opt) !== '') {
                $stmt->execute(['cid' => $cardId, 'opt' => $opt]);
                $optionIds[] = $this->db->lastInsertId();
            }
        }
        return $optionIds;
    }

    // Add options for a new card and return their IDs
    public function addOptionsAndReturnIds($cardId, $options)
    {
        $stmt = $this->db->prepare("INSERT INTO card_options(card_id, option_text) VALUES(:cid, :opt)");
        $optionIds = [];
        foreach ($options as $opt) {
            if (trim($opt) !== '') {
                $stmt->execute(['cid' => $cardId, 'opt' => $opt]);
                $optionIds[] = $this->db->lastInsertId();
            }
        }
        return $optionIds;
    }
}
