<?php
$room = $_GET['room'];
$user = $_GET['user'] ?? null;

$file = "../data/members.json";
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

// Add/update user activity
if ($user) {
    $data[$room][$user] = time();
}

// Clean inactive users (15 sec)
if (isset($data[$room])) {
    foreach ($data[$room] as $u => $t) {
        if (time() - $t > 15) {
            unset($data[$room][$u]);
        }
    }
}

file_put_contents($file, json_encode($data));

$members = isset($data[$room]) ? array_keys($data[$room]) : [];

header('Content-Type: application/json');
echo json_encode($members);
?>