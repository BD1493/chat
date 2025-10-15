<?php
$room = $_GET['room'];
$channel = $_GET['channel'] ?? 'general';

$file = "../data/rooms.json";
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

$messages = $data[$room]['channels'][$channel] ?? [];

header('Content-Type: application/json');
echo json_encode($messages);
?>