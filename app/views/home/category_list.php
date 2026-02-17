<?php
define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/app/views/layout/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Categories</h3>
        <div>
            <button id="btnAddCategory" class="btn btn-primary">Add Category</button>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-info"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $index=>$c): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($c->name) ?></td>
                            <td><?= $c->created_at ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary btn-edit" data-id="<?= $c->id ?>" data-name="<?= htmlspecialchars($c->name, ENT_QUOTES) ?>">Edit</button>
                                <button class="btn btn-sm btn-outline-danger btn-delete" data-id="<?= $c->id ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No categories found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="categoryForm" method="post" action="/app/action/CategoryAction.php?action=create">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="categoryId" value="">

                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="categorySubmit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirm Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage">Are you sure you want to delete this category?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger btn-sm">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const categoryModalEl = document.getElementById('categoryModal');
        const categoryModal = new bootstrap.Modal(categoryModalEl);
        const btnAdd = document.getElementById('btnAddCategory');
        const categoryForm = document.getElementById('categoryForm');
        const categoryModalLabel = document.getElementById('categoryModalLabel');
        const categorySubmit = document.getElementById('categorySubmit');

        btnAdd.addEventListener('click', function () {
            categoryForm.action = '/app/action/CategoryAction.php?action=create';
            categoryModalLabel.textContent = 'Add Category';
            categorySubmit.textContent = 'Create';
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryName').value = '';
            categoryModal.show();
        });

        document.querySelectorAll('.btn-edit').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;

                categoryForm.action = '/app/action/CategoryAction.php?action=update&id=' + id;
                categoryModalLabel.textContent = 'Edit Category';
                categorySubmit.textContent = 'Update';

                document.getElementById('categoryId').value = id;
                document.getElementById('categoryName').value = name;

                categoryModal.show();
            });
        });

        // Delete confirmation using Bootstrap modal
        const deleteModalEl = document.getElementById('deleteModal');
        const deleteModal = new bootstrap.Modal(deleteModalEl);
        const deleteMessage = document.getElementById('deleteMessage');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const row = this.closest('tr');
                const name = row ? row.querySelector('td:nth-child(2)').textContent.trim() : '';
                deleteMessage.textContent = 'Are you sure you want to delete category "' + name + '"?';
                confirmDeleteBtn.href = '/app/action/CategoryAction.php?action=delete&id=' + id;
                deleteModal.show();
            });
        });
    })();
</script>

<?php
require_once ROOT_PATH . '/app/views/layout/footer.php';
?>