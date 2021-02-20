DROP SCHEMA IF EXISTS ori CASCADE;
CREATE SCHEMA ori;

-- ----------
-- collations
-- ----------

CREATE COLLATION ori.strict (
    provider = "icu",
    locale = "und-x-icu",
    deterministic = true
);

CREATE COLLATION ori.ignore_case (
    provider = "icu",
    locale = "und-u-ks-level2",
    deterministic = false
);

CREATE COLLATION ori.ignore_accents (
    provider = "icu",
    locale = "und@colStrength=primary;colCaseLevel=yes",
    deterministic = false
);

CREATE COLLATION ori.ignore_case_and_accents (
    provider = "icu",
    locale = "und@colStrength=primary",
    deterministic = false
);

-- ----------
-- extensions
-- ----------

CREATE EXTENSION IF NOT EXISTS unaccent;

-- ---------------------
-- search configurations
-- ---------------------

CREATE TEXT SEARCH CONFIGURATION ori.ignore_accents ( COPY = simple );
ALTER TEXT SEARCH CONFIGURATION ori.ignore_accents
    ALTER MAPPING FOR hword, hword_part, word
        WITH unaccent, simple;

-- ------
-- tables
-- ------

CREATE TABLE ori.users
(
    "id"         uuid PRIMARY KEY,
    "created_at" timestamptz NOT NULL,
    "person_id"  uuid        NOT NULL UNIQUE
);

CREATE TABLE ori.emails
(
    "id"            uuid PRIMARY KEY,
    "created_at"    timestamptz                                      NOT NULL,
    "email_address" varchar(254) COLLATE ori.ignore_case_and_accents NOT NULL UNIQUE CHECK ("email_address" IS NORMALIZED),
    "is_primary"    bool                                             NOT NULL,
    "person_id"     uuid                                             NOT NULL
);

CREATE INDEX emails_person_id_key
    ON ori.emails (person_id);

CREATE UNIQUE INDEX emails_is_primary_key
    ON ori.emails ("person_id", "is_primary")
    WHERE "is_primary" IS TRUE;

CREATE TABLE ori.passwords
(
    "id"               uuid PRIMARY KEY,
    "created_at"       timestamptz                     NOT NULL,
    "encoded_password" varchar(250) COLLATE ori.strict NOT NULL,
    "user_id"          uuid                            NOT NULL UNIQUE
);

CREATE TABLE ori.people
(
    "id"         uuid PRIMARY KEY,
    "created_at" timestamptz                                      NOT NULL,
    "first_name" varchar(250) COLLATE ori.strict                  NOT NULL,
    "last_name"  varchar(250) COLLATE ori.strict                  NOT NULL,
    "nick_name"  varchar(250) COLLATE ori.ignore_case_and_accents NOT NULL UNIQUE CHECK ("nick_name" IS NORMALIZED)
);

CREATE TABLE ori.roles
(
    "id"           uuid PRIMARY KEY,
    "created_at"   timestamptz                     NOT NULL,
    "name"         varchar(250) COLLATE ori.strict NOT NULL UNIQUE,
    "is_immutable" bool                            NOT NULL,
    "privileges"   jsonb                           NOT NULL
);

CREATE TABLE ori.user_roles
(
    "user_id" uuid NOT NULL,
    "role_id" uuid NOT NULL,
    PRIMARY KEY ("user_id", "role_id")
);

-- ------------
-- foreign keys
-- ------------

ALTER TABLE ori.users
    ADD CONSTRAINT "people_fkey"
        FOREIGN KEY ("person_id")
            REFERENCES ori.people ("id")
            ON UPDATE RESTRICT
            ON DELETE RESTRICT;

ALTER TABLE ori.passwords
    ADD CONSTRAINT "users_fkey"
        FOREIGN KEY ("user_id")
            REFERENCES ori.users ("id")
            ON UPDATE RESTRICT
            ON DELETE RESTRICT;

ALTER TABLE ori.emails
    ADD CONSTRAINT "people_fkey"
        FOREIGN KEY ("person_id")
            REFERENCES ori.people ("id")
            ON UPDATE RESTRICT
            ON DELETE RESTRICT;

ALTER TABLE ori.user_roles
    ADD CONSTRAINT "users_fkey"
        FOREIGN KEY ("user_id")
            REFERENCES ori.users ("id")
            ON UPDATE RESTRICT
            ON DELETE RESTRICT,
    ADD CONSTRAINT "roles_fkey"
        FOREIGN KEY ("role_id")
            REFERENCES ori.roles ("id")
            ON UPDATE RESTRICT
            ON DELETE RESTRICT;
