<?php



require_once '../config/database.php';


$conn = getDatabaseConnection();
if (!$conn) {
    die("Connection failed. Please try again later.");
}

$message = "";
$status = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $rating      = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $subject     = isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : "General";
    $user_name   = !empty($_POST['name']) ? htmlspecialchars($_POST['name']) : "Anonymous";
    $suggestions = isset($_POST['suggestions']) ? htmlspecialchars($_POST['suggestions']) : "";

    
    $stmt = $conn->prepare("INSERT INTO feedback_submissions (user_name, rating, subject, suggestions) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $user_name, $rating, $subject, $suggestions);

    if ($stmt->execute()) {
        $status = "success";
        $message = "Thank you " . $user_name . "! Your feedback has been saved.";
    } else {
        $status = "error";
        $message = "Something went wrong: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: 
        .status-card { border: 1px solid 
        .text-gold { color: 
        .btn-gold { border: 1px solid 
        .btn-gold:hover { background: 
    </style>
</head>
<body>

    <div class="status-card">
        <?php if ($status == "success"): ?>
            <h2 class="text-gold mb-3">Submission Successful!</h2>
            <p><?php echo $message; ?></p>
        <?php else: ?>
            <h2 class="text-danger mb-3">Error</h2>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <hr style="border-color: 
        <a href="index.html" class="btn-gold d-inline-block mt-3">Go Back</a>
    </div>

    </body>
</html>