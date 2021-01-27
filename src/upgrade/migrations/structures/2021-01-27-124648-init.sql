DROP SCHEMA IF EXISTS ori_core CASCADE;
CREATE SCHEMA ori_core;

-- ------
-- tables
-- ------

CREATE TABLE ori_core.users
(
    "id"         uuid PRIMARY KEY,
    "created_at" timestamptz NOT NULL,
    "person_id"  uuid        NOT NULL UNIQUE
);

CREATE TABLE ori_core.emails
(
    "id"         uuid PRIMARY KEY,
    "created_at" timestamptz  NOT NULL,
    "email"      varchar(250) NOT NULL UNIQUE,
    "person_id"  uuid         NOT NULL
);

CREATE INDEX emails_person_id_key ON ori_core.emails (person_id);

CREATE TABLE ori_core.passwords
(
    "id"            uuid PRIMARY KEY,
    "created_at"    timestamptz  NOT NULL,
    "password_hash" varchar(250) NOT NULL,
    "user_id"       uuid         NOT NULL UNIQUE
);

CREATE TABLE ori_core.people
(
    "id"         uuid PRIMARY KEY,
    "created_at" timestamptz  NOT NULL,
    "first_name" varchar(250) NOT NULL,
    "last_name"  varchar(250) NOT NULL,
    "nick_name"  varchar(250) NOT NULL UNIQUE
);

CREATE TABLE ori_core.roles
(
    "id"         uuid PRIMARY KEY,
    "created_at" timestamptz    NOT NULL,
    "name"       varchar(250)   NOT NULL UNIQUE,
    "privileges" varchar(250)[] NOT NULL
);

CREATE TABLE ori_core.user_roles
(
    "user_id" uuid NOT NULL,
    "role_id" uuid NOT NULL,
    PRIMARY KEY ("user_id", "role_id")
);

-- ------------
-- foreign keys
-- ------------

ALTER TABLE ori_core.users
    ADD CONSTRAINT "people_fkey"
        FOREIGN KEY ("person_id")
            REFERENCES ori_core.people ("id")
            ON UPDATE RESTRICT
            ON DELETE RESTRICT;

ALTER TABLE ori_core.passwords
    ADD CONSTRAINT "users_fkey"
        FOREIGN KEY ("user_id")
            REFERENCES ori_core.users ("id")
            ON UPDATE RESTRICT
            ON DELETE RESTRICT;

ALTER TABLE ori_core.emails
    ADD CONSTRAINT "people_fkey"
        FOREIGN KEY ("person_id")
            REFERENCES ori_core.people ("id")
            ON UPDATE RESTRICT
            ON DELETE RESTRICT;

ALTER TABLE ori_core.user_roles
    ADD CONSTRAINT "users_fkey"
        FOREIGN KEY ("user_id")
            REFERENCES ori_core.users ("id")
            ON UPDATE RESTRICT
            ON DELETE RESTRICT,
    ADD CONSTRAINT "roles_fkey"
        FOREIGN KEY ("role_id")
            REFERENCES ori_core.roles ("id")
            ON UPDATE RESTRICT
            ON DELETE RESTRICT;
