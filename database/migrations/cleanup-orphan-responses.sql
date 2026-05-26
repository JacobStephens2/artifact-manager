-- One-off cleanup of orphan rows in `responses` that block the
-- migrate-responses-to-uses migration.
--
-- - id=11395: junk row with user_id=0, Title=0, Player=0, PlayDate=0000-00-00.
-- - ids 11776..11780: 5 player rows for user_id=8 on 2021-12-30 referencing
--   bad_artifact_id=2722. Game 2722 was deleted from `games` and the user
--   chose not to recreate it, so these rows are unreachable from the UI.
--
-- Idempotent: DELETE-by-id is a no-op once the rows are gone.

START TRANSACTION;

DELETE FROM responses
WHERE id IN (11395, 11776, 11777, 11778, 11779, 11780);

COMMIT;
