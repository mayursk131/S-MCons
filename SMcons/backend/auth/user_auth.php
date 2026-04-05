<?php
session_start();


require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header('Location: ../../user-login.php?error=empty');
        exit;
    }

    try {
        
        $stmt = $conn->prepare("SELECT id, username, email, password, full_name, user_type, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                if ($user['status'] === 'active') {
                    
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_type'] = $user['user_type'];

                    header('Location: ../../user.php');
                    exit;
                } else {
                    header('Location: ../../user-login.php?error=inactive');
                    exit;
                }
            } else {
                header('Location: ../../user-login.php?error=invalid');
                exit;
            }
        } else {
            header('Location: ../../user-login.php?error=notfound');
            exit;
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("User authentication error: " . $e->getMessage());
        header('Location: ../../user-login.php?error=system');
        exit;
    }
} else {
    header('Location: ../../user-login.php');
    exit;
}
?>