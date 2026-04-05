<?php

require_once '../config/database.php';


$conn = getDatabaseConnection();
if (!$conn) {
    die("Connection failed. Please try again later.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $property_name = htmlspecialchars($_POST['property_name']);
    $user_name = htmlspecialchars($_POST['user_name']);
    $email = filter_var($_POST['email_address'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone_no']);
    $message = htmlspecialchars($_POST['message']);

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address'); window.history.back();</script>";
        exit;
    }

    
    $stmt = $conn->prepare("INSERT INTO bookings (property_name, user_name, email, phone, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $property_name, $user_name, $email, $phone, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Thank you! Your booking request for " . $property_name . " has been submitted.'); window.location.href='../../index.html';</script>";
    } else {
        echo "<script>alert('Error submitting booking. Please try again.'); window.history.back();</script>";
    }
    $stmt->close();
}
$conn->close();
?>
