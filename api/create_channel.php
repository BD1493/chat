<?php
$room = $_GET['room'];
$channel = $_GET['channel'];

$file = "../data/rooms.json";
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if (!isset($data[$room])) {
    $data[$room] = ['subject' => '', 'channels' => ['general' => []]];
}
if (!isset($data[$room]['channels'][$channel])) {
    $data[$room]['channels'][$channel] = [];
}

file_put_contents($file, json_encode($data));
echo json_encode(['status' => 'success']);
?>