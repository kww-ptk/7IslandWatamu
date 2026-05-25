<?php
declare(strict_types=1);

function db(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $env = parse_env();

    $dsn = sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        $env['DB_HOST'] ?? 'localhost',
        $env['DB_PORT'] ?? '5432',
        $env['DB_NAME'] ?? '7island'
    );

    $pdo = new PDO($dsn, $env['DB_USER'] ?? '', $env['DB_PASS'] ?? '', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    return $pdo;
}

function parse_env(): array {
    static $env = null;
    if ($env !== null) return $env;

    // Render and other hosts inject env vars directly — use those first
    $env = $_ENV + $_SERVER;

    // Fall back to .env file for local dev
    $file = __DIR__ . '/../.env';
    if (file_exists($file)) {
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
            [$key, $val] = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val);
            if (!isset($env[$key])) $env[$key] = $val;
        }
    }

    return $env;
}

function db_query(string $sql, array $params = []): PDOStatement {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function fetch_room_by_slug(string $slug): array|false {
    return db_query(
        'SELECT * FROM rooms WHERE slug = :slug AND is_published = TRUE',
        [':slug' => $slug]
    )->fetch();
}

function fetch_room_images(int $room_id): array {
    return db_query(
        'SELECT * FROM room_images WHERE room_id = :id ORDER BY sort_order ASC',
        [':id' => $room_id]
    )->fetchAll();
}

function setting(string $key, string $default = ''): string {
    $row = db_query(
        'SELECT setting_value FROM settings WHERE setting_key = :key',
        [':key' => $key]
    )->fetch();
    return $row ? $row['setting_value'] : $default;
}

function set_setting(string $key, string $value): void {
    db_query(
        'INSERT INTO settings (setting_key, setting_value, updated_at)
         VALUES (:key, :val, NOW())
         ON CONFLICT (setting_key) DO UPDATE SET setting_value = :val, updated_at = NOW()',
        [':key' => $key, ':val' => $value]
    );
}

function e(mixed $val): string {
    return htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8');
}
