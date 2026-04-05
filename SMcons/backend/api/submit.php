<?php

require_once '../config/database.php';


$conn = getDatabaseConnection();
if (!$conn) {
    die("Connection failed. Please try again later.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = htmlspecialchars($_POST['full_name']);
    $phone = htmlspecialchars($_POST['phone_no']);
    $email = filter_var($_POST['email_address'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if ($isAjax) {
            echo json_encode(['success' => false, 'error' => 'Invalid email address']);
        } else {
            echo "<script>alert('Invalid email address'); window.location.href='index.html';</script>";
        }
        exit;
    }

    
    $stmt = $conn->prepare("INSERT INTO inquiries (name, phone, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $message);

    if ($stmt->execute()) {
        if ($isAjax) {
            echo json_encode(['success' => true, 'message' => 'Thank you! Your inquiry has been submitted.']);
        } else {
            echo "<script>alert('Thank you! Your inquiry has been submitted.'); window.location.href='index.html';</script>";
        }
    } else {
        if ($isAjax) {
            echo json_encode(['success' => false, 'error' => 'Error submitting inquiry. Please try again.']);
        } else {
            echo "<script>alert('Error submitting inquiry. Please try again.'); window.location.href='index.html';</script>";
        }
    }
    $stmt->close();
}
$conn->close();
?>