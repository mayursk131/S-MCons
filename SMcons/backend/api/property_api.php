<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$conn = getDatabaseConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$propertyName = isset($_GET['property']) ? trim($_GET['property']) : '';
if (empty($propertyName)) {
    echo json_encode(['success' => false, 'error' => 'Property name is required']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM property_availability WHERE property_name = ? LIMIT 1");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Failed to prepare statement']);
    exit;
}

$stmt->bind_param('s', $propertyName);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'error' => 'No availability data found for this property']);
}

$stmt->close();
$conn->close();
