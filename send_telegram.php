<?php
// send_telegram.php

header('Content-Type: application/json');

// Put your bot token and chat ID here
$botToken = 'YOUR_BOT_TOKEN';

 $chatId = 'YOUR_CHAT_ID';


// Get the raw POST data (JSON)
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['text'])) {
    echo json_encode(['success' => false, 'error' => 'No text provided']);
    exit;
}

$text = $data['text'];

$url = "https://api.telegram.org/bot$botToken/sendMessage";

$postFields = [
    'chat_id' => $chatId,
    'text' => $text,
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['success' => false, 'error' => curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

$response = json_decode($result, true);

if ($response && $response['ok']) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $response['description'] ?? 'Unknown error']);
}
