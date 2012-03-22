










CREATE SEQUENCE ezcomment_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE SEQUENCE ezcomment_notification_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE SEQUENCE ezcomment_subscriber_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE SEQUENCE ezcomment_subscription_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcomment (
    contentobject_id integer DEFAULT 0 NOT NULL,
    created integer DEFAULT 0 NOT NULL,
    email character varying(75) DEFAULT ''::character varying NOT NULL,
    id integer DEFAULT nextval('ezcomment_s'::text) NOT NULL,
    ip character varying(100) DEFAULT ''::character varying NOT NULL,
    language_id integer DEFAULT 0 NOT NULL,
    modified integer DEFAULT 0 NOT NULL,
    name character varying(255) DEFAULT ''::character varying NOT NULL,
    parent_comment_id integer DEFAULT 0 NOT NULL,
    session_key character varying(32) DEFAULT ''::character varying NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    text text NOT NULL,
    title character varying(255),
    url character varying(255),
    user_id integer DEFAULT 0 NOT NULL
);







CREATE TABLE ezcomment_notification (
    comment_id integer DEFAULT 0 NOT NULL,
    contentobject_id integer DEFAULT 0 NOT NULL,
    id integer DEFAULT nextval('ezcomment_notification_s'::text) NOT NULL,
    language_id integer DEFAULT 0 NOT NULL,
    send_time integer DEFAULT 0 NOT NULL,
    status integer DEFAULT 1 NOT NULL
);







CREATE TABLE ezcomment_subscriber (
    email character varying(50) DEFAULT ''::character varying NOT NULL,
    enabled integer DEFAULT 1 NOT NULL,
    hash_string character varying(50),
    id integer DEFAULT nextval('ezcomment_subscriber_s'::text) NOT NULL,
    user_id integer DEFAULT 0 NOT NULL
);







CREATE TABLE ezcomment_subscription (
    content_id integer DEFAULT 0 NOT NULL,
    enabled integer DEFAULT 1 NOT NULL,
    hash_string character varying(50),
    id integer DEFAULT nextval('ezcomment_subscription_s'::text) NOT NULL,
    language_id integer DEFAULT 0 NOT NULL,
    subscriber_id integer DEFAULT 0 NOT NULL,
    subscription_time integer DEFAULT 0 NOT NULL,
    subscription_type character varying(30) DEFAULT ''::character varying NOT NULL,
    user_id integer DEFAULT 0 NOT NULL
);







CREATE INDEX content_parentcomment ON ezcomment USING btree (contentobject_id, language_id, parent_comment_id);







CREATE INDEX user_id_session_key_ip ON ezcomment USING btree (user_id, session_key, ip);








ALTER TABLE ONLY ezcomment
    ADD CONSTRAINT ezcomment_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcomment_notification
    ADD CONSTRAINT ezcomment_notification_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcomment_subscriber
    ADD CONSTRAINT ezcomment_subscriber_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcomment_subscription
    ADD CONSTRAINT ezcomment_subscription_pkey PRIMARY KEY (id);








