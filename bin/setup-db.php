<?php
// One-time DB setup script. DELETE this file after first use.
// Usage: /bin/setup-db.php?secret=7iw-setup-2024&admin_email=you@example.com&admin_pass=yourpassword
declare(strict_types=1);

if (($_GET['secret'] ?? '') !== '7iw-setup-2024') {
    http_response_code(403);
    die('403 Forbidden');
}

require_once __DIR__ . '/../includes/db.php';

header('Content-Type: text/plain; charset=utf-8');

$pdo = db();

// Schema
echo "=== schema.sql ===\n";
try {
    $pdo->exec(file_get_contents(__DIR__ . '/../db/schema.sql'));
    echo "OK\n\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

// Seed
echo "=== seed.sql ===\n";
try {
    $pdo->exec(file_get_contents(__DIR__ . '/../db/seed.sql'));
    echo "OK\n\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

// Admin user
$email = trim($_GET['admin_email'] ?? '');
$pass  = trim($_GET['admin_pass']  ?? '');

echo "=== admin user ===\n";
if (!$email || !$pass) {
    echo "Skipped — pass admin_email and admin_pass in the URL.\n\n";
} elseif (strlen($pass) < 8) {
    echo "ERROR: Password must be at least 8 characters.\n\n";
} else {
    try {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $pdo->prepare(
            'INSERT INTO admin_users (email, password_hash)
             VALUES (:e, :h)
             ON CONFLICT (email) DO UPDATE SET password_hash = :h, last_login_at = NULL'
        )->execute([':e' => $email, ':h' => $hash]);
        echo "Created / updated: $email\n\n";
    } catch (Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n\n";
    }
}

echo "=== Done ===\n";
echo "IMPORTANT: Delete bin/setup-db.php from your repo after setup!\n";
