-- audit_log table for TaskTracker
CREATE TABLE IF NOT EXISTS audit_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  happened_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  action VARCHAR(100) NOT NULL,
  entity VARCHAR(50) NOT NULL,
  entity_id INT NULL,
  actor VARCHAR(50) NOT NULL,
  ip VARCHAR(45) NULL,
  details TEXT NULL,
  KEY idx_when (happened_at),
  KEY idx_action (action),
  KEY idx_entity (entity, entity_id)
);
