<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access</title>
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
        }
        .form-control {
            background: 
            border: 1px solid 
            color: 
        }
        .btn-gold {
            background: var(--gold);
            color: 
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box text-center">
            <div class="mb-3" id="loginLanguageSelector"></div>
            <h2 class="text-gold mb-4" data-translate="classfied">CLASSIFIED</h2>
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <div class="alert alert-danger mb-3">Invalid access code. Please try again.</div>
            <?php endif; ?>
            <p class="text-muted mb-4" data-translate="agent_login_required">AGENT LOGIN REQUIRED</p>
            <form action="backend/auth/admin_auth.php" method="POST">
                <div class="mb-3">
                    <input type="password" name="secret_code" class="form-control text-center" 
                           data-translate-placeholder="enter_access_code" 
                           placeholder="ENTER ACCESS CODE" required>
                </div>
                <button type="submit" class="btn btn-gold btn-lg" data-translate="authenticate">AUTHENTICATE</button>
            </form>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/utils/language.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            if (typeof languageManager !== 'undefined') {
                const selectorContainer = document.getElementById('loginLanguageSelector');
                if (selectorContainer) {
                    selectorContainer.innerHTML = languageManager.createLanguageSelector();
                }
            } else {
                console.error('Language manager not loaded');
            }
        });
    </script>
</body>
</html>
