-- Seven Islands Resort — PostgreSQL Schema
-- Run: psql $DATABASE_URL -f db/schema.sql

-- Admin users
CREATE TABLE IF NOT EXISTS admin_users (
    id            SERIAL PRIMARY KEY,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    last_login_at TIMESTAMP
);

-- Rooms
CREATE TABLE IF NOT EXISTS rooms (
    id               SERIAL PRIMARY KEY,
    slug             VARCHAR(100) NOT NULL UNIQUE,
    name             VARCHAR(255) NOT NULL,
    price_amount     NUMERIC(10,2) NOT NULL DEFAULT 0,
    price_currency   VARCHAR(10)  NOT NULL DEFAULT 'USD',
    price_unit       VARCHAR(50)  NOT NULL DEFAULT 'per night',
    size_sqm         INT,
    capacity         INT,
    bed_count        INT,
    short_desc       TEXT,
    long_desc        TEXT,
    features_json    JSONB        NOT NULL DEFAULT '[]',
    seo_title        VARCHAR(255),
    seo_description  VARCHAR(320),
    sort_order       INT          NOT NULL DEFAULT 0,
    is_published     BOOLEAN      NOT NULL DEFAULT TRUE,
    updated_at       TIMESTAMP    NOT NULL DEFAULT NOW()
);

-- Room images
CREATE TABLE IF NOT EXISTS room_images (
    id         SERIAL PRIMARY KEY,
    room_id    INT          NOT NULL REFERENCES rooms(id) ON DELETE CASCADE,
    filename   VARCHAR(255) NOT NULL,
    alt_text   VARCHAR(255),
    is_hero    BOOLEAN      NOT NULL DEFAULT FALSE,
    sort_order INT          NOT NULL DEFAULT 0,
    created_at TIMESTAMP    NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_room_images_room_id ON room_images(room_id);

-- Form submissions
CREATE TABLE IF NOT EXISTS submissions (
    id               SERIAL PRIMARY KEY,
    type             VARCHAR(20)  NOT NULL CHECK (type IN ('enquiry','contact','agency')),
    room_id          INT          REFERENCES rooms(id) ON DELETE SET NULL,
    guest_name       VARCHAR(255),
    guest_email      VARCHAR(255),
    guest_phone      VARCHAR(50),
    message          TEXT,
    check_in         DATE,
    check_out        DATE,
    guests_adults    INT          DEFAULT 1,
    guests_children  INT          DEFAULT 0,
    payload_json     JSONB        NOT NULL DEFAULT '{}',
    source_page      TEXT,
    referrer         TEXT,
    utm_source       VARCHAR(255),
    utm_medium       VARCHAR(255),
    utm_campaign     VARCHAR(255),
    utm_term         VARCHAR(255),
    utm_content      VARCHAR(255),
    user_agent       TEXT,
    ip_address       VARCHAR(45),
    created_at       TIMESTAMP    NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_submissions_type_created  ON submissions(type, created_at);
CREATE INDEX IF NOT EXISTS idx_submissions_room_id       ON submissions(room_id);

-- Settings (key-value store)
CREATE TABLE IF NOT EXISTS settings (
    setting_key   VARCHAR(100) PRIMARY KEY,
    setting_value TEXT         NOT NULL DEFAULT '',
    updated_at    TIMESTAMP    NOT NULL DEFAULT NOW()
);

-- Login attempts (brute-force protection)
CREATE TABLE IF NOT EXISTS login_attempts (
    id         SERIAL PRIMARY KEY,
    email      VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45)  NOT NULL,
    success    BOOLEAN      NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP    NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_login_attempts_email     ON login_attempts(email, created_at);
CREATE INDEX IF NOT EXISTS idx_login_attempts_ip        ON login_attempts(ip_address, created_at);
