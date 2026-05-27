<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

$slug = trim($_GET['room'] ?? '');
if (!$slug) {
    http_response_code(400);
    exit(json_encode(['error' => 'room parameter required']));
}

$room = fetch_room_by_slug($slug);
if (!$room) {
    http_response_code(404);
    exit(json_encode(['error' => 'Room not found']));
}

$check_in  = trim($_GET['check_in']  ?? '');
$check_out = trim($_GET['check_out'] ?? '');

// ── Specific date check (used when guest selects a range) ────────
if ($check_in && $check_out) {
    if ($check_in >= $check_out) {
        http_response_code(422);
        exit(json_encode(['error' => 'Check-out must be after check-in']));
    }

    $unit = find_available_unit($room['id'], $check_in, $check_out);

    // Rate override for these dates (use earliest matching rate)
    $rate = db_query(
        "SELECT price_amount FROM rates
         WHERE room_id = :rid AND date_from <= :ci AND date_to >= :co
         ORDER BY created_at DESC LIMIT 1",
        [':rid' => $room['id'], ':ci' => $check_in, ':co' => $check_out]
    )->fetchColumn();

    $price_per_night = $rate !== false ? (float)$rate : (float)$room['price_amount'];
    $nights = max(1, (int)((strtotime($check_out) - strtotime($check_in)) / 86400));

    exit(json_encode([
        'available'       => (bool)$unit,
        'price_per_night' => $price_per_night,
        'currency'        => $room['price_currency'],
        'price_unit'      => $room['price_unit'],
        'nights'          => $nights,
        'total'           => round($price_per_night * $nights, 2),
    ]));
}

// ── Calendar view: return fully-blocked dates for next 18 months ─
$from = date('Y-m-d');
$to   = date('Y-m-d', strtotime('+18 months'));

exit(json_encode([
    'fully_blocked' => get_room_blocked_dates($room['id'], $from, $to),
    'price'         => (float)$room['price_amount'],
    'currency'      => $room['price_currency'],
    'price_unit'    => $room['price_unit'],
]));
