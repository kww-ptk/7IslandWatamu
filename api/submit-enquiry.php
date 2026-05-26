<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mail.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['ok' => false, 'error' => 'Method not allowed']));
}

$data = json_decode(file_get_contents('php://input'), true) ?? [];

// Honeypot
if (!empty($data['website'])) {
    exit(json_encode(['ok' => true]));
}

// Rate limit — max 5 submissions per IP in 10 minutes
$ip     = $_SERVER['REMOTE_ADDR'] ?? '';
$window = date('Y-m-d H:i:s', time() - 600);
$count  = db_query(
    "SELECT COUNT(*) AS cnt FROM submissions WHERE ip_address = :ip AND created_at > :window",
    [':ip' => $ip, ':window' => $window]
)->fetch()['cnt'];

if ((int)$count >= 5) {
    http_response_code(429);
    exit(json_encode(['ok' => false, 'error' => 'Too many requests. Please wait a few minutes.']));
}

// Validate
$errors = [];
$name    = trim($data['name']    ?? '');
$email   = trim($data['email']   ?? '');
$checkin = trim($data['checkin'] ?? '');
$checkout= trim($data['checkout']?? '');

if (!$name)                                          $errors['name']  = 'Your name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))      $errors['email'] = 'A valid email is required.';

if ($errors) {
    http_response_code(422);
    exit(json_encode(['ok' => false, 'errors' => $errors]));
}

// Look up room or tour
$slug      = trim($data['room_slug'] ?? '');
$room      = $slug ? fetch_room_by_slug($slug) : false;
$tourSlug  = trim($data['tour_slug'] ?? '');
$tour      = $tourSlug ? fetch_tour_by_slug($tourSlug) : false;

// Tracking from session
if (session_status() === PHP_SESSION_NONE) session_start();
$tracking = $_SESSION['tracking'] ?? [];

// Insert
db_query(
    "INSERT INTO submissions
        (type, room_id, tour_id, guest_name, guest_email, guest_phone, message,
         check_in, check_out, guests_adults, guests_children,
         source_page, referrer, utm_source, utm_medium, utm_campaign, utm_term, utm_content,
         user_agent, ip_address)
     VALUES
        ('enquiry', :room_id, :tour_id, :name, :email, :phone, :message,
         :checkin, :checkout, :adults, :children,
         :source_page, :referrer, :utm_source, :utm_medium, :utm_campaign, :utm_term, :utm_content,
         :user_agent, :ip)",
    [
        ':room_id'     => $room ? $room['id'] : null,
        ':tour_id'     => $tour ? $tour['id'] : null,
        ':name'        => $name,
        ':email'       => $email,
        ':phone'       => trim($data['phone'] ?? ''),
        ':message'     => trim($data['message'] ?? ''),
        ':checkin'     => $checkin ?: null,
        ':checkout'    => $checkout ?: null,
        ':adults'      => max(1, (int)($data['adults'] ?? 1)),
        ':children'    => max(0, (int)($data['children'] ?? 0)),
        ':source_page' => $tracking['source_page'] ?? '',
        ':referrer'    => $tracking['referrer']    ?? '',
        ':utm_source'  => $tracking['utm_source']  ?? '',
        ':utm_medium'  => $tracking['utm_medium']  ?? '',
        ':utm_campaign'=> $tracking['utm_campaign']?? '',
        ':utm_term'    => $tracking['utm_term']    ?? '',
        ':utm_content' => $tracking['utm_content'] ?? '',
        ':user_agent'  => $tracking['user_agent']  ?? '',
        ':ip'          => $ip,
    ]
);

$id = (int)db()->lastInsertId();

send_notification([
    'id'         => $id,
    'type'       => 'enquiry',
    'room_name'  => $room ? $room['name'] : ($tour ? 'Tour: ' . $tour['name'] : ''),
    'guest_name' => $name,
    'guest_email'=> $email,
    'guest_phone'=> $data['phone'] ?? '',
    'message'    => $data['message'] ?? '',
    'check_in'   => $checkin,
    'check_out'  => $checkout,
    'guests_adults'   => $data['adults']   ?? 1,
    'guests_children' => $data['children'] ?? 0,
    'created_at' => date('Y-m-d H:i:s'),
] + $tracking);

echo json_encode(['ok' => true, 'id' => $id]);
