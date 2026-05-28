-- Migration: enrich seeded tours with long descriptions, highlights and durations.
-- Idempotent — safe to re-run. Only updates rows that match by slug.
-- Run: psql $DATABASE_URL -f db/migrations/enrich_tours.sql

-- 1. Tsavo East (demo tour)
UPDATE tours SET
  duration = '2 days / 1 night',
  long_desc = 'Tsavo East is Kenya''s largest national park and the closest great wilderness to Watamu — a few hours by road inland from the coast. Its red earth, vast open plains and famous "red elephants" coated in Tsavo dust make for an unmistakable first safari.\n\nFrom the seasonal banks of the Galana River to the Yatta Plateau — the world''s longest lava flow — Tsavo East rewards visitors with sightings of elephant, lion, buffalo, giraffe, zebra and an exceptional variety of birdlife. Game drives are unhurried, with time to stop, observe and take in the silence of the bush.',
  highlights_json = '["Game drives across the open red-earth plains","Encounters with Tsavo''s famous red elephants","Sunset over the Yatta Plateau","Stops at Aruba Dam and the Galana River","Overnight at a tented camp or safari lodge inside the park"]'::jsonb
WHERE slug = 'tsavo-east';

-- Bump updated_at so admin shows fresh dates
UPDATE tours SET updated_at = NOW() WHERE long_desc IS NOT NULL AND long_desc != '';
