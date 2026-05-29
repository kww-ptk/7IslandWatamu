<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function send_notification(array $sub): void {
    $env       = parse_env();
    $to        = setting('notify_email', 'reservation@sevenislandswatamu.com');
    $from      = $env['MAIL_FROM'] ?? 'noreply@sevenislandswatamu.com';
    $site_url  = rtrim($env['SITE_URL'] ?? '', '/');

    $label     = notification_label($sub);
    $guest     = $sub['guest_name'] ?? 'Guest';
    $room_name = $sub['room_name']  ?? '';
    $date      = date('d M Y', strtotime($sub['created_at'] ?? 'now'));

    $subject = $room_name
        ? "[{$label}] {$room_name} — {$guest} — {$date}"
        : "[{$label}] {$guest} — {$date}";

    $text = build_email_body($sub, $site_url);
    $html = build_email_html($sub, $site_url);

    _dispatch_mail($to, $subject, $text, $from, $sub['guest_email'] ?? $from, $env, $html);
}

// Auto-reply confirming receipt, sent to the person who submitted a public form.
// $a: guest_name, guest_email, kind (enquiry|hold|contact|agency), and any of
// room_name / check_in / check_out / agency_name / subject / message for the summary.
function send_guest_acknowledgement(array $a): void {
    $to = trim((string)($a['guest_email'] ?? ''));
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) return;

    $env   = parse_env();
    $from  = $env['MAIL_FROM'] ?? 'noreply@sevenislandswatamu.com';
    $reply = setting('notify_email', 'reservation@sevenislandswatamu.com');
    $site  = rtrim($env['SITE_URL'] ?? '', '/');
    $name  = trim((string)($a['guest_name'] ?? '')) ?: 'Guest';

    [$subject, $intro] = match ($a['kind'] ?? 'enquiry') {
        'hold'    => ['We\'ve received your booking request — Seven Islands Resort',
                      'Thank you for your booking request. We are holding your selected dates for 24 hours while our reservations team confirms availability — you will receive a separate confirmation email shortly.'],
        'contact' => ['We\'ve received your message — Seven Islands Resort',
                      'Thank you for getting in touch. We have received your message and a member of our team will reply as soon as possible.'],
        'agency'  => ['We\'ve received your enquiry — Seven Islands Resort',
                      'Thank you for your interest in working with us. We have received your travel agency enquiry and our team will be in touch shortly.'],
        default   => ['We\'ve received your enquiry — Seven Islands Resort',
                      'Thank you for your enquiry. We have received your message and a member of our reservations team will get back to you as soon as possible.'],
    };

    $rows = [];
    foreach (['room_name' => 'Room', 'check_in' => 'Check-in', 'check_out' => 'Check-out',
              'agency_name' => 'Agency', 'subject' => 'Subject'] as $key => $label) {
        if (!empty($a[$key])) $rows[] = [$label, (string)$a[$key]];
    }

    $tl = ["Dear {$name},", '', $intro, ''];
    if ($rows) {
        $tl[] = 'YOUR DETAILS';
        foreach ($rows as [$k, $v]) $tl[] = "  {$k}: {$v}";
        $tl[] = '';
    }
    if (!empty($a['message'])) {
        $tl[] = 'Your message:';
        $tl[] = (string)$a['message'];
        $tl[] = '';
    }
    $tl[] = "If your enquiry is urgent you can reply to this email or write to {$reply}.";
    $tl[] = '';
    $tl[] = 'Warm regards,';
    $tl[] = 'Seven Islands Resort';
    $tl[] = 'Watamu, Kenya';
    if ($site) $tl[] = $site;
    $text = implode("\n", $tl);

    $html = _guest_ack_html([
        'name'    => $name,
        'intro'   => $intro,
        'rows'    => $rows,
        'message' => (string)($a['message'] ?? ''),
        'reply'   => $reply,
        'site'    => $site,
    ]);

    _dispatch_mail($to, $subject, $text, $from, $reply, $env, $html);
}

function _guest_ack_html(array $d): string {
    $esc  = fn(string $v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    $site = rtrim($d['site'] ?? '', '/');

    $detail_rows = '';
    foreach ($d['rows'] as [$k, $v]) {
        $detail_rows .= '<tr>'
            . '<td style="padding:7px 0;color:#777;font-size:13px;width:110px;vertical-align:top">' . $esc($k) . '</td>'
            . '<td style="padding:7px 0;font-size:14px;color:#222;font-weight:600">' . $esc($v) . '</td>'
            . '</tr>';
    }
    $detail_block = $detail_rows
        ? '<div style="background:#f0f9fa;border-radius:6px;padding:18px 22px;margin:20px 0">'
            . '<p style="margin:0 0 10px;font-size:12px;font-weight:700;text-transform:uppercase;color:#0b6273;letter-spacing:.5px">Your details</p>'
            . '<table style="width:100%;border-collapse:collapse">' . $detail_rows . '</table>'
          . '</div>'
        : '';

    $message_block = '';
    if ($d['message'] !== '') {
        $message_block = '<div style="margin:20px 0">'
            . '<p style="margin:0 0 8px;font-size:12px;font-weight:700;text-transform:uppercase;color:#777;letter-spacing:.5px">Your message</p>'
            . '<div style="background:#f9fafb;border-left:3px solid #0b6273;padding:14px 18px;border-radius:0 4px 4px 0;font-size:14px;color:#333;line-height:1.7">'
            . nl2br($esc($d['message']))
            . '</div></div>';
    }

    return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>'
        . '<body style="margin:0;padding:0;background:#f0f4f5;font-family:Arial,Helvetica,sans-serif">'
        . '<div style="max-width:600px;margin:32px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)">'
          . '<div style="background:#0b6273;padding:24px 32px">'
            . '<h1 style="margin:0;color:#fff;font-size:20px;font-weight:700">Thank you for contacting us</h1>'
            . '<p style="margin:6px 0 0;color:#b2d8de;font-size:14px">Seven Islands Resort, Watamu &mdash; Kenya</p>'
          . '</div>'
          . '<div style="padding:32px">'
            . '<p style="margin:0 0 18px;font-size:15px">Dear <strong>' . $esc($d['name']) . '</strong>,</p>'
            . '<p style="margin:0 0 4px;font-size:15px;line-height:1.6">' . $esc($d['intro']) . '</p>'
            . $detail_block
            . $message_block
            . '<p style="font-size:13px;color:#777;line-height:1.6;margin-top:24px">If your enquiry is urgent you can simply reply to this email, or write to '
              . '<a href="mailto:' . $esc($d['reply']) . '" style="color:#0b6273">' . $esc($d['reply']) . '</a>.</p>'
            . '<p style="font-size:14px;margin:24px 0 0">Warm regards,<br><strong>Seven Islands Resort</strong></p>'
          . '</div>'
          . '<div style="background:#f9fafb;padding:16px 32px;text-align:center;font-size:12px;color:#aaa">'
            . '<a href="' . $esc($site ?: '#') . '" style="color:#0b6273;text-decoration:none">sevenislandswatamu.com</a>'
          . '</div>'
        . '</div></body></html>';
}

// Human-friendly heading for a submission, used in subject line + email header.
function notification_label(array $sub): string {
    if (!empty($sub['label'])) return $sub['label'];
    $type = $sub['type'] ?? 'enquiry';
    return match ($type) {
        'agency'  => 'Travel Agency Enquiry',
        'contact' => 'Contact Message',
        'enquiry' => !empty($sub['room_name']) ? 'Booking Enquiry' : 'General Enquiry',
        default   => ucfirst($type) . ' Submission',
    };
}

// The contact-type "subject" field means different things per form.
function subject_label(array $sub): string {
    $label = strtolower($sub['label'] ?? '');
    if (str_contains($label, 'spa'))  return 'Preferred date';
    if (str_contains($label, 'tour')) return 'Interested in';
    return 'Subject';
}

function build_email_body(array $sub, string $site_url): string {
    $lines = [];
    $lines[] = strtoupper(notification_label($sub));
    $lines[] = str_repeat('-', 40);
    $lines[] = 'Name:    ' . ($sub['guest_name']  ?? '');
    $lines[] = 'Email:   ' . ($sub['guest_email'] ?? '');
    if (!empty($sub['guest_phone'])) $lines[] = 'Phone:   ' . $sub['guest_phone'];

    if (!empty($sub['room_name']))       $lines[] = 'Room:    ' . $sub['room_name'];
    if (!empty($sub['subject']))         $lines[] = subject_label($sub) . ': ' . $sub['subject'];
    if (!empty($sub['agency_name']))     $lines[] = 'Agency:  ' . $sub['agency_name'];
    if (!empty($sub['iata']))            $lines[] = 'IATA:    ' . $sub['iata'];
    if (!empty($sub['country']))         $lines[] = 'Country: ' . $sub['country'];
    if (!empty($sub['check_in']))        $lines[] = 'Check-in:  ' . $sub['check_in'];
    if (!empty($sub['check_out']))       $lines[] = 'Check-out: ' . $sub['check_out'];
    if (!empty($sub['guests_adults']))   $lines[] = 'Adults:  ' . $sub['guests_adults'];
    if (!empty($sub['guests_children'])) $lines[] = 'Children: ' . $sub['guests_children'];

    if (!empty($sub['message'])) {
        $lines[] = '';
        $lines[] = 'Message:';
        $lines[] = $sub['message'];
    }
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

function _email_row(string $key, string $val, array $opt = []): string {
    if ($val === '') return '';
    $esc     = fn(string $v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    $display = !empty($opt['raw']) ? $val : $esc($val);
    $weight  = !empty($opt['bold']) ? ';font-weight:700' : '';
    return '<tr>'
        . '<td style="padding:7px 0;color:#777;font-size:13px;width:120px;vertical-align:top">' . $esc($key) . '</td>'
        . '<td style="padding:7px 0;font-size:14px;color:#222' . $weight . '">' . $display . '</td>'
        . '</tr>';
}

function _email_track(string $key, string $val): string {
    if ($val === '') return '';
    $esc = fn(string $v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    return '<tr>'
        . '<td style="padding:3px 0;color:#bbb;font-size:11px;width:110px;vertical-align:top">' . $esc($key) . '</td>'
        . '<td style="padding:3px 0;color:#999;font-size:11px;word-break:break-all">' . $esc($val) . '</td>'
        . '</tr>';
}

function build_email_html(array $sub, string $site_url): string {
    $esc     = fn(string $v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    $label   = notification_label($sub);
    $created = date('d M Y \a\t H:i', strtotime($sub['created_at'] ?? 'now'));

    // Guest details
    $guest_rows  = _email_row('Name', (string)($sub['guest_name'] ?? ''), ['bold' => true]);
    if (!empty($sub['guest_email'])) {
        $guest_rows .= _email_row('Email',
            '<a href="mailto:' . $esc($sub['guest_email']) . '" style="color:#0b6273;text-decoration:none">' . $esc($sub['guest_email']) . '</a>',
            ['raw' => true]);
    }
    $guest_rows .= _email_row('Phone', (string)($sub['guest_phone'] ?? ''));

    // Context details (only the rows that apply to this submission type)
    $detail_rows  = _email_row('Room',                 (string)($sub['room_name']   ?? ''));
    $detail_rows .= _email_row(subject_label($sub),    (string)($sub['subject']     ?? ''));
    $detail_rows .= _email_row('Agency',               (string)($sub['agency_name'] ?? ''));
    $detail_rows .= _email_row('IATA',                 (string)($sub['iata']        ?? ''));
    $detail_rows .= _email_row('Country',              (string)($sub['country']     ?? ''));
    $detail_rows .= _email_row('Check-in',             (string)($sub['check_in']    ?? ''));
    $detail_rows .= _email_row('Check-out',            (string)($sub['check_out']   ?? ''));
    if (!empty($sub['guests_adults']))   $detail_rows .= _email_row('Adults',   (string)$sub['guests_adults']);
    if (!empty($sub['guests_children'])) $detail_rows .= _email_row('Children', (string)$sub['guests_children']);

    $detail_block = $detail_rows
        ? '<div style="background:#f0f9fa;border-radius:6px;padding:18px 22px;margin:20px 0">'
            . '<p style="margin:0 0 10px;font-size:12px;font-weight:700;text-transform:uppercase;color:#0b6273;letter-spacing:.5px">Details</p>'
            . '<table style="width:100%;border-collapse:collapse">' . $detail_rows . '</table>'
          . '</div>'
        : '';

    // Message
    $message_block = '';
    if (!empty($sub['message'])) {
        $message_block =
            '<div style="margin:20px 0">'
            . '<p style="margin:0 0 8px;font-size:12px;font-weight:700;text-transform:uppercase;color:#777;letter-spacing:.5px">Message</p>'
            . '<div style="background:#f9fafb;border-left:3px solid #0b6273;padding:14px 18px;border-radius:0 4px 4px 0;font-size:14px;color:#333;line-height:1.7">'
            . nl2br($esc($sub['message']))
            . '</div></div>';
    }

    // Call to action
    $cta_block = '';
    if (!empty($sub['id']) && $site_url) {
        $cta_block =
            '<div style="margin:28px 0;text-align:center">'
            . '<a href="' . $esc($site_url . '/admin/submission-view.php?id=' . $sub['id']) . '" '
            . 'style="background:#0b6273;color:#fff;padding:13px 26px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:700;display:inline-block">'
            . 'View in Dashboard &rarr;</a></div>';
    }

    // Tracking (subtle)
    $track_rows  = _email_track('Source page',  (string)($sub['source_page']  ?? ''));
    $track_rows .= _email_track('Referrer',     (string)($sub['referrer']     ?? ''));
    $track_rows .= _email_track('UTM source',   (string)($sub['utm_source']   ?? ''));
    $track_rows .= _email_track('UTM medium',   (string)($sub['utm_medium']   ?? ''));
    $track_rows .= _email_track('UTM campaign', (string)($sub['utm_campaign'] ?? ''));
    $track_block = '<div style="border-top:1px solid #eee;margin-top:24px;padding-top:16px">'
        . '<p style="margin:0 0 6px;font-size:11px;font-weight:700;text-transform:uppercase;color:#bbb;letter-spacing:.5px">Tracking</p>'
        . ($track_rows
            ? '<table style="width:100%;border-collapse:collapse">' . $track_rows . '</table>'
            : '<p style="margin:0;font-size:11px;color:#ccc">No tracking data captured.</p>')
        . '</div>';

    return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>'
        . '<body style="margin:0;padding:0;background:#f0f4f5;font-family:Arial,Helvetica,sans-serif">'
        . '<div style="max-width:600px;margin:32px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)">'
          . '<div style="background:#0b6273;padding:24px 32px">'
            . '<h1 style="margin:0;color:#fff;font-size:20px;font-weight:700">' . $esc($label) . '</h1>'
            . '<p style="margin:6px 0 0;color:#b2d8de;font-size:14px">New submission &middot; ' . $esc($created) . '</p>'
          . '</div>'
          . '<div style="padding:32px">'
            . '<p style="margin:0 0 8px;font-size:12px;font-weight:700;text-transform:uppercase;color:#777;letter-spacing:.5px">Guest</p>'
            . '<table style="width:100%;border-collapse:collapse">' . $guest_rows . '</table>'
            . $detail_block
            . $message_block
            . $cta_block
            . $track_block
          . '</div>'
          . '<div style="background:#f9fafb;padding:16px 32px;text-align:center;font-size:12px;color:#aaa">'
            . '<a href="' . $esc($site_url ?: '#') . '" style="color:#0b6273;text-decoration:none">sevenislandswatamu.com</a>'
          . '</div>'
        . '</div></body></html>';
}

function send_resend(string $to, string $subject, string $text, string $from, string $reply_to, string $api_key, string $html = ''): void {
    $body = [
        'from'     => $from,
        'to'       => [$to],
        'reply_to' => $reply_to ?: null,
        'subject'  => $subject,
        'text'     => $text,
    ];
    if ($html !== '') $body['html'] = $html;
    $payload = json_encode($body);

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
    require_once __DIR__ . '/booking.php';

    $env     = parse_env();
    $to      = setting('notify_email', 'reservation@sevenislandswatamu.com');
    $from    = $env['MAIL_FROM'] ?? 'noreply@sevenislandswatamu.com';
    $site    = rtrim($env['SITE_URL'] ?? '', '/');
    $holdId  = (int)$hold['id'];
    $expires = isset($hold['expires_at']) ? date('d M Y H:i', strtotime($hold['expires_at'])) . ' (UTC+3)' : '24 hours';

    $subject = "[Hold Request] {$hold['room_name']} — {$hold['guest_name']} — {$hold['check_in']} to {$hold['check_out']}";

    // Build action URLs if token secret is configured
    $confirm_url = $site . '/admin/holds.php';
    $decline_url = $site . '/admin/holds.php';
    $has_tokens  = false;
    $ct = make_hold_token($holdId, 'confirm');
    $dt = make_hold_token($holdId, 'decline');
    if ($ct && $dt) {
        $confirm_url = $site . '/admin/hold-action.php?id=' . $holdId . '&action=confirm&t=' . urlencode($ct);
        $decline_url = $site . '/admin/hold-action.php?id=' . $holdId . '&action=decline&t=' . urlencode($dt);
        $has_tokens  = true;
    }

    // Plain-text fallback (all mail drivers)
    $text_lines = [
        'NEW HOLD REQUEST — 24-HOUR SOFT HOLD',
        str_repeat('-', 40),
        "Guest:     {$hold['guest_name']}",
        "Email:     {$hold['guest_email']}",
        "Room:      {$hold['room_name']} ({$hold['unit_name']})",
        "Check-in:  {$hold['check_in']}",
        "Check-out: {$hold['check_out']}",
        "Expires:   {$expires}",
        '',
    ];
    if ($has_tokens) {
        $text_lines[] = "CONFIRM: {$confirm_url}";
        $text_lines[] = "DECLINE: {$decline_url}";
    } else {
        $text_lines[] = "Manage holds: {$site}/admin/holds.php";
        $text_lines[] = '(Set BOOKING_TOKEN_SECRET in .env to enable one-click confirm/decline buttons.)';
    }
    $text = implode("\n", $text_lines);

    // HTML email with action buttons
    $html = _hold_notification_html([
        'guest_name'  => $hold['guest_name'],
        'guest_email' => $hold['guest_email'],
        'room_name'   => $hold['room_name'],
        'unit_name'   => $hold['unit_name'],
        'check_in'    => $hold['check_in'],
        'check_out'   => $hold['check_out'],
        'expires'     => $expires,
        'confirm_url' => $confirm_url,
        'decline_url' => $decline_url,
        'holds_url'   => $site . '/admin/holds.php',
        'has_tokens'  => $has_tokens,
    ]);

    _dispatch_mail($to, $subject, $text, $from, $hold['guest_email'] ?? '', $env, $html);
}

function _hold_notification_html(array $d): string {
    $esc = fn(string $v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    $btn = fn(string $url, string $label, string $bg) =>
        '<a href="' . $esc($url) . '" style="background:' . $bg . ';color:#fff;padding:14px 28px;border-radius:6px;'
        . 'text-decoration:none;font-size:15px;font-weight:700;display:inline-block;margin:0 6px;mso-padding-alt:0">'
        . $label . '</a>';

    $action_block = $d['has_tokens']
        ? '<div style="margin:32px 0;text-align:center">'
            . $btn($d['confirm_url'], '&#10003; Confirm Hold', '#16a34a')
            . $btn($d['decline_url'], '&#10007; Decline', '#dc2626')
          . '</div>'
        : '<div style="margin:24px 0;text-align:center">'
            . $btn($d['holds_url'], 'Open Holds &amp; Bookings', '#0b6273')
          . '</div>'
          . '<p style="font-size:12px;color:#999;text-align:center">Set BOOKING_TOKEN_SECRET in .env to enable one-click email buttons.</p>';

    return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>'
        . '<body style="margin:0;padding:0;background:#f0f4f5;font-family:Arial,Helvetica,sans-serif">'
        . '<div style="max-width:600px;margin:32px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)">'
          . '<div style="background:#0b6273;padding:24px 32px">'
            . '<h1 style="margin:0;color:#fff;font-size:20px;font-weight:700">New Hold Request</h1>'
            . '<p style="margin:6px 0 0;color:#b2d8de;font-size:14px">24-hour soft hold &mdash; please confirm or decline</p>'
          . '</div>'
          . '<div style="padding:32px">'
            . '<table style="width:100%;border-collapse:collapse;margin-bottom:8px">'
              . '<tr><td style="padding:8px 0;color:#777;font-size:13px;width:90px;vertical-align:top">Guest</td>'
                  . '<td style="padding:8px 0;font-weight:700">' . $esc($d['guest_name']) . '</td></tr>'
              . '<tr><td style="padding:8px 0;color:#777;font-size:13px">Email</td>'
                  . '<td style="padding:8px 0"><a href="mailto:' . $esc($d['guest_email']) . '" style="color:#0b6273">'
                  . $esc($d['guest_email']) . '</a></td></tr>'
              . '<tr><td style="padding:8px 0;color:#777;font-size:13px">Room</td>'
                  . '<td style="padding:8px 0">' . $esc($d['room_name']) . ' &middot; ' . $esc($d['unit_name']) . '</td></tr>'
              . '<tr><td style="padding:8px 0;color:#777;font-size:13px">Check-in</td>'
                  . '<td style="padding:8px 0;font-weight:600">' . $esc($d['check_in']) . '</td></tr>'
              . '<tr><td style="padding:8px 0;color:#777;font-size:13px">Check-out</td>'
                  . '<td style="padding:8px 0;font-weight:600">' . $esc($d['check_out']) . '</td></tr>'
              . '<tr><td style="padding:8px 0;color:#777;font-size:13px">Expires</td>'
                  . '<td style="padding:8px 0;color:#b45309;font-weight:600">' . $esc($d['expires']) . '</td></tr>'
            . '</table>'
            . $action_block
            . '<p style="font-size:12px;color:#aaa;text-align:center;margin:24px 0 0">'
              . 'Links require admin login &mdash; hold expires automatically if not actioned.<br>'
              . '<a href="' . $esc($d['holds_url']) . '" style="color:#0b6273">Manage all holds &rarr;</a>'
            . '</p>'
          . '</div>'
        . '</div>'
        . '</body></html>';
}

function send_hold_confirmed(array $hold): void {
    require_once __DIR__ . '/booking.php';

    $env  = parse_env();
    $from = $env['MAIL_FROM'] ?? 'noreply@sevenislandswatamu.com';
    $site = rtrim($env['SITE_URL'] ?? '', '/');

    $ref        = make_guest_ref((int)$hold['id']);
    $manage_url = $ref ? $site . '/booking.php?ref=' . urlencode($ref) : '';

    $subject = "Booking Confirmed — {$hold['room_name']} — {$hold['check_in']} to {$hold['check_out']}";

    $checkin_instructions = setting('checkin_instructions', '');

    $text_lines = [
        "Dear {$hold['guest_name']},",
        '',
        'We are delighted to confirm your booking at Seven Islands Resort, Watamu.',
        '',
        "Reference:  {$ref}",
        "Room:       {$hold['room_name']}",
        "Check-in:   {$hold['check_in']}",
        "Check-out:  {$hold['check_out']}",
        '',
    ];
    if ($checkin_instructions) {
        $text_lines[] = 'CHECK-IN INFORMATION';
        $text_lines[] = $checkin_instructions;
        $text_lines[] = '';
    }
    $text_lines[] = 'Our team will be in touch if you have any further questions.';
    $text_lines[] = '';
    if ($manage_url) {
        $text_lines[] = 'View or manage your booking:';
        $text_lines[] = $manage_url;
        $text_lines[] = '';
    }
    $text_lines[] = 'Warm regards,';
    $text_lines[] = 'Seven Islands Resort';
    $text_lines[] = 'reservation@sevenislandswatamu.com';

    $body = implode("\n", $text_lines);
    $html = _hold_confirmed_html([
        'guest_name'           => $hold['guest_name'],
        'room_name'            => $hold['room_name'],
        'unit_name'            => $hold['unit_name'] ?? '',
        'check_in'             => $hold['check_in'],
        'check_out'            => $hold['check_out'],
        'ref'                  => $ref,
        'manage_url'           => $manage_url,
        'site'                 => $site,
        'checkin_instructions' => $checkin_instructions,
    ]);

    _dispatch_mail($hold['guest_email'], $subject, $body, $from, $from, $env, $html);
}

function _hold_confirmed_html(array $d): string {
    $esc  = fn(string $v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    $site = rtrim($d['site'] ?? '', '/');

    $manage_block = '';
    if ($d['manage_url']) {
        $manage_block =
            '<div style="margin:28px 0;text-align:center">'
            . '<a href="' . $esc($d['manage_url']) . '" style="background:#0b6273;color:#fff;padding:14px 28px;'
            . 'border-radius:6px;text-decoration:none;font-size:15px;font-weight:700;display:inline-block">'
            . 'View Your Booking &rarr;</a>'
            . '</div>';
    }

    return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>'
        . '<body style="margin:0;padding:0;background:#f0f4f5;font-family:Arial,Helvetica,sans-serif">'
        . '<div style="max-width:600px;margin:32px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)">'
          . '<div style="background:#0b6273;padding:24px 32px">'
            . '<h1 style="margin:0;color:#fff;font-size:20px;font-weight:700">Booking Confirmed</h1>'
            . '<p style="margin:6px 0 0;color:#b2d8de;font-size:14px">Seven Islands Resort, Watamu &mdash; Kenya</p>'
          . '</div>'
          . '<div style="padding:32px">'
            . '<p style="margin:0 0 20px;font-size:15px">Dear <strong>' . $esc($d['guest_name']) . '</strong>,</p>'
            . '<p style="margin:0 0 20px;font-size:15px;line-height:1.6">We are delighted to confirm your booking. Everything is set — we look forward to welcoming you!</p>'
            . '<div style="background:#f0f9fa;border-radius:6px;padding:20px 24px;margin-bottom:8px">'
              . '<table style="width:100%;border-collapse:collapse">'
                . ($d['ref'] ? '<tr><td style="padding:7px 0;color:#777;font-size:13px;width:100px">Reference</td>'
                    . '<td style="padding:7px 0;font-weight:700;font-family:monospace;font-size:14px;color:#0b6273">' . $esc($d['ref']) . '</td></tr>' : '')
                . '<tr><td style="padding:7px 0;color:#777;font-size:13px">Room</td>'
                    . '<td style="padding:7px 0;font-weight:600">' . $esc($d['room_name']) . '</td></tr>'
                . '<tr><td style="padding:7px 0;color:#777;font-size:13px">Check-in</td>'
                    . '<td style="padding:7px 0;font-weight:600">' . $esc($d['check_in']) . '</td></tr>'
                . '<tr><td style="padding:7px 0;color:#777;font-size:13px">Check-out</td>'
                    . '<td style="padding:7px 0;font-weight:600">' . $esc($d['check_out']) . '</td></tr>'
              . '</table>'
            . '</div>'
            . $manage_block
            . (!empty($d['checkin_instructions'])
                ? '<div style="background:#f0f9fa;border-left:3px solid #0b6273;padding:14px 18px;margin:20px 0;border-radius:0 4px 4px 0">'
                  . '<p style="margin:0 0 6px;font-size:12px;font-weight:700;text-transform:uppercase;color:#0b6273;letter-spacing:.5px">Check-in Information</p>'
                  . '<p style="margin:0;font-size:13px;color:#444;line-height:1.7;white-space:pre-line">'
                  . htmlspecialchars($d['checkin_instructions'], ENT_QUOTES, 'UTF-8')
                  . '</p></div>'
                : '')
            . '<p style="font-size:13px;color:#777;line-height:1.6;margin-top:24px">If you have any questions, please email us at <a href="mailto:reservation@sevenislandswatamu.com" style="color:#0b6273">reservation@sevenislandswatamu.com</a>.</p>'
            . '<p style="font-size:14px;margin:24px 0 0">Warm regards,<br><strong>Seven Islands Resort</strong></p>'
          . '</div>'
          . '<div style="background:#f9fafb;padding:16px 32px;text-align:center;font-size:12px;color:#aaa">'
            . '<a href="' . $esc($site) . '" style="color:#0b6273">sevenislandswatamu.com</a>'
          . '</div>'
        . '</div>'
        . '</body></html>';
}

function send_admin_guest_cancelled(array $hold): void {
    $env  = parse_env();
    $to   = setting('notify_email', 'reservation@sevenislandswatamu.com');
    $from = $env['MAIL_FROM'] ?? 'noreply@sevenislandswatamu.com';
    $site = rtrim($env['SITE_URL'] ?? '', '/');

    $subject = "[Guest Cancelled] {$hold['room_name']} — {$hold['guest_name']} — {$hold['check_in']} to {$hold['check_out']}";
    $body = implode("\n", [
        'A GUEST HAS CANCELLED THEIR OWN BOOKING',
        str_repeat('-', 40),
        "Guest:     {$hold['guest_name']}",
        "Email:     {$hold['guest_email']}",
        "Room:      {$hold['room_name']}",
        "Check-in:  {$hold['check_in']}",
        "Check-out: {$hold['check_out']}",
        '',
        'The dates have been freed and the guest has been notified.',
        '',
        "View holds: {$site}/admin/holds.php",
    ]);

    _dispatch_mail($to, $subject, $body, $from, $hold['guest_email'] ?? $from, $env);
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

function _dispatch_mail(string $to, string $subject, string $body, string $from, string $reply_to, array $env, string $html = ''): void {
    $headers  = "From: {$from}\r\n";
    $headers .= "Reply-To: {$reply_to}\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "MIME-Version: 1.0\r\n";

    if (!empty($env['RESEND_API_KEY'])) {
        send_resend($to, $subject, $body, $from, $reply_to, $env['RESEND_API_KEY'], $html);
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
