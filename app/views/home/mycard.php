<?php
require "/home/htun/Desktop/PHP_Playground/flash_card_php/app/views/layout/header.php";
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
    <form>
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
                <a class="btn btn-primary w-100">Add Title</a>
            </div>
        </div>
    </form>


    <?php foreach ($cards as $c): ?>
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <?= $c->name ?>
                    <small class="badge bg-primary"><?= $c->uname ?></small>
                    <small class="badge bg-primary"><?= $c->cname ?></small>
                </h5>

                <div>
                    <a href="/app/action/CardAction.php?action=edit&id=<?= $c->id ?>"
                        class="btn btn-sm btn-outline-primary">Edit</a>
                    <a href="/app/action/CardAction.php?action=delete&id=<?= $c->id ?>"
                        class="btn btn-sm btn-outline-danger">Delete</a>
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p><?= $c->desc ?></p>
                    <p><?= $c->total ?> Questions</p>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<script>
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
require "/home/htun/Desktop/PHP_Playground/flash_card_php/app/views/layout/footer.php";
?>