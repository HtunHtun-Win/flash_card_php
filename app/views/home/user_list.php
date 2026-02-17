<?php
define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/app/views/layout/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Users</h3>
        <div>
            <button id="btnAddUser" class="btn btn-primary">Add User</button>
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
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $index => $u): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($u->name) ?></td>
                            <td><?= htmlspecialchars($u->email) ?></td>
                            <td><?= htmlspecialchars($u->role) ?></td>
                            <td><?= $u->created_at ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary btn-edit" data-id="<?= $u->id ?>" data-name="<?= htmlspecialchars($u->name, ENT_QUOTES) ?>" data-email="<?= htmlspecialchars($u->email, ENT_QUOTES) ?>" data-role="<?= htmlspecialchars($u->role, ENT_QUOTES) ?>">Edit</button>
                                <?php if ($u->id != 1): ?>
                                    <button class="btn btn-sm btn-outline-danger btn-delete" data-id="<?= $u->id ?>">Delete</button>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No users found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="userForm" method="post" action="/app/action/UserAction.php?action=create">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="userId" value="">

                    <div class="mb-3">
                        <label for="userName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="userName" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="userEmail" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="userPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="userPassword" name="password" placeholder="Leave blank to keep current password">
                    </div>

                    <div class="mb-3">
                        <label for="userRole" class="form-label">Role</label>
                        <select id="userRole" name="role" class="form-select">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="modalSubmit">Save</button>
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
                <p id="deleteMessage">Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger btn-sm">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const userModalEl = document.getElementById('userModal');
        const userModal = new bootstrap.Modal(userModalEl);
        const btnAdd = document.getElementById('btnAddUser');
        const userForm = document.getElementById('userForm');
        const userModalLabel = document.getElementById('userModalLabel');
        const modalSubmit = document.getElementById('modalSubmit');

        btnAdd.addEventListener('click', function() {
            userForm.action = '/app/action/UserAction.php?action=create';
            userModalLabel.textContent = 'Add User';
            modalSubmit.textContent = 'Create';
            document.getElementById('userId').value = '';
            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userRole').value = 'user';
            userModal.show();
        });

        document.querySelectorAll('.btn-edit').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const email = this.dataset.email;
                const role = this.dataset.role || 'user';

                userForm.action = '/app/action/UserAction.php?action=update&id=' + id;
                userModalLabel.textContent = 'Edit User';
                modalSubmit.textContent = 'Update';

                document.getElementById('userId').value = id;
                document.getElementById('userName').value = name;
                document.getElementById('userEmail').value = email;
                document.getElementById('userPassword').value = '';
                document.getElementById('userRole').value = role;

                userModal.show();
            });
        });

        // Delete confirmation using Bootstrap modal
        const deleteModalEl = document.getElementById('deleteModal');
        const deleteModal = new bootstrap.Modal(deleteModalEl);
        const deleteMessage = document.getElementById('deleteMessage');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        document.querySelectorAll('.btn-delete').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const row = this.closest('tr');
                const name = row ? row.querySelector('td:nth-child(2)').textContent.trim() : '';
                deleteMessage.textContent = 'Are you sure you want to delete user "' + name + '"?';
                confirmDeleteBtn.href = '/app/action/UserAction.php?action=delete&id=' + id;
                deleteModal.show();
            });
        });
    })();
</script>

<?php
require_once ROOT_PATH . '/app/views/layout/footer.php';
?>