-- Enforce 6-digit numeric PINs (nullable) and keep uniqueness
ALTER TABLE users
  MODIFY pin VARCHAR(6) NULL,
  ADD CONSTRAINT chk_users_pin_format CHECK (pin IS NULL OR pin REGEXP '^[0-9]{6}$');

-- If you're on MySQL < 8.0.16 where CHECK is parsed but ignored,
-- the server-side validation in api_users.php will enforce the rule.
