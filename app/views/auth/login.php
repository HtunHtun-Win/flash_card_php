<!DOCTYPE html>
<html>

<head>
    <title>FlashCard — Login</title>
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

        .small-note {
            font-size: 0.9rem;
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
                        <h4 class="mb-0">Welcome back</h4>
                        <div class="small-note">Sign in to continue to your flashcards</div>
                    </div>
                </div>

                <?php if (isset($_GET['msg'])) { ?>
                    <div class="alert alert-danger py-2" role="alert"><?= htmlspecialchars($_GET['msg']) ?></div>
                <?php } ?>

                <form action="/app/action/AuthAction.php?action=login" method="post" novalidate>
                    <div class="mb-3 form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                        <label for="email">Email</label>
                    </div>

                    <div class="mb-3 form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>

                    <!-- <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember" class="small-note">Remember me</label>
                        </div>
                        <div>
                            <a href="#" class="small-note">Forgot?</a>
                        </div>
                    </div> -->

                    <button id="loginBtn" class="btn btn-primary w-100 mb-2" type="submit">Sign in</button>

                    <div class="text-center small-note">
                        Don't have an account? <a href="/app/views/auth/register.php">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const loginBtn = document.getElementById('loginBtn');

            function validate() {
                loginBtn.disabled = !(email.value && password.value);
            }

            email.addEventListener('input', validate);
            password.addEventListener('input', validate);

            // initial state
            validate();
        })();
    </script>

</body>

</html>