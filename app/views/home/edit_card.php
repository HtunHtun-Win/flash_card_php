<?php
session_start();
define('ROOT_PATH', dirname(__DIR__, 3));
require_once ROOT_PATH . '/app/controller/CardController.php';
require_once ROOT_PATH . '/app/views/layout/header.php';

// Get card set ID from query
$cardSetId = $_GET['id'] ?? 0;
$userId = $_SESSION['user']->id ?? 0;

$controller = new CardController();
$data = $controller->getCardSetWithCards($cardSetId, $userId);

if (!$data) {
    die("Card set not found or you are not authorized.");
}

$cardSet = $data['cardSet'];
$cards = $data['cards'];
$categories = $_SESSION['categories'] ?? [];
$selectedCat = $cardSet->category_id ?? 0;
?>

<div class="container mt-5">
    <form method="POST" action="/app/action/CardAction.php?action=update&id=<?= $cardSet->id ?>">
        <div class="row g-2 align-items-end mb-3">
            <!-- Card Set Name -->
            <div class="col-md">
                <label for="setName" class="form-label">Card Set Name</label>
                <input type="text" class="form-control" name="set_name" id="setName"
                    value="<?= htmlspecialchars($cardSet->name) ?>" required>
            </div>

            <div class="col-md">
                <label for="desc" class="form-label">Description</label>
                <input type="text" class="form-control" name="desc" id="desc" value="<?= htmlspecialchars($cardSet->desc) ?>" required>
            </div>

            <!-- Category -->
            <div class="col-md">
                <label for="cat" class="form-label">Category</label>
                <select name="cat" id="cat" class="form-select">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= ($selectedCat == $cat->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Add Card Button -->
            <div class="col-md-auto">
                <button type="button" id="addCardBtn" class="btn btn-secondary">Add Card</button>
            </div>

            <!-- Save Button -->
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>

        <!-- Cards Container -->
        <div id="cardsContainer">
            <?php foreach ($cards as $i => $card): ?>
                <div class="card mb-3 card-block position-relative">
                    <input type="hidden" name="cards[<?= $i ?>][id]" value="<?= $card->id ?>">
                    <!-- Remove Button -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-card-btn" aria-label="Remove"></button>

                    <div class="card-body">
                        <h5 class="card-title">Card <?= $i + 1 ?></h5>

                        <div class="mb-3">
                            <label>Question</label>
                            <input type="text" name="cards[<?= $i ?>][question]" class="form-control" value="<?= htmlspecialchars($card->question) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Options</label>
                            <div class="row g-2">
                                <?php foreach ($card->options as $j => $opt): ?>
                                    <div class="col">
                                        <input type="text" name="cards[<?= $i ?>][options][]" class="form-control" value="<?= htmlspecialchars($opt->option_text) ?>" required>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Correct Option</label>
                            <select name="cards[<?= $i ?>][answer]" class="form-select" required>
                                <?php foreach ($card->options as $j => $opt): ?>
                                    <option value="<?= $j ?>" <?= ($card->answer == $opt->id) ? 'selected' : '' ?>>Option <?= $j + 1 ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </form>
</div>

<script>
    let cardIndex = <?= count($cards) ?>;

    const container = document.getElementById('cardsContainer');

    // EVENT DELEGATION for remove buttons
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-card-btn')) {
            e.target.closest('.card-block').remove();
        }
    });

    document.getElementById('addCardBtn').addEventListener('click', function() {

        const cardHtml = `
        <div class="card mb-3 card-block position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-card-btn"></button>

            <div class="card-body">
                <h5 class="card-title">Card ${cardIndex + 1}</h5>

                <div class="mb-3">
                    <label>Question</label>
                    <input type="text" name="cards[${cardIndex}][question]" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Options</label>
                    <div class="row g-2">
                        <div class="col"><input type="text" name="cards[${cardIndex}][options][]" class="form-control" required></div>
                        <div class="col"><input type="text" name="cards[${cardIndex}][options][]" class="form-control" required></div>
                        <div class="col"><input type="text" name="cards[${cardIndex}][options][]" class="form-control"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Correct Option</label>
                    <select name="cards[${cardIndex}][answer]" class="form-select" required>
                        <option value="0">Option 1</option>
                        <option value="1">Option 2</option>
                        <option value="2">Option 3</option>
                    </select>
                </div>
            </div>
        </div>
        `;

        container.insertAdjacentHTML('beforeend', cardHtml);
        cardIndex++;
    });
</script>

<?php
require_once ROOT_PATH . '/app/views/layout/footer.php';
?>