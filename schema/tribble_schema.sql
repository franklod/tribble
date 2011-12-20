--
-- MySQL 5.5.16
-- Tue, 20 Dec 2011 13:10:56 +0000
--

CREATE DATABASE `tribble_schema` DEFAULT CHARSET utf8;

USE `tribble_schema`;

CREATE TABLE `tr_comments` (
   `comment_id` int(11) not null,
   `comment_text` varchar(255) not null,
   `comment_image` varchar(255),
   `comment_user_id` int(11) not null,
   PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- [Table `tr_comments` is empty]

CREATE TABLE `tr_likes` (
   `like_id` int(11) not null,
   `like_post_id` int(11) not null,
   `like_user_id` int(11) not null,
   PRIMARY KEY (`like_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- [Table `tr_likes` is empty]

CREATE TABLE `tr_posts` (
   `post_id` int(11) not null auto_increment,
   `post_text` varchar(255) not null,
   `post_image` varchar(255) not null,
   `post_user_id` int(11) not null,
   PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- [Table `tr_posts` is empty]