-- DRY RUN for migrate-use_table-to-uses.sql.
-- SELECT-only: makes no writes. Run it first to see what the real
-- migration would insert.
--
-- The real migration resolves use_table.ObjectName to a games.id in
-- two passes: (1) direct id match, (2) objects.ObjectName -> games.Title
-- name-match. Unresolvable rows are skipped.

SELECT '=== 1. Resolution-pass breakdown for use_table rows with non-null fields' AS section;

SELECT
  CASE
    WHEN g_direct.id IS NOT NULL THEN '1. direct id match'
    WHEN g_name.id   IS NOT NULL THEN '2. name-match via objects'
    ELSE '3. unresolvable (skipped)'
  END AS resolution_pass,
  COUNT(*) AS row_count
FROM use_table ut
LEFT JOIN games g_direct ON g_direct.id = ut.ObjectName
LEFT JOIN (
  SELECT o.ID AS object_id, MIN(g.id) AS id
  FROM objects o
  INNER JOIN games g ON g.Title = o.ObjectName
  GROUP BY o.ID
) g_name ON g_name.object_id = ut.ObjectName
WHERE ut.ObjectName IS NOT NULL
  AND ut.UseDate    IS NOT NULL
  AND ut.user_id    IS NOT NULL
GROUP BY resolution_pass
ORDER BY resolution_pass;

SELECT '=== 2. Final uses rows that would be inserted (deduped, only resolvable, not already present)' AS section;

SELECT COUNT(*) AS uses_rows_to_insert
FROM (
  SELECT DISTINCT
    COALESCE(g_direct.id, g_name.id) AS artifact_id,
    ut.UseDate AS use_date,
    ut.user_id
  FROM use_table ut
  LEFT JOIN games g_direct ON g_direct.id = ut.ObjectName
  LEFT JOIN (
    SELECT o.ID AS object_id, MIN(g.id) AS id
    FROM objects o INNER JOIN games g ON g.Title = o.ObjectName
    GROUP BY o.ID
  ) g_name ON g_name.object_id = ut.ObjectName
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

SELECT '=== 3. Sample of rows that would be inserted (up to 20)' AS section;

SELECT
  resolved.user_id,
  resolved.artifact_id,
  g.Title       AS artifact_title,
  resolved.use_date,
  resolved.matched_via
FROM (
  SELECT DISTINCT
    ut.user_id,
    COALESCE(g_direct.id, g_name.id) AS artifact_id,
    ut.UseDate                       AS use_date,
    CASE
      WHEN g_direct.id IS NOT NULL THEN 'direct'
      ELSE 'name-match'
    END AS matched_via
  FROM use_table ut
  LEFT JOIN games g_direct ON g_direct.id = ut.ObjectName
  LEFT JOIN (
    SELECT o.ID AS object_id, MIN(g.id) AS id
    FROM objects o INNER JOIN games g ON g.Title = o.ObjectName
    GROUP BY o.ID
  ) g_name ON g_name.object_id = ut.ObjectName
  WHERE ut.ObjectName IS NOT NULL
    AND ut.UseDate    IS NOT NULL
    AND ut.user_id    IS NOT NULL
    AND COALESCE(g_direct.id, g_name.id) IS NOT NULL
) AS resolved
INNER JOIN games g ON g.id = resolved.artifact_id
LEFT JOIN uses u
  ON u.artifact_id = resolved.artifact_id
  AND u.use_date   = resolved.use_date
  AND u.user_id    = resolved.user_id
WHERE u.id IS NULL
ORDER BY resolved.use_date DESC, resolved.user_id, resolved.artifact_id
LIMIT 20;

SELECT '=== 4. Sample of unresolvable rows that will be SKIPPED (up to 20)' AS section;

SELECT
  ut.id   AS use_table_id,
  ut.user_id,
  ut.ObjectName,
  ut.UseDate,
  o.ObjectName AS object_name_if_in_objects
FROM use_table ut
LEFT JOIN games g_direct ON g_direct.id = ut.ObjectName
LEFT JOIN (
  SELECT o.ID AS object_id, MIN(g.id) AS id
  FROM objects o INNER JOIN games g ON g.Title = o.ObjectName
  GROUP BY o.ID
) g_name ON g_name.object_id = ut.ObjectName
LEFT JOIN objects o ON o.ID = ut.ObjectName
WHERE ut.ObjectName IS NOT NULL
  AND ut.UseDate    IS NOT NULL
  AND ut.user_id    IS NOT NULL
  AND g_direct.id   IS NULL
  AND g_name.id     IS NULL
ORDER BY ut.UseDate DESC
LIMIT 20;

SELECT '=== 5. Rows with NULL fields that the migration ignores entirely' AS section;

SELECT COUNT(*) AS use_table_rows_with_nulls
FROM use_table
WHERE ObjectName IS NULL
   OR UseDate    IS NULL
   OR user_id    IS NULL;
