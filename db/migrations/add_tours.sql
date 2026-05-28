-- Migration: add tours feature
-- Run: psql $DATABASE_URL -f db/migrations/add_tours.sql

-- Tours
CREATE TABLE IF NOT EXISTS tours (
    id               SERIAL PRIMARY KEY,
    slug             VARCHAR(100) NOT NULL UNIQUE,
    name             VARCHAR(255) NOT NULL,
    category         VARCHAR(20)  NOT NULL DEFAULT 'classic',
    tag_label        VARCHAR(100),
    duration         VARCHAR(100),
    short_desc       TEXT,
    long_desc        TEXT,
    highlights_json  JSONB        NOT NULL DEFAULT '[]',
    seo_title        VARCHAR(255),
    seo_description  VARCHAR(320),
    sort_order       INT          NOT NULL DEFAULT 0,
    is_published     BOOLEAN      NOT NULL DEFAULT TRUE,
    updated_at       TIMESTAMP    NOT NULL DEFAULT NOW()
);

-- Tour images
CREATE TABLE IF NOT EXISTS tour_images (
    id         SERIAL PRIMARY KEY,
    tour_id    INT          NOT NULL REFERENCES tours(id) ON DELETE CASCADE,
    filename   VARCHAR(255) NOT NULL,
    alt_text   VARCHAR(255),
    is_hero    BOOLEAN      NOT NULL DEFAULT FALSE,
    sort_order INT          NOT NULL DEFAULT 0,
    created_at TIMESTAMP    NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_tour_images_tour_id ON tour_images(tour_id);

-- Add tour_id to submissions
ALTER TABLE submissions ADD COLUMN IF NOT EXISTS tour_id INT REFERENCES tours(id) ON DELETE SET NULL;

-- Seed: 1 demo tour
INSERT INTO tours (slug, name, category, tag_label, duration, short_desc, sort_order) VALUES
('tsavo-east', 'Tsavo East', 'classic', 'Classic Safari', NULL, 'A first encounter with Kenya''s red-earth wilderness and its famous elephants — the closest of the great parks to Watamu.', 1)
ON CONFLICT (slug) DO NOTHING;
