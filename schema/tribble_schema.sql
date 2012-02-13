# SQL Manager 2007 for MySQL 4.3.4.1
# ---------------------------------------
# Host     : blobsnark
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
# Structure for the `tr_comment` table : 
#

DROP TABLE IF EXISTS `tr_comment`;

CREATE TABLE `tr_comment` (
  `comment_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `comment_text` TEXT COLLATE utf8_general_ci NOT NULL,
  `comment_user_id` INTEGER(11) NOT NULL,
  `comment_is_deleted` TINYINT(1) DEFAULT '0',
  PRIMARY KEY (`comment_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=46 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_image` table : 
#

DROP TABLE IF EXISTS `tr_image`;

CREATE TABLE `tr_image` (
  `image_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `image_post_id` INTEGER(11) NOT NULL,
  `image_path` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `image_palette` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `image_color_ranges` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`image_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=1249 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_like` table : 
#

DROP TABLE IF EXISTS `tr_like`;

CREATE TABLE `tr_like` (
  `like_post_id` INTEGER(11) NOT NULL,
  `like_user_id` INTEGER(11) NOT NULL,
  `like_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`like_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=1332 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_post` table : 
#

DROP TABLE IF EXISTS `tr_post`;

CREATE TABLE `tr_post` (
  `post_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `post_parent_id` INTEGER(11) DEFAULT '0',
  `post_title` VARCHAR(255) COLLATE utf8_general_ci DEFAULT '',
  `post_text` TEXT COLLATE utf8_general_ci NOT NULL,
  `post_user_id` INTEGER(11) NOT NULL,
  `post_timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_is_deleted` TINYINT(1) DEFAULT '0',
  PRIMARY KEY (`post_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=1249 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_reply` table : 
#

DROP TABLE IF EXISTS `tr_reply`;

CREATE TABLE `tr_reply` (
  `reply_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `reply_post_id` INTEGER(11) NOT NULL,
  `reply_comment_id` INTEGER(11) DEFAULT NULL,
  `reply_rebound_id` INTEGER(11) DEFAULT NULL,
  `reply_timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reply_is_deleted` TINYINT(1) DEFAULT '0',
  PRIMARY KEY (`reply_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=54 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_tag` table : 
#

DROP TABLE IF EXISTS `tr_tag`;

CREATE TABLE `tr_tag` (
  `tag_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `tag_post_id` INTEGER(11) DEFAULT NULL,
  `tag_content` TEXT COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`tag_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=1249 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_trash` table : 
#

DROP TABLE IF EXISTS `tr_trash`;

CREATE TABLE `tr_trash` (
  `trash_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `trash_path` VARCHAR(256) COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`trash_id`)
)ENGINE=MyISAM
AUTO_INCREMENT=1 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_user` table : 
#

DROP TABLE IF EXISTS `tr_user`;

CREATE TABLE `tr_user` (
  `user_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `user_email` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `user_password` VARCHAR(128) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `user_realname` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `user_bio` TEXT COLLATE utf8_general_ci,
  `user_is_deleted` TINYINT(1) DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email_UNIQUE` (`user_email`)
)ENGINE=InnoDB
AUTO_INCREMENT=425 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
COMMENT='\t';



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;