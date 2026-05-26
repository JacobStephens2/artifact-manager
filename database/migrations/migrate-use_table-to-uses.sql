-- Migrate gen-1 `use_table` rows into gen-3 `uses`.
--
-- Each `use_table` row becomes a `uses` row. Gen-1 had no per-player
-- tracking, so `uses_players` is not populated — you'll see those uses
-- in `/uses/record-edit.php` with an empty People List that you can
-- fill in retroactively if desired.
--
-- ASSUMPTIONS YOU MUST VERIFY BEFORE RUNNING:
--   1. `use_table.ObjectName` is an integer FK matching `games.id`.
--      If gen-1 used a separate `objects` table whose IDs DO NOT
--      match `games.id`, this migration will produce orphaned
--      `uses.artifact_id` values. Verify with:
--        SELECT COUNT(*) FROM use_table
--        WHERE ObjectName NOT IN (SELECT id FROM games);
--      Expected: 0. If non-zero, stop and tell me — we need to add
--      an objects.ID → games.id mapping step first.
--   2. `use_table.UseDate` is in a DATE-compatible format.
--   3. `use_table.user_id` matches `users.id` and is non-null.
--
-- Idempotent: only inserts (user_id, artifact_id, use_date) combos
-- that don't already exist in `uses`.
--
-- Run order:
--   1. BACK UP THE DATABASE FIRST.
--   2. Run the verification SELECT above.
--   3. Run this migration in a transaction.

START TRANSACTION;

INSERT INTO uses (artifact_id, use_date, user_id, note, notesTwo)
SELECT
  ut.ObjectName AS artifact_id,
  ut.UseDate    AS use_date,
  ut.user_id    AS user_id,
  NULL          AS note,
  NULL          AS notesTwo
FROM use_table ut
LEFT JOIN uses u
  ON u.artifact_id = ut.ObjectName
  AND u.use_date   = ut.UseDate
  AND u.user_id    = ut.user_id
WHERE ut.ObjectName IS NOT NULL
  AND ut.UseDate    IS NOT NULL
  AND ut.user_id    IS NOT NULL
  AND u.id          IS NULL;

COMMIT;

-- Post-migration sanity check. Should return 0 rows when migration is
-- complete:
--
-- SELECT ut.id, ut.user_id, ut.ObjectName, ut.UseDate
-- FROM use_table ut
-- LEFT JOIN uses u
--   ON u.artifact_id = ut.ObjectName
--   AND u.use_date   = ut.UseDate
--   AND u.user_id    = ut.user_id
-- WHERE ut.ObjectName IS NOT NULL
--   AND ut.UseDate    IS NOT NULL
--   AND ut.user_id    IS NOT NULL
--   AND u.id          IS NULL;
