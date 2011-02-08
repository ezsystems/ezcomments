CREATE TABLE ezcomment (
    id                integer NOT NULL,
    language_id       integer NOT NULL,
    created           integer NOT NULL,
    modified          integer NOT NULL,
    user_id           integer NOT NULL,
    session_key       varchar2(32) NOT NULL,
    ip                varchar2(100) NOT NULL,
    contentobject_id  integer NOT NULL,
    parent_comment_id integer DEFAULT 0 NOT NULL,
    name              varchar2(255) NOT NULL,
    email             varchar2(75) NOT NULL,
    url               varchar2(255) DEFAULT NULL,
    text              clob NOT NULL,
    status            integer NOT NULL,
    title             varchar2(255) DEFAULT NULL
);

ALTER TABLE ezcomment
    ADD PRIMARY KEY (id);

CREATE INDEX user_id_session_key_ip ON ezcomment(user_id,session_key,ip);
CREATE INDEX content_parentcomment ON ezcomment(contentobject_id,language_id,parent_comment_id);

CREATE SEQUENCE s_comment;

CREATE TRIGGER ezcomment_id_tr
BEFORE INSERT ON ezcomment FOR EACH ROW WHEN (new.id IS NULL)
BEGIN
    SELECT s_comment.nextval INTO :new.id FROM dual;
END;
/

CREATE TABLE ezcomment_notification (
    id               integer NOT NULL,
    contentobject_id integer NOT NULL,
    language_id      integer NOT NULL,
    send_time        integer NOT NULL,
    status           integer NOT NULL,
    comment_id       integer NOT NULL
);

ALTER TABLE ezcomment_notification
    ADD PRIMARY KEY (id);

CREATE SEQUENCE s_comment_notification;

CREATE TRIGGER ezcomment_notification_id_tr
BEFORE INSERT ON ezcomment_notification FOR EACH ROW WHEN (new.id IS NULL)
BEGIN
    SELECT s_comment_notification.nextval INTO :new.id FROM dual;
END;
/


CREATE TABLE ezcomment_subscriber (
    id          integer NOT NULL,
    user_id     integer NOT NULL,
    email       varchar2(50) NOT NULL,
    enabled     integer DEFAULT 1 NOT NULL,
    hash_string varchar2(50)
);

ALTER TABLE ezcomment_subscriber
    ADD PRIMARY KEY (id);

CREATE SEQUENCE s_comment_subscriber;

CREATE TRIGGER ezcomment_subscriber_id_tr
BEFORE INSERT ON ezcomment_subscriber FOR EACH ROW WHEN (new.id IS NULL)
BEGIN
    SELECT s_comment_subscriber.nextval INTO :new.id FROM dual;
END;
/


CREATE TABLE ezcomment_subscription (
    id                integer NOT NULL,
    user_id           integer NOT NULL,
    subscriber_id     integer NOT NULL,
    subscription_type varchar2(30) NOT NULL,
    content_id        integer NOT NULL,
    language_id       integer NOT NULL,
    subscription_time integer NOT NULL,
    enabled           integer DEFAULT 1 NOT NULL,
    hash_string       varchar2(50) DEFAULT NULL
);

ALTER TABLE ezcomment_subscription
    ADD PRIMARY KEY (id);

CREATE SEQUENCE s_comment_subscription;

CREATE TRIGGER ezcomment_subscription_id_tr
BEFORE INSERT ON ezcomment_subscription FOR EACH ROW WHEN (new.id IS NULL)
BEGIN
    SELECT s_comment_subscription.nextval INTO :new.id FROM dual;
END;
/