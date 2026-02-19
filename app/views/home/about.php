<?php
define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/app/views/layout/header.php';
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">

            <h2 class="mb-4 text-primary">Flash Card Learning System</h2>

            <p>
                This Flash Card system is designed to help students practice
                <strong>multiple choice questions (MCQ)</strong> in an interactive way.
            </p>

            <hr>

            <h4>🔐 User Authentication</h4>
            <ul>
                <li>Users must register and login to use the system.</li>
                <li>Each user can manage their own flash card sets.</li>
            </ul>

            <h4>📚 Card Set Management</h4>
            <ul>
                <li>Create a flash card set with a custom title.</li>
                <li>Add multiple choice questions and answers.</li>
                <li>Set card visibility as:
                    <ul>
                        <li><strong>Public</strong> – Other users can practice.</li>
                        <li><strong>Private</strong> – Only the owner can access.</li>
                    </ul>
                </li>
            </ul>

            <h4>📝 Practice Mode</h4>
            <ul>
                <li>Users can practice public flash card sets.</li>
                <li>System shows score after completion.</li>
                <li>Instant feedback for correct and incorrect answers.</li>
            </ul>

            <h4>📥 Import from Excel</h4>
            <ul>
                <li>Users can upload Excel files.</li>
                <li>Questions are automatically imported into the system.</li>
                <li>Saves time when adding large number of questions.</li>
            </ul>

            <hr>

            <div class="alert alert-info">
                This system helps students improve knowledge through active learning and self-testing.
            </div>

        </div>
    </div>
</div>

<?php
require_once ROOT_PATH . '/app/views/layout/footer.php';
?>