<?php
// create_room.php
header('Content-Type: application/json');

$subject = $_GET['subject'] ?? '';
$subject = trim($subject);

if ($subject === '') {
    echo json_encode(['success' => false, 'error' => 'Missing subject']);
    exit;
}

$code = strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 6));
$dataFile = __DIR__ . "/../data/rooms.json";

// Open file with locking
$fp = fopen($dataFile, 'c+');
if (!$fp) {
    echo json_encode(['success' => false, 'error' => 'Unable to open data file']);
    exit;
}

flock($fp, LOCK_EX);
$contents = stream_get_contents($fp);
$data = $contents ? json_decode($contents, true) : [];

if (!is_array($data)) $data = [];

$data[$code] = [
    'subject' => $subject,
    'messages' => []
];

// Rewind and write
ftruncate($fp, 0);
rewind($fp);
fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
fflush($fp);
flock($fp, LOCK_UN);
fclose($fp);

echo json_encode(['success' => true, 'code' => $code]);
exit;
?>