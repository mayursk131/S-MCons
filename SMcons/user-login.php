<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - SM Construction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <style>
        body {
            background-color: 
            color: 
        }
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: 
            padding: 40px;
            border-radius: 10px;
            border: 1px solid var(--gold);
            box-shadow: 0 0 20px rgba(218, 165, 32, 0.2);
            max-width: 400px;
            width: 100%;
        }
        .form-control {
            background: 
            border: 1px solid 
            color: 
        }
        .form-control:focus {
            background: 
            border-color: var(--gold);
            color: 
            box-shadow: 0 0 0 0.2rem rgba(218, 165, 32, 0.25);
        }
        .btn-gold {
            background: var(--gold);
            color: 
            border: none;
        }
        .btn-gold:hover {
            background: 
            color: 
        }
        .text-gold {
            color: var(--gold) !important;
        }
        .register-link {
            color: var(--gold);
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="text-center mb-4">
                <h2 class="text-gold mb-3">SM Construction</h2>
                <h4>User Portal</h4>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger mb-3">
                    <?php
                    switch ($_GET['error']) {
                        case 'empty':
                            echo 'Please fill in all fields.';
                            break;
                        case 'invalid':
                            echo 'Invalid email or password.';
                            break;
                        case 'notfound':
                            echo 'User account not found.';
                            break;
                        case 'inactive':
                            echo 'Your account is inactive. Please contact support.';
                            break;
                        case 'system':
                            echo 'System error. Please try again later.';
                            break;
                        default:
                            echo 'Login failed. Please try again.';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success']) && $_GET['success'] == 'registered'): ?>
                <div class="alert alert-success mb-3">
                    Registration successful! Please login with your credentials.
                </div>
            <?php endif; ?>

            <form action="backend/auth/user_auth.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control"
                           placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control"
                           placeholder="Enter your password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-gold btn-lg w-100 mb-3">Login</button>
            </form>

            <div class="text-center">
                <p class="mb-2">Don't have an account?</p>
                <a href="user-register.php" class="register-link">Register Here</a>
            </div>

            <div class="text-center mt-3">
                <a href="index.php" class="text-muted">← Back to Home</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>