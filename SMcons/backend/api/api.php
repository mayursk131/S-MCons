<?php

header('Content-Type: application/json');


$apiKey = "AIzaSyAUmewI1OrEfyVStVQuwcV0xW2jB7twJJ8"; 


$conn = new mysqli("localhost", "admin_user", "your_password", "contact_db");


if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}


$input = json_decode(file_get_contents('php://input'), true);
$userMsg = $input['message'] ?? '';

if (!$userMsg) {
    echo json_encode(["error" => "Message is empty"]);
    exit;
}


$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

$payload = [
    "contents" => [["parts" => [["text" => $userMsg]]]]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);
$aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? "No response from AI";


$stmt = $conn->prepare("INSERT INTO chats (prompt, response) VALUES (?, ?)");
$stmt->bind_param("ss", $userMsg, $aiResponse);
$stmt->execute();


echo json_encode(["reply" => $aiResponse]);
?>