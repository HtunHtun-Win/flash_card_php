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

// ensure categories array exists to avoid warnings
$categories = $categories ?? [];
?>

<div class="container mt-4">
    <div class="row mb-4 g-3">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="card stat-card text-white bg-info shadow-sm p-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Users</h6>
                            <p class="card-text h3 mb-0"><?= $userCount ?? 0 ?></p>
                        </div>
                        <i class="bi bi-people-fill fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>

</div>

<div class="container mt-4">
    <div class="row mb-4 g-3">
        <div class="col-sm-6">
            <div class="card stat-card text-white bg-success shadow-sm p-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Categories</h6>
                        <p class="card-text h3 mb-0"><?= $categoryCount ?? 0 ?></p>
                    </div>
                    <i class="bi bi-tags-fill fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card stat-card text-white bg-warning shadow-sm p-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Card Sets</h6>
                        <p class="card-text h3 mb-0"><?= $cardSetCount ?? 0 ?></p>
                    </div>
                    <i class="bi bi-folder-fill fs-1"></i>
                </div>
            </div>
        </div>
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

    // search and filter handling
    const searchInput = document.getElementById("searchInput");
    const clearBtn = document.getElementById("clearSearch");
    const categorySelect = document.getElementById('categorySelect');

    function toggleClear() {
        clearBtn.style.display = searchInput.value ? "block" : "none";
    }

    function applyFilters() {
        const q = searchInput.value.trim();
        const cat = categorySelect ? categorySelect.value : '0';
        const params = new URLSearchParams();
        if (q) params.set('q', q);
        if (cat && cat !== '0') params.set('cat', cat);
        const query = params.toString();
        window.location.href = window.location.pathname + (query ? ('?' + query) : '');
    }

    // initial state
    toggleClear();

    // show/hide while typing
    searchInput.addEventListener("input", toggleClear);

    // Enter submits search
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyFilters();
        }
    });

    // clear on click and submit to refresh results
    clearBtn.addEventListener("click", () => {
        searchInput.value = "";
        toggleClear();
        searchInput.focus();
        applyFilters();
    });

    // category change
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            applyFilters();
        });
    }
</script>


<?php
require_once ROOT_PATH . '/app/views/layout/footer.php';
?>