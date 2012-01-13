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
AUTO_INCREMENT=37 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_image` table : 
#

DROP TABLE IF EXISTS `tr_image`;

CREATE TABLE `tr_image` (
  `image_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `image_post_id` INTEGER(11) NOT NULL,
  `image_path` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `image_palette` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`image_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=87 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

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
AUTO_INCREMENT=40 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_post` table : 
#

DROP TABLE IF EXISTS `tr_post`;

CREATE TABLE `tr_post` (
  `post_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `post_title` VARCHAR(255) COLLATE utf8_general_ci DEFAULT '',
  `post_text` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `post_user_id` INTEGER(11) NOT NULL,
  `post_timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_is_deleted` TINYINT(1) DEFAULT '0',
  PRIMARY KEY (`post_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=90 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

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
AUTO_INCREMENT=34 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `tr_session` table : 
#

DROP TABLE IF EXISTS `tr_session`;

CREATE TABLE `tr_session` (
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
# Structure for the `tr_tag` table : 
#

DROP TABLE IF EXISTS `tr_tag`;

CREATE TABLE `tr_tag` (
  `tag_id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `tag_post_id` INTEGER(11) DEFAULT NULL,
  `tag_content` TEXT COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`tag_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=83 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

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
  `user_avatar` VARCHAR(256) COLLATE utf8_general_ci DEFAULT NULL,
  `user_stubb` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email_UNIQUE` (`user_email`)
)ENGINE=InnoDB
AUTO_INCREMENT=11 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
COMMENT='\t';

#
# Data for the `tr_comment` table  (LIMIT 0,500)
#

INSERT INTO `tr_comment` (`comment_id`, `comment_text`, `comment_user_id`, `comment_is_deleted`) VALUES 
  (34,'godon brwon still it ati see the media won t admit that iraq is raising petrol rices because they have given away our sovriegnty all true brits should smash the system sick to the backt eeth',8,0),
  (35,'is this what the bbc licence tax gets spent on i read that council estate scum are pandering to minorities because they have given away our sovriegnty isnt it obvious chop their hand s off see how they like it the last to leave please turn out the light',9,0),
  (36,'Lets see now I heard this week that communists are hugging hoodies because muslims are running the show, It is vital that we elect Jeremy Clarkson as prime minister, BRITISH FIRST!',10,0);
COMMIT;

#
# Data for the `tr_image` table  (LIMIT 0,500)
#

INSERT INTO `tr_image` (`image_id`, `image_post_id`, `image_path`, `image_palette`) VALUES 
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
# Data for the `tr_like` table  (LIMIT 0,500)
#

INSERT INTO `tr_like` (`like_post_id`, `like_user_id`, `like_id`) VALUES 
  (68,8,1),
  (69,8,2),
  (70,8,3),
  (71,8,4),
  (72,8,5),
  (73,8,6),
  (74,8,7),
  (75,8,8),
  (76,8,9),
  (77,8,10),
  (78,8,11),
  (79,8,12),
  (80,8,13),
  (81,8,14),
  (82,8,15),
  (83,8,16),
  (84,8,17),
  (85,8,18),
  (86,8,19),
  (87,8,20),
  (88,8,21),
  (69,10,23),
  (89,8,29),
  (88,10,30),
  (88,9,32),
  (83,9,33),
  (89,9,35),
  (86,9,36),
  (79,9,37),
  (81,10,38),
  (82,10,39);
COMMIT;

#
# Data for the `tr_post` table  (LIMIT 0,500)
#

INSERT INTO `tr_post` (`post_id`, `post_title`, `post_text`, `post_user_id`, `post_timestamp`, `post_is_deleted`) VALUES 
  (68,'Edit Setting/Viewing selection','At last his spout grew thick, and with a frightful roll and vomit, he turned upon his back a corpse. While the two headsmen were engaged in making fast cords to his flukes, and in other ways getting the mass in readiness for towing, some conversation ensu',8,'2012-01-09 11:52:56',0),
  (69,'2012 - Things in the MyEnergy pipeline','I am super pumped for a bunch of things we have in the boiling in the 2012 pipeline at MyEnergy.\r\n\r\nIt''s so fun and exciting to play in a space that typically isn''t known to be very \"sexy\", yet has tons of data and exciting things you can do with it. This',8,'2012-01-09 11:53:37',0),
  (70,'3 Wise Men 3 Wise Men ','So over Christmas break I did some sketching. Finally got a new scanner and have been trying to work them out. Word.',8,'2012-01-09 11:55:08',0),
  (71,'Mr. Klyn and Insomnia','Working on these.',8,'2012-01-09 11:56:24',0),
  (72,'Photoshop Simulator','My weekend project is finally complete; it''s a Photoshop simulator, written in HTML 5, CSS3, canvas, and jQuery.',8,'2012-01-09 11:57:15',0),
  (73,'Jellybean','I miss the feeling of opening a new model kit and thinking about the possibilities of what it was to become.',8,'2012-01-09 11:58:08',0),
  (74,'Search Suggestions','\"Wants with it?\" said Flask, coiling some spare line in the boat''s bow, \"did you never hear that the ship which but once has a Sperm Whale''s head hoisted on her starboard side, and at the same time a Right Whale''s on the larboard; did you never hear, Stub',8,'2012-01-09 11:59:18',0),
  (75,'Fuck Yeah','A screenshot from my latest animated short ''Football & Socialism'' from a monologue by Bill Maher.',8,'2012-01-09 12:00:14',0),
  (76,'Coca-cola Campaign','\"Wants with it?\" said Flask, coiling some spare line in the boat''s bow, \"did you never hear that the ship which but once has a Sperm Whale''s head hoisted on her starboard side, and at the same time a Right Whale''s on the larboard; did you never hear, Stub',8,'2012-01-09 12:01:25',0),
  (77,'Follow me','\"I don''t know, but I heard that gamboge ghost of a Fedallah saying so, and he seems to know all about ships'' charms. But I sometimes think he''ll charm the ship to no good at last. I don''t half like that chap, Stubb. Did you ever notice how that tusk of hi',8,'2012-01-09 12:07:15',0),
  (78,'Pssst','\"He sleeps in his boots, don''t he? He hasn''t got any hammock; but I''ve seen him lay of nights in a coil of rigging.\"\r\n\"No doubt, and it''s because of his cursed tail; he coils it down, do ye see, in the eye of the rigging.\"',8,'2012-01-09 12:08:23',0),
  (79,'UI Preview','After a long time without making something for graphic river I am having lots of fun with my new UI. Stay tuned for more.... and press ♥ if you Like it :-)',8,'2012-01-09 12:09:19',0),
  (80,'Another old Kühl site pitch direction (*)','We''re currently slammed on work that we can''t post on Dribbble yet, but thought we''d share another pitch direction for Kühl that was done last May. See attached screens for bigger versions.\r\n\r\nSomeday we''ll be able to post what we''re currently working on,',8,'2012-01-09 12:10:17',0),
  (81,'San Francisco','commercial calligraphy from TaS ... as seen on ... \r\nhttp://www.behance.net/gallery/commercial-calligraphy/2774503',8,'2012-01-09 12:11:05',0),
  (82,'Portfolio Site','I haven''t updated or managed my personal portfolio site in 5.5 years! A much needed update is in progress.',8,'2012-01-09 12:14:45',0),
  (83,'Buttheads','While the two headsmen were engaged in making fast cords to his flukes, and in other ways getting the mass in readiness for towing, some conversation ensued between them.',8,'2012-01-09 12:15:34',0),
  (84,'Awesome Code Editor','In few words: \r\n1. Cloud sync - Dropbox & GIT integration. \r\n2. Focus mode - lets you fadeout parts of the code that you don''t need to see. You can fadeout whatever parts of code you want with whatever indent level. Everything with keyboard shortcuts. \r\n3',8,'2012-01-09 12:17:20',0),
  (85,'Election Survey Pie','A pie graph for an election survey site.\r\n\r\nThank you Vucek for inviting me!',8,'2012-01-09 12:18:34',0),
  (86,'VolksWagen Bubble!','FINISH! look entire project here: http://bit.ly/zN4KYD',8,'2012-01-09 12:20:28',0),
  (87,'Ex Libris Riet de Haas','Wolfgang von Goethe \"Reynard The Fox\" \r\nThe engraving on the plastic. Paper, 6 colors. Authoring printing. ©Galitsyn',8,'2012-01-09 12:21:30',0),
  (88,'Stats (iPad UX/UI)','Here is part of an iPad App UX project I''m working on... I don''t have much time for UI, it''s basically UX work, but still doing best for UI in the very limited time... Dummy content, thanks to NDA :|\r\n\r\nI also attached bigger photo.\r\n\r\nYou can follow me o',8,'2012-01-09 13:21:39',0),
  (89,'Nucky''s Speakeasy Lounge - Block Print ','I can''t say hot much I like the real craftmanship of your work. It makes me want to get wood and chisels tomorrow. Absolutely fantastic!',8,'2012-01-09 15:05:28',0);
COMMIT;

#
# Data for the `tr_reply` table  (LIMIT 0,500)
#

INSERT INTO `tr_reply` (`reply_id`, `reply_post_id`, `reply_comment_id`, `reply_rebound_id`, `reply_timestamp`, `reply_is_deleted`) VALUES 
  (31,85,34,NULL,'2012-01-12 16:30:19',0),
  (32,85,35,NULL,'2012-01-12 16:30:52',0),
  (33,85,36,NULL,'2012-01-12 16:31:28',0);
COMMIT;

#
# Data for the `tr_session` table  (LIMIT 0,500)
#

INSERT INTO `tr_session` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES 
  ('82602a1d6528ab1ca35ec51f9d2aadbd','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.16 (KHTML, like Gecko) Chrome/18.0.1003.1 Safari/535.16',1326397851,'a:5:{s:9:\"user_data\";s:0:\"\";s:3:\"uid\";s:2:\"10\";s:5:\"uname\";s:5:\"Zbing\";s:6:\"unique\";s:22:\"zbing.zboing@gmail.com\";s:5:\"stubb\";N;}'),
  ('ec9f7acd0c1ad4342dfe3d7bd024a60f','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.16 (KHTML, like Gecko) Chrome/18.0.1003.1 Safari/535.16',1326453446,'');
COMMIT;

#
# Data for the `tr_tag` table  (LIMIT 0,500)
#

INSERT INTO `tr_tag` (`tag_id`, `tag_post_id`, `tag_content`) VALUES 
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
# Data for the `tr_user` table  (LIMIT 0,500)
#

INSERT INTO `tr_user` (`user_id`, `user_email`, `user_password`, `user_realname`, `user_bio`, `user_avatar`, `user_stubb`) VALUES 
  (8,'pedro.a.correia@co.sapo.pt','69c5fcebaa65b560eaf06c3fbeb481ae44b8d618','Pedro Correia',NULL,NULL,'pedro-correia'),
  (9,'pedro.correia@gmail.com','cbcce4ebcf0e63f32a3d6904397792720f7e40ba','José António','',NULL,NULL),
  (10,'zbing.zboing@gmail.com','cbcce4ebcf0e63f32a3d6904397792720f7e40ba','Zbing','',NULL,NULL);
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;