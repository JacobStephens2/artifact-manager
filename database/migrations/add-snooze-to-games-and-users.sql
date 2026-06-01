-- Add a per-artifact snooze date and a per-user default snooze length.
-- snoozed_until        when set to a future date, the artifact is hidden from the
--                      dashboard "Most past due" priority queue until that date passes.
-- default_snooze_days  how many days the Snooze button defers an item (default 7).

ALTER TABLE games
  ADD COLUMN snoozed_until DATE NULL DEFAULT NULL AFTER to_get_rid_of;

ALTER TABLE users
  ADD COLUMN default_snooze_days INT NOT NULL DEFAULT 7 AFTER default_use_interval;
