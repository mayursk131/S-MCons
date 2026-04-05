<?php
session_start();


require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type'];
    $terms = isset($_POST['terms']) ? $_POST['terms'] : '';

    
    if (empty($username) || empty($full_name) || empty($email) || empty($phone) || empty($password) || empty($user_type)) {
        header('Location: ../../user-register.php?error=empty');
        exit;
    }

    if ($password !== $confirm_password) {
        header('Location: ../../user-register.php?error=password_mismatch');
        exit;
    }

    if (strlen($password) < 8) {
        header('Location: ../../user-register.php?error=password_weak');
        exit;
    }

    if (empty($terms)) {
        header('Location: ../../user-register.php?error=terms');
        exit;
    }

    try {
        
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header('Location: ../../user-register.php?error=email_exists');
            exit;
        }
        $stmt->close();

        
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header('Location: ../../user-register.php?error=username_exists');
            exit;
        }
        $stmt->close();

        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, user_type, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())");
        $stmt->bind_param("ssssss", $username, $email, $hashed_password, $full_name, $phone, $user_type);

        if ($stmt->execute()) {
            header('Location: ../../user-login.php?success=registered');
            exit;
        } else {
            error_log("User registration failed: " . $stmt->error);
            header('Location: ../../user-register.php?error=system');
            exit;
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("User registration error: " . $e->getMessage());
        header('Location: ../../user-register.php?error=system');
        exit;
    }
} else {
    header('Location: ../../user-register.php');
    exit;
}
?>