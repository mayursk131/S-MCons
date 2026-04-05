<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    $message = $input['message'];

    $apiKey = "AIzaSyAUmewI1OrEfyVStVQuwcV0xW2jB7twJJ8"; 
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=AIzaSyAUmewI1OrEfyVStVQuwcV0xW2jB7twJJ8";

    $data = [
        "contents" => [
            ["parts" => [["text" => $message]]]
        ]
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    echo $result;
}
?>
