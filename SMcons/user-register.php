<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - SM Construction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <style>
        body {
            background-color: 
            color: 
        }
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-box {
            background: 
            padding: 40px;
            border-radius: 10px;
            border: 1px solid var(--gold);
            box-shadow: 0 0 20px rgba(218, 165, 32, 0.2);
            max-width: 500px;
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
        .login-link {
            color: var(--gold);
            text-decoration: none;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <div class="text-center mb-4">
                <h2 class="text-gold mb-3">SM Construction</h2>
                <h4>Create Account</h4>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger mb-3">
                    <?php
                    switch ($_GET['error']) {
                        case 'empty':
                            echo 'Please fill in all required fields.';
                            break;
                        case 'email_exists':
                            echo 'An account with this email already exists.';
                            break;
                        case 'username_exists':
                            echo 'This username is already taken.';
                            break;
                        case 'password_mismatch':
                            echo 'Passwords do not match.';
                            break;
                        case 'password_weak':
                            echo 'Password must be at least 8 characters long.';
                            break;
                        case 'system':
                            echo 'Registration failed. Please try again.';
                            break;
                        default:
                            echo 'Registration failed. Please try again.';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="backend/auth/user_register.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username *</label>
                        <input type="text" name="username" id="username" class="form-control"
                               placeholder="Choose a username" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name *</label>
                        <input type="text" name="full_name" id="full_name" class="form-control"
                               placeholder="Your full name" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" name="email" id="email" class="form-control"
                           placeholder="your.email@example.com" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number *</label>
                    <input type="tel" name="phone" id="phone" class="form-control"
                           placeholder="+91 9876543210" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="Create a password" required minlength="8">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password *</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                               placeholder="Confirm password" required minlength="8">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="user_type" class="form-label">I am a *</label>
                    <select name="user_type" id="user_type" class="form-control" required>
                        <option value="">Select user type</option>
                        <option value="buyer">Property Buyer</option>
                        <option value="investor">Investor</option>
                        <option value="agent">Real Estate Agent</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#" class="text-gold">Terms of Service</a> and <a href="#" class="text-gold">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-gold btn-lg w-100 mb-3">Create Account</button>
            </form>

            <div class="text-center">
                <p class="mb-2">Already have an account?</p>
                <a href="user-login.php" class="login-link">Login Here</a>
            </div>

            <div class="text-center mt-3">
                <a href="index.php" class="text-muted">← Back to Home</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;

            if (password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>