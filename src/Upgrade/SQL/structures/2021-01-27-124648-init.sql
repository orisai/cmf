DROP SCHEMA IF EXISTS ori CASCADE;
CREATE SCHEMA ori_cmf;

-- ----------
-- collations
-- ----------

CREATE COLLATION ori_cmf.strict (
	provider = "icu",
	locale = "und-x-icu",
	deterministic = true
	);

CREATE COLLATION ori_cmf.ignore_case (
	provider = "icu",
	locale = "und-u-ks-level2",
	deterministic = false
	);

CREATE COLLATION ori_cmf.ignore_accents (
	provider = "icu",
	locale = "und@colStrength=primary;colCaseLevel=yes",
	deterministic = false
	);

CREATE COLLATION ori_cmf.ignore_case_and_accents (
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

CREATE TEXT SEARCH CONFIGURATION ori_cmf.ignore_accents ( COPY = simple );
ALTER TEXT SEARCH CONFIGURATION ori_cmf.ignore_accents
	ALTER MAPPING FOR hword, hword_part, word
		WITH unaccent, simple;

-- ------
-- tables
-- ------

CREATE TABLE ori_cmf.users
(
	"id"         uuid PRIMARY KEY,
	"created_at" timestamptz                                          NOT NULL,
	"full_name"  varchar(500) COLLATE ori_cmf.strict                  NOT NULL,
	"user_name"  varchar(250) COLLATE ori_cmf.ignore_case_and_accents NOT NULL UNIQUE CHECK ("user_name" IS NORMALIZED),
	"type"       varchar(250) COLLATE ori_cmf.strict                  NULL UNIQUE,
	"state"      text         COLLATE ori_cmf.strict                  NOT NULL
);

CREATE TABLE ori_cmf.emails
(
	"id"            uuid PRIMARY KEY,
	"created_at"    timestamptz                                          NOT NULL,
	"email_address" varchar(254) COLLATE ori_cmf.ignore_case_and_accents NOT NULL CHECK ("email_address" IS NORMALIZED),
	"type"          varchar(100) COLLATE ori_cmf.strict                  NOT NULL,
	"user_id"       uuid                                                 NOT NULL
);

CREATE INDEX emails_email_address_key
	ON ori_cmf.emails (email_address);

CREATE UNIQUE INDEX emails_is_primary_key
	ON ori_cmf.emails ("email_address")
	WHERE "type" = 'primary';

CREATE INDEX emails_user_id_key
	ON ori_cmf.emails (user_id);

CREATE TABLE ori_cmf.passwords
(
	"id"               uuid PRIMARY KEY,
	"created_at"       timestamptz                         NOT NULL,
	"encoded_password" varchar(250) COLLATE ori_cmf.strict NOT NULL,
	"user_id"          uuid                                NOT NULL UNIQUE
);

CREATE TABLE ori_cmf.roles
(
	"id"           uuid PRIMARY KEY,
	"created_at"   timestamptz                         NOT NULL,
	"name"         varchar(250) COLLATE ori_cmf.strict NOT NULL UNIQUE,
	"is_immutable" bool                                NOT NULL,
	"privileges"   jsonb                               NOT NULL
);

CREATE TABLE ori_cmf.user_roles
(
	"user_id" uuid NOT NULL,
	"role_id" uuid NOT NULL,
	PRIMARY KEY ("user_id", "role_id")
);

-- ------------
-- foreign keys
-- ------------

ALTER TABLE ori_cmf.passwords
	ADD CONSTRAINT "user_id_fkey"
		FOREIGN KEY ("user_id")
			REFERENCES ori_cmf.users ("id")
			ON UPDATE RESTRICT
			ON DELETE RESTRICT;

ALTER TABLE ori_cmf.emails
	ADD CONSTRAINT "user_id_fkey"
		FOREIGN KEY ("user_id")
			REFERENCES ori_cmf.users ("id")
			ON UPDATE RESTRICT
			ON DELETE RESTRICT;

ALTER TABLE ori_cmf.user_roles
	ADD CONSTRAINT "user_id_fkey"
		FOREIGN KEY ("user_id")
			REFERENCES ori_cmf.users ("id")
			ON UPDATE RESTRICT
			ON DELETE RESTRICT,
	ADD CONSTRAINT "role_id_fkey"
		FOREIGN KEY ("role_id")
			REFERENCES ori_cmf.roles ("id")
			ON UPDATE RESTRICT
			ON DELETE RESTRICT;
