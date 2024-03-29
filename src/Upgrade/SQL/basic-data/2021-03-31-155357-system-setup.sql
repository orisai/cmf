-- Supervisor role
INSERT INTO ori_cmf.roles ("id", "created_at", "name", "is_immutable", "privileges")
VALUES ('017889a1-9630-c4ad-0a60-5916894bfd5d', '1970-01-01 00:00:00.000000', 'Supervisor', TRUE, '["*"]');

-- System user
INSERT INTO ori_cmf.users ("id", "created_at", "full_name", "user_name", "type", "state")
VALUES ('01786105-b2a2-d048-33f7-cf944438d799', '1970-01-01 00:00:00.000000', 'System', 'system', 'system', 'active');

-- Add Supervisor role to System
INSERT INTO ori_cmf.user_roles ("user_id", "role_id")
VALUES ('01786105-b2a2-d048-33f7-cf944438d799', '017889a1-9630-c4ad-0a60-5916894bfd5d');
