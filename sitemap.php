<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

$base = canonical_base();

$staticPages = [
    ['loc' => '/',            'changefreq' => 'weekly',  'priority' => '1.0'],
    ['loc' => '/about.php',   'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => '/rooms.php',   'changefreq' => 'weekly',  'priority' => '0.9'],
    ['loc' => '/dining.php',  'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '/spa.php',     'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '/tours.php',   'changefreq' => 'weekly',  'priority' => '0.8'],
    ['loc' => '/agency.php',  'changefreq' => 'monthly', 'priority' => '0.6'],
    ['loc' => '/contact.php', 'changefreq' => 'monthly', 'priority' => '0.7'],
];

$rooms = [];
$tours = [];
try {
    $rooms = db_query("SELECT slug, updated_at FROM rooms WHERE is_published = TRUE ORDER BY sort_order ASC")->fetchAll();
    $tours = db_query("SELECT slug, updated_at FROM tours WHERE is_published = TRUE ORDER BY sort_order ASC")->fetchAll();
} catch (Throwable) {}
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($staticPages as $p): ?>
  <url>
    <loc><?= htmlspecialchars($base . $p['loc']) ?></loc>
    <changefreq><?= $p['changefreq'] ?></changefreq>
    <priority><?= $p['priority'] ?></priority>
  </url>
<?php endforeach; ?>
<?php foreach ($rooms as $r): ?>
  <url>
    <loc><?= htmlspecialchars($base . '/room.php?slug=' . urlencode($r['slug'])) ?></loc>
    <lastmod><?= date('Y-m-d', strtotime($r['updated_at'])) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
<?php endforeach; ?>
<?php foreach ($tours as $t): ?>
  <url>
    <loc><?= htmlspecialchars($base . '/tour.php?slug=' . urlencode($t['slug'])) ?></loc>
    <lastmod><?= date('Y-m-d', strtotime($t['updated_at'])) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
<?php endforeach; ?>
</urlset>
