-- Extend users table: first/last/pin + disable + soft delete
-- Run this in your `tasktracker` database.

ALTER TABLE users
  ADD COLUMN IF NOT EXISTS first_name VARCHAR(100) NULL AFTER name,
  ADD COLUMN IF NOT EXISTS last_name  VARCHAR(100) NULL AFTER first_name,
  ADD COLUMN IF NOT EXISTS pin        VARCHAR(10)  NULL AFTER last_name,
  ADD COLUMN IF NOT EXISTS disabled   TINYINT(1)   NOT NULL DEFAULT 0 AFTER pin,
  ADD COLUMN IF NOT EXISTS deleted_at DATETIME     NULL AFTER disabled,
  ADD KEY IF NOT EXISTS idx_users_disabled (disabled),
  ADD KEY IF NOT EXISTS idx_users_deleted (deleted_at),
  ADD UNIQUE KEY IF NOT EXISTS uq_users_pin (pin);

-- Optional backfill: split existing `name` into first/last if empty
UPDATE users
SET first_name = COALESCE(first_name, TRIM(SUBSTRING_INDEX(name,' ',1))),
    last_name  = COALESCE(last_name, NULLIF(TRIM(SUBSTRING(name, LENGTH(SUBSTRING_INDEX(name,' ',1)) + 2)), ''))
WHERE first_name IS NULL OR last_name IS NULL;

-- Keep legacy `name` in sync (one-time). Application code will prefer first+last.
