<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'save_general') {
        $form_mode     = in_array($_POST['form_mode'] ?? '', ['enquiry', 'availability']) ? $_POST['form_mode'] : 'enquiry';
        $notify_email  = trim($_POST['notify_email']  ?? '');
        $site_currency = trim($_POST['site_currency'] ?? 'USD');

        if ($notify_email && !filter_var($notify_email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid notification email.';
        } else {
            set_setting('form_mode',     $form_mode);
            set_setting('notify_email',  $notify_email);
            set_setting('site_currency', $site_currency);
            $success = 'Settings saved.';
        }
    }

    if ($action === 'change_password') {
        $current  = $_POST['current_password']  ?? '';
        $new      = $_POST['new_password']       ?? '';
        $confirm  = $_POST['confirm_password']   ?? '';

        $admin = current_admin();
        $user  = db_query('SELECT * FROM admin_users WHERE id = :id', [':id' => $admin['id']])->fetch();

        if (!password_verify($current, $user['password_hash'])) {
            $error = 'Current password is incorrect.';
        } elseif (strlen($new) < 8) {
            $error = 'New password must be at least 8 characters.';
        } elseif ($new !== $confirm) {
            $error = 'New passwords do not match.';
        } else {
            db_query(
                'UPDATE admin_users SET password_hash = :hash WHERE id = :id',
                [':hash' => password_hash($new, PASSWORD_BCRYPT), ':id' => $admin['id']]
            );
            $success = 'Password changed successfully.';
        }
    }
}

$form_mode     = setting('form_mode',     'enquiry');
$notify_email  = setting('notify_email',  '');
$site_currency = setting('site_currency', 'USD');

$pageTitle  = 'Settings';
$activeMenu = 'settings';
include __DIR__ . '/_layout.php';
?>

<div class="page-header">
  <h1>Settings</h1>
</div>

<?php if ($success): ?><div class="alert alert--success"><?= e($success) ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert alert--error"><?= e($error) ?></div><?php endif; ?>

<!-- General settings -->
<div class="card">
  <div class="card__head"><span class="card__title">General</span></div>
  <div class="card__body" style="padding:20px">
    <form method="POST" action="/admin/settings.php">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_general">

      <div class="form-section">
        <div class="form-section__title">Booking Form Mode</div>
        <p style="font-size:13px;color:var(--muted);margin-bottom:14px">
          Controls which form appears on all room pages. Changing this takes effect immediately on the public site.
        </p>
        <label style="display:flex;align-items:flex-start;gap:12px;margin-bottom:12px;cursor:pointer">
          <input type="radio" name="form_mode" value="enquiry" <?= $form_mode==='enquiry'?'checked':'' ?> style="margin-top:3px">
          <div>
            <strong>Enquiry mode</strong>
            <div style="font-size:12.5px;color:var(--muted)">Guests fill in a form — name, dates, message. Goes to your inbox.</div>
          </div>
        </label>
        <label style="display:flex;align-items:flex-start;gap:12px;cursor:pointer">
          <input type="radio" name="form_mode" value="availability" <?= $form_mode==='availability'?'checked':'' ?> style="margin-top:3px">
          <div>
            <strong>Availability mode</strong> <span class="badge badge--orange" style="font-size:10px">v2</span>
            <div style="font-size:12.5px;color:var(--muted)">Shows "coming soon" placeholder. Use when building live availability.</div>
          </div>
        </label>
      </div>

      <div class="form-section">
        <div class="form-section__title">Notifications</div>
        <div class="field">
          <label>Notification email</label>
          <input type="email" name="notify_email" value="<?= e($notify_email) ?>"
                 placeholder="reservation@sevenislandswatamu.com">
          <span class="field-hint">All form submissions send a notification to this address.</span>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section__title">Currency</div>
        <div class="field" style="max-width:160px">
          <label>Default currency code</label>
          <input type="text" name="site_currency" value="<?= e($site_currency) ?>" maxlength="10" placeholder="USD">
        </div>
      </div>

      <button type="submit" class="btn-primary">Save Settings</button>
    </form>
  </div>
</div>

<!-- Change password -->
<div class="card">
  <div class="card__head"><span class="card__title">Change Password</span></div>
  <div class="card__body" style="padding:20px">
    <form method="POST" action="/admin/settings.php" style="max-width:400px">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="change_password">

      <div class="field">
        <label>Current password</label>
        <input type="password" name="current_password" required placeholder="••••••••">
      </div>
      <div class="field">
        <label>New password <span class="text-muted">(min 8 characters)</span></label>
        <input type="password" name="new_password" required placeholder="••••••••" minlength="8">
      </div>
      <div class="field">
        <label>Confirm new password</label>
        <input type="password" name="confirm_password" required placeholder="••••••••">
      </div>

      <button type="submit" class="btn-primary">Change Password</button>
    </form>
  </div>
</div>

<!-- System info -->
<div class="card">
  <div class="card__head"><span class="card__title">System Info</span></div>
  <div class="card__body" style="padding:20px">
    <div class="detail-grid">
      <div>
        <div class="detail-item__label">PHP Version</div>
        <div class="detail-item__value"><?= e(PHP_VERSION) ?></div>
      </div>
      <div>
        <div class="detail-item__label">Current form mode</div>
        <div class="detail-item__value">
          <span class="badge <?= $form_mode==='enquiry'?'badge--green':'badge--orange' ?>"><?= e($form_mode) ?></span>
        </div>
      </div>
      <div>
        <div class="detail-item__label">Notify email</div>
        <div class="detail-item__value"><?= e($notify_email ?: '—') ?></div>
      </div>
      <div>
        <div class="detail-item__label">Currency</div>
        <div class="detail-item__value"><?= e($site_currency) ?></div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/_layout_end.php'; ?>
