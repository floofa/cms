SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `cms_roles`;
CREATE TABLE `cms_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `cms_roles` (`id`, `name`, `description`) VALUES
(1,  'login',  'Login privileges, granted after account confirmation'),
(2,  'admin',  'Administrative user, has access to everything.');

DROP TABLE IF EXISTS `cms_roles_cms_users`;
CREATE TABLE `cms_roles_cms_users` (
  `cms_user_id` int(10) unsigned NOT NULL,
  `cms_role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cms_user_id`,`cms_role_id`),
  KEY `fk_role_id` (`cms_role_id`),
  CONSTRAINT `cms_roles_cms_users_ibfk_1` FOREIGN KEY (`cms_user_id`) REFERENCES `cms_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cms_roles_cms_users_ibfk_2` FOREIGN KEY (`cms_role_id`) REFERENCES `cms_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cms_roles_cms_users` (`cms_user_id`, `cms_role_id`) VALUES
(1,  1);

DROP TABLE IF EXISTS `cms_users`;
CREATE TABLE `cms_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(127) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `cms_users` (`id`, `email`, `username`, `password`, `logins`, `last_login`) VALUES
(1,  'ondrej.trojanek@gmail.com',  'admin',  'e930b8bc9784bb542c9eafce0b5ff72b1da215d534b89c1b4e0f7e58151258d1',  184,  1323365333);

DROP TABLE IF EXISTS `galleries`;
CREATE TABLE `galleries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(32) COLLATE utf8_czech_ci DEFAULT NULL,
  `model` varchar(30) COLLATE utf8_czech_ci DEFAULT NULL,
  `model_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(80) COLLATE utf8_czech_ci NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DROP TABLE IF EXISTS `gallery_items`;
CREATE TABLE `gallery_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_id` int(10) unsigned NOT NULL,
  `ext` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `description` text COLLATE utf8_czech_ci,
  `size` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  `sequence` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_id` (`gallery_id`),
  CONSTRAINT `gallery_items_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `url` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `page_id` int(10) unsigned DEFAULT NULL,
  `menu_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lft` int(10) unsigned NOT NULL DEFAULT '0',
  `rgt` int(10) unsigned NOT NULL DEFAULT '0',
  `lvl` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `lvl` (`lvl`),
  KEY `page_id` (`page_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `menu_items_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci ROW_FORMAT=COMPACT;

INSERT INTO `menu_items` (`id`, `name`, `url`, `page_id`, `menu_id`, `lft`, `rgt`, `lvl`, `parent_id`) VALUES
(1,  'ROOT - Hlavní menu',  NULL,  NULL,  1,  1,  2,  1,  NULL);

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sys_name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `sequence` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_name` (`sys_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `menus` (`id`, `sys_name`, `name`, `sequence`) VALUES
(1,  'main_menu',  'Hlavní menu',  3);

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rew_id` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `sys_name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `head_title` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `page_type` enum('static','homepage','news','articles') COLLATE utf8_czech_ci NOT NULL,
  `page_layout` enum('static','dynamic','homepage','fancy') COLLATE utf8_czech_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `content` text COLLATE utf8_czech_ci NOT NULL,
  `cms_status` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rew_id` (`rew_id`),
  UNIQUE KEY `sys_name` (`sys_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;