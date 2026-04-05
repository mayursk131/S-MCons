<?php


$servername = "localhost";
$username = "mayur";
$password = "123";
$dbname = "contact_db";


$conn = new mysqli($servername, $username, $password);


if ($conn->connect_error) {
    die("<div style='color:red; padding:20px;'>Connection failed: " . $conn->connect_error . "</div>");
}

echo "<div style='font-family: Arial; padding:20px; background:#000; color:#fff;'>";
echo "<h2 style='color: 


$sql_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql_db) === TRUE) {
    echo "<p style='color:green;'>✔ Database '$dbname' created/verified.</p>";
} else {
    echo "<p style='color:red;'>✘ Error creating database: " . $conn->error . "</p>";
    exit;
}


$conn->select_db($dbname);


$sql_chats = "CREATE TABLE IF NOT EXISTS chats (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prompt TEXT NOT NULL,
    response TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_chats) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'chats' ready for AI conversations.</p>";
} else {
    echo "<p style='color:red;'>✘ Error creating chats table: " . $conn->error . "</p>";
}


$sql_feedback = "CREATE TABLE IF NOT EXISTS feedback (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) DEFAULT 'Anonymous',
    email VARCHAR(100),
    message TEXT NOT NULL,
    rating INT(1) DEFAULT 0,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_feedback) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'feedback' ready for user feedback.</p>";
} else {
    echo "<p style='color:red;'>✘ Error creating feedback table: " . $conn->error . "</p>";
}


$sql_inquiries = "CREATE TABLE IF NOT EXISTS inquiries (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_inquiries) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'inquiries' ready for contact forms.</p>";
} else {
    echo "<p style='color:red;'>✘ Error creating inquiries table: " . $conn->error . "</p>";
}


$sql_projects = "CREATE TABLE IF NOT EXISTS projects (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    status ENUM('active', 'completed', 'pending') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_projects) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'projects' ready for project management.</p>";
} else {
    echo "<p style='color:red;'>✘ Error creating projects table: " . $conn->error . "</p>";
}



$projects_check = $conn->query("SELECT COUNT(*) as count FROM projects");
if ($projects_check->fetch_assoc()['count'] == 0) {
    $sample_projects = [
        ['Website Development', 'Corporate website with modern design', 'active'],
        ['Mobile Application', 'Cross-platform mobile app development', 'completed'],
        ['AI Integration', 'Machine learning solutions for business', 'pending']
    ];
    
    foreach ($sample_projects as $project) {
        $stmt = $conn->prepare("INSERT INTO projects (title, description, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $project[0], $project[1], $project[2]);
        $stmt->execute();
    }
    echo "<p style='color:#c5a059;'>📊 Sample project data inserted.</p>";
}


$backups_dir = '../backups';
if (!is_dir($backups_dir)) {
    mkdir($backups_dir, 0755, true);
    echo "<p style='color:green;'>✔ Backups directory created.</p>";
}

echo "<hr style='border-color: 
echo "<h3 style='color: 
echo "<p>Your admin panel is now ready with the following features:</p>";
echo "<ul>";
echo "<li>📊 Real-time statistics dashboard</li>";
echo "<li>💬 Chat history management</li>";
echo "<li>⭐ User feedback system</li>";
echo "<li>📝 Contact form management</li>";
echo "<li>🚀 Project tracking</li>";
echo "<li>🔧 System controls & maintenance</li>";
echo "<li>💾 Database backup functionality</li>";
echo "</ul>";
echo "<p style='margin-top:20px;'>";
echo "<a href='../admin-login.html' style='background:#c5a059; color:#000; padding:10px 20px; text-decoration:none; font-weight:bold;'>🎯 Go to Admin Login</a>";
echo "</p>";
echo "</div>";

$conn->close();
?>
