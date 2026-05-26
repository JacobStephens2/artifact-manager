-- DRY RUN for migrate-responses-to-uses.sql.
-- SELECT-only: makes no writes. Run it first to see what the real
-- migration WOULD insert, so you can sanity-check before committing.
--
-- Each query is labeled so you can read the results top to bottom.

SELECT '=== 1. How many uses rows would be inserted? (one per user/artifact/date group not already in uses)' AS section;

SELECT COUNT(*) AS uses_rows_to_insert
FROM (
  SELECT r.user_id, r.Title, r.PlayDate
  FROM responses r
  LEFT JOIN uses u
    ON u.artifact_id = r.Title
    AND u.use_date   = r.PlayDate
    AND u.user_id    = r.user_id
  WHERE r.PlayDate IS NOT NULL
    AND r.Title    IS NOT NULL
    AND r.user_id  IS NOT NULL
    AND u.id       IS NULL
  GROUP BY r.user_id, r.Title, r.PlayDate
) AS grouped;

SELECT '=== 2. Sample of the uses rows that would be inserted (up to 20)' AS section;

SELECT
  r.user_id,
  r.Title       AS artifact_id,
  r.PlayDate    AS use_date,
  NULLIF(GROUP_CONCAT(DISTINCT NULLIF(TRIM(r.Note), '') SEPARATOR ' | '), '') AS note,
  COUNT(*)      AS legacy_response_rows_in_group
FROM responses r
LEFT JOIN uses u
  ON u.artifact_id = r.Title
  AND u.use_date   = r.PlayDate
  AND u.user_id    = r.user_id
WHERE r.PlayDate IS NOT NULL
  AND r.Title    IS NOT NULL
  AND r.user_id  IS NOT NULL
  AND u.id       IS NULL
GROUP BY r.user_id, r.Title, r.PlayDate
ORDER BY r.PlayDate DESC, r.user_id, r.Title
LIMIT 20;

SELECT '=== 3. How many uses_players rows would be inserted? (player links not already present)' AS section;

SELECT COUNT(*) AS uses_players_rows_to_insert
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

SELECT '=== 4. ALSO counts uses_players rows that the migration WILL eventually insert after step 1 runs' AS section;
SELECT '    (i.e., players linked to use rows that do NOT exist yet but would be created by step 1)' AS section;

SELECT COUNT(*) AS uses_players_rows_dependent_on_step1
FROM responses r
LEFT JOIN uses u
  ON u.artifact_id = r.Title
  AND u.use_date   = r.PlayDate
  AND u.user_id    = r.user_id
WHERE r.PlayDate IS NOT NULL
  AND r.Player   IS NOT NULL
  AND r.user_id  IS NOT NULL
  AND u.id       IS NULL;

SELECT '=== 5. Group-size distribution (how many players per use)' AS section;

SELECT
  group_size,
  COUNT(*) AS num_groups
FROM (
  SELECT COUNT(*) AS group_size
  FROM responses r
  LEFT JOIN uses u
    ON u.artifact_id = r.Title
    AND u.use_date   = r.PlayDate
    AND u.user_id    = r.user_id
  WHERE r.PlayDate IS NOT NULL
    AND r.Title    IS NOT NULL
    AND r.user_id  IS NOT NULL
    AND u.id       IS NULL
  GROUP BY r.user_id, r.Title, r.PlayDate
) AS sizes
GROUP BY group_size
ORDER BY group_size;

SELECT '=== 6. Orphan check: responses with a Title that does not exist in games' AS section;

SELECT COUNT(*) AS orphaned_responses
FROM responses r
LEFT JOIN games g ON g.id = r.Title
WHERE r.PlayDate IS NOT NULL
  AND r.Title    IS NOT NULL
  AND g.id       IS NULL;

SELECT '=== 7. Orphan check: responses with a Player that does not exist in players' AS section;

SELECT COUNT(*) AS orphaned_player_refs
FROM responses r
LEFT JOIN players p ON p.id = r.Player
WHERE r.PlayDate IS NOT NULL
  AND r.Player   IS NOT NULL
  AND p.id       IS NULL;

SELECT '=== 8. Aversion rows (will NOT be migrated; aversions feature still reads from responses)' AS section;

SELECT COUNT(*) AS aversion_rows_left_alone
FROM responses
WHERE AversionDate IS NOT NULL
  AND AversionDate > 0;
