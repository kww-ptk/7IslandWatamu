<?php
// One-time helper: add bookable units to any room that currently has none.
// Rooms that already have at least one unit (e.g. Luxury Suite) are skipped,
// so this is safe to load more than once.
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$pdo = db();
$UNITS_PER_ROOM = 3;
$names = ['Unit A', 'Unit B', 'Unit C', 'Unit D', 'Unit E', 'Unit F'];
$results = [];

$rooms = $pdo->query(
    "SELECT r.id, r.name
     FROM rooms r
     LEFT JOIN units u ON u.room_id = r.id
     GROUP BY r.id, r.name
     HAVING COUNT(u.id) = 0
     ORDER BY r.sort_order, r.id"
)->fetchAll();

if (!$rooms) {
    $results[] = ['ok' => true, 'msg' => 'Nothing to do — every room already has at least one unit.'];
}

foreach ($rooms as $r) {
    try {
        $pdo->beginTransaction();
        $ins = $pdo->prepare('INSERT INTO units (room_id, name, sort_order) VALUES (:rid, :name, :ord)');
        for ($i = 0; $i < $UNITS_PER_ROOM; $i++) {
            $ins->execute([
                ':rid'  => $r['id'],
                ':name' => $names[$i] ?? ('Unit ' . ($i + 1)),
                ':ord'  => $i + 1,
            ]);
        }
        $pdo->commit();
        $results[] = ['ok' => true, 'msg' => "Added {$UNITS_PER_ROOM} units to: {$r['name']}"];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $results[] = ['ok' => false, 'msg' => "Failed for {$r['name']} — " . $e->getMessage()];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Seed Units</title>
<style>
  body { font-family: monospace; padding: 40px; background: #f5f5f5; }
  h2 { margin-bottom: 20px; }
  ul { list-style: none; padding: 0; }
  li { padding: 4px 0; }
  .ok { color: #1a7f37; }
  .err { color: #cf222e; }
  .note { margin-top: 30px; padding: 16px; background: #fff3cd; border-left: 4px solid #f0ad4e; }
</style>
</head>
<body>
<h2>Seed Units Results</h2>
<ul>
<?php foreach ($results as $r): ?>
  <li class="<?= $r['ok'] ? 'ok' : 'err' ?>"><?= ($r['ok'] ? '✓ ' : '✗ ') . htmlspecialchars($r['msg']) ?></li>
<?php endforeach; ?>
</ul>
<div class="note">
  <strong>Done.</strong> Each affected room now has <?= (int)$UNITS_PER_ROOM ?> bookable units (Unit A/B/C).
  You can rename, deactivate, or add more per room under <strong>Rooms &rarr; (room) &rarr; Units</strong>.
  Then delete <code>admin/seed-units.php</code> from the repo — it is no longer needed.
</div>
</body>
</html>
