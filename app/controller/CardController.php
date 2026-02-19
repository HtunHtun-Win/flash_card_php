<?php

require_once ROOT_PATH . '/app/models/CardSet.php';
require_once ROOT_PATH . '/app/models/Card.php';
require_once ROOT_PATH . '/app/models/CardOption.php';

class CardController
{

    private $cardSetModel;
    private $cardModel;
    private $cardOptionModel;

    public function __construct()
    {
        $this->cardSetModel = new CardSet();
        $this->cardModel = new Card();
        $this->cardOptionModel = new CardOption();
    }

    public function getAll()
    {
        require_once "../models/Category.php";
        $category = new Category();
        $cards = $this->cardSetModel->getAllTitles();
        $categories = $category->getAll();
        $_SESSION['categories'] = $categories;
        include "../views/home/home.php";
        return;
    }

    public function getAllById($id)
    {
        require_once "../models/Category.php";
        $category = new Category();
        $cards = $this->cardSetModel->getAllTitlesByUser($id);
        $categories = $category->getAll();
        // include "../views/home/mycard.php";
        include "../views/home/home.php";
        return;
    }

    public function getAllBySearch($keyword, $cid, $uid)
    {
        require_once "../models/Category.php";
        $category = new Category();
        $cards = $this->cardSetModel->getTitleBySearch($keyword, $cid, $uid);
        $categories = $category->getAll();
        // include "../views/home/mycard.php";
        include "../views/home/home.php";
        return;
    }

    public function saveCardSet($data, $userId)
    {
        $this->cardSetModel->saveCardSet($data, $userId);
    }

    public function delete($cardId, $userId)
    {
        // Verify ownership
        $card = $this->cardSetModel->findById($cardId);
        if ($card && $card->user_id == $userId) {
            return $this->cardSetModel->delete($cardId);
        }
        return false;
    }

    public function getCardSetWithCards($cardSetId, $userId)
    {
        // Get card set info
        $cardSet = $this->cardSetModel->findById($cardSetId);
        // if (!$cardSet || ($cardSet->user_id != $userId && $_SESSION['user']->role != 'admin')) {
        //     return null;
        // }

        // Get all cards in the set
        $cards = $this->cardModel->getAllBySetId($cardSetId);

        // For each card, get options
        foreach ($cards as $card) {
            $card->options = $this->cardOptionModel->getByCardId($card->id);
        }

        return [
            'cardSet' => $cardSet,
            'cards' => $cards
        ];
    }

    public function update($data, $cardSetId)
    {
        $userId = $_SESSION['user']->id;

        // 1 Update Card Set info
        $this->cardSetModel->update($cardSetId, $userId, $data['set_name'], $data['desc'], $data['cat']);

        $cards = $data['cards'] ?? [];
        $newAddedId = [];

        foreach ($cards as $cardData) {
            if (isset($cardData['id']) && $cardData['id'] > 0) {
                // Existing card
                $cardId = $cardData['id'];

                // Replace options first
                $optionIds = $this->cardOptionModel->replaceOptionsAndReturnIds($cardId, $cardData['options']);

                // Set the answer as the corresponding option ID
                $selectedIndex = (int) $cardData['answer'];
                $answerOptionId = $optionIds[$selectedIndex] ?? null;

                // Update card question + answer
                $this->cardModel->update($cardId, $cardSetId, $cardData['question'], $answerOptionId);
            } else {
                // New card
                $newCardId = $this->cardModel->create($cardSetId, $cardData['question'], null);
                $newAddedId[] = $newCardId;

                // Insert options and get IDs
                $optionIds = $this->cardOptionModel->addOptionsAndReturnIds($newCardId, $cardData['options']);

                // Set answer FK
                $selectedIndex = (int) $cardData['answer'];
                $answerOptionId = $optionIds[$selectedIndex] ?? null;

                $this->cardModel->updateAnswer($newCardId, $answerOptionId);
            }
        }

        // Delete removed cards
        $existingIds = $this->cardModel->getIdsBySet($cardSetId);
        $postedIds = array_filter(array_column($cards, 'id'));
        $fliter = array_diff($existingIds, $postedIds);
        $toDelete = array_diff($fliter, $newAddedId);

        if (!empty($toDelete)) {
            $this->cardOptionModel->deleteByCards($toDelete);
            $this->cardModel->deleteMultiple($toDelete);
        }

        header("Location: /app/action/CardAction.php");
        exit;
    }

    public function dashboard()
    {
        require_once ROOT_PATH . '/app/models/User.php';
        require_once ROOT_PATH . '/app/models/Category.php';
        require_once ROOT_PATH . '/app/models/CardSet.php';

        $userModel = new User();
        $categoryModel = new Category();
        $cardSetModel = new CardSet();

        $userCount = $userModel->countAll();
        $categoryCount = $categoryModel->countAll();
        $cardSetCount = $cardSetModel->countAll();

        // ensure variables used by the view exist
        $categories = $categoryModel->getAll();
        $cards = [];

        include "../views/home/dashboard.php";
        return;
    }
}
