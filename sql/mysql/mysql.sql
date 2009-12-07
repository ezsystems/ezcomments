CREATE TABLE IF NOT EXISTS `ezcomment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_key` varchar(32) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `contentobject_id` int(11) NOT NULL,
  `contentobject_attribute_id` int(11) NOT NULL,
  `parent_comment_id` bigint(20) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `email` varchar(75) NOT NULL,
  `url` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `status` int(11) NOT NULL,
  `notification` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id_session_key_ip` (`user_id`,`session_key`,`ip`),
  KEY `contentobject_id_contentobject_attribute_id_lang_id_parentcom_id` (`contentobject_id`,`contentobject_attribute_id`,`language_id`,`parent_comment_id`)
) ENGINE=InnoDB;

CREATE TABLE  `ezcomment_notification` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `contentobject_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8