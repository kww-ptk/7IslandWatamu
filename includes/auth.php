<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function session_init(): void {
    if (session_status() !== PHP_SESSION_NONE) return;

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();

    // 2-hour idle timeout
    if (isset($_SESSION['last_active']) && time() - $_SESSION['last_active'] > 7200) {
        session_unset();
        session_destroy();
        session_start();
    }
    $_SESSION['last_active'] = time();
}

function require_login(): void {
    session_init();
    if (empty($_SESSION['admin_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

function current_admin(): array|false {
    session_init();
    if (empty($_SESSION['admin_id'])) return false;
    try {
        return db_query(
            'SELECT id, name, email, role, created_at FROM admin_users WHERE id = :id',
            [':id' => $_SESSION['admin_id']]
        )->fetch();
    } catch (\PDOException) {
        // Migration not yet run — fall back to pre-Phase4 columns
        $row = db_query(
            'SELECT id, email, created_at FROM admin_users WHERE id = :id',
            [':id' => $_SESSION['admin_id']]
        )->fetch();
        if ($row) {
            $row['name'] = null;
            $row['role'] = 'super_admin'; // treat all existing accounts as super_admin
        }
        return $row ?: false;
    }
}

function is_super_admin(): bool {
    $admin = current_admin();
    return $admin && ($admin['role'] ?? 'staff') === 'super_admin';
}

function require_super_admin(): void {
    require_login();
    if (!is_super_admin()) {
        http_response_code(403);
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Access denied</title>
              <link rel="stylesheet" href="/admin/assets/admin.css"></head><body class="admin-body">
              <div style="display:flex;align-items:center;justify-content:center;height:100vh">
              <div style="text-align:center">
                <h2 style="color:var(--danger,#dc2626)">Access denied</h2>
                <p style="color:var(--muted)">Super admin access required.</p>
                <a href="/admin/dashboard.php" style="color:var(--primary)">← Back to dashboard</a>
              </div></div></body></html>';
        exit;
    }
}

function login(string $email, string $password): bool {
    session_init();

    if (is_rate_limited($email, $_SERVER['REMOTE_ADDR'] ?? '')) return false;

    $user = db_query(
        'SELECT * FROM admin_users WHERE email = :email',
        [':email' => $email]
    )->fetch();

    $success = $user && password_verify($password, $user['password_hash']);

    db_query(
        'INSERT INTO login_attempts (email, ip_address, success) VALUES (:email, :ip, :ok)',
        [':email' => $email, ':ip' => $_SERVER['REMOTE_ADDR'] ?? '', ':ok' => $success ? 'TRUE' : 'FALSE']
    );

    if ($success) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $user['id'];
        db_query(
            'UPDATE admin_users SET last_login_at = NOW() WHERE id = :id',
            [':id' => $user['id']]
        );
    }

    return $success;
}

function logout(): void {
    session_init();
    session_unset();
    session_destroy();
}

function is_rate_limited(string $email, string $ip): bool {
    $window = date('Y-m-d H:i:s', time() - 600); // 10 minutes
    $row = db_query(
        "SELECT COUNT(*) AS cnt FROM login_attempts
         WHERE (email = :email OR ip_address = :ip)
           AND success = FALSE
           AND created_at > :window",
        [':email' => $email, ':ip' => $ip, ':window' => $window]
    )->fetch();
    return (int)$row['cnt'] >= 5;
}

function csrf_token(): string {
    session_init();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void {
    session_init();
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Invalid CSRF token.');
    }
}
