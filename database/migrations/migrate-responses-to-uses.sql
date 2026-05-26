-- Migrate gen-2 `responses` rows into gen-3 `uses` + `uses_players`.
--
-- Each (user_id, Title, PlayDate) group in `responses` becomes a single
-- `uses` row. Each `responses.Player` within the group becomes a row in
-- `uses_players` tied to that new use.
--
-- Idempotent: safe to re-run. New uses are only inserted for groups
-- that don't already exist in `uses`; player links are only inserted
-- when not already present in `uses_players`.
--
-- Aversion rows in `responses` (AversionDate > 0, PlayDate IS NULL)
-- are left alone — the aversions feature continues to read from
-- `responses` directly.
--
-- Run order:
--   1. BACK UP THE DATABASE FIRST.
--   2. Verify the SELECT-only query at the end of this file matches your
--      expectations.
--   3. Run the migration in a transaction. If the row counts surprise
--      you, ROLLBACK and reach out.

START TRANSACTION;

-- 1. Create one `uses` row per (user_id, artifact, date) group that does
--    not already exist. Notes from multiple rows in the same group are
--    joined with " | " so nothing is lost (most groups will only have
--    one non-empty note in practice).
INSERT INTO uses (artifact_id, use_date, user_id, note, notesTwo)
SELECT
  r.Title       AS artifact_id,
  r.PlayDate    AS use_date,
  r.user_id     AS user_id,
  NULLIF(GROUP_CONCAT(DISTINCT NULLIF(TRIM(r.Note), '') SEPARATOR ' | '), '') AS note,
  NULL          AS notesTwo
FROM responses r
LEFT JOIN uses u
  ON u.artifact_id = r.Title
  AND u.use_date   = r.PlayDate
  AND u.user_id    = r.user_id
WHERE r.PlayDate IS NOT NULL
  AND r.Title    IS NOT NULL
  AND r.user_id  IS NOT NULL
  AND u.id       IS NULL
GROUP BY r.user_id, r.Title, r.PlayDate;

-- 2. Link each responses.Player to the corresponding uses row. Skips
--    pairs that already exist so re-runs don't create duplicates.
INSERT INTO uses_players (use_id, player_id, user_id)
SELECT
  u.id    AS use_id,
  r.Player AS player_id,
  r.user_id AS user_id
FROM responses r
INNER JOIN uses u
  ON u.artifact_id = r.Title
  AND u.use_date   = r.PlayDate
  AND u.user_id    = r.user_id
LEFT JOIN uses_players up
  ON up.use_id    = u.id
  AND up.player_id = r.Player
  AND up.user_id  = r.user_id
WHERE r.PlayDate IS NOT NULL
  AND r.Player   IS NOT NULL
  AND r.user_id  IS NOT NULL
  AND up.use_id  IS NULL;

COMMIT;

-- Post-migration sanity check. Should return 0 rows when migration is
-- complete: every legacy responses row (excluding aversions) should
-- have a matching uses row and a matching uses_players row.
--
-- SELECT r.id, r.user_id, r.Title, r.PlayDate, r.Player
-- FROM responses r
-- LEFT JOIN uses u
--   ON u.artifact_id = r.Title
--   AND u.use_date   = r.PlayDate
--   AND u.user_id    = r.user_id
-- LEFT JOIN uses_players up
--   ON up.use_id    = u.id
--   AND up.player_id = r.Player
--   AND up.user_id  = r.user_id
-- WHERE r.PlayDate IS NOT NULL
--   AND r.Player   IS NOT NULL
--   AND r.user_id  IS NOT NULL
--   AND (u.id IS NULL OR up.use_id IS NULL);
