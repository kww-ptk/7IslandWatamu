-- Seven Islands Resort — Seed Data
-- Run AFTER schema.sql: psql $DATABASE_URL -f db/seed.sql

-- Default settings
INSERT INTO settings (setting_key, setting_value) VALUES
    ('form_mode',      'enquiry'),
    ('notify_email',   'reservation@sevenislandswatamu.com'),
    ('site_currency',  'USD')
ON CONFLICT (setting_key) DO NOTHING;

-- 6 Rooms
INSERT INTO rooms (slug, name, price_amount, price_currency, size_sqm, capacity, bed_count, short_desc, long_desc, features_json, seo_title, seo_description, sort_order, is_published) VALUES

('standard', 'Standard Room', 450.00, 'USD', 55, 6, 2,
 'Spacious sea-view room with private balcony and warm Swahili interiors.',
 'All our Standard rooms have big windows to help you take a broad view of the ocean and gardens. We offer comfortable beds and every bathroom has a bathtub and shower, which brings relaxation after a long day. Fast WiFi, satellite TV and international standard electric sockets are standard throughout the resort.',
 '["24-hour in-room dining","Coffee set","Safe box","Ambassador Service","Turndown Service","Hairdryer","Air conditioning","WiFi","Minibar with premium drinks","Sitting Room Area","Direct phone","Resort flat screen TV"]',
 'Standard Room — Seven Islands Resort, Watamu',
 'Spacious 55m² standard room with ocean view, private balcony and all-inclusive amenities at Seven Islands Resort, Watamu Kenya.',
 1, TRUE),

('double', 'Double Room', 300.00, 'USD', 60, 3, 2,
 'Comfortable double room with garden views and all-inclusive amenities.',
 'Our Double rooms offer a relaxing retreat with lush garden views and a private balcony. Featuring two comfortable beds, a spacious bathroom with bathtub and shower, and all the modern amenities you need for a perfect stay.',
 '["24-hour in-room dining","Coffee set","Safe box","Air conditioning","WiFi","Minibar","Flat screen TV","Private balcony","Hairdryer"]',
 'Double Room — Seven Islands Resort, Watamu',
 'Comfortable 60m² double room with garden views and private balcony at Seven Islands Resort, Watamu Kenya.',
 2, TRUE),

('king-size', 'King Size Room', 500.00, 'USD', 80, 7, 3,
 'Our largest standard room with a king-size bed and panoramic ocean views.',
 'The King Size Room is our most spacious standard offering — 80 square metres of elegantly furnished space with panoramic views of the Indian Ocean. Three beds accommodate families or groups comfortably, with a full suite of amenities.',
 '["24-hour in-room dining","Coffee set","Safe box","Ambassador Service","Turndown Service","Hairdryer","Air conditioning","WiFi","Minibar with premium drinks","Sitting Room Area","Direct phone","Resort flat screen TV","Private balcony","Ocean view"]',
 'King Size Room — Seven Islands Resort, Watamu',
 'Spacious 80m² king size room with panoramic ocean views at Seven Islands Resort, Watamu Kenya.',
 3, TRUE),

('junior-suite', 'Junior Suite', 399.00, 'USD', 50, 4, 2,
 'Elegant junior suite with separate sitting area and Indian Ocean views.',
 'The Junior Suite offers a refined experience with a separate sitting area, premium furnishings and stunning views of the Indian Ocean. Perfect for couples or small families looking for that extra touch of luxury.',
 '["24-hour in-room dining","Coffee set","Safe box","Ambassador Service","Turndown Service","Hairdryer","Air conditioning","WiFi","Minibar with premium drinks","Sitting Room Area","Direct phone","Resort flat screen TV","Ocean view"]',
 'Junior Suite — Seven Islands Resort, Watamu',
 'Elegant 50m² junior suite with ocean views and separate sitting area at Seven Islands Resort, Watamu Kenya.',
 4, TRUE),

('classic-single', 'Classic Single Room', 250.00, 'USD', 45, 2, 2,
 'Cosy and well-appointed room, ideal for solo travellers or couples.',
 'The Classic Single Room is a cosy and thoughtfully appointed retreat — ideal for solo travellers or couples. At 45 square metres, it features all the comforts you need with the warm Swahili character the resort is known for.',
 '["Coffee set","Safe box","Air conditioning","WiFi","Flat screen TV","Private balcony","Hairdryer","Direct phone"]',
 'Classic Single Room — Seven Islands Resort, Watamu',
 'Cosy 45m² classic single room with Swahili interiors at Seven Islands Resort, Watamu Kenya.',
 5, TRUE),

('luxury-suite', 'Luxury Suite', 450.00, 'USD', 60, 6, 2,
 'Our premier suite offering butler service, a plunge pool and ocean views.',
 'The Luxury Suite is the ultimate expression of Swahili coastal living. At 60 square metres, it features a private plunge pool, butler service, premium minibar and breathtaking views of the Indian Ocean — a truly unforgettable stay.',
 '["24-hour in-room dining","Coffee set","Safe box","Ambassador Service","Turndown Service","Hairdryer","Air conditioning","WiFi","Minibar with premium drinks","Sitting Room Area","Direct phone","Resort flat screen TV","Private plunge pool","Butler service","Ocean view"]',
 'Luxury Suite — Seven Islands Resort, Watamu',
 'Premier 60m² luxury suite with private plunge pool and butler service at Seven Islands Resort, Watamu Kenya.',
 6, TRUE);

-- Room images (using existing assets)
INSERT INTO room_images (room_id, filename, alt_text, is_hero, sort_order) VALUES
-- Standard Room
((SELECT id FROM rooms WHERE slug='standard'), '7islands_resort_watamu14.webp', 'Standard Room at Seven Islands Resort', TRUE,  1),
((SELECT id FROM rooms WHERE slug='standard'), '7islands_resort_watamu9.webp',  'Standard Room interior',                FALSE, 2),
((SELECT id FROM rooms WHERE slug='standard'), '7islands_resort_watamu10.webp', 'Standard Room view',                    FALSE, 3),
((SELECT id FROM rooms WHERE slug='standard'), '7islands_resort_watamu5_Beach.webp', 'Beach view from Standard Room',   FALSE, 4),
((SELECT id FROM rooms WHERE slug='standard'), '7islands_resort_watamu2.webp',  'Standard Room balcony',                 FALSE, 5),

-- Double Room
((SELECT id FROM rooms WHERE slug='double'), '7islands_resort_watamu9.webp',  'Double Room at Seven Islands Resort',     TRUE,  1),
((SELECT id FROM rooms WHERE slug='double'), '7islands_resort_watamu14.webp', 'Double Room interior',                    FALSE, 2),
((SELECT id FROM rooms WHERE slug='double'), '7islands_resort_watamu2.webp',  'Double Room garden view',                 FALSE, 3),

-- King Size Room
((SELECT id FROM rooms WHERE slug='king-size'), '7islands_resort_watamu10.webp', 'King Size Room at Seven Islands Resort', TRUE,  1),
((SELECT id FROM rooms WHERE slug='king-size'), '7islands_resort_watamu14.webp', 'King Size Room interior',                FALSE, 2),
((SELECT id FROM rooms WHERE slug='king-size'), '7islands_resort_watamu3.webp',  'King Size Room ocean view',              FALSE, 3),

-- Junior Suite
((SELECT id FROM rooms WHERE slug='junior-suite'), '7islands_resort_watamu14.webp', 'Junior Suite at Seven Islands Resort', TRUE,  1),
((SELECT id FROM rooms WHERE slug='junior-suite'), '7islands_resort_watamu10.webp', 'Junior Suite sitting area',            FALSE, 2),
((SELECT id FROM rooms WHERE slug='junior-suite'), '7islands_resort_watamu6.webp',  'Junior Suite view',                    FALSE, 3),

-- Classic Single Room
((SELECT id FROM rooms WHERE slug='classic-single'), '7islands_resort_watamu9.webp',  'Classic Single Room at Seven Islands Resort', TRUE,  1),
((SELECT id FROM rooms WHERE slug='classic-single'), '7islands_resort_watamu14.webp', 'Classic Single Room interior',               FALSE, 2),

-- Luxury Suite
((SELECT id FROM rooms WHERE slug='luxury-suite'), '7islands_resort_watamu10.webp', 'Luxury Suite at Seven Islands Resort', TRUE,  1),
((SELECT id FROM rooms WHERE slug='luxury-suite'), '7islands_resort_watamu7.webp',  'Luxury Suite plunge pool',             FALSE, 2),
((SELECT id FROM rooms WHERE slug='luxury-suite'), '7islands_resort_watamu6.webp',  'Luxury Suite ocean view',              FALSE, 3);
