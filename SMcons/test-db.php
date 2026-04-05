<?php

require_once 'backend/config/database.php';

echo "<h2>Database Connection Test</h2>";

$conn = getDatabaseConnection();
if ($conn) {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    
    $conn->select_db(DB_NAME);
    echo "<p style='color: green;'>✅ Database '" . DB_NAME . "' selected successfully!</p>";
    
    
    $tables = ['inquiries', 'feedback_submissions', 'bookings', 'chats', 'projects', 'property_availability'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Table '$table' exists!</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Table '$table' does not exist. <a href='backend/config/setup.php'>Run Setup</a></p>";
        }
    }
    
    $conn->close();
} else {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    echo "<p>Please check your database configuration in backend/config/database.php</p>";
}

echo "<hr>";
echo "<p><a href='index.html'>← Back to Website</a> | <a href='backend/config/setup.php'>Run Database Setup</a> | <a href='admin-login.php'>Admin Login</a></p>";
?>
