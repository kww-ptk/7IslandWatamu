<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/mail.php';
require_once __DIR__ . '/includes/booking.php';

$ref     = trim($_GET['ref'] ?? '');
$holdId  = verify_guest_ref($ref);
$hold    = null;
$error   = '';
$success = '';
$can_cancel = false;
$cancel_blocked_reason = '';

if (!$holdId) {
    $error = 'This booking link is invalid or has expired. Please check your confirmation email.';
} else {
    $hold = db_query(
        "SELECT h.*, u.name AS unit_name, r.name AS room_name, r.slug AS room_slug
         FROM holds h
         JOIN units u ON u.id = h.unit_id
         JOIN rooms r ON r.id = u.room_id
         WHERE h.id = :id",
        [':id' => $holdId]
    )->fetch();

    if (!$hold) {
        $error = 'Booking not found.';
    }
}

// Determine cancel eligibility before handling POST
if ($hold && !$error) {
    $status = $hold['status'];
    if ($status === 'pending') {
        $can_cancel = true;
    } elseif ($status === 'confirmed') {
        $days_until = (strtotime($hold['check_in']) - time()) / 86400;
        if ($days_until >= 7) {
            $can_cancel = true;
        } else {
            $cancel_blocked_reason = 'Online cancellation is only available more than 7 days before check-in. Please contact us directly.';
        }
    }
}

// Handle cancel POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $hold && $can_cancel) {
    $action = $_POST['action'] ?? '';

    if ($action === 'cancel') {
        db_query(
            "UPDATE holds SET status='cancelled', cancelled_at=NOW() WHERE id=:id AND status IN ('pending','confirmed')",
            [':id' => $holdId]
        );
        db_query(
            "DELETE FROM availability_blocks WHERE hold_id=:hid",
            [':hid' => $holdId]
        );

        // Re-fetch so emails use current state
        $hold = db_query(
            "SELECT h.*, u.name AS unit_name, r.name AS room_name, r.slug AS room_slug
             FROM holds h
             JOIN units u ON u.id = h.unit_id
             JOIN rooms r ON r.id = u.room_id
             WHERE h.id = :id",
            [':id' => $holdId]
        )->fetch();

        send_hold_cancelled($hold, 'cancelled');
        send_admin_guest_cancelled($hold);

        $can_cancel = false;
        $success    = 'Your booking has been cancelled. A confirmation email is on its way.';
    }
}

$status        = $hold['status'] ?? '';
$badge_text    = match($status) {
    'pending'   => 'Pending Confirmation',
    'confirmed' => 'Confirmed',
    'cancelled' => 'Cancelled',
    'expired'   => 'Expired',
    default     => ucfirst($status),
};
$badge_color   = match($status) {
    'pending'   => '#b45309',
    'confirmed' => '#16a34a',
    'cancelled' => '#dc2626',
    'expired'   => '#6b7280',
    default     => '#6b7280',
};
$badge_bg      = match($status) {
    'pending'   => '#fef3c7',
    'confirmed' => '#dcfce7',
    'cancelled' => '#fee2e2',
    'expired'   => '#f3f4f6',
    default     => '#f3f4f6',
};

$pageTitle    = $hold
    ? "Booking {$ref} — Seven Islands Resort"
    : 'Booking — Seven Islands Resort';
$metaDesc     = 'View and manage your hold or booking at Seven Islands Resort, Watamu.';
$headerSolid  = true;
$canonicalUrl = '';

include __DIR__ . '/includes/header.php';
?>

  <section class="page-hero" style="background:linear-gradient(rgba(11,98,115,.55),rgba(11,98,115,.65)),url('assets/img/7islands_resort_watamu1.jpg') center/cover no-repeat;min-height:220px">
    <div class="page-hero__inner">
      <p class="page-hero__eyebrow">Seven Islands Resort</p>
      <h1 class="page-hero__title">Your Booking</h1>
    </div>
  </section>

  <section class="section" style="background:#f0f4f5;min-height:60vh;padding:48px 0 80px">
    <div class="container" style="max-width:680px">

      <?php if ($error): ?>
      <div style="background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:40px 36px;text-align:center">
        <div style="font-size:48px;margin-bottom:16px">&#128274;</div>
        <h2 style="margin:0 0 12px;font-size:20px;color:#1a1a1a">Link not recognised</h2>
        <p style="color:#6b7280;line-height:1.6;margin:0 0 28px"><?= e($error) ?></p>
        <a href="rooms.php" class="btn btn--primary">View Our Rooms</a>
        <p style="margin:20px 0 0;font-size:13px;color:#9ca3af">
          Need help? Email us at
          <a href="mailto:reservation@sevenislandswatamu.com" style="color:#0b6273">reservation@sevenislandswatamu.com</a>
        </p>
      </div>

      <?php elseif ($hold): ?>

      <?php if ($success): ?>
      <div style="background:#dcfce7;border:1px solid #86efac;border-radius:8px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:flex-start;gap:12px">
        <span style="font-size:20px;flex-shrink:0">&#10003;</span>
        <p style="margin:0;color:#166534;font-size:14px;line-height:1.6"><?= e($success) ?></p>
      </div>
      <?php endif; ?>

      <!-- Booking summary card -->
      <div style="background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,.08);overflow:hidden;margin-bottom:24px">

        <!-- Header bar -->
        <div style="background:#0b6273;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
          <div>
            <p style="margin:0;color:#b2d8de;font-size:12px;font-weight:600;letter-spacing:.05em;text-transform:uppercase">Booking Reference</p>
            <p style="margin:4px 0 0;color:#fff;font-size:18px;font-weight:700;font-family:monospace;letter-spacing:.04em"><?= e($ref) ?></p>
          </div>
          <span style="background:<?= e($badge_bg) ?>;color:<?= e($badge_color) ?>;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:700">
            <?= e($badge_text) ?>
          </span>
        </div>

        <!-- Details -->
        <div style="padding:28px">
          <table style="width:100%;border-collapse:collapse">
            <tr style="border-bottom:1px solid #f3f4f6">
              <td style="padding:12px 0;color:#6b7280;font-size:13px;font-weight:600;width:110px;vertical-align:top">Guest</td>
              <td style="padding:12px 0;font-weight:700;color:#1a1a1a"><?= e($hold['guest_name']) ?></td>
            </tr>
            <tr style="border-bottom:1px solid #f3f4f6">
              <td style="padding:12px 0;color:#6b7280;font-size:13px;font-weight:600;vertical-align:top">Room</td>
              <td style="padding:12px 0;color:#1a1a1a">
                <?= e($hold['room_name']) ?>
                <?php if ($hold['unit_name']): ?>
                <span style="font-size:12px;color:#9ca3af;margin-left:6px">&middot; <?= e($hold['unit_name']) ?></span>
                <?php endif; ?>
              </td>
            </tr>
            <tr style="border-bottom:1px solid #f3f4f6">
              <td style="padding:12px 0;color:#6b7280;font-size:13px;font-weight:600;vertical-align:top">Check-in</td>
              <td style="padding:12px 0;font-weight:600;color:#1a1a1a"><?= e($hold['check_in']) ?></td>
            </tr>
            <tr style="border-bottom:1px solid #f3f4f6">
              <td style="padding:12px 0;color:#6b7280;font-size:13px;font-weight:600;vertical-align:top">Check-out</td>
              <td style="padding:12px 0;font-weight:600;color:#1a1a1a"><?= e($hold['check_out']) ?></td>
            </tr>
            <?php if ($status === 'pending' && $hold['expires_at']): ?>
            <tr>
              <td style="padding:12px 0;color:#6b7280;font-size:13px;font-weight:600;vertical-align:top">Expires</td>
              <td style="padding:12px 0;color:#b45309;font-weight:600">
                <?php
                  $diff = strtotime($hold['expires_at']) - time();
                  echo $diff > 0
                      ? 'In ' . gmdate('H\h i\m', $diff)
                      : 'Expiring soon';
                ?>
              </td>
            </tr>
            <?php elseif ($status === 'confirmed' && $hold['confirmed_at']): ?>
            <tr>
              <td style="padding:12px 0;color:#6b7280;font-size:13px;font-weight:600;vertical-align:top">Confirmed</td>
              <td style="padding:12px 0;color:#16a34a;font-weight:600"><?= date('d M Y', strtotime($hold['confirmed_at'])) ?></td>
            </tr>
            <?php endif; ?>
          </table>
        </div>

        <?php if ($status === 'pending'): ?>
        <div style="background:#fffbeb;border-top:1px solid #fde68a;padding:16px 28px">
          <p style="margin:0;font-size:13px;color:#92400e;line-height:1.6">
            <strong>Awaiting confirmation</strong> — Our team will confirm or decline your hold within 24 hours. You will receive an email either way.
          </p>
        </div>
        <?php elseif ($status === 'confirmed'): ?>
        <div style="background:#f0fdf4;border-top:1px solid #bbf7d0;padding:16px 28px">
          <p style="margin:0;font-size:13px;color:#166534;line-height:1.6">
            <strong>Your booking is confirmed!</strong> Our team will be in touch with arrival details. We look forward to welcoming you.
          </p>
        </div>
        <?php elseif ($status === 'expired'): ?>
        <div style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:16px 28px">
          <p style="margin:0;font-size:13px;color:#6b7280;line-height:1.6">
            This hold was not confirmed within the 24-hour window and has expired. Please <a href="rooms.php" style="color:#0b6273">browse available rooms</a> to make a new request.
          </p>
        </div>
        <?php elseif ($status === 'cancelled'): ?>
        <div style="background:#fef2f2;border-top:1px solid #fecaca;padding:16px 28px">
          <p style="margin:0;font-size:13px;color:#991b1b;line-height:1.6">
            This booking has been cancelled. <a href="rooms.php" style="color:#0b6273">Browse available rooms</a> if you would like to make a new request.
          </p>
        </div>
        <?php endif; ?>

      </div><!-- /card -->

      <!-- Cancel section -->
      <?php if ($can_cancel): ?>
      <div style="background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:24px 28px">
        <h3 style="margin:0 0 8px;font-size:16px;color:#1a1a1a">Need to cancel?</h3>
        <p style="margin:0 0 20px;font-size:14px;color:#6b7280;line-height:1.6">
          If your plans have changed you can cancel now. The dates will be freed and you will receive a cancellation confirmation by email.
        </p>
        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking? This cannot be undone.')">
          <input type="hidden" name="action" value="cancel">
          <button type="submit"
                  style="background:#dc2626;color:#fff;border:none;padding:12px 28px;border-radius:6px;font-size:14px;font-weight:700;cursor:pointer;width:100%;max-width:220px">
            Cancel My Booking
          </button>
        </form>
      </div>

      <?php elseif ($cancel_blocked_reason): ?>
      <div style="background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:24px 28px">
        <h3 style="margin:0 0 8px;font-size:16px;color:#1a1a1a">Need to cancel?</h3>
        <p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6"><?= e($cancel_blocked_reason) ?></p>
        <p style="margin:12px 0 0;font-size:14px;color:#6b7280">
          Email us at <a href="mailto:reservation@sevenislandswatamu.com" style="color:#0b6273">reservation@sevenislandswatamu.com</a>
        </p>
      </div>
      <?php endif; ?>

      <!-- Help footer -->
      <div style="text-align:center;margin-top:32px">
        <p style="font-size:13px;color:#9ca3af;margin:0 0 8px">Questions about your booking?</p>
        <a href="mailto:reservation@sevenislandswatamu.com" style="color:#0b6273;font-size:14px;font-weight:600">reservation@sevenislandswatamu.com</a>
        <span style="color:#d1d5db;margin:0 8px">&middot;</span>
        <a href="tel:+2540713326336" style="color:#0b6273;font-size:14px;font-weight:600">+254 0713 326 336</a>
      </div>

      <?php endif; ?>

    </div>
  </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
