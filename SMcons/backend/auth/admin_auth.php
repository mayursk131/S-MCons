<?php
session_start();


require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_code = $_POST['secret_code'];

    if ($submitted_code === ADMIN_ACCESS_CODE) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: ../../admin.php');
        exit;
    } else {
        header('Location: ../../admin-login.php?error=1');
        exit;
    }
} else {
    header('Location: ../../admin-login.php');
    exit;
}
?>