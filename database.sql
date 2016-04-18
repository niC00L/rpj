-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.6.24 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.2.0.4947
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table rpj.banners
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) DEFAULT NULL,
  `title` varchar(120) DEFAULT NULL,
  `alt` varchar(120) DEFAULT NULL,
  `image_name` varchar(120) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `FK__controls` (`banner_id`),
  CONSTRAINT `FK__controls` FOREIGN KEY (`banner_id`) REFERENCES `controls` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.banners: ~4 rows (approximately)
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` (`id`, `banner_id`, `title`, `alt`, `image_name`, `description`) VALUES
	(1, 10, 'img', 'img', 'pool.png', 'pool'),
	(2, 10, 'h', 'h', 'hunterOMG.png', 'hunter'),
	(3, 11, 'f', 'd', 'fhg.png', 'fgh'),
	(4, 11, 'fdas', 'dsfa', 'avto.png', 'avto');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;


-- Dumping structure for table rpj.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned DEFAULT NULL,
  `post_ctg_id` int(10) unsigned DEFAULT NULL,
  `img_id` int(10) unsigned DEFAULT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `text` longtext,
  `img` varchar(200) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(10) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.comments: ~6 rows (approximately)
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` (`id`, `post_id`, `post_ctg_id`, `img_id`, `parent_id`, `user_id`, `name`, `email`, `text`, `img`, `date`, `status`) VALUES
	(1, 2, NULL, NULL, NULL, 5, '', '', 'ahoj ako sa mas', NULL, '2016-01-20 17:17:49', 1),
	(10, 1, NULL, NULL, NULL, 1, '', '', 'skus?', NULL, '2016-04-04 21:21:29', 1),
	(11, 1, NULL, NULL, NULL, 1, NULL, NULL, 'Ahoj ako sa mas? Pozri ako funguju komentare. Super, ze?', NULL, '2016-04-09 23:38:31', 1),
	(12, 1, NULL, NULL, NULL, 1, NULL, NULL, 'Co ked napisem <strong>srnka</strong> ?', NULL, '2016-04-09 23:41:27', 0),
	(13, 1, NULL, NULL, NULL, 1, NULL, NULL, 'Vsetci vieme ze srnka je Linda a Linda je srnka', NULL, '2016-04-09 23:41:48', 1),
	(14, NULL, 1, NULL, NULL, 5, NULL, NULL, 'Tu nie je ziaden komentar este', NULL, '2016-04-10 23:57:59', 1);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;


-- Dumping structure for table rpj.controls
CREATE TABLE IF NOT EXISTS `controls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `component_name` varchar(120) NOT NULL DEFAULT '0',
  `namespace` varchar(200) NOT NULL DEFAULT '0',
  `position` varchar(200) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` longtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 => loaded by basePresenter, 2=> loaded by loadControls, 0=> not loaded',
  `editable` int(11) NOT NULL DEFAULT '0',
  `template` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.controls: ~16 rows (approximately)
/*!40000 ALTER TABLE `controls` DISABLE KEYS */;
INSERT INTO `controls` (`id`, `component_name`, `namespace`, `position`, `title`, `description`, `status`, `editable`, `template`) VALUES
	(1, 'menu', '\\App\\Components\\Menu\\MenuControl', 'main-menu', 'Menu', 'menu', 1, 1, 5),
	(2, 'editForm', '\\App\\AdminModule\\Components\\Forms\\EditForm\\editFormControl', NULL, 'Edit form', '', 1, 0, NULL),
	(3, 'deleteForm', '\\App\\AdminModule\\Components\\Forms\\DeleteForm\\deleteFormControl', NULL, 'Delete form', '', 1, 0, NULL),
	(4, 'base', '\\App\\AdminModule\\Components\\baseControl', NULL, 'Base control', '', 1, 0, NULL),
	(5, 'renewForm', '\\App\\AdminModule\\Components\\Forms\\RenewForm\\renewFormControl', NULL, 'Renew form', '', 1, 0, NULL),
	(6, 'publishForm', '\\App\\AdminModule\\Components\\Forms\\PublishForm\\publishFormControl', NULL, 'Publish form', '', 1, 0, NULL),
	(7, 'comments', '\\App\\Components\\Comments\\CommentsControl', NULL, 'Comments', '', 1, 0, NULL),
	(8, 'gallery', '\\App\\Components\\Gallery\\GalleryControl', NULL, 'Gallery', '', 1, 0, NULL),
	(9, 'gridForm', '\\App\\AdminModule\\Components\\Forms\\GridForm\\gridFormControl', NULL, 'Grid form', '', 1, 0, NULL),
	(10, 'banner', '\\App\\Components\\Banner\\BannerControl', 'homepage-header', 'Banner', 'Banner na homepage header', 0, 1, 8),
	(11, 'banner', '\\App\\Components\\Banner\\BannerControl', 'homepage-header', 'Banner', 'Druhy banner na homepage', 0, 1, 9),
	(12, 'loadControl', '\\App\\AdminModule\\Components\\LoadControls\\loadControl', NULL, 'Load Control', '', 1, 0, NULL),
	(13, 'banner', '\\App\\Components\\Banner\\BannerControl', 'homepage-header', '', '', 0, 1, 8),
	(14, 'banner', '\\App\\Components\\Banner\\BannerControl', 'homepage-header', '', '', 0, 1, 8),
	(15, 'textBlock', '\\App\\Components\\TextBlock\\TextBlockControl', 'homepage-header', '', '', 2, 1, 11),
	(22, 'textBlock', '\\App\\Components\\TextBlock\\TextBlockControl', 'homepage-header', 'TextBlock', 'Textovy blok na homepage', 0, 1, 11);
/*!40000 ALTER TABLE `controls` ENABLE KEYS */;


-- Dumping structure for table rpj.ctrl_menu
CREATE TABLE IF NOT EXISTS `ctrl_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(5) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `class` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='tabulka pre control menu';

-- Dumping data for table rpj.ctrl_menu: ~5 rows (approximately)
/*!40000 ALTER TABLE `ctrl_menu` DISABLE KEYS */;
INSERT INTO `ctrl_menu` (`id`, `order`, `menu_id`, `type`, `action`, `address`, `title`, `class`, `status`) VALUES
	(1, 4, 1, 'Post', 'category', 'pilots', 'TOP', '', 1),
	(2, 6, 1, 'Sign', NULL, NULL, 'Sign', NULL, 1),
	(10, 3, 1, 'Post', 'show', 'polaroid', 'Polaroid', '', 1),
	(17, 1, 1, 'Homepage', NULL, NULL, 'Domov', '', 1),
	(24, 2, 1, 'Post', 'category', 'dragons', 'Dragons', '', 1);
/*!40000 ALTER TABLE `ctrl_menu` ENABLE KEYS */;


-- Dumping structure for table rpj.global_settings
CREATE TABLE IF NOT EXISTS `global_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `value` varchar(150) DEFAULT NULL,
  `editable` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.global_settings: ~18 rows (approximately)
/*!40000 ALTER TABLE `global_settings` DISABLE KEYS */;
INSERT INTO `global_settings` (`id`, `setting_name`, `description`, `value`, `editable`) VALUES
	(1, 'site_title', 'Titulok stránky', 'Rainbow dust', 1),
	(2, 'meta_description', 'Meta popis stránky', 'Best CMS ever', 1),
	(3, 'meta_keywords', 'Meta kľúčové slová ', 'cms, best, rainbow, unicorn', 1),
	(4, 'contact_email', 'Kontaktný email', 'admin@nicool.rocks', 1),
	(5, 'contact_phone', 'Kontaktný telefón', NULL, 1),
	(6, 'site_url', 'Adresa stránky', 'http://rainbow.dust', 1),
	(7, 'site_token', 'Token stránky', NULL, 0),
	(8, 'comment_post', 'Komentáre pre články', '1', 1),
	(9, 'comment_ctg', 'Komentáre pre kategórie', '1', 1),
	(10, 'comment_img', 'Komentáre pre obrázky', '1', 0),
	(11, 'comment_all', 'Pridávanie komentárov aj pre neregistrovaných', '1', 1),
	(12, 'comment_length', 'Dĺžka komentárov', '250', 1),
	(13, 'social_facebook', 'Odkaz na facebook', NULL, 1),
	(14, 'social_twitter', 'Odkaz na twitter', 'https://twitter.com/nicool147', 1),
	(15, 'social_instagram', 'Odkaz na instagram', 'https://instagram.com/nic00l147/', 1),
	(16, 'social_steam', 'Odkaz na steam', 'http://steamcommunity.com/profiles/76561198084256596/', 1),
	(17, 'social_devaintart', 'Odkaz na DeviantArt', 'http://nic00l.deviantart.com/', 1),
	(18, 'social_youtube', 'Odkaz na YouTube', 'https://www.youtube.com/channel/UCNrna7wnTu4s8AQ5TAkPHUQ', 1);
/*!40000 ALTER TABLE `global_settings` ENABLE KEYS */;


-- Dumping structure for table rpj.imgs
CREATE TABLE IF NOT EXISTS `imgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `image_name` varchar(150) DEFAULT NULL,
  `alt` varchar(200) DEFAULT NULL,
  `template` int(11) DEFAULT NULL,
  `comments` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.imgs: ~2 rows (approximately)
/*!40000 ALTER TABLE `imgs` DISABLE KEYS */;
INSERT INTO `imgs` (`id`, `title`, `description`, `image_name`, `alt`, `template`, `comments`, `status`) VALUES
	(1, 'title', 'description', 'magic.gif', 'alt', NULL, NULL, 1),
	(2, 'Asdf', 'test', 'afdsj.png', 'test', NULL, NULL, 1),
	(3, 'Eagle', 'je to jaguar', 'avto.png', 'eagle', NULL, NULL, 1);
/*!40000 ALTER TABLE `imgs` ENABLE KEYS */;


-- Dumping structure for table rpj.img_sort
CREATE TABLE IF NOT EXISTS `img_sort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) NOT NULL,
  `img_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_id` (`gallery_id`),
  KEY `FK_img_sort_imgs` (`img_id`),
  CONSTRAINT `FK_img_sort_imgs` FOREIGN KEY (`img_id`) REFERENCES `imgs` (`id`),
  CONSTRAINT `FK_img_sort_post` FOREIGN KEY (`gallery_id`) REFERENCES `post` (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.img_sort: ~3 rows (approximately)
/*!40000 ALTER TABLE `img_sort` DISABLE KEYS */;
INSERT INTO `img_sort` (`id`, `gallery_id`, `img_id`) VALUES
	(2, 1, 1),
	(3, 1, 2),
	(4, 1, 3);
/*!40000 ALTER TABLE `img_sort` ENABLE KEYS */;


-- Dumping structure for table rpj.post
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gallery_id` int(11) NOT NULL,
  `address` varchar(50) DEFAULT NULL COMMENT 'Adresa',
  `title` varchar(50) DEFAULT NULL COMMENT 'Titulok',
  `description` text COMMENT 'Popis',
  `text` text NOT NULL COMMENT 'Text',
  `title_image` varchar(200) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0-v kosi, 1-publikovane, 2-nepublikovane',
  `template` int(11) DEFAULT NULL,
  `comments` int(11) DEFAULT NULL,
  `author` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_id` (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.post: ~9 rows (approximately)
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` (`id`, `gallery_id`, `address`, `title`, `description`, `text`, `title_image`, `create_date`, `last_edit`, `status`, `template`, `comments`, `author`) VALUES
	(1, 1, 'polaroid', 'Polaroid', 'song about lone red rover ', 'All my life I\'ve been living in a fast lane, can\'t slow down I\'m a rolling freight train. One more time gotta start all over can\'t slow down I\'m a lone red rover zu to funguje stale? urcite?', '', '2016-04-08 15:53:12', '2016-04-17 21:34:48', 1, 1, 1, 1),
	(2, 2, 'holding', 'Holding on to you', 'song about i dont even know about what this is about skusam ci to este funguje', 'You are surrounding all my surroundings, sounding down the mountain range of my left side brain. You are surrounding all my surroundings, twisting that kaleidoscope behind both of my eyes.', '', '2016-04-08 15:53:13', '2016-04-08 22:46:02', 1, 1, 1, 1),
	(3, 3, 'third', 'Demons', 'song about demons', 'When you feel my heat, look into my eyes. That\'s where my demons hide, that\'s where my demons hide', '', '2016-04-08 15:53:14', '2016-04-08 22:46:02', 0, 1, 0, 1),
	(4, 4, 'fourth', 'Car radio', 'song about vandals who steal car radios', 'ahoj?', 'Gngxf0f.png', '2016-04-08 21:47:48', '2016-04-10 23:34:48', 1, 1, 1, 1),
	(5, 5, 'fifth', 'Ready, Aim, Fire!', 'song about soviet army training', 'With our backs to the wall, the darkness will fall, we never quit though we could lose it all.', '', '2016-04-08 15:53:15', '2016-04-08 22:46:02', 1, 1, 1, 1),
	(6, 6, 'sixth', 'Monster', 'song about linda', 'I\'m only a man with a candle to guide me. I\'m taking a stand to escape what\'s inside me', '', '2016-04-08 16:56:18', '2016-04-08 22:46:02', 0, 1, 0, 1),
	(7, 7, 'seventh', 'Tear in my heart', 'song about love', 'You fell asleep in my car I drove the whole time. But that\'s okay I\'ll just avoid the holes so you sleep fine.\nA pritom Linda je stále srnka. Neuveriteľné', '', '2016-04-08 15:53:19', '2016-04-08 22:46:02', 1, 1, 1, 5),
	(8, 8, 'daco', 'Srnka', 'srnka zapiera ze je linda ', 'Linda je srnka a srnka je Linda a to všetci vedia\nahoj ako sa máš\nfunguje to :D jej\nhaha je to super že?\naj ja si myslím', '', '2016-04-08 15:53:20', '2016-04-08 22:46:02', 0, 1, 0, 5),
	(27, 27, 'srnka', 'Srnka je Linda', 'a tak to navzdy bude', '', '', '2016-04-08 15:53:21', '2016-04-08 22:46:02', 1, 1, 1, 5);
/*!40000 ALTER TABLE `post` ENABLE KEYS */;


-- Dumping structure for table rpj.post_ctg
CREATE TABLE IF NOT EXISTS `post_ctg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(50) DEFAULT NULL,
  `title` varchar(50) NOT NULL DEFAULT '0',
  `description` text,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0-v kosi, 1-publikovane, 2-nepublikovane',
  `item_per_page` int(11) DEFAULT '5',
  `template` int(11) DEFAULT NULL,
  `comments` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='page_category';

-- Dumping data for table rpj.post_ctg: ~3 rows (approximately)
/*!40000 ALTER TABLE `post_ctg` DISABLE KEYS */;
INSERT INTO `post_ctg` (`id`, `address`, `title`, `description`, `status`, `item_per_page`, `template`, `comments`) VALUES
	(1, 'pilots', 'Pilots', 'songs from pilots because they are awesome jes JES ahoj top toast funguje', 1, 5, 2, NULL),
	(2, 'dragons', 'Dragons', 'songs from dragons hej skusam toast', 1, 5, 2, NULL),
	(3, 'stuped', 'Stuped shet', 'some serious stuff here', 0, 5, 2, NULL);
/*!40000 ALTER TABLE `post_ctg` ENABLE KEYS */;


-- Dumping structure for table rpj.post_ctg_sort
CREATE TABLE IF NOT EXISTS `post_ctg_sort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctg_id` int(11) DEFAULT NULL COMMENT 'id of categories',
  `post_id` int(11) unsigned DEFAULT NULL COMMENT 'id of pages',
  PRIMARY KEY (`id`),
  KEY `category` (`ctg_id`),
  KEY `page` (`post_id`),
  CONSTRAINT `category` FOREIGN KEY (`ctg_id`) REFERENCES `post_ctg` (`id`),
  CONSTRAINT `page` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='sorting pages to categories';

-- Dumping data for table rpj.post_ctg_sort: ~4 rows (approximately)
/*!40000 ALTER TABLE `post_ctg_sort` DISABLE KEYS */;
INSERT INTO `post_ctg_sort` (`id`, `ctg_id`, `post_id`) VALUES
	(4, 2, 3),
	(7, 1, 2),
	(12, 1, 4),
	(13, 2, 1);
/*!40000 ALTER TABLE `post_ctg_sort` ENABLE KEYS */;


-- Dumping structure for table rpj.site_templates
CREATE TABLE IF NOT EXISTS `site_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '0',
  `file_name` varchar(120) DEFAULT '0',
  `title` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.site_templates: ~11 rows (approximately)
/*!40000 ALTER TABLE `site_templates` DISABLE KEYS */;
INSERT INTO `site_templates` (`id`, `type`, `file_name`, `title`) VALUES
	(1, 'post', 'post', 'Článok s galériou'),
	(2, 'post_ctg', 'category', 'Kategória s galériou'),
	(3, 'image', '', NULL),
	(4, 'comment', 'commentsDefault.latte', 'Komentáre'),
	(5, 'menu', 'menuDefault.latte', 'Horné menu'),
	(6, 'post', 'postNoGallery', 'Článok bez galérie'),
	(7, 'post', 'gallery', 'Galéria'),
	(8, 'banner', 'slider', 'Slider s textom'),
	(9, 'banner', 'carousel', 'Carousel s malými obrázkami'),
	(10, 'banner', 'carousel-slider', 'Carousel s veľkými obrázkami'),
	(11, 'textBlock', 'textBlockDefault', 'Základná šablóna');
/*!40000 ALTER TABLE `site_templates` ENABLE KEYS */;


-- Dumping structure for table rpj.text_block
CREATE TABLE IF NOT EXISTS `text_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `control_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.text_block: ~2 rows (approximately)
/*!40000 ALTER TABLE `text_block` DISABLE KEYS */;
INSERT INTO `text_block` (`id`, `control_id`, `title`, `text`) VALUES
	(1, 15, NULL, 'Textový blok. Kliknutím editujte.'),
	(2, 22, NULL, 'Textový blok. Kliknutím editujte.');
/*!40000 ALTER TABLE `text_block` ENABLE KEYS */;


-- Dumping structure for table rpj.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(72) NOT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `about` text,
  `profile_image` varchar(150) DEFAULT NULL,
  `background_image` varchar(150) DEFAULT NULL,
  `visible_mail` int(11) NOT NULL DEFAULT '0',
  `visible_content` int(11) NOT NULL DEFAULT '0',
  `token` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.users: ~4 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `role`, `username`, `password`, `display_name`, `email`, `about`, `profile_image`, `background_image`, `visible_mail`, `visible_content`, `token`) VALUES
	(1, 'admin', 'admin', 'Deny7706', 'niC00L', 'nika.blanarova@gmail.com', 'Kratky popis o mne', '0log7n5.jpg', '63 (4).jpg', 0, 0, ''),
	(2, 'editor', 'a', 'Deny7706', NULL, NULL, '', NULL, NULL, 0, 0, ''),
	(5, 'user', 'srnka', 'Deny7706', 'Linda', 'srnka@linda.sk', '', '', NULL, 0, 0, '42'),
	(6, 'user', 'casey662', 'Deny7706', 'John Casey', 'main@casey.org', '', NULL, NULL, 0, 0, 'tx345bxtoulo0mcvwc9hk');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
