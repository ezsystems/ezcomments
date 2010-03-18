CREATE table "EZCOMMENT" (
    "ID"                NUMBER NOT NULL,
    "LANGUAGE_ID"       NUMBER NOT NULL,
    "CREATED"           NUMBER NOT NULL,
    "MODIFIED"          NUMBER NOT NULL,
    "USER_ID"           NUMBER NOT NULL,
    "SESSION_KEY"       VARCHAR2(32) NOT NULL,
    "IP"                VARCHAR2(100) NOT NULL,
    "CONTENTOBJECT_ID"  NUMBER NOT NULL,
    "PARENT_COMMENT_ID" NUMBER DEFAULT 0 NOT NULL,
    "NAME"              VARCHAR2(255) NOT NULL,
    "EMAIL"             VARCHAR2(75) NOT NULL,
    "URL"               VARCHAR2(255) NOT NULL,
    "TEXT"              CLOB NOT NULL,
    "STATUS"            NUMBER NOT NULL,
    "TITLE"             VARCHAR2(255) DEFAULT NULL,
    constraint  "EZCOMMENT_PK" primary key ("ID")
)
/

CREATE sequence "S_EZCOMMENT" 
/

CREATE trigger "EZCOMMENT_ID_TR"  
  before insert on "EZCOMMENT"
  for each row 
begin  
    select "S_EZCOMMENT".nextval into :NEW.ID from dual;
end;
/

CREATE table "EZCOMMENT_NOTIFICATION" (
    "ID"               NUMBER NOT NULL,
    "CONTENTOBJECT_ID" NUMBER NOT NULL,
    "LANGUAGE_ID"      NUMBER NOT NULL,
    "SEND_TIME"        NUMBER NOT NULL,
    "STATUS"           NUMBER NOT NULL,
    "COMMENT_ID"       NUMBER NOT NULL,
    constraint  "EZCOMMENT_NOTIFICATION_PK" primary key ("ID")
)
/

CREATE sequence "S_EZCOMMENT_NOTIFICATION" 
/

CREATE trigger "EZCOMMENT_NOTIFICATION_ID_TR"  
  before insert on "EZCOMMENT_NOTIFICATION"
  for each row 
begin  
    select "S_EZCOMMENT_NOTIFICATION".nextval into :NEW.ID from dual;
end;
/


CREATE table "EZCOMMENT_SUBSCRIBER" (
    "ID"          NUMBER NOT NULL,
    "USER_ID"     NUMBER NOT NULL,
    "EMAIL"       VARCHAR2(50) NOT NULL,
    "ENABLED"     NUMBER DEFAULT 1 NOT NULL,
    "HASH_STRING" VARCHAR2(50),
    constraint  "EZCOMMENT_SUBSCRIBER_PK" primary key ("ID")
)
/

CREATE sequence "S_EZCOMMENT_SUBSCRIBER" 
/

CREATE trigger "EZCOMMENT_SUBSCRIBER_ID_TR"
  before insert on "EZCOMMENT_SUBSCRIBER"
  for each row 
begin  
    select "S_EZCOMMENT_SUBSCRIBER".nextval into :NEW.ID from dual;
end;
/   


CREATE table "EZCOMMENT_SUBSCRIPTION" (
    "ID"                NUMBER NOT NULL,
    "USER_ID"           NUMBER NOT NULL,
    "SUBSCRIBER_ID"     NUMBER NOT NULL,
    "SUBSCRIPTION_TYPE" VARCHAR2(30) NOT NULL,
    "CONTENT_ID"        NUMBER NOT NULL,
    "LANGUAGE_ID"       NUMBER NOT NULL,
    "SUBSCRIPTION_TIME" NUMBER NOT NULL,
    "ENABLED"           NUMBER DEFAULT 1 NOT NULL,
    "HASH_STRING"       VARCHAR2(50) DEFAULT NULL,
    constraint  "EZCOMMENT_SUBSCRIPTION_PK" primary key ("ID")
)
/

CREATE sequence "S_EZCOMMENT_SUBSCRIPTION" 
/

CREATE trigger "EZCOMMENT_SUBSCRIPTION_ID_TR"
  before insert on "EZCOMMENT_SUBSCRIPTION"
  for each row 
begin  
    select "S_EZCOMMENT_SUBSCRIPTION".nextval into :NEW.ID from dual;
end;
/

