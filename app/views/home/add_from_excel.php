<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

define('ROOT_PATH', dirname(__DIR__, 3));
require_once ROOT_PATH . '/app/views/layout/header.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['excel_file'])) {
    $filePath = $_FILES['excel_file']['tmp_name'];

    // Load Excel file
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();
    unset($rows[0]);
}
$no = 1;
?>

<div class="container mt-5">
    <h2>Create New Card Set</h2>
    <!-- load data from excel file -->
    <form action="/app/views/home/add_from_excel.php" method="post" enctype="multipart/form-data">
        <div class="row g-2 align-items-end mb-3">
            <div class="col-md-auto">
                <label for="excel_file" class="form-label">Import Excel File</label>
                <input type="file" name="excel_file" id="excel_file" class="form-control" required>
            </div>

            <!-- Add Card Button -->
            <div class="col-md-auto">
                <button type="submit" class="btn btn-success w-100">Check</button>
            </div>
        </div>
    </form>
    <!-- save data to db -->
    <form id="cardSetForm" action="/app/action/CardAction.php?action=saveSet" method="post">

        <div class="row g-2 align-items-end mb-3">
            <!-- Card Set Name -->
            <div class="col-md">
                <label for="setName" class="form-label">Card Set Name</label>
                <input type="text" class="form-control" name="set_name" id="setName" required>
            </div>

            <div class="col-md">
                <label for="desc" class="form-label">Description</label>
                <input type="text" class="form-control" name="desc" id="desc" required>
            </div>

            <!-- Category Select -->
            <div class="col-md-auto">
                <label for="cat" class="form-label">Category</label>
                <select name="cat" id="cat" class="form-select">
                    <?php foreach ($_SESSION["categories"] as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= ($selectedCat ?? 0) == $cat->id ? "selected" : "" ?>>
                            <?= $cat->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-auto">
                <label for="visibility" class="form-label">Visibility</label>
                <select name="visibility" id="visibility" class="form-select">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>

            <!-- Save Button -->
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary w-100">Save Card Set</button>
            </div>
        </div>

        <!-- Cards container -->
        <?php foreach ($rows as $index => $row) {
            if (!empty($row[0]) && !empty($row[4])) {
        ?>
                <div id="cardsContainer">
                    <div class="card mb-3 card-block">
                        <div class="card-body">
                            <h5 class="card-title">Card <?= $no ?></h5>
                            <div class="mb-3">
                                <label>Question</label>
                                <input type="text" name="cards[<?= $index ?>][question]" class="form-control" value="<?= htmlspecialchars($row[0]) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Options</label>
                                <div class="row g-2"> <!-- g-2 adds small gap between columns -->
                                    <div class="col">
                                        <input type="text" name="cards[<?= $index ?>][options][]" class="form-control" placeholder="Option 1" value="<?= htmlspecialchars($row[1]) ?>" required>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="cards[<?= $index ?>][options][]" class="form-control" placeholder="Option 2" value="<?= htmlspecialchars($row[2]) ?>" required>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="cards[<?= $index ?>][options][]" class="form-control" placeholder="Option 3" value="<?= htmlspecialchars($row[3]) ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Correct Option</label>
                                <select name="cards[<?= $index ?>][answer]" class="form-select" required>
                                    <option value="0" <?= $row[4] == "a" ? "selected" : "" ?>>Option 1</option>
                                    <option value="1" <?= $row[4] == "b" ? "selected" : "" ?>>Option 2</option>
                                    <option value="2" <?= $row[4] == "c" ? "selected" : "" ?>>Option 3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

        <?php
                $no++;
            }
        }
        ?>
    </form>
</div>

<script>
    let cardIndex = 1; // already have 1 card

    document.getElementById('addCardBtn').addEventListener('click', function() {
        const container = document.getElementById('cardsContainer');

        const cardHtml = `
    <div class="card mb-3 card-block position-relative">
        <!-- Remove Button -->
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-card-btn" aria-label="Remove"></button>

        <div class="card-body">
            <h5 class="card-title">Card ${cardIndex + 1}</h5>

            <div class="mb-3">
                <label>Question</label>
                <input type="text" name="cards[${cardIndex}][question]" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Options</label>
                <div class="row g-2">
                    <div class="col">
                        <input type="text" name="cards[${cardIndex}][options][]" class="form-control" placeholder="Option 1" required>
                    </div>
                    <div class="col">
                        <input type="text" name="cards[${cardIndex}][options][]" class="form-control" placeholder="Option 2" required>
                    </div>
                    <div class="col">
                        <input type="text" name="cards[${cardIndex}][options][]" class="form-control" placeholder="Option 3">
                    </div>
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

        // Add event listener for the newly added remove button
        container.querySelectorAll('.remove-card-btn').forEach(btn => {
            btn.onclick = function() {
                this.closest('.card-block').remove();
            }
        });

        cardIndex++;
    });
</script>

<?php
require_once ROOT_PATH . '/app/views/layout/header.php';
?>