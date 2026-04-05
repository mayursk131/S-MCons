<?php


$host = "localhost";
$user = "admin_user";
$pass = "your_password"; 


$conn = new mysqli($host, $user, $pass);


if ($conn->connect_error) {
    die("<h3 style='color:red;'>Connection failed: " . $conn->connect_error . "</h3>");
}


$sql = "CREATE DATABASE IF NOT EXISTS academic_db;
        USE academic_db;
        CREATE TABLE IF NOT EXISTS chats (
            id INT AUTO_INCREMENT PRIMARY KEY,
            prompt TEXT,
            response TEXT,
            time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";


if ($conn->multi_query($sql)) {
    echo "<h3 style='color:green;'>Success! Database 'academic_db' and table 'chats' are ready.</h3>";
    echo "<p>You can now delete this file and proceed to Step 2.</p>";
} else {
    echo "<h3 style='color:red;'>Error creating database: " . $conn->error . "</h3>";
}

$conn->close();
?>