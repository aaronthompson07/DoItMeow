-- Soft delete support for tasks
ALTER TABLE tasks
  ADD COLUMN deleted_at DATETIME NULL AFTER end_date,
  ADD KEY idx_tasks_deleted (deleted_at);
