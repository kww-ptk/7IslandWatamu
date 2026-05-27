<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function send_notification(array $sub): void {
    $env        = parse_env();
    $to         = setting('notify_email', 'reservation@sevenislandswatamu.com');
    $from       = $env['MAIL_FROM'] ?? 'noreply@sevenislandswatamu.com';
    $driver     = $env['MAIL_DRIVER'] ?? 'mail';
    $site_url   = rtrim($env['SITE_URL'] ?? '', '/');

    $type       = ucfirst($sub['type'] ?? 'enquiry');
    $guest      = $sub['guest_name']  ?? 'Guest';
    $room_name  = $sub['room_name']   ?? '';
    $date       = date('d M Y', strtotime($sub['created_at'] ?? 'now'));

    $subject = $room_name
        ? "[{$type}] {$room_name} — {$guest} — {$date}"
        : "[{$type}] {$guest} — {$date}";

    $body = build_email_body($sub, $site_url);

    $headers  = "From: {$from}\r\n";
    $headers .= "Reply-To: {$sub['guest_email']}\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "MIME-Version: 1.0\r\n";

    if (!empty($env['RESEND_API_KEY'])) {
        send_resend($to, $subject, $body, $from, $sub['guest_email'] ?? '', $env['RESEND_API_KEY']);
    } elseif ($driver === 'smtp') {
        send_smtp($to, $subject, $body, $headers, $env);
    } else {
        if (!mail($to, $subject, $body, $headers)) {
            log_mail_error("mail() failed for submission #{$sub['id']}");
        }
    }
}

function build_email_body(array $sub, string $site_url): string {
    $lines = [];
    $lines[] = strtoupper($sub['type'] ?? 'ENQUIRY') . ' SUBMISSION';
    $lines[] = str_repeat('-', 40);
    $lines[] = 'Name:    ' . ($sub['guest_name']  ?? '');
    $lines[] = 'Email:   ' . ($sub['guest_email'] ?? '');
    $lines[] = 'Phone:   ' . ($sub['guest_phone'] ?? '');

    if (!empty($sub['room_name']))      $lines[] = 'Room:    ' . $sub['room_name'];
    if (!empty($sub['check_in']))       $lines[] = 'Check-in:  ' . $sub['check_in'];
    if (!empty($sub['check_out']))      $lines[] = 'Check-out: ' . $sub['check_out'];
    if (!empty($sub['guests_adults']))  $lines[] = 'Adults:  ' . $sub['guests_adults'];
    if (!empty($sub['guests_children'])) $lines[] = 'Children: ' . $sub['guests_children'];

    $lines[] = '';
    $lines[] = 'Message:';
    $lines[] = $sub['message'] ?? '';
    $lines[] = '';
    $lines[] = str_repeat('-', 40);
    $lines[] = 'TRACKING';
    $lines[] = 'Source page: ' . ($sub['source_page'] ?? '');
    $lines[] = 'Referrer:    ' . ($sub['referrer']    ?? '');
    $lines[] = 'UTM source:  ' . ($sub['utm_source']  ?? '');
    $lines[] = 'UTM medium:  ' . ($sub['utm_medium']  ?? '');
    $lines[] = 'UTM campaign:' . ($sub['utm_campaign'] ?? '');
    $lines[] = '';

    if (!empty($sub['id'])) {
        $lines[] = 'View in dashboard: ' . $site_url . '/admin/submission-view.php?id=' . $sub['id'];
    }

    return implode("\n", $lines);
}

function send_resend(string $to, string $subject, string $text, string $from, string $reply_to, string $api_key): void {
    $payload = json_encode([
        'from'     => $from,
        'to'       => [$to],
        'reply_to' => $reply_to ?: null,
        'subject'  => $subject,
        'text'     => $text,
    ]);

    $ctx = stream_context_create(['http' => [
        'method'        => 'POST',
        'header'        => implode("\r\n", [
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
        ]),
        'content'       => $payload,
        'ignore_errors' => true,
    ]]);

    $result = @file_get_contents('https://api.resend.com/emails', false, $ctx);
    $status = isset($http_response_header) ? (int)explode(' ', $http_response_header[0])[1] : 0;

    if ($status !== 200 && $status !== 201) {
        log_mail_error("Resend API error {$status}: {$result}");
    }
}

function send_smtp(string $to, string $subject, string $body, string $headers, array $env): void {
    log_mail_error('SMTP driver selected but not implemented. Set RESEND_API_KEY instead.');
    if (!mail($to, $subject, $body, $headers)) {
        log_mail_error("mail() fallback also failed for: {$to}");
    }
}

// ── Hold notifications ──────────────────────────────────────────

function send_hold_notification(array $hold): void {
    $env    = parse_env();
    $to     = setting('notify_email', 'reservation@sevenislandswatamu.com');
    $from   = $env['MAIL_FROM'] ?? 'noreply@sevenislandswatamu.com';
    $site   = rtrim($env['SITE_URL'] ?? '', '/');

    $subject = "[Hold Request] {$hold['room_name']} ({$hold['unit_name']}) — {$hold['guest_name']} — {$hold['check_in']} to {$hold['check_out']}";
    $expires = isset($hold['expires_at']) ? date('d M Y H:i', strtotime($hold['expires_at'])) : '24 hours';

    $body = implode("\n", [
        'NEW HOLD REQUEST — 24-HOUR SOFT HOLD',
        str_repeat('-', 40),
        "Guest:     {$hold['guest_name']}",
        "Email:     {$hold['guest_email']}",
        "Room:      {$hold['room_name']} ({$hold['unit_name']})",
        "Check-in:  {$hold['check_in']}",
        "Check-out: {$hold['check_out']}",
        "Expires:   {$expires}",
        '',
        'Confirm or cancel within 24 hours — hold expires automatically.',
        '',
        "Manage holds: {$site}/admin/holds.php",
    ]);

    _dispatch_mail($to, $subject, $body, $from, $hold['guest_email'] ?? '', $env);
}

function send_hold_confirmed(array $hold): void {
    $env  = parse_env();
    $from = $env['MAIL_FROM'] ?? 'noreply@sevenislandswatamu.com';

    $subject = "Booking Confirmed — {$hold['room_name']} — {$hold['check_in']} to {$hold['check_out']}";
    $body = implode("\n", [
        "Dear {$hold['guest_name']},",
        '',
        'We are delighted to confirm your booking at Seven Islands Resort, Watamu.',
        '',
        "Room:      {$hold['room_name']}",
        "Check-in:  {$hold['check_in']}",
        "Check-out: {$hold['check_out']}",
        '',
        'Our team will be in touch shortly with arrival details.',
        '',
        'Warm regards,',
        'Seven Islands Resort',
        'reservation@sevenislandswatamu.com',
    ]);

    _dispatch_mail($hold['guest_email'], $subject, $body, $from, $from, $env);
}

function send_hold_cancelled(array $hold, string $reason = 'cancelled'): void {
    $env  = parse_env();
    $from = $env['MAIL_FROM'] ?? 'noreply@sevenislandswatamu.com';

    $is_expired = $reason === 'expired';
    $subject    = $is_expired
        ? "Hold Expired — {$hold['room_name']} — {$hold['check_in']}"
        : "Hold Cancelled — {$hold['room_name']} — {$hold['check_in']}";

    $body = implode("\n", [
        "Dear {$hold['guest_name']},",
        '',
        $is_expired
            ? "Unfortunately we were unable to confirm your hold request for {$hold['room_name']} within the 24-hour window."
            : "Your hold request for {$hold['room_name']} has been cancelled.",
        '',
        "Dates: {$hold['check_in']} to {$hold['check_out']}",
        '',
        'Please contact us to check alternative availability:',
        'Email: reservation@sevenislandswatamu.com',
        '',
        'Warm regards,',
        'Seven Islands Resort',
    ]);

    _dispatch_mail($hold['guest_email'], $subject, $body, $from, $from, $env);
}

function _dispatch_mail(string $to, string $subject, string $body, string $from, string $reply_to, array $env): void {
    $headers  = "From: {$from}\r\n";
    $headers .= "Reply-To: {$reply_to}\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "MIME-Version: 1.0\r\n";

    if (!empty($env['RESEND_API_KEY'])) {
        send_resend($to, $subject, $body, $from, $reply_to, $env['RESEND_API_KEY']);
    } elseif (($env['MAIL_DRIVER'] ?? '') === 'smtp') {
        send_smtp($to, $subject, $body, $headers, $env);
    } else {
        if (!mail($to, $subject, $body, $headers)) {
            log_mail_error("mail() failed sending '{$subject}' to {$to}");
        }
    }
}

function log_mail_error(string $message): void {
    $log = __DIR__ . '/../logs/mail.log';
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents($log, $line, FILE_APPEND | LOCK_EX);
}
