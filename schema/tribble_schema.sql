# SQL Manager 2007 for MySQL 4.3.4.1
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : tribble_schema


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS `tribble_schema`;

CREATE DATABASE `tribble_schema`
    CHARACTER SET 'utf8'
    COLLATE 'utf8_general_ci';

USE `tribble_schema`;

#
# Structure for the `tr_comments` table : 
#

DROP TABLE IF EXISTS `tr_comments`;

CREATE TABLE `tr_comments` (
  `comment_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `comment_text` TEXT COLLATE utf8_general_ci NOT NULL,
  `comment_user_id` INTEGER(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=18 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_images` table : 
#

DROP TABLE IF EXISTS `tr_images`;

CREATE TABLE `tr_images` (
  `image_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `image_tribble_id` INTEGER(11) NOT NULL,
  `image_path` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `image_palette` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`image_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=85 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_likes` table : 
#

DROP TABLE IF EXISTS `tr_likes`;

CREATE TABLE `tr_likes` (
  `like_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `like_tribble_id` INTEGER(11) NOT NULL,
  `like_user_id` INTEGER(11) NOT NULL,
  PRIMARY KEY (`like_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=77 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_replies` table : 
#

DROP TABLE IF EXISTS `tr_replies`;

CREATE TABLE `tr_replies` (
  `reply_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `reply_tribble_id` INTEGER(11) NOT NULL,
  `reply_comment_id` INTEGER(11) DEFAULT NULL,
  `reply_rebound_id` INTEGER(11) DEFAULT NULL,
  `reply_timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reply_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=16 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_sessions` table : 
#

DROP TABLE IF EXISTS `tr_sessions`;

CREATE TABLE `tr_sessions` (
  `session_id` VARCHAR(40) COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `ip_address` VARCHAR(16) COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `user_agent` VARCHAR(120) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `last_activity` INTEGER(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` TEXT COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
)ENGINE=InnoDB
CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_tags` table : 
#

DROP TABLE IF EXISTS `tr_tags`;

CREATE TABLE `tr_tags` (
  `tags_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `tags_tribble_id` INTEGER(11) DEFAULT NULL,
  `tags_content` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`tags_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=81 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_tribbles` table : 
#

DROP TABLE IF EXISTS `tr_tribbles`;

CREATE TABLE `tr_tribbles` (
  `tribble_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `tribble_title` VARCHAR(255) COLLATE utf8_general_ci DEFAULT '',
  `tribble_text` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `tribble_user_id` INTEGER(11) NOT NULL,
  `tribble_timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tribble_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=88 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_users` table : 
#

DROP TABLE IF EXISTS `tr_users`;

CREATE TABLE `tr_users` (
  `user_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `user_email` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `user_password` VARCHAR(128) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `user_realname` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `user_bio` TEXT COLLATE utf8_general_ci,
  `user_avatar` VARCHAR(256) COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email_UNIQUE` (`user_email`)
)ENGINE=InnoDB
AUTO_INCREMENT=9 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
COMMENT='\t';



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;