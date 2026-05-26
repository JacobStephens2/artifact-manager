-- Null out the one games row with a '0000-00-00' Acq that was blocking
-- CREATE INDEX (MySQL's strict mode rejects the zero date when rebuilding
-- the table). The migration in add-indexes-for-useby.sql needs this fix
-- to run on a fresh database; on production it's already been applied.
--
-- Idempotent.

UPDATE games SET Acq = NULL WHERE CAST(Acq AS CHAR) = '0000-00-00';
