<!DOCTYPE html>
<html>

<head>
    <title>FlashCard — Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8fbff 0%, #e9f0ff 100%);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        .auth-card {
            max-width: 420px;
            border: 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .brand {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: white;
            box-shadow: 0 6px 18px rgba(13, 110, 253, .08);
            font-weight: 700;
            color: #0d6efd;
            margin-right: 12px;
            font-size: 20px;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            opacity: 0.9;
        }

        .small-note {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .form-help {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>

    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card auth-card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="brand">FB</div>
                    <div>
                        <h4 class="mb-0">Create an account</h4>
                        <div class="small-note">Welcome — join and save your flashcards</div>
                    </div>
                </div>

                <?php if (isset($_GET['msg'])) { ?>
                    <div class="alert alert-danger py-2" role="alert"><?= htmlspecialchars($_GET['msg']) ?></div>
                <?php } ?>

                <form action="/app/action/AuthAction.php?action=register" method="post" novalidate>
                    <div class="mb-3 form-floating">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                        <label for="name">Name</label>
                    </div>

                    <div class="mb-3 form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                        <label for="email">Email</label>
                    </div>

                    <div class="mb-3 form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>

                    <div class="mb-2 form-floating">
                        <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password" required>
                        <label for="cpassword">Confirm Password</label>
                    </div>

                    <div class="mb-3 form-help text-danger" id="passMsg"></div>

                    <button id="registerBtn" class="btn btn-primary w-100 mb-2" type="submit">Create account</button>

                    <div class="text-center small-note">
                        Already have an account? <a href="/app/views/auth/login.php">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const password = document.getElementById('password');
            const cpassword = document.getElementById('cpassword');
            const msg = document.getElementById('passMsg');
            const registerBtn = document.getElementById('registerBtn');

            function validatePasswords() {
                msg.textContent = '';
                registerBtn.disabled = false;

                if (!password.value || !cpassword.value) return;

                if (password.value.length < 6) {
                    msg.textContent = 'Password should be at least 6 characters';
                    registerBtn.disabled = true;
                    return;
                }

                if (password.value !== cpassword.value) {
                    msg.textContent = 'Passwords do not match';
                    registerBtn.disabled = true;
                } else {
                    msg.textContent = 'Passwords match';
                    msg.className = 'form-help text-success';
                }
            }

            password.addEventListener('input', validatePasswords);
            cpassword.addEventListener('input', validatePasswords);
        })();
    </script>

</body>

</html>