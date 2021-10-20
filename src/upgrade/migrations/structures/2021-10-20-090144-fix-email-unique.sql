ALTER TABLE ori.emails
	DROP CONSTRAINT emails_email_address_key;

CREATE INDEX emails_email_address_key
	ON ori.emails (email_address);
