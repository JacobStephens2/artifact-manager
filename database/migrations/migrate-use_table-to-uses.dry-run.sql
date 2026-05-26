-- DRY RUN for migrate-use_table-to-uses.sql.
-- SELECT-only: makes no writes. Run it first to confirm the assumptions
-- the real migration makes about your data.
--
-- The most important question this answers: does use_table.ObjectName
-- (the gen-1 FK) actually point at games.id (the gen-3 artifact table)?
-- If not, every migrated row will end up with an orphaned artifact_id.

SELECT '=== 1. CRITICAL ASSUMPTION CHECK: do use_table.ObjectName values exist in games.id?' AS section;
SELECT '    Expected: 0. If non-zero, STOP and tell me — we need a separate mapping step.' AS section;

SELECT COUNT(*) AS use_table_rows_with_orphaned_artifact_id
FROM use_table ut
LEFT JOIN games g ON g.id = ut.ObjectName
WHERE ut.ObjectName IS NOT NULL
  AND g.id          IS NULL;

SELECT '=== 1b. Sample of the orphans (if any) so you can spot the mismatch pattern' AS section;

SELECT ut.id, ut.ObjectName, ut.UseDate, ut.user_id
FROM use_table ut
LEFT JOIN games g ON g.id = ut.ObjectName
WHERE ut.ObjectName IS NOT NULL
  AND g.id          IS NULL
LIMIT 20;

SELECT '=== 2. How many use_table rows would be inserted into uses? (combos not already present)' AS section;

SELECT COUNT(*) AS use_table_rows_to_migrate
FROM use_table ut
LEFT JOIN uses u
  ON u.artifact_id = ut.ObjectName
  AND u.use_date   = ut.UseDate
  AND u.user_id    = ut.user_id
WHERE ut.ObjectName IS NOT NULL
  AND ut.UseDate    IS NOT NULL
  AND ut.user_id    IS NOT NULL
  AND u.id          IS NULL;

SELECT '=== 3. Sample of the rows that would be inserted (up to 20)' AS section;

SELECT
  ut.user_id,
  ut.ObjectName AS artifact_id,
  ut.UseDate    AS use_date
FROM use_table ut
LEFT JOIN uses u
  ON u.artifact_id = ut.ObjectName
  AND u.use_date   = ut.UseDate
  AND u.user_id    = ut.user_id
WHERE ut.ObjectName IS NOT NULL
  AND ut.UseDate    IS NOT NULL
  AND ut.user_id    IS NOT NULL
  AND u.id          IS NULL
ORDER BY ut.UseDate DESC, ut.user_id, ut.ObjectName
LIMIT 20;

SELECT '=== 4. Rows that will be SKIPPED because a matching uses row already exists (informational)' AS section;

SELECT COUNT(*) AS use_table_rows_already_in_uses
FROM use_table ut
INNER JOIN uses u
  ON u.artifact_id = ut.ObjectName
  AND u.use_date   = ut.UseDate
  AND u.user_id    = ut.user_id
WHERE ut.ObjectName IS NOT NULL
  AND ut.UseDate    IS NOT NULL
  AND ut.user_id    IS NOT NULL;

SELECT '=== 5. Rows with NULL fields that the migration ignores' AS section;

SELECT COUNT(*) AS use_table_rows_with_nulls
FROM use_table
WHERE ObjectName IS NULL
   OR UseDate    IS NULL
   OR user_id    IS NULL;
