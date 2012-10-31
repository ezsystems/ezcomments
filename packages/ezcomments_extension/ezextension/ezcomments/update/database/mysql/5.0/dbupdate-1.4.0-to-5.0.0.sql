
-- See http://issues.ez.no/19418
ALTER TABLE ezcomment CHANGE COLUMN text text longtext NOT NULL;

-- https://jira.ez.no/browse/EZP-19883
ALTER TABLE ezcomment CHANGE COLUMN session_key session_key varchar(32) DEFAULT NULL;

