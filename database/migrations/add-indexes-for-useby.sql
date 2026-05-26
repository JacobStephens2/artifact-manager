-- Indexes that make the interact-by-date (`use_by`) query fast.
--
-- Before: EXPLAIN shows full scans on `games` (3,860 rows) and `uses`
-- (5,405 rows, hash joined) because neither games.user_id nor
-- uses.artifact_id is indexed. The query takes ~445ms for one user.
--
-- Idempotent: each ALTER is wrapped in a guard that skips if the
-- index already exists (MySQL 5.7+ via INFORMATION_SCHEMA).

-- uses(artifact_id, use_date): primary win for the games←uses join.
-- (artifact_id alone would be enough; including use_date lets the
-- planner use the index for the MAX() in the subquery too.)
SET @exists := (
  SELECT COUNT(*) FROM information_schema.statistics
  WHERE table_schema = DATABASE()
    AND table_name = 'uses'
    AND index_name = 'idx_uses_artifact_date'
);
SET @sql := IF(@exists = 0,
  'CREATE INDEX idx_uses_artifact_date ON uses (artifact_id, use_date)',
  'SELECT "idx_uses_artifact_date already exists" AS note');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- games(user_id, KeptCol, to_get_rid_of): lets the use_by filter
-- jump straight to the ~500 rows for the current user.
SET @exists := (
  SELECT COUNT(*) FROM information_schema.statistics
  WHERE table_schema = DATABASE()
    AND table_name = 'games'
    AND index_name = 'idx_games_user_kept'
);
SET @sql := IF(@exists = 0,
  'CREATE INDEX idx_games_user_kept ON games (user_id, KeptCol, to_get_rid_of)',
  'SELECT "idx_games_user_kept already exists" AS note');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
