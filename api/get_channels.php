<?php
$room = $_GET['room'];
$file = "../data/rooms.json";
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

$channels = isset($data[$room]['channels']) ? array_keys($data[$room]['channels']) : ['general'];

header('Content-Type: application/json');
echo json_encode($channels);
?>