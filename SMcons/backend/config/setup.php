<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'database.php';


$conn = getDatabaseConnection();
if (!$conn) {
    die("<div style='color:red;'>Database connection failed. Please check configuration.</div>");
}

echo "<h3>Setting up your system...</h3>";


$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
$conn->select_db(DB_NAME);


$sql_inquiry = "CREATE TABLE IF NOT EXISTS inquiries (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_inquiry) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'inquiries' ready.</p>";
} else {
    echo "<p style='color:red;'>✘ Error creating inquiries table: " . $conn->error . "</p>";
}


$sql_feedback = "CREATE TABLE IF NOT EXISTS feedback_submissions (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) DEFAULT 'Anonymous',
    rating INT(1) NOT NULL,
    subject VARCHAR(50) NOT NULL,
    suggestions TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_feedback) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'feedback_submissions' ready for your form.</p>";
} else {
    echo "<p style='color:red;'>✘ Error creating feedback table: " . $conn->error . "</p>";
}

echo "<hr>";
echo "<p><strong>Setup Complete!</strong> You can now use both Inquiry and Feedback forms.</p>";
echo "<p><a href='../../index.html'>← Back to Website</a></p>";




$conn->close();
?>