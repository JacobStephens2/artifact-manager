-- Migrate gen-1 `use_table` rows into gen-3 `uses`.
--
-- Each `use_table` row becomes a `uses` row. Gen-1 had no per-player
-- tracking, so `uses_players` is not populated — you'll see those
-- uses in `/uses/record-edit.php` with an empty People List that you
-- can fill in retroactively if desired.
--
-- Artifact ID resolution happens in two passes:
--
--   1. Direct match: `use_table.ObjectName` is itself a valid `games.id`.
--      (The dominant case: 522 rows in the current data.)
--   2. Name-match fallback: `use_table.ObjectName` points at an
--      `objects.ID` whose `ObjectName` (string title) matches a
--      `games.Title`. We use MIN(games.id) when more than one game
--      shares that title so the migration stays deterministic.
--      (Recovers another 26 rows in the current data.)
--
-- Rows whose ObjectName can't be resolved either way (166 rows
-- currently) are SKIPPED. They'd land with an artifact_id that
-- references a missing game and would be unreachable from the UI.
-- They stay in `use_table` so you can revisit later if you want to
-- recreate the missing games rows.
--
-- Idempotent: only inserts (user_id, artifact_id, use_date) combos
-- that don't already exist in `uses`. Re-running is a no-op.
--
-- Run order:
--   1. BACK UP THE DATABASE FIRST.
--   2. Run the dry-run to see what would be inserted.
--   3. Run this migration in a transaction.

START TRANSACTION;

INSERT INTO uses (artifact_id, use_date, user_id, note, notesTwo)
SELECT
  resolved.artifact_id,
  resolved.use_date,
  resolved.user_id,
  NULL AS note,
  NULL AS notesTwo
FROM (
  SELECT DISTINCT
    COALESCE(g_direct.id, g_name.id) AS artifact_id,
    ut.UseDate                       AS use_date,
    ut.user_id                       AS user_id
  FROM use_table ut
  LEFT JOIN games g_direct
    ON g_direct.id = ut.ObjectName
  LEFT JOIN (
    -- objects.ID -> deterministic games.id when objects.ObjectName matches
    -- a games.Title. MIN() handles titles that exist in `games` more than
    -- once (currently just "The Responsible Self").
    SELECT o.ID AS object_id, MIN(g.id) AS id
    FROM objects o
    INNER JOIN games g ON g.Title = o.ObjectName
    GROUP BY o.ID
  ) g_name
    ON g_name.object_id = ut.ObjectName
  WHERE ut.ObjectName IS NOT NULL
    AND ut.UseDate    IS NOT NULL
    AND ut.user_id    IS NOT NULL
    AND COALESCE(g_direct.id, g_name.id) IS NOT NULL
) AS resolved
LEFT JOIN uses u
  ON u.artifact_id = resolved.artifact_id
  AND u.use_date   = resolved.use_date
  AND u.user_id    = resolved.user_id
WHERE u.id IS NULL;

COMMIT;

-- Post-migration sanity check. Should return 0 rows when migration
-- is complete (excluding the unresolvable orphans):
--
-- SELECT ut.id, ut.user_id, ut.ObjectName, ut.UseDate, o.ObjectName AS object_name
-- FROM use_table ut
-- LEFT JOIN objects o ON o.ID = ut.ObjectName
-- LEFT JOIN games g_direct ON g_direct.id = ut.ObjectName
-- LEFT JOIN (
--   SELECT o.ID AS object_id, MIN(g.id) AS id
--   FROM objects o INNER JOIN games g ON g.Title = o.ObjectName
--   GROUP BY o.ID
-- ) g_name ON g_name.object_id = ut.ObjectName
-- LEFT JOIN uses u
--   ON u.artifact_id = COALESCE(g_direct.id, g_name.id)
--   AND u.use_date   = ut.UseDate
--   AND u.user_id    = ut.user_id
-- WHERE ut.ObjectName IS NOT NULL
--   AND ut.UseDate    IS NOT NULL
--   AND ut.user_id    IS NOT NULL
--   AND COALESCE(g_direct.id, g_name.id) IS NOT NULL
--   AND u.id IS NULL;
