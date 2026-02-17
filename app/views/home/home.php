<?php

define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/app/views/layout/header.php';

if (isset($_GET['cat'])) {
    $selectedCat = $_GET['cat'];
} else {
    $selectedCat = 0;
}

if (isset($_GET['q'])) {
    $searchKey = $_GET['q'];
} else {
    $searchKey = "";
}
?>

<div class="container mt-4">
    <form method="GET">
        <?php if (isset($_GET['action'])) : ?>
            <input type="hidden" name="action" value="<?= htmlspecialchars($_GET['action']) ?>">
        <?php endif ?>
        <div class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="cat" class="form-select">
                    <option value="0" <?= $selectedCat == 0 ? "selected" : "" ?>>All</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= $selectedCat == $cat->id ? "selected" : "" ?>><?= $cat->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-md-5">
                <div class="position-relative">
                    <input
                        id="searchInput"
                        name="q"
                        class="form-control pe-5"
                        placeholder="Search"
                        value="<?= htmlspecialchars($searchKey ?? '') ?>">

                    <span
                        id="clearSearch"
                        class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                        style="cursor: pointer; display: none;">
                        &times;
                    </span>
                </div>
            </div>


            <div class="col-md-2">
                <button class="btn btn-secondary w-100">Search</button>
            </div>
            <div class="col-md-2">
                <a href="/app/views/home/add_card.php" class="btn btn-primary w-100">Add Title</a>
            </div>
        </div>
    </form>

    <div class="row mt-4">
        <?php foreach ($cards as $c): ?>
            <div class="col-md-6 mb-4"> <!-- 2 columns per row -->
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <?= $c->name ?>
                            <small class="badge bg-primary"><?= $c->uname ?></small>
                            <small class="badge bg-primary"><?= $c->cname ?></small>
                        </h5>

                        <?php if ($c->user_id == $_SESSION['user']->id || $_SESSION['user']->role == 'admin'): ?>
                            <div>
                                <a href="/app/views/home/edit_card.php?id=<?= $c->id ?>"
                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="#"
                                    class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteModal"
                                    data-id="<?= $c->id ?>">
                                    Delete
                                </a>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="card-body d-flex justify-content-between align-items-center">
                        <p class="mb-0"><?= $c->desc ?></p>
                        <p class="mb-0"><?= $c->total ?> Questions</p>
                        <a href="/app/views/home/review_card.php?id=<?= $c->id ?>"
                            class="btn btn-sm btn-outline-success">Review</a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this card? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger" id="deleteConfirmBtn">Delete</a>
            </div>
        </div>
    </div>
</div>


<script>
    // show delete modal
    var confirmDeleteModal = document.getElementById('confirmDeleteModal');
    confirmDeleteModal.addEventListener('show.bs.modal', function(event) {
        // Button that triggered the modal
        var button = event.relatedTarget;
        var cardId = button.getAttribute('data-id');

        // Update the modal delete button link
        var deleteBtn = document.getElementById('deleteConfirmBtn');
        deleteBtn.href = '/app/action/CardAction.php?action=delete&id=' + cardId;
    });
    //
    const searchInput = document.getElementById("searchInput");
    const clearBtn = document.getElementById("clearSearch");

    function toggleClear() {
        clearBtn.style.display = searchInput.value ? "block" : "none";
    }

    // initial state
    toggleClear();

    // show/hide while typing
    searchInput.addEventListener("input", toggleClear);

    // clear on click
    clearBtn.addEventListener("click", () => {
        searchInput.value = "";
        toggleClear();
        searchInput.focus();
    });
</script>


<?php
require_once ROOT_PATH . '/app/views/layout/footer.php';
?>