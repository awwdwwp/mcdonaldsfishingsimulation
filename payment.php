<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: thankyou.html');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$card = trim($_POST['card'] ?? '');
$expiry = trim($_POST['expiry'] ?? '');
$cvv = trim($_POST['cvv'] ?? '');


if ($name === '') {
    die('Please enter your name.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Invalid email address.');
}

/*
Card validation
 */

$cardNumber = preg_replace('/\s+/', '', $card);

if (!preg_match('/^\d{16}$/', $cardNumber)) {
    die('Invalid card number format. Use 16 digits.');
}

/*
  Validate MM/YY format.
 */

if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
    die('Invalid expiration date. Use MM/YY.');
}

/*
 * Validate CVV.
 */

if (!preg_match('/^\d{3}$/', $cvv)) {
    die('Invalid CVV format.');
}

/*
 Only validation state is recorded
 */

$timestamp = date('Y-m-d H:i:s');

$logLine =
    "[$timestamp] SIMULATED PAYMENT VALIDATION | " .
    "name=$name | email=$email | card_format=valid | " .
    "expiry_format=valid | cvv_format=valid" .
    PHP_EOL;

file_put_contents(
    __DIR__ . '/payment_test_log.txt',
    $logLine,
    FILE_APPEND | LOCK_EX
);

?>

<!DOCTYPE html>

<html lang="sk">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>50% Discount Claimed</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    background: #fff8e8;
    color: #241c15;
    padding-top: 48px;
}


.sim-banner span {
    background: #fff;
    padding: 2px 8px;
    border-radius: 3px;
}

main {
    max-width: 720px;
    margin: 70px auto;
    background: #fff;
    padding: 40px;
    text-align: center;
    border-radius: 6px;
    box-shadow: 0 2px 10px rgba(0,0,0,.1);
}

h1 {
    color: #c8102e;
    margin-bottom: 20px;
}

p {
    line-height: 1.5;
    margin-bottom: 15px;
}

.success {
    background: #ffc72c;
    padding: 18px;
    border-radius: 5px;
    font-weight: bold;
    margin-bottom: 20px;
}

a {
    color: #c8102e;
}

</style>

</head>

<body>



<main>


<h1>Thank You!</h1>

<div class="success">
    ✓ Payment information format validated
</div>

<p>
    Your 50% discount has been successfully claimed.
</p>

<p>
    <a href="https://www.mcdonalds.sk/menu/">Return to the Menu</a>
</p>


</main>

</body>
</html>
