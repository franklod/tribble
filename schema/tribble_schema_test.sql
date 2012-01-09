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
AUTO_INCREMENT=19 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

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
AUTO_INCREMENT=87 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

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
AUTO_INCREMENT=79 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

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
AUTO_INCREMENT=17 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

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
AUTO_INCREMENT=83 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

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
AUTO_INCREMENT=90 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

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

#
# Data for the `tr_comments` table  (LIMIT 0,500)
#

INSERT INTO `tr_comments` (`comment_id`, `comment_text`, `comment_user_id`) VALUES 
  (18,'kkash dkajsdkjash dkjash dkjsakdhaskjdsakhdkajs dj haskj sadk jsadkjsadk sadk jasdkj sadkjasdkj asdkj sad kjasdkj sadkj asdkj asdkj asdk asdkj',8);
COMMIT;

#
# Data for the `tr_images` table  (LIMIT 0,500)
#

INSERT INTO `tr_images` (`image_id`, `image_tribble_id`, `image_path`, `image_palette`) VALUES 
  (65,68,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/1.jpg','[\"000000\",\"CCCCCC\",333333,\"FFFFFF\",330000,\"CCFFFF\",663333,999999]'),
  (66,69,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/2.jpg','[\"000000\",330000,\"CC6600\",\"FFFFFF\",333333,999999,\"CCCCCC\",663300]'),
  (67,70,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/3.jpg','[330000,\"FF9900\",\"FF6600\",\"CC3366\",\"CC3399\",\"FFCC99\",660000,\"FF6633\"]'),
  (68,71,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/4.png','[\"CCCCCC\",999999,\"CC9966\",\"CC9999\",\"CCCC99\",\"FFFFFF\",666666,996666]'),
  (69,72,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/5.png','[\"CCCCCC\",\"FFFFFF\",999999,\"CCFFFF\",\"CCCCFF\",333366,\"FFCC33\",666666]'),
  (70,73,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/6.jpg','[\"CCCCCC\",\"FFFFCC\",\"FFCCCC\",996699,\"FFFFFF\",\"CC99CC\",333333,\"CC9999\"]'),
  (71,74,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/7.png','[\"CCCCCC\",\"FFFFFF\",333333,999999,\"99CCCC\",\"3399CC\",336699,333366]'),
  (72,75,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/8.png','[\"CCCCCC\",\"FFFFCC\",333333,999999,993333,993366,\"CCCC99\",666666]'),
  (73,76,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/9.png','[\"FFFFFF\",666666,333333,\"CCCCCC\",\"3399CC\",\"3366CC\",999999,\"FFFFCC\"]'),
  (74,77,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/10.jpg','[666666,999999,333333,333366,336666,669999,666699,\"99CCCC\"]'),
  (75,78,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/11.jpg','[999999,\"CC9966\",333333,996633,993333,\"CCCC66\",\"CC6666\",\"CC6633\"]'),
  (76,79,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/12.png','[\"CCFFFF\",\"CCCCCC\",\"FFFFFF\",\"CCCCFF\",999999,\"000000\",\"CCFFCC\",333333]'),
  (77,80,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/13.png','[\"000000\",333333,\"CCCCCC\",\"CC9999\",999999,996666,\"FFFFFF\",666666]'),
  (78,81,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/14.jpg','[\"000000\",\"FFFFFF\",333333,\"CCCCCC\",999999,666666]'),
  (79,82,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/15.png','[333333,\"FF6633\",666666,\"CC6633\",666633,\"003333\",\"CC6666\",663333]'),
  (80,83,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/16.png','[\"FF6633\",\"000000\",\"FFFFFF\",333333,333300,\"FF9966\",\"CC6633\",330000]'),
  (81,84,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/17.png','[333333,\"CCCCFF\",\"CCFFFF\",\"3399CC\",336666,\"66CCFF\",\"CCCCCC\",333366]'),
  (82,85,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/18.jpg','[\"FFFFFF\",\"CCFFFF\",\"CCCCCC\",\"CCCCFF\",\"003399\",993366,\"FF6633\",\"CC0000\"]'),
  (83,86,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/19.jpg','[\"FFFFFF\",\"CCCCCC\",999999,666666,\"99CCCC\",\"000000\",333333,669999]'),
  (84,87,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/20.jpg','[\"FFFFFF\",996633,663333,\"CC9933\",\"CC9966\",\"FFCC66\",\"CCCCFF\",\"CC6633\"]'),
  (85,88,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/21.png','[\"CCCCCC\",\"000000\",\"FFFFFF\",999999,\"99CCCC\",\"CCFFFF\",\"9999CC\",\"FFCCCC\"]'),
  (86,89,'/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/23.jpg','[333333,996633,\"CC6633\",\"CC9933\",999966,\"CC9966\",663300,663333]');
COMMIT;

#
# Data for the `tr_likes` table  (LIMIT 0,500)
#

INSERT INTO `tr_likes` (`like_id`, `like_tribble_id`, `like_user_id`) VALUES 
  (57,68,8),
  (58,69,8),
  (59,70,8),
  (60,71,8),
  (61,72,8),
  (62,73,8),
  (63,74,8),
  (64,75,8),
  (65,76,8),
  (66,77,8),
  (67,78,8),
  (68,79,8),
  (69,80,8),
  (70,81,8),
  (71,82,8),
  (72,83,8),
  (73,84,8),
  (74,85,8),
  (75,86,8),
  (76,87,8),
  (77,88,8),
  (78,89,8);
COMMIT;

#
# Data for the `tr_replies` table  (LIMIT 0,500)
#

INSERT INTO `tr_replies` (`reply_id`, `reply_tribble_id`, `reply_comment_id`, `reply_rebound_id`, `reply_timestamp`) VALUES 
  (16,88,18,NULL,'2012-01-09 15:03:28');
COMMIT;

#
# Data for the `tr_tags` table  (LIMIT 0,500)
#

INSERT INTO `tr_tags` (`tags_id`, `tags_tribble_id`, `tags_content`) VALUES 
  (61,68,'app,iphone,dropdown,mobile,buttons,energy,settings'),
  (62,69,'app,iphone,ui,energy,image,leaderboard'),
  (63,70,'crown,star,3,gift,gold,men,myrrh,nativity,north,wise'),
  (64,71,'illustration,texture,art,portrait,modeling,3d,character,clay,craft,figure,pillow,jeans,process'),
  (65,72,'css3,html5jquery,photoshop,simulator'),
  (66,73,'illustration,robot,creaturebox,jellybean,model,texture'),
  (67,74,'search,psd,suggestions'),
  (68,75,'football,nfl,jets,super,bowl,packers,bill.maher,socialism,cowboys'),
  (69,76,'ui,interface,ux,design,icons'),
  (70,77,'button,follow me'),
  (71,78,'lettering,type,painting,handmade,quiet'),
  (72,79,'ui,interface,user,ux,display,light,white,cable'),
  (73,80,'art direction,design,creative direction,ui,rally interactive,e-commerce,html'),
  (74,81,'tas,love,fun,calligraphy,creativity,america,city,san francisco'),
  (75,82,'rinker,illustration,typography,design,logo,type,branding,alex rinker,portfolio,site,website'),
  (76,83,'orange,horns'),
  (77,84,'dark,application,mac, osx,editor,code'),
  (78,85,'ui,election survey,chart,graph,pie'),
  (79,86,'illustration,drawing,pencil,sketch,texture,car,automotive,draw,old,vehicle,vw'),
  (80,87,'logo,illustration,animal,typography,lion,bull,fox,ferret,rabbit,ram'),
  (81,88,'interface,minimalist,app,dropdown,ipad,ios,ipad app,tabs,application,buttons,stats'),
  (82,89,'art,cena,graphic design');
COMMIT;

#
# Data for the `tr_tribbles` table  (LIMIT 0,500)
#

INSERT INTO `tr_tribbles` (`tribble_id`, `tribble_title`, `tribble_text`, `tribble_user_id`, `tribble_timestamp`) VALUES 
  (68,'Edit Setting/Viewing selection','At last his spout grew thick, and with a frightful roll and vomit, he turned upon his back a corpse. While the two headsmen were engaged in making fast cords to his flukes, and in other ways getting the mass in readiness for towing, some conversation ensu',8,'2012-01-09 11:52:56'),
  (69,'2012 - Things in the MyEnergy pipeline','I am super pumped for a bunch of things we have in the boiling in the 2012 pipeline at MyEnergy.\r\n\r\nIt''s so fun and exciting to play in a space that typically isn''t known to be very \"sexy\", yet has tons of data and exciting things you can do with it. This',8,'2012-01-09 11:53:37'),
  (70,'3 Wise Men 3 Wise Men ','So over Christmas break I did some sketching. Finally got a new scanner and have been trying to work them out. Word.',8,'2012-01-09 11:55:08'),
  (71,'Mr. Klyn and Insomnia','Working on these.',8,'2012-01-09 11:56:24'),
  (72,'Photoshop Simulator','My weekend project is finally complete; it''s a Photoshop simulator, written in HTML 5, CSS3, canvas, and jQuery.',8,'2012-01-09 11:57:15'),
  (73,'Jellybean','I miss the feeling of opening a new model kit and thinking about the possibilities of what it was to become.',8,'2012-01-09 11:58:08'),
  (74,'Search Suggestions','\"Wants with it?\" said Flask, coiling some spare line in the boat''s bow, \"did you never hear that the ship which but once has a Sperm Whale''s head hoisted on her starboard side, and at the same time a Right Whale''s on the larboard; did you never hear, Stub',8,'2012-01-09 11:59:18'),
  (75,'Fuck Yeah','A screenshot from my latest animated short ''Football & Socialism'' from a monologue by Bill Maher.',8,'2012-01-09 12:00:14'),
  (76,'Coca-cola Campaign','\"Wants with it?\" said Flask, coiling some spare line in the boat''s bow, \"did you never hear that the ship which but once has a Sperm Whale''s head hoisted on her starboard side, and at the same time a Right Whale''s on the larboard; did you never hear, Stub',8,'2012-01-09 12:01:25'),
  (77,'Follow me','\"I don''t know, but I heard that gamboge ghost of a Fedallah saying so, and he seems to know all about ships'' charms. But I sometimes think he''ll charm the ship to no good at last. I don''t half like that chap, Stubb. Did you ever notice how that tusk of hi',8,'2012-01-09 12:07:15'),
  (78,'Pssst','\"He sleeps in his boots, don''t he? He hasn''t got any hammock; but I''ve seen him lay of nights in a coil of rigging.\"\r\n\"No doubt, and it''s because of his cursed tail; he coils it down, do ye see, in the eye of the rigging.\"',8,'2012-01-09 12:08:23'),
  (79,'UI Preview','After a long time without making something for graphic river I am having lots of fun with my new UI. Stay tuned for more.... and press ♥ if you Like it :-)',8,'2012-01-09 12:09:19'),
  (80,'Another old Kühl site pitch direction (*)','We''re currently slammed on work that we can''t post on Dribbble yet, but thought we''d share another pitch direction for Kühl that was done last May. See attached screens for bigger versions.\r\n\r\nSomeday we''ll be able to post what we''re currently working on,',8,'2012-01-09 12:10:17'),
  (81,'San Francisco','commercial calligraphy from TaS ... as seen on ... \r\nhttp://www.behance.net/gallery/commercial-calligraphy/2774503',8,'2012-01-09 12:11:05'),
  (82,'Portfolio Site','I haven''t updated or managed my personal portfolio site in 5.5 years! A much needed update is in progress.',8,'2012-01-09 12:14:45'),
  (83,'Buttheads','While the two headsmen were engaged in making fast cords to his flukes, and in other ways getting the mass in readiness for towing, some conversation ensued between them.',8,'2012-01-09 12:15:34'),
  (84,'Awesome Code Editor','In few words: \r\n1. Cloud sync - Dropbox & GIT integration. \r\n2. Focus mode - lets you fadeout parts of the code that you don''t need to see. You can fadeout whatever parts of code you want with whatever indent level. Everything with keyboard shortcuts. \r\n3',8,'2012-01-09 12:17:20'),
  (85,'Election Survey Pie','A pie graph for an election survey site.\r\n\r\nThank you Vucek for inviting me!',8,'2012-01-09 12:18:34'),
  (86,'VolksWagen Bubble!','FINISH! look entire project here: http://bit.ly/zN4KYD',8,'2012-01-09 12:20:28'),
  (87,'Ex Libris Riet de Haas','Wolfgang von Goethe \"Reynard The Fox\" \r\nThe engraving on the plastic. Paper, 6 colors. Authoring printing. ©Galitsyn',8,'2012-01-09 12:21:30'),
  (88,'Stats (iPad UX/UI)','Here is part of an iPad App UX project I''m working on... I don''t have much time for UI, it''s basically UX work, but still doing best for UI in the very limited time... Dummy content, thanks to NDA :|\r\n\r\nI also attached bigger photo.\r\n\r\nYou can follow me o',8,'2012-01-09 13:21:39'),
  (89,'Nucky''s Speakeasy Lounge - Block Print ','I can''t say hot much I like the real craftmanship of your work. It makes me want to get wood and chisels tomorrow. Absolutely fantastic!',8,'2012-01-09 15:05:28');
COMMIT;

#
# Data for the `tr_users` table  (LIMIT 0,500)
#

INSERT INTO `tr_users` (`user_id`, `user_email`, `user_password`, `user_realname`, `user_bio`, `user_avatar`) VALUES 
  (8,'pedro.a.correia@co.sapo.pt','69c5fcebaa65b560eaf06c3fbeb481ae44b8d618','Pedro Correia','',NULL);
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;