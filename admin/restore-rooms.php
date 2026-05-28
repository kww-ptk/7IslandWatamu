<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$pdo = db();
$results = [];

function run(PDO $pdo, string $label, string $sql, array $params = []): string {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return "✓ $label";
    } catch (PDOException $e) {
        return "✗ $label — " . $e->getMessage();
    }
}

// ── Rooms ────────────────────────────────────────────────────────────────────

$rooms = [
    ['standard', 'Standard Room', 450.00, 'USD', 55, 6, 2,
     'Spacious sea-view room with private balcony and warm Swahili interiors.',
     'All our Standard rooms have big windows to help you take a broad view of the ocean and gardens. We offer comfortable beds and every bathroom has a bathtub and shower, which brings relaxation after a long day. Fast WiFi, satellite TV and international standard electric sockets are standard throughout the resort.',
     '["24-hour in-room dining","Coffee set","Safe box","Ambassador Service","Turndown Service","Hairdryer","Air conditioning","WiFi","Minibar with premium drinks","Sitting Room Area","Direct phone","Resort flat screen TV"]',
     'Standard Room — Seven Islands Resort, Watamu',
     'Spacious 55m² standard room with ocean view, private balcony and all-inclusive amenities at Seven Islands Resort, Watamu Kenya.',
     1],
    ['double', 'Double Room', 300.00, 'USD', 60, 3, 2,
     'Comfortable double room with garden views and all-inclusive amenities.',
     'Our Double rooms offer a relaxing retreat with lush garden views and a private balcony. Featuring two comfortable beds, a spacious bathroom with bathtub and shower, and all the modern amenities you need for a perfect stay.',
     '["24-hour in-room dining","Coffee set","Safe box","Air conditioning","WiFi","Minibar","Flat screen TV","Private balcony","Hairdryer"]',
     'Double Room — Seven Islands Resort, Watamu',
     'Comfortable 60m² double room with garden views and private balcony at Seven Islands Resort, Watamu Kenya.',
     2],
    ['king-size', 'King Size Room', 500.00, 'USD', 80, 7, 3,
     'Our largest standard room with a king-size bed and panoramic ocean views.',
     'The King Size Room is our most spacious standard offering — 80 square metres of elegantly furnished space with panoramic views of the Indian Ocean. Three beds accommodate families or groups comfortably, with a full suite of amenities.',
     '["24-hour in-room dining","Coffee set","Safe box","Ambassador Service","Turndown Service","Hairdryer","Air conditioning","WiFi","Minibar with premium drinks","Sitting Room Area","Direct phone","Resort flat screen TV","Private balcony","Ocean view"]',
     'King Size Room — Seven Islands Resort, Watamu',
     'Spacious 80m² king size room with panoramic ocean views at Seven Islands Resort, Watamu Kenya.',
     3],
    ['junior-suite', 'Junior Suite', 399.00, 'USD', 50, 4, 2,
     'Elegant junior suite with separate sitting area and Indian Ocean views.',
     'The Junior Suite offers a refined experience with a separate sitting area, premium furnishings and stunning views of the Indian Ocean. Perfect for couples or small families looking for that extra touch of luxury.',
     '["24-hour in-room dining","Coffee set","Safe box","Ambassador Service","Turndown Service","Hairdryer","Air conditioning","WiFi","Minibar with premium drinks","Sitting Room Area","Direct phone","Resort flat screen TV","Ocean view"]',
     'Junior Suite — Seven Islands Resort, Watamu',
     'Elegant 50m² junior suite with ocean views and separate sitting area at Seven Islands Resort, Watamu Kenya.',
     4],
    ['classic-single', 'Classic Single Room', 250.00, 'USD', 45, 2, 2,
     'Cosy and well-appointed room, ideal for solo travellers or couples.',
     'The Classic Single Room is a cosy and thoughtfully appointed retreat — ideal for solo travellers or couples. At 45 square metres, it features all the comforts you need with the warm Swahili character the resort is known for.',
     '["Coffee set","Safe box","Air conditioning","WiFi","Flat screen TV","Private balcony","Hairdryer","Direct phone"]',
     'Classic Single Room — Seven Islands Resort, Watamu',
     'Cosy 45m² classic single room with Swahili interiors at Seven Islands Resort, Watamu Kenya.',
     5],
    ['luxury-suite', 'Luxury Suite', 450.00, 'USD', 60, 6, 2,
     'Our premier suite offering butler service, a plunge pool and ocean views.',
     'The Luxury Suite is the ultimate expression of Swahili coastal living. At 60 square metres, it features a private plunge pool, butler service, premium minibar and breathtaking views of the Indian Ocean — a truly unforgettable stay.',
     '["24-hour in-room dining","Coffee set","Safe box","Ambassador Service","Turndown Service","Hairdryer","Air conditioning","WiFi","Minibar with premium drinks","Sitting Room Area","Direct phone","Resort flat screen TV","Private plunge pool","Butler service","Ocean view"]',
     'Luxury Suite — Seven Islands Resort, Watamu',
     'Premier 60m² luxury suite with private plunge pool and butler service at Seven Islands Resort, Watamu Kenya.',
     6],
];

$roomSql = 'INSERT INTO rooms (slug,name,price_amount,price_currency,size_sqm,capacity,bed_count,short_desc,long_desc,features_json,seo_title,seo_description,sort_order,is_published)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,TRUE) ON CONFLICT (slug) DO NOTHING';

foreach ($rooms as $r) {
    $results[] = run($pdo, "Room: {$r[1]}", $roomSql, $r);
}

// ── Images ───────────────────────────────────────────────────────────────────

$imgSql = 'INSERT INTO room_images (room_id,filename,alt_text,is_hero,sort_order)
           SELECT id,?,?,?,? FROM rooms WHERE slug=? ON CONFLICT DO NOTHING';

$images = [
    ['7islands_resort_watamu14.webp',      'Standard Room at Seven Islands Resort',       TRUE,  1, 'standard'],
    ['7islands_resort_watamu9.webp',       'Standard Room interior',                      FALSE, 2, 'standard'],
    ['7islands_resort_watamu10.webp',      'Standard Room view',                          FALSE, 3, 'standard'],
    ['7islands_resort_watamu5_Beach.webp', 'Beach view from Standard Room',               FALSE, 4, 'standard'],
    ['7islands_resort_watamu2.webp',       'Standard Room balcony',                       FALSE, 5, 'standard'],
    ['7islands_resort_watamu9.webp',       'Double Room at Seven Islands Resort',          TRUE,  1, 'double'],
    ['7islands_resort_watamu14.webp',      'Double Room interior',                         FALSE, 2, 'double'],
    ['7islands_resort_watamu2.webp',       'Double Room garden view',                      FALSE, 3, 'double'],
    ['7islands_resort_watamu10.webp',      'King Size Room at Seven Islands Resort',       TRUE,  1, 'king-size'],
    ['7islands_resort_watamu14.webp',      'King Size Room interior',                      FALSE, 2, 'king-size'],
    ['7islands_resort_watamu3.webp',       'King Size Room ocean view',                    FALSE, 3, 'king-size'],
    ['7islands_resort_watamu14.webp',      'Junior Suite at Seven Islands Resort',         TRUE,  1, 'junior-suite'],
    ['7islands_resort_watamu10.webp',      'Junior Suite sitting area',                    FALSE, 2, 'junior-suite'],
    ['7islands_resort_watamu6.webp',       'Junior Suite view',                            FALSE, 3, 'junior-suite'],
    ['7islands_resort_watamu9.webp',       'Classic Single Room at Seven Islands Resort',  TRUE,  1, 'classic-single'],
    ['7islands_resort_watamu14.webp',      'Classic Single Room interior',                 FALSE, 2, 'classic-single'],
    ['7islands_resort_watamu10.webp',      'Luxury Suite at Seven Islands Resort',         TRUE,  1, 'luxury-suite'],
    ['7islands_resort_watamu7.webp',       'Luxury Suite plunge pool',                     FALSE, 2, 'luxury-suite'],
    ['7islands_resort_watamu6.webp',       'Luxury Suite ocean view',                      FALSE, 3, 'luxury-suite'],
];

foreach ($images as $img) {
    $results[] = run($pdo, "Image: {$img[4]} / {$img[0]}", $imgSql, $img);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Restore Rooms</title>
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
<h2>Room Restore Results</h2>
<ul>
<?php foreach ($results as $r): ?>
  <li class="<?= str_starts_with($r, '✓') ? 'ok' : 'err' ?>"><?= htmlspecialchars($r) ?></li>
<?php endforeach; ?>
</ul>
<div class="note">
  <strong>Done.</strong> Delete <code>admin/restore-rooms.php</code> from the repo now — it is no longer needed.
</div>
</body>
</html>
