-- Seven Islands Resort — Seed Data
-- Run AFTER schema.sql: psql $DATABASE_URL -f db/seed.sql

-- Default settings
INSERT INTO settings (setting_key, setting_value) VALUES
    ('form_mode',      'enquiry'),
    ('notify_email',   'reservation@sevenislandswatamu.com'),
    ('site_currency',  'USD')
ON CONFLICT (setting_key) DO NOTHING;

-- 1 Demo Room
INSERT INTO rooms (slug, name, price_amount, price_currency, size_sqm, capacity, bed_count, short_desc, long_desc, features_json, seo_title, seo_description, sort_order, is_published) VALUES

('standard', 'Standard Room', 450.00, 'USD', 55, 6, 2,
 'Spacious sea-view room with private balcony and warm Swahili interiors.',
 'All our Standard rooms have big windows to help you take a broad view of the ocean and gardens. We offer comfortable beds and every bathroom has a bathtub and shower, which brings relaxation after a long day. Fast WiFi, satellite TV and international standard electric sockets are standard throughout the resort.',
 '["24-hour in-room dining","Coffee set","Safe box","Ambassador Service","Turndown Service","Hairdryer","Air conditioning","WiFi","Minibar with premium drinks","Sitting Room Area","Direct phone","Resort flat screen TV"]',
 'Standard Room — Seven Islands Resort, Watamu',
 'Spacious 55m² standard room with ocean view, private balcony and all-inclusive amenities at Seven Islands Resort, Watamu Kenya.',
 1, TRUE);

-- Room images (using existing assets)
INSERT INTO room_images (room_id, filename, alt_text, is_hero, sort_order) VALUES
-- Standard Room
((SELECT id FROM rooms WHERE slug='standard'), '7islands_resort_watamu14.webp', 'Standard Room at Seven Islands Resort', TRUE,  1),
((SELECT id FROM rooms WHERE slug='standard'), '7islands_resort_watamu9.webp',  'Standard Room interior',                FALSE, 2),
((SELECT id FROM rooms WHERE slug='standard'), '7islands_resort_watamu10.webp', 'Standard Room view',                    FALSE, 3),
((SELECT id FROM rooms WHERE slug='standard'), '7islands_resort_watamu5_Beach.webp', 'Beach view from Standard Room',   FALSE, 4),
((SELECT id FROM rooms WHERE slug='standard'), '7islands_resort_watamu2.webp',  'Standard Room balcony',                 FALSE, 5);
