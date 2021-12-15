ALTER TABLE ori.users
    ADD COLUMN "state" text COLLATE ori.strict NULL;

UPDATE ori.users
SET "state" = 'active'
WHERE "state" IS NULL;

ALTER TABLE ori.users
    ALTER COLUMN "state" SET NOT NULL;
