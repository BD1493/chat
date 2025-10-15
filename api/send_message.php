<?php
$room = $_GET['room'];
$channel = $_GET['channel'] ?? 'general';
$user = $_GET['user'];
$text = $_GET['text'];

$file = "../data/rooms.json";
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if (!isset($data[$room])) {
    $data[$room] = ['subject' => '', 'channels' => ['general' => []]];
}
if (!isset($data[$room]['channels'][$channel])) {
    $data[$room]['channels'][$channel] = [];
}

$data[$room]['channels'][$channel][] = ['user' => $user, 'text' => $text];

file_put_contents($file, json_encode($data));
echo json_encode(['status' => 'success']);
?>