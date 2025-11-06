-- Require 6-digit PINs for all users
-- Backfill any missing pins deterministically, then enforce NOT NULL + format + uniqueness

-- Backfill: set a 6-digit PIN where missing (uses id to avoid collisions for typical table sizes)
UPDATE users
SET pin = LPAD(100000 + (id % 900000), 6, '0')
WHERE pin IS NULL OR pin NOT REGEXP '^[0-9]{6}$';

-- Enforce required and format
ALTER TABLE users
  MODIFY pin VARCHAR(6) NOT NULL;

-- MySQL 8.0.16+ CHECK (older versions parse but ignore; server-side API validation also enforces)
ALTER TABLE users
  DROP CHECK IF EXISTS chk_users_pin_format;

ALTER TABLE users
  ADD CONSTRAINT chk_users_pin_format CHECK (pin REGEXP '^[0-9]{6}$');

-- Ensure uniqueness (create if missing)
CREATE UNIQUE INDEX uq_users_pin ON users(pin);
