--
-- MySQL 5.5.16
-- Mon, 26 Dec 2011 18:18:47 +0000
--

CREATE TABLE `tr_images` (
   `image_id` int(11) not null auto_increment,
   `image_tribble_id` int(11) not null,
   `image_path` varchar(255) not null,
   `image_palette` varchar(255) not null,
   PRIMARY KEY (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

-- [Table `tr_images` is empty]

CREATE TABLE `tr_likes` (
   `like_id` int(11) not null,
   `like_post_id` int(11) not null,
   `like_user_id` int(11) not null,
   PRIMARY KEY (`like_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- [Table `tr_likes` is empty]

CREATE TABLE `tr_sessions` (
   `session_id` varchar(40) not null default '0',
   `ip_address` varchar(16) not null default '0',
   `user_agent` varchar(120) not null,
   `last_activity` int(10) unsigned not null default '0',
   `user_data` text not null,
   PRIMARY KEY (`session_id`),
   KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- [Table `tr_sessions` is empty]

CREATE TABLE `tr_tags` (
   `tags_id` int(11) not null auto_increment,
   `tags_tribble_id` int(11),
   `tags_content` varchar(255) not null,
   PRIMARY KEY (`tags_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- [Table `tr_tags` is empty]

CREATE TABLE `tr_tribbles` (
   `tribble_id` int(11) not null auto_increment,
   `tribble_parent` int(11) not null default '0',
   `tribble_title` varchar(255) not null,
   `tribble_text` varchar(255) not null,
   `tribble_user_id` int(11) not null,
   `tribble_views` int(11) not null default '0',
   `tribble_timestamp` timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
   PRIMARY KEY (`tribble_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=8;

-- [Table `tr_tribbles` is empty]

CREATE TABLE `tr_users` (
   `user_id` int(11) not null auto_increment,
   `user_email` varchar(255) not null,
   `user_password` varchar(128) not null,
   `user_realname` varchar(255) not null,
   `user_bio` text,
   PRIMARY KEY (`user_id`),
   UNIQUE KEY (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

-- [Table `tr_users` is empty]