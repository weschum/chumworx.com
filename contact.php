<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

function clean_input(string $value): string
{
    $value = trim($value);
    $value = str_replace(["\r", "\n"], ' ', $value);
    return $value;
}

function fail_and_redirect(string $message): void
{
    header('Location: /?contact=' . urlencode($message));
    exit;
}

$honeypot = $_POST['company'] ?? '';
if (!empty($honeypot)) {
    fail_and_redirect('ok');
}

$name = clean_input($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    fail_and_redirect('missing');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fail_and_redirect('invalid-email');
}

if (mb_strlen($name) > 150) {
    fail_and_redirect('invalid-name');
}

if (mb_strlen($message) > 5000) {
    fail_and_redirect('message-too-long');
}

$to = 'admin@weschumley.com';
$subject = 'Chumworx contact form message';

$bodyLines = [
    'You received a new message from chumworx.com',
    '',
    'Name: ' . $name,
    'Email: ' . $email,
    '',
    'Message:',
    $message,
];

$body = implode("\n", $bodyLines);

$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: Chumworx Contact <no-reply@chumworx.com>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
];

$mailSent = mail($to, $subject, $body, implode("\r\n", $headers));

if (!$mailSent) {
    fail_and_redirect('send-failed');
}

header('Location: /?contact=success');
exit;