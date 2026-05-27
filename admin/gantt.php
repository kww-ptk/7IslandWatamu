<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();

$msg = $err = '';

// ── POST handlers ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'create_block') {
        $unit_id   = (int)($_POST['unit_id']    ?? 0);
        $date_from = $_POST['date_from'] ?? '';
        $date_to   = $_POST['date_to']   ?? '';
        $type      = in_array($_POST['block_type'] ?? '', ['booked','blocked']) ? $_POST['block_type'] : 'blocked';
        $notes     = trim($_POST['notes'] ?? '');

        // date_to from modal is the last blocked night (inclusive) — add 1 day for exclusive DB storage
        $date_to_excl = $date_to ? date('Y-m-d', strtotime($date_to . ' +1 day')) : '';
        if ($unit_id && $date_from && $date_to_excl && $date_from < $date_to_excl) {
            db_query(
                "INSERT INTO availability_blocks (unit_id, date_from, date_to, block_type, notes)
                 VALUES (:uid, :df, :dt, :type, :notes)",
                [':uid' => $unit_id, ':df' => $date_from, ':dt' => $date_to_excl,
                 ':type' => $type, ':notes' => $notes]
            );
            $msg = 'Block created.';
        } else {
            $err = 'Invalid dates or unit — last blocked night must be on or after first blocked night.';
        }
    }

    if ($action === 'delete_block') {
        $block_id = (int)($_POST['block_id'] ?? 0);
        // Don't delete hold-type blocks here (managed via holds.php)
        db_query(
            "DELETE FROM availability_blocks WHERE id = :id AND block_type != 'hold'",
            [':id' => $block_id]
        );
        $msg = 'Block removed.';
    }

    if ($action === 'set_rate') {
        $room_id   = (int)($_POST['room_id']    ?? 0);
        $date_from = $_POST['rate_from']  ?? '';
        $date_to   = $_POST['rate_to']    ?? '';
        $price     = (float)($_POST['price']    ?? 0);
        $label     = trim($_POST['rate_label']  ?? '');

        // date_to from form is inclusive last night — add 1 day for exclusive DB storage
        $date_to_excl = $date_to ? date('Y-m-d', strtotime($date_to . ' +1 day')) : '';

        if ($room_id && $date_from && $date_to_excl && $price > 0 && $date_from < $date_to_excl) {
            db_query(
                "INSERT INTO rates (room_id, date_from, date_to, price_amount, label)
                 VALUES (:rid, :df, :dt, :price, :label)",
                [':rid' => $room_id, ':df' => $date_from, ':dt' => $date_to_excl,
                 ':price' => $price, ':label' => $label]
            );
            $msg = 'Rate override added.';
        } else {
            $err = 'Invalid rate data — check all fields are filled and dates are valid.';
        }
    }

    if ($action === 'delete_rate') {
        db_query('DELETE FROM rates WHERE id = :id', [':id' => (int)($_POST['rate_id'] ?? 0)]);
        $msg = 'Rate removed.';
    }

    if ($action === 'add_ical_feed') {
        $unit_id   = (int)($_POST['feed_unit_id'] ?? 0);
        $label     = trim($_POST['feed_label']    ?? '');
        $feed_url  = trim($_POST['feed_url']      ?? '');
        if ($unit_id && $feed_url && filter_var($feed_url, FILTER_VALIDATE_URL)) {
            db_query(
                "INSERT INTO ical_feeds (unit_id, label, feed_url) VALUES (:uid, :label, :url)",
                [':uid' => $unit_id, ':label' => $label, ':url' => $feed_url]
            );
            $msg = 'iCal feed added.';
        } else {
            $err = 'Invalid unit or feed URL.';
        }
    }

    if ($action === 'delete_ical_feed') {
        db_query('DELETE FROM ical_feeds WHERE id = :id', [':id' => (int)($_POST['feed_id'] ?? 0)]);
        $msg = 'iCal feed removed.';
    }
}

// ── Date range: 3-month window with prev/next offset ────────────
$offset = (int)($_GET['offset'] ?? 0);
$start  = new DateTime('first day of this month');
if ($offset) $start->modify("{$offset} months");
$end = clone $start;
$end->modify('+3 months');

$start_str = $start->format('Y-m-d');
$end_str   = $end->format('Y-m-d');

// Build day list
$days = [];
$d = clone $start;
while ($d < $end) { $days[] = $d->format('Y-m-d'); $d->modify('+1 day'); }
$day_count  = count($days);
$day_index  = array_flip($days); // date→index for fast lookup

// ── Load data ────────────────────────────────────────────────────
$units = db_query(
    "SELECT u.*, r.name AS room_name, r.id AS room_db_id,
            u.feed_token,
            COALESCE(f.feed_count,0) AS feed_count
     FROM units u
     JOIN rooms r ON r.id = u.room_id
     LEFT JOIN (
         SELECT unit_id, COUNT(*) AS feed_count FROM ical_feeds GROUP BY unit_id
     ) f ON f.unit_id = u.id
     WHERE u.is_active = TRUE
     ORDER BY r.sort_order ASC, u.sort_order ASC"
)->fetchAll();

$blocks = db_query(
    "SELECT ab.*, u.room_id
     FROM availability_blocks ab
     JOIN units u ON u.id = ab.unit_id
     WHERE ab.date_from < :end AND ab.date_to > :start
     ORDER BY ab.date_from ASC",
    [':start' => $start_str, ':end' => $end_str]
)->fetchAll();

$blocks_by_unit = [];
foreach ($blocks as $b) $blocks_by_unit[(int)$b['unit_id']][] = $b;

$rates = db_query(
    "SELECT r.*, rm.name AS room_name
     FROM rates r JOIN rooms rm ON rm.id = r.room_id
     ORDER BY r.date_from ASC LIMIT 100"
)->fetchAll();

// Build rate-date lookups scoped correctly:
// $rate_dates[room_id][date] — used on unit rows (only highlight the right room)
// $rate_dates_any[date]      — used on the shared day-header (highlight if any room has a rate)
$rate_dates     = [];
$rate_dates_any = [];
foreach ($rates as $r) {
    if ($r['date_from'] >= $end_str || $r['date_to'] <= $start_str) continue;
    $rid = (int)$r['room_id'];
    $rd  = new DateTime(max($r['date_from'], $start_str));
    $re  = new DateTime(min($r['date_to'],   $end_str));
    while ($rd < $re) {
        $key = $rd->format('Y-m-d');
        $rate_dates[$rid][$key] = true;
        $rate_dates_any[$key]   = true;
        $rd->modify('+1 day');
    }
}

$ical_feeds = db_query(
    "SELECT f.*, u.name AS unit_name, r.name AS room_name
     FROM ical_feeds f
     JOIN units u ON u.id = f.unit_id
     JOIN rooms r ON r.id = u.room_id
     ORDER BY r.sort_order ASC, f.id ASC"
)->fetchAll();

$rooms       = db_query("SELECT id, name FROM rooms ORDER BY sort_order ASC")->fetchAll();
$env         = parse_env();
$site_url    = rtrim($env['SITE_URL'] ?? 'https://sevenislandswatamu.com', '/');
$sync_secret = $env['ICAL_SYNC_SECRET'] ?? '';

$pageTitle  = 'Availability Calendar';
$activeMenu = 'gantt';
include __DIR__ . '/_layout.php';
?>

<style>
/* ── Gantt ── */
.gantt-outer { overflow-x: auto; border: 1px solid var(--border); border-radius: var(--radius); background: #fff; margin-bottom: 24px; }
.gantt-head, .gantt-row { display: flex; min-width: max-content; }
.gantt-head { border-bottom: 2px solid var(--border); background: #f9fafb; position: sticky; top: 0; z-index: 20; }
.gantt-label { width: 150px; min-width: 150px; padding: 6px 10px; font-size: 11.5px; font-weight: 600; border-right: 2px solid var(--border); position: sticky; left: 0; background: inherit; z-index: 5; }
.gantt-row .gantt-label { background: #fff; font-weight: 500; display: flex; flex-direction: column; justify-content: center; border-bottom: 1px solid var(--border); }
.gantt-row .gantt-label small { font-size: 10px; color: var(--muted); font-weight: 400; }
.gantt-days { display: flex; flex: 1; }
/* Month sub-header */
.gantt-months { display: flex; min-width: max-content; border-bottom: 1px solid var(--border); }
.gantt-month-cell { display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; background: var(--sidebar-bg); color: #fff; height: 20px; border-right: 1px solid rgba(255,255,255,.15); }
/* Day header */
.gantt-day-h { width: 28px; min-width: 28px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: var(--muted); border-right: 1px solid var(--border); flex-shrink: 0; }
.gantt-day-h.is-today { background: #e0f2fe; color: #0369a1; font-weight: 700; }
.gantt-day-h.is-weekend { background: #f8f9fa; }
/* Row cells */
.gantt-cells { position: relative; display: flex; flex: 1; height: 36px; border-bottom: 1px solid var(--border); }
.gantt-day-cell { width: 28px; min-width: 28px; height: 100%; border-right: 1px solid #f0f0f0; cursor: pointer; flex-shrink: 0; transition: background .1s; }
.gantt-day-cell:hover { background: #f0f8fa; }
.gantt-day-cell.is-today { background: #f0f9ff; }
.gantt-day-cell.is-weekend { background: #fafafa; }
.gantt-day-cell.is-selecting { background: #dceeff; }
/* Blocks */
.gantt-block {
  position: absolute; top: 4px; bottom: 4px;
  border-radius: 3px; font-size: 10px; color: #fff;
  padding: 2px 5px; cursor: pointer; z-index: 3;
  overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
  transition: opacity .12s;
}
.gantt-block:hover { opacity: .85; }
.gantt-block--hold    { background: #e07b39; }
.gantt-block--booked  { background: #2e7d32; }
.gantt-block--blocked { background: #6b7c85; }
/* Modal */
.g-modal { position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 1000; display: flex; align-items: center; justify-content: center; }
.g-modal.is-hidden { display: none; }
.g-modal__box { background: #fff; border-radius: 8px; padding: 24px; width: 100%; max-width: 420px; box-shadow: 0 8px 32px rgba(0,0,0,.2); }
.g-modal__box h2 { font-size: 15px; font-weight: 700; margin-bottom: 16px; }
.g-modal__actions { display: flex; gap: 8px; margin-top: 20px; justify-content: flex-end; }
/* Rate-override day highlight */
.gantt-day-h.is-rate { background: #fef9c3; color: #92400e; }
.gantt-day-cell.is-rate { background: #fefce8; }
.gantt-day-cell.is-rate:hover { background: #fef08a; }
.gantt-day-cell.is-today.is-rate { background: #fef9c3; }
/* Custom date picker */
.dp { position: relative; }
.dp__display { display: block; width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px; cursor: pointer; background: #fff; color: var(--muted); user-select: none; box-sizing: border-box; }
.dp__display.has-val { color: var(--text, #1a2730); }
.dp__display:hover { border-color: var(--brand); }
.dp__pop { position: absolute; z-index: 300; background: #fff; border: 1px solid var(--border); border-radius: 8px; padding: 12px; box-shadow: 0 4px 20px rgba(0,0,0,.18); min-width: 240px; top: calc(100% + 4px); left: 0; }
.dp__pop.is-hidden { display: none; }
.dp__head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
.dp__mlabel { font-size: 13px; font-weight: 600; }
.dp__nav { background: none; border: none; font-size: 18px; cursor: pointer; padding: 2px 6px; color: var(--brand); line-height: 1; border-radius: 4px; }
.dp__nav:hover { background: #e8f4f6; }
.dp__daynames { display: grid; grid-template-columns: repeat(7,1fr); text-align: center; font-size: 10px; color: var(--muted); font-weight: 600; margin-bottom: 4px; gap: 2px; }
.dp__grid { display: grid; grid-template-columns: repeat(7,1fr); gap: 2px; }
.dp__cell { text-align: center; padding: 5px 2px; font-size: 12px; cursor: pointer; border-radius: 3px; line-height: 1.4; }
.dp__cell:hover:not(.dp__cell--blank) { background: #e8f4f6; color: var(--brand); }
.dp__cell--sel { background: var(--brand) !important; color: #fff !important; font-weight: 600; }
.dp__cell--blank { cursor: default; }
</style>

<div class="page-header">
  <h1>Availability Calendar</h1>
  <div class="actions">
    <a href="?offset=<?= $offset - 1 ?>" class="btn-outline btn-sm">&#8249; Prev</a>
    <a href="/admin/gantt.php"            class="btn-outline btn-sm">Today</a>
    <a href="?offset=<?= $offset + 1 ?>" class="btn-outline btn-sm">Next &#8250;</a>
    <?php if ($sync_secret): ?>
    <button class="btn-primary btn-sm" id="syncBtn">&#8635; Sync iCal</button>
    <?php endif; ?>
  </div>
</div>

<?php if ($msg): ?><div class="alert alert--success"><?= e($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert--error"><?= e($err) ?></div><?php endif; ?>

<?php if (empty($units)): ?>
<div class="alert alert--info">No units defined yet. Go to <a href="/admin/rooms.php">Rooms</a>, edit a room, and add units under the <strong>Units</strong> tab.</div>
<?php else: ?>

<!-- ── Gantt ── -->
<div class="gantt-outer" id="ganttOuter">

  <!-- Month band -->
  <?php
  $month_spans = [];
  $prev_m = ''; $span = 0;
  foreach ($days as $day) {
      $m = date('M Y', strtotime($day));
      if ($m !== $prev_m) { if ($prev_m) $month_spans[] = [$prev_m, $span]; $prev_m = $m; $span = 1; }
      else $span++;
  }
  if ($prev_m) $month_spans[] = [$prev_m, $span];
  ?>
  <div class="gantt-head" style="flex-direction:column">
    <div class="gantt-months">
      <div class="gantt-label" style="background:var(--sidebar-bg);color:#fff;font-size:10px">Unit</div>
      <?php foreach ($month_spans as [$mname, $mspan]): ?>
      <div class="gantt-month-cell" style="width:<?= $mspan * 28 ?>px;min-width:<?= $mspan * 28 ?>px"><?= e($mname) ?></div>
      <?php endforeach; ?>
    </div>
    <div class="gantt-head" style="min-width:0">
      <div class="gantt-label" style="height:30px;align-items:flex-start;padding-top:8px;border-bottom:none"></div>
      <div class="gantt-days">
        <?php foreach ($days as $day):
          $dow = (int)date('N', strtotime($day));
          $isToday = $day === date('Y-m-d');
          $isRate  = isset($rate_dates_any[$day]);
          $cls = ($isToday ? ' is-today' : '') . ($dow >= 6 ? ' is-weekend' : '') . ($isRate ? ' is-rate' : '');
        ?>
        <div class="gantt-day-h<?= $cls ?>" title="<?= date('D d M', strtotime($day)) . ($isRate ? ' ★ Rate override' : '') ?>">
          <?= date('j', strtotime($day)) ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Unit rows -->
  <?php
  $prev_room = '';
  foreach ($units as $unit):
    $unit_blocks = $blocks_by_unit[(int)$unit['id']] ?? [];
    $view_start_ts = strtotime($start_str);
    $view_end_ts   = strtotime($end_str);
  ?>
  <div class="gantt-row">
    <div class="gantt-label">
      <?php if ($unit['room_name'] !== $prev_room): $prev_room = $unit['room_name']; ?>
      <small><?= e($unit['room_name']) ?></small>
      <?php endif; ?>
      <?= e($unit['name']) ?>
    </div>
    <div class="gantt-cells" data-unit-id="<?= e($unit['id']) ?>">
      <?php foreach ($days as $i => $day):
        $dow = (int)date('N', strtotime($day));
        $isToday = $day === date('Y-m-d');
        $isRate  = isset($rate_dates[(int)$unit['room_db_id']][$day]);
        $cls = ($isToday ? ' is-today' : '') . ($dow >= 6 ? ' is-weekend' : '') . ($isRate ? ' is-rate' : '');
      ?>
      <div class="gantt-day-cell<?= $cls ?>" data-date="<?= e($day) ?>" data-unit="<?= e($unit['id']) ?>"></div>
      <?php endforeach; ?>

      <?php foreach ($unit_blocks as $b):
        $b_start = strtotime($b['date_from']);
        $b_end   = strtotime($b['date_to']);
        $vis_start = max($b_start, $view_start_ts);
        $vis_end   = min($b_end,   $view_end_ts);
        if ($vis_end <= $vis_start) continue;
        $left_days = (int)(($vis_start - $view_start_ts) / 86400);
        $span_days = (int)(($vis_end   - $vis_start)     / 86400);
        $left_px   = $left_days * 28;
        $width_px  = max(4, $span_days * 28 - 2);
        $label     = $b['notes'] ?: $b['block_type'];
        $type_cls  = 'gantt-block--' . $b['block_type'];
        $title     = ucfirst($b['block_type']) . ': ' . $b['date_from'] . ' → ' . $b['date_to'] . ($b['notes'] ? ' · ' . $b['notes'] : '');
      ?>
      <div class="gantt-block <?= $type_cls ?>"
           style="left:<?= $left_px ?>px;width:<?= $width_px ?>px"
           data-block-id="<?= e($b['id']) ?>"
           data-block-type="<?= e($b['block_type']) ?>"
           title="<?= e($title) ?>">
        <?= e($label) ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Legend -->
<div style="display:flex;gap:20px;margin-bottom:28px;font-size:12px;align-items:center;flex-wrap:wrap">
  <strong>Legend:</strong>
  <span><span style="display:inline-block;width:12px;height:12px;background:#2e7d32;border-radius:2px;vertical-align:middle;margin-right:4px"></span>Booked</span>
  <span><span style="display:inline-block;width:12px;height:12px;background:#e07b39;border-radius:2px;vertical-align:middle;margin-right:4px"></span>Hold (pending)</span>
  <span><span style="display:inline-block;width:12px;height:12px;background:#6b7c85;border-radius:2px;vertical-align:middle;margin-right:4px"></span>Blocked</span>
  <span><span style="display:inline-block;width:12px;height:12px;background:#fef9c3;border:1px solid #f59e0b;border-radius:2px;vertical-align:middle;margin-right:4px"></span>Rate override</span>
  <span style="color:var(--muted)">Click empty cells to block · Click blocks to delete</span>
</div>

<?php endif; // end if units ?>

<!-- ── Rate overrides ── -->
<div class="card" style="margin-bottom:24px">
  <div class="card__head">
    <span class="card__title">Price Overrides</span>
    <span class="text-muted" style="font-size:12px">Set a nightly rate for a date range (overrides default room price)</span>
  </div>
  <div class="card__body" style="padding:20px">
    <form method="POST" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;margin-bottom:20px">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="set_rate">
      <div class="field" style="margin:0">
        <label>Room</label>
        <select name="room_id" required>
          <?php foreach ($rooms as $r): ?><option value="<?= e($r['id']) ?>"><?= e($r['name']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="field" style="margin:0">
        <label>From (first night)</label>
        <div class="dp" style="min-width:140px">
          <div class="dp__display" id="dpRateFromDisplay" tabindex="0">Pick a date</div>
          <input type="hidden" name="rate_from" id="dpRateFromVal">
          <div class="dp__pop is-hidden" id="dpRateFromPop"></div>
        </div>
      </div>
      <div class="field" style="margin:0">
        <label>To (last night)</label>
        <div class="dp" style="min-width:140px">
          <div class="dp__display" id="dpRateToDisplay" tabindex="0">Pick a date</div>
          <input type="hidden" name="rate_to" id="dpRateToVal">
          <div class="dp__pop is-hidden" id="dpRateToPop"></div>
        </div>
      </div>
      <div class="field" style="margin:0"><label>Price / night</label><input type="number" name="price" step="0.01" min="1" placeholder="450" required style="width:90px"></div>
      <div class="field" style="margin:0"><label>Label</label><input type="text" name="rate_label" placeholder="Peak Season" style="width:130px"></div>
      <button type="submit" class="btn-primary btn-sm">Add Override</button>
    </form>
    <?php if ($rates): ?>
    <table class="data-table">
      <thead><tr><th>Room</th><th>From</th><th>To</th><th>Price/night</th><th>Label</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($rates as $rate): ?>
      <tr>
        <td><?= e($rate['room_name']) ?></td>
        <td><?= e($rate['date_from']) ?></td>
        <td><?= e($rate['date_to']) ?></td>
        <td><?= e($rate['price_amount']) ?></td>
        <td><?= e($rate['label'] ?? '') ?></td>
        <td>
          <form method="POST" style="display:inline">
            <?= csrf_field() ?>
            <input type="hidden" name="action"  value="delete_rate">
            <input type="hidden" name="rate_id" value="<?= e($rate['id']) ?>">
            <button type="submit" class="btn-danger btn-sm" onclick="return confirm('Remove this rate?')">Remove</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <p style="color:var(--muted);font-size:13px">No rate overrides. Default room prices apply.</p>
    <?php endif; ?>
  </div>
</div>

<!-- ── iCal feeds ── -->
<div class="card">
  <div class="card__head">
    <span class="card__title">OTA iCal Feeds</span>
    <span class="text-muted" style="font-size:12px">Import blocks from Airbnb, Booking.com etc. — add feed URL per unit</span>
  </div>
  <div class="card__body" style="padding:20px">
    <!-- Push feeds: outbound iCal URLs for each unit -->
    <?php if (!empty($units)): ?>
    <div style="margin-bottom:20px">
      <div class="form-section__title">Your iCal feed URLs (share with OTAs to export your calendar)</div>
      <table class="data-table" style="margin-top:8px">
        <thead><tr><th>Room</th><th>Unit</th><th>Feed URL</th></tr></thead>
        <tbody>
        <?php foreach ($units as $u): ?>
        <tr>
          <td><?= e($u['room_name']) ?></td>
          <td><?= e($u['name']) ?></td>
          <td>
            <input type="text" readonly
                   value="<?= e($site_url . '/api/ical.php?unit=' . $u['id'] . '&token=' . $u['feed_token']) ?>"
                   onclick="this.select()"
                   style="width:100%;font-size:11px;font-family:monospace;background:#f9fafb">
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>

    <!-- Pull feeds: import from OTA -->
    <div class="form-section__title" style="margin-bottom:12px">Import feeds (pull from OTA into your calendar)</div>
    <form method="POST" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;margin-bottom:16px">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="add_ical_feed">
      <div class="field" style="margin:0">
        <label>Unit</label>
        <select name="feed_unit_id" required>
          <?php foreach ($units as $u): ?>
          <option value="<?= e($u['id']) ?>"><?= e($u['room_name'] . ' — ' . $u['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field" style="margin:0"><label>Label</label><input type="text" name="feed_label" placeholder="Airbnb" style="width:110px"></div>
      <div class="field" style="margin:0"><label>iCal URL</label><input type="url" name="feed_url" placeholder="https://www.airbnb.com/calendar/ical/..." required style="width:280px"></div>
      <button type="submit" class="btn-primary btn-sm">Add Feed</button>
    </form>

    <?php if ($ical_feeds): ?>
    <table class="data-table">
      <thead><tr><th>Room / Unit</th><th>Label</th><th>Feed URL</th><th>Last synced</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($ical_feeds as $feed): ?>
      <tr>
        <td><?= e($feed['room_name']) ?> — <?= e($feed['unit_name']) ?></td>
        <td><?= e($feed['label'] ?? '') ?></td>
        <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;font-size:11px">
          <a href="<?= e($feed['feed_url']) ?>" target="_blank" title="<?= e($feed['feed_url']) ?>" style="color:var(--brand)">
            <?= e(parse_url($feed['feed_url'], PHP_URL_HOST) ?: $feed['feed_url']) ?>
          </a>
        </td>
        <td style="font-size:12px;color:var(--muted)"><?= $feed['last_synced_at'] ? date('d M H:i', strtotime($feed['last_synced_at'])) : 'Never' ?></td>
        <td>
          <form method="POST" style="display:inline">
            <?= csrf_field() ?>
            <input type="hidden" name="action"  value="delete_ical_feed">
            <input type="hidden" name="feed_id" value="<?= e($feed['id']) ?>">
            <button type="submit" class="btn-danger btn-sm" onclick="return confirm('Remove this feed?')">Remove</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <p style="color:var(--muted);font-size:13px">No import feeds added yet.</p>
    <?php endif; ?>

    <?php if ($sync_secret): ?>
    <p style="margin-top:12px;font-size:12px;color:var(--muted)">
      Sync endpoint (call via external cron every 1–6 hours):
      <code style="font-size:11px;background:#f4f6f8;padding:2px 6px;border-radius:3px">
        <?= e($site_url . '/api/sync-ical.php?secret=' . $sync_secret) ?>
      </code>
    </p>
    <?php else: ?>
    <p style="margin-top:12px;font-size:12px;color:var(--muted)">
      Set <code>ICAL_SYNC_SECRET</code> in your environment to enable pull sync.
    </p>
    <?php endif; ?>
  </div>
</div>

<!-- ── Create block modal ── -->
<div class="g-modal is-hidden" id="blockModal">
  <div class="g-modal__box">
    <h2>Block Dates</h2>
    <form method="POST" id="blockForm">
      <?= csrf_field() ?>
      <input type="hidden" name="action"  value="create_block">
      <input type="hidden" name="unit_id" id="m_unit_id">
      <div class="field">
        <label>Unit</label>
        <input type="text" id="m_unit_name" disabled style="background:#f4f6f8">
      </div>
      <div class="form-row">
        <div class="field">
          <label>From (first blocked night)</label>
          <div class="dp" id="dpFromWrap">
            <div class="dp__display" id="dpFromDisplay" tabindex="0">Pick a date</div>
            <input type="hidden" name="date_from" id="m_date_from">
            <div class="dp__pop is-hidden" id="dpFromPop"></div>
          </div>
        </div>
        <div class="field">
          <label>To (last blocked night)</label>
          <div class="dp" id="dpToWrap">
            <div class="dp__display" id="dpToDisplay" tabindex="0">Pick a date</div>
            <input type="hidden" name="date_to" id="m_date_to">
            <div class="dp__pop is-hidden" id="dpToPop"></div>
          </div>
        </div>
      </div>
      <div class="field">
        <label>Type</label>
        <select name="block_type">
          <option value="blocked">Blocked (maintenance / manual)</option>
          <option value="booked">Booked (direct)</option>
        </select>
      </div>
      <div class="field"><label>Notes (optional)</label><input type="text" name="notes" placeholder="Reason, guest name, etc."></div>
      <div class="g-modal__actions">
        <button type="button" class="btn-outline btn-sm" id="blockModalClose">Cancel</button>
        <button type="submit" class="btn-primary btn-sm">Create Block</button>
      </div>
    </form>
  </div>
</div>

<!-- ── Delete block modal ── -->
<div class="g-modal is-hidden" id="deleteModal">
  <div class="g-modal__box">
    <h2>Remove Block</h2>
    <p id="deleteDesc" style="font-size:13px;color:var(--muted);margin-bottom:20px"></p>
    <form method="POST" id="deleteForm">
      <?= csrf_field() ?>
      <input type="hidden" name="action"   value="delete_block">
      <input type="hidden" name="block_id" id="m_block_id">
      <div class="g-modal__actions">
        <button type="button" class="btn-outline btn-sm" id="deleteModalClose">Cancel</button>
        <button type="submit" class="btn-danger btn-sm">Remove</button>
      </div>
    </form>
  </div>
</div>

<script>
// ── Unit name lookup ─────────────────────────────────────────────
const unitNames = {<?php foreach ($units as $u) echo (int)$u['id'] . ':"' . addslashes($u['room_name'].' — '.$u['name']) . '",'; ?>};

// ── Modal helpers ────────────────────────────────────────────────
const blockModal  = document.getElementById('blockModal');
const deleteModal = document.getElementById('deleteModal');
const closeModal  = m => m.classList.add('is-hidden');
document.getElementById('blockModalClose').onclick  = () => { closeModal(blockModal); dpFrom.clear(); dpTo.clear(); };
document.getElementById('deleteModalClose').onclick = () => closeModal(deleteModal);
[blockModal, deleteModal].forEach(m => m.addEventListener('click', e => { if(e.target===m) closeModal(m); }));

// ── Drag-select on cells to open create-block modal ──────────────
let selUnit = null, selStart = null, selCurrent = null;

document.querySelectorAll('.gantt-day-cell').forEach(cell => {
  cell.addEventListener('mousedown', e => {
    if (e.button !== 0) return;
    selUnit    = cell.dataset.unit;
    selStart   = cell.dataset.date;
    selCurrent = cell.dataset.date;
    cell.classList.add('is-selecting');
    e.preventDefault();
  });
  cell.addEventListener('mouseenter', () => {
    if (!selStart || cell.dataset.unit !== selUnit) return;
    selCurrent = cell.dataset.date;
    document.querySelectorAll(`.gantt-day-cell[data-unit="${selUnit}"]`).forEach(c => {
      const d = c.dataset.date;
      const lo = selStart <= selCurrent ? selStart : selCurrent;
      const hi = selStart <= selCurrent ? selCurrent : selStart;
      c.classList.toggle('is-selecting', d >= lo && d <= hi);
    });
  });
});

document.addEventListener('mouseup', () => {
  if (!selStart) return;
  const lo = selStart <= selCurrent ? selStart : selCurrent;
  const hi = selStart <= selCurrent ? selCurrent : selStart;

  document.querySelectorAll('.gantt-day-cell.is-selecting').forEach(c => c.classList.remove('is-selecting'));

  document.getElementById('m_unit_id').value   = selUnit;
  document.getElementById('m_unit_name').value = unitNames[selUnit] || ('Unit ' + selUnit);
  dpFrom.setValue(lo);
  dpTo.setValue(hi);
  blockModal.classList.remove('is-hidden');

  selUnit = selStart = selCurrent = null;
});

// ── Click on block → delete modal ───────────────────────────────
document.querySelectorAll('.gantt-block').forEach(block => {
  block.addEventListener('click', e => {
    e.stopPropagation();
    if (block.dataset.blockType === 'hold') {
      alert('Holds are managed via the Holds & Bookings page.');
      return;
    }
    document.getElementById('deleteDesc').textContent = 'Remove: ' + block.title;
    document.getElementById('m_block_id').value = block.dataset.blockId;
    deleteModal.classList.remove('is-hidden');
  });
});

// ── Sync iCal button ─────────────────────────────────────────────
const syncBtn = document.getElementById('syncBtn');
if (syncBtn) {
  syncBtn.addEventListener('click', () => {
    syncBtn.disabled = true;
    syncBtn.textContent = 'Syncing…';
    fetch('/api/sync-ical.php?secret=<?= e($sync_secret) ?>')
      .then(r => r.json())
      .then(data => {
        const total = (data.feeds||[]).reduce((s,f) => s + (f.imported||0), 0);
        alert('Sync complete. ' + total + ' new block(s) imported.');
        if (total > 0) location.reload();
      })
      .catch(() => alert('Sync failed — check the ICAL_SYNC_SECRET setting.'))
      .finally(() => { syncBtn.disabled = false; syncBtn.textContent = '⟳ Sync iCal'; });
  });
}

// ── Mini date-picker ─────────────────────────────────────────────
function makePicker(popId, hiddenId, displayId) {
  const pop     = document.getElementById(popId);
  const hidden  = document.getElementById(hiddenId);
  const display = document.getElementById(displayId);
  const MONTHS  = ['January','February','March','April','May','June',
                   'July','August','September','October','November','December'];
  const now = new Date();
  let viewYear  = now.getFullYear();
  let viewMonth = now.getMonth();
  let selected  = null;

  function toYmd(d) {
    return d.getFullYear() + '-' +
      String(d.getMonth()+1).padStart(2,'0') + '-' +
      String(d.getDate()).padStart(2,'0');
  }
  function fmtDisplay(s) {
    const d = new Date(s + 'T00:00');
    return d.getDate() + ' ' + MONTHS[d.getMonth()].slice(0,3) + ' ' + d.getFullYear();
  }

  function render() {
    const first  = new Date(viewYear, viewMonth, 1);
    const last   = new Date(viewYear, viewMonth + 1, 0);
    const blanks = (first.getDay() + 6) % 7;
    let html = `<div class="dp__head">
      <button type="button" class="dp__nav" data-dir="-1">&#8249;</button>
      <span class="dp__mlabel">${MONTHS[viewMonth]} ${viewYear}</span>
      <button type="button" class="dp__nav" data-dir="1">&#8250;</button>
    </div>
    <div class="dp__daynames">
      <span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span><span>Su</span>
    </div>
    <div class="dp__grid">`;
    for (let i = 0; i < blanks; i++) html += `<div class="dp__cell dp__cell--blank"></div>`;
    for (let d = 1; d <= last.getDate(); d++) {
      const key = toYmd(new Date(viewYear, viewMonth, d));
      html += `<div class="dp__cell${key === selected ? ' dp__cell--sel' : ''}" data-date="${key}">${d}</div>`;
    }
    html += `</div>`;
    pop.innerHTML = html;

    pop.querySelectorAll('.dp__nav').forEach(btn => btn.addEventListener('click', e => {
      e.stopPropagation();
      viewMonth += parseInt(btn.dataset.dir);
      if (viewMonth < 0)  { viewMonth = 11; viewYear--; }
      if (viewMonth > 11) { viewMonth = 0;  viewYear++; }
      render();
    }));
    pop.querySelectorAll('.dp__cell[data-date]').forEach(cell => cell.addEventListener('click', e => {
      e.stopPropagation();
      selected = cell.dataset.date;
      hidden.value = selected;
      display.textContent = fmtDisplay(selected);
      display.classList.add('has-val');
      pop.classList.add('is-hidden');
      render();
    }));
  }

  display.addEventListener('click', e => {
    e.stopPropagation();
    document.querySelectorAll('.dp__pop').forEach(p => { if (p !== pop) p.classList.add('is-hidden'); });
    pop.classList.toggle('is-hidden');
    if (!pop.classList.contains('is-hidden')) render();
  });
  document.addEventListener('click', () => pop.classList.add('is-hidden'));

  return {
    setValue(s) {
      selected = s;
      hidden.value = s;
      display.textContent = fmtDisplay(s);
      display.classList.add('has-val');
      const d = new Date(s + 'T00:00');
      viewYear = d.getFullYear();
      viewMonth = d.getMonth();
    },
    clear() {
      selected = null;
      hidden.value = '';
      display.textContent = 'Pick a date';
      display.classList.remove('has-val');
    }
  };
}

const dpFrom     = makePicker('dpFromPop',     'm_date_from',  'dpFromDisplay');
const dpTo       = makePicker('dpToPop',       'm_date_to',    'dpToDisplay');
const dpRateFrom = makePicker('dpRateFromPop', 'dpRateFromVal','dpRateFromDisplay');
const dpRateTo   = makePicker('dpRateToPop',   'dpRateToVal',  'dpRateToDisplay');
</script>

<?php include __DIR__ . '/_layout_end.php'; ?>
