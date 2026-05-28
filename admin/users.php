<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_super_admin();

$success = '';
$error   = '';
$me = current_admin();

// ── Delete user ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_user') {
    verify_csrf();
    $del_id = (int)($_POST['user_id'] ?? 0);

    if ($del_id === (int)$me['id']) {
        $error = 'You cannot delete your own account.';
    } else {
        // Prevent deleting the last super_admin
        $super_count = (int)db_query(
            "SELECT COUNT(*) FROM admin_users WHERE role = 'super_admin'"
        )->fetchColumn();
        $del_role = db_query(
            'SELECT role FROM admin_users WHERE id = :id',
            [':id' => $del_id]
        )->fetchColumn();

        if ($del_role === 'super_admin' && $super_count <= 1) {
            $error = 'Cannot delete the last super admin account.';
        } else {
            db_query('DELETE FROM admin_users WHERE id = :id', [':id' => $del_id]);
            audit_log('users.delete', 'admin_users', $del_id);
            $success = 'User deleted.';
        }
    }
}

// ── Change role ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'change_role') {
    verify_csrf();
    $chg_id   = (int)($_POST['user_id'] ?? 0);
    $new_role = in_array($_POST['role'] ?? '', ['super_admin', 'staff']) ? $_POST['role'] : 'staff';

    if ($chg_id === (int)$me['id'] && $new_role !== 'super_admin') {
        $error = 'You cannot demote your own account.';
    } else {
        // Prevent removing last super_admin
        $super_count = (int)db_query(
            "SELECT COUNT(*) FROM admin_users WHERE role = 'super_admin'"
        )->fetchColumn();
        $old_role = db_query(
            'SELECT role FROM admin_users WHERE id = :id',
            [':id' => $chg_id]
        )->fetchColumn();

        if ($old_role === 'super_admin' && $new_role !== 'super_admin' && $super_count <= 1) {
            $error = 'Cannot demote the last super admin.';
        } else {
            db_query(
                'UPDATE admin_users SET role = :role WHERE id = :id',
                [':role' => $new_role, ':id' => $chg_id]
            );
            audit_log('users.change_role', 'admin_users', $chg_id, $new_role);
            $success = 'Role updated.';
        }
    }
}

// ── Create user ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_user') {
    verify_csrf();
    $new_name  = trim($_POST['name']     ?? '');
    $new_email = trim($_POST['email']    ?? '');
    $new_pass  = $_POST['password']      ?? '';
    $new_role  = in_array($_POST['role'] ?? '', ['super_admin', 'staff']) ? $_POST['role'] : 'staff';

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($new_pass) < 8) {
        $error = 'Password must be at least 8 characters.';
    } else {
        $exists = db_query(
            'SELECT id FROM admin_users WHERE email = :email',
            [':email' => $new_email]
        )->fetchColumn();

        if ($exists) {
            $error = 'An account with this email already exists.';
        } else {
            db_query(
                'INSERT INTO admin_users (name, email, password_hash, role) VALUES (:name, :email, :hash, :role)',
                [
                    ':name'  => $new_name ?: null,
                    ':email' => $new_email,
                    ':hash'  => password_hash($new_pass, PASSWORD_BCRYPT),
                    ':role'  => $new_role,
                ]
            );
            audit_log('users.create', 'admin_users', 0, "$new_email | $new_role");
            $success = 'Account created for ' . htmlspecialchars($new_email) . '.';
        }
    }
}

// ── Load users ───────────────────────────────────────────────────────────
$users = db_query(
    'SELECT id, name, email, role, created_at, last_login_at FROM admin_users ORDER BY id ASC'
)->fetchAll();

$pageTitle  = 'Users';
$activeMenu = 'users';
include __DIR__ . '/_layout.php';
?>

<div class="page-header">
  <h1>Admin Users</h1>
</div>

<?php if ($success): ?><div class="alert alert--success"><?= e($success) ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert alert--error"><?= e($error) ?></div><?php endif; ?>

<!-- User list -->
<div class="card">
  <div class="card__head">
    <span class="card__title">All Accounts</span>
    <span class="text-muted" style="font-size:12px"><?= count($users) ?> account<?= count($users) !== 1 ? 's' : '' ?></span>
  </div>
  <div class="card__body">
    <table class="data-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Last login</th>
          <th>Created</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td>
            <?= e($u['name'] ?: '—') ?>
            <?php if ((int)$u['id'] === (int)$me['id']): ?>
              <span class="badge badge--green" style="font-size:10px;margin-left:4px">You</span>
            <?php endif; ?>
          </td>
          <td><?= e($u['email']) ?></td>
          <td>
            <form method="POST" action="/admin/users.php" style="display:inline">
              <?= csrf_field() ?>
              <input type="hidden" name="action"  value="change_role">
              <input type="hidden" name="user_id" value="<?= e($u['id']) ?>">
              <select name="role" onchange="this.form.submit()" style="font-size:12px;padding:3px 6px;border:1px solid var(--border);border-radius:6px;cursor:pointer">
                <option value="super_admin" <?= $u['role']==='super_admin'?'selected':'' ?>>Super admin</option>
                <option value="staff"       <?= $u['role']==='staff'      ?'selected':'' ?>>Staff</option>
              </select>
            </form>
          </td>
          <td class="text-muted" style="font-size:12px">
            <?= $u['last_login_at'] ? date('d M Y, H:i', strtotime($u['last_login_at'])) : 'Never' ?>
          </td>
          <td class="text-muted" style="font-size:12px">
            <?= date('d M Y', strtotime($u['created_at'])) ?>
          </td>
          <td>
            <?php if ((int)$u['id'] !== (int)$me['id']): ?>
            <form method="POST" action="/admin/users.php"
                  onsubmit="return confirm('Delete <?= e(addslashes($u['email'])) ?>? This cannot be undone.')">
              <?= csrf_field() ?>
              <input type="hidden" name="action"  value="delete_user">
              <input type="hidden" name="user_id" value="<?= e($u['id']) ?>">
              <button type="submit" class="btn-sm" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca">Delete</button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Create new user -->
<div class="card">
  <div class="card__head"><span class="card__title">Add New Account</span></div>
  <div class="card__body" style="padding:20px">
    <form method="POST" action="/admin/users.php" style="max-width:520px">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="create_user">

      <div class="form-row">
        <div class="field">
          <label>Name <span class="text-muted">(optional)</span></label>
          <input type="text" name="name" placeholder="Full name">
        </div>
        <div class="field">
          <label>Email</label>
          <input type="email" name="email" required placeholder="staff@resort.com">
        </div>
      </div>

      <div class="form-row">
        <div class="field">
          <label>Password <span class="text-muted">(min 8 chars)</span></label>
          <input type="password" name="password" required minlength="8" placeholder="••••••••">
          <span class="field-hint">They can change it from Settings after logging in.</span>
        </div>
        <div class="field">
          <label>Role</label>
          <select name="role">
            <option value="staff">Staff — view &amp; manage bookings</option>
            <option value="super_admin">Super admin — full access</option>
          </select>
          <span class="field-hint">Staff cannot manage users or some system settings.</span>
        </div>
      </div>

      <button type="submit" class="btn-primary">Create Account</button>
    </form>
  </div>
</div>

<!-- Role guide -->
<div class="card">
  <div class="card__head"><span class="card__title">Role Permissions</span></div>
  <div class="card__body" style="padding:20px">
    <table class="data-table">
      <thead>
        <tr><th>Permission</th><th>Staff</th><th>Super admin</th></tr>
      </thead>
      <tbody>
        <tr><td>View dashboard &amp; submissions</td><td>✓</td><td>✓</td></tr>
        <tr><td>Manage rooms &amp; tours</td><td>✓</td><td>✓</td></tr>
        <tr><td>Manage holds &amp; bookings</td><td>✓</td><td>✓</td></tr>
        <tr><td>View audit log</td><td>✓</td><td>✓</td></tr>
        <tr><td>Change general settings</td><td>—</td><td>✓</td></tr>
        <tr><td>Manage admin accounts</td><td>—</td><td>✓</td></tr>
        <tr><td>Export data</td><td>—</td><td>✓</td></tr>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/_layout_end.php'; ?>
