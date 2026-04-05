<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('DB_HOST', 'localhost');
define('DB_USER', 'mayur');      
define('DB_PASS', '123');
define('DB_NAME', 'contact_db');



function getDatabaseConnection() {
    
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        
        
        if ($conn->connect_errno == 1049) { 
            return new mysqli(DB_HOST, DB_USER, DB_PASS);
        }
        error_log("Database connection failed: " . $conn->connect_error);
        return null;
    }

    return $conn;
}


$conn = getDatabaseConnection();




$conn = getDatabaseConnection();
if (!$conn) {
    
    die('Database connection failed. Check configuration.');
}


define('ADMIN_ACCESS_CODE', 'CLASSIFIED123');

?>
