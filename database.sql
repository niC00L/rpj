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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.comments: ~2 rows (approximately)
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` (`id`, `post_id`, `post_ctg_id`, `img_id`, `parent_id`, `user_id`, `name`, `email`, `text`, `img`, `date`, `status`) VALUES
	(1, 5, NULL, NULL, NULL, NULL, 'Marienka', 'marienka@gmail.com', 'Tento komentár písal neregistrovaný používateľ.', NULL, '2016-04-22 02:10:55', 1),
	(2, 5, NULL, NULL, NULL, 1, NULL, NULL, 'Tento komentár už písal registrovaný používateľ, ktorý nemusel vypisovať meno ani e-mail.', NULL, '2016-04-22 02:11:50', 1);
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.controls: ~17 rows (approximately)
/*!40000 ALTER TABLE `controls` DISABLE KEYS */;
INSERT INTO `controls` (`id`, `component_name`, `namespace`, `position`, `title`, `description`, `status`, `editable`, `template`) VALUES
	(1, 'menu', '\\App\\Components\\Menu\\MenuControl', 'layout-menu', 'Hlavné menu', 'Hlavné menu na vrchu stránky', 2, 1, 5),
	(2, 'editForm', '\\App\\AdminModule\\Components\\Forms\\EditForm\\editFormControl', NULL, 'Edit form', '', 2, 0, NULL),
	(3, 'deleteForm', '\\App\\AdminModule\\Components\\Forms\\DeleteForm\\deleteFormControl', NULL, 'Delete form', '', 2, 0, NULL),
	(4, 'base', '\\App\\AdminModule\\Components\\baseControl', NULL, 'Base control', '', 2, 0, NULL),
	(5, 'renewForm', '\\App\\AdminModule\\Components\\Forms\\RenewForm\\renewFormControl', NULL, 'Renew form', '', 2, 0, NULL),
	(6, 'publishForm', '\\App\\AdminModule\\Components\\Forms\\PublishForm\\publishFormControl', NULL, 'Publish form', '', 2, 0, NULL),
	(7, 'comments', '\\App\\Components\\Comments\\CommentsControl', NULL, 'Comments', '', 2, 0, NULL),
	(8, 'gridForm', '\\App\\AdminModule\\Components\\Forms\\GridForm\\gridFormControl', NULL, 'Grid form', '', 2, 0, NULL),
	(9, 'loadControl', '\\App\\AdminModule\\Components\\LoadControls\\loadControl', NULL, 'Load Control', '', 0, 0, NULL),
	(10, 'multiplierForm', '\\App\\AdminModule\\Components\\Forms\\MultiplierForm\\multiplierFormControl', NULL, 'MultiplierForm', '', 2, 0, NULL),
	(11, 'banner', '\\App\\Components\\Banner\\BannerControl', 'homepage-default-header', 'Homepage banner', 'Veľký banner na homepage', 1, 1, 8),
	(12, 'gallery', '\\App\\Components\\Gallery\\GalleryControl', NULL, NULL, '', 2, 0, NULL),
	(13, 'gallery', '\\App\\Components\\Gallery\\GalleryControl', NULL, NULL, '', 2, 1, 13),
	(14, 'gallery', '\\App\\Components\\Gallery\\GalleryControl', NULL, NULL, '', 2, 1, 13),
	(15, 'textBlock', '\\App\\Components\\TextBlock\\TextBlockControl', 'homepage-default-content', '', '', 1, 1, 14),
	(16, 'menu', '\\App\\Components\\Menu\\MenuControl', 'layout-footer', 'Odkazy na sociálne siete', '', 1, 1, 12);
/*!40000 ALTER TABLE `controls` ENABLE KEYS */;


-- Dumping structure for table rpj.ctrl_banner
CREATE TABLE IF NOT EXISTS `ctrl_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) DEFAULT NULL,
  `title` varchar(120) DEFAULT NULL,
  `alt` varchar(120) DEFAULT NULL,
  `image_name` varchar(120) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `FK__controls` (`banner_id`),
  CONSTRAINT `FK__controls` FOREIGN KEY (`banner_id`) REFERENCES `controls` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.ctrl_banner: ~3 rows (approximately)
/*!40000 ALTER TABLE `ctrl_banner` DISABLE KEYS */;
INSERT INTO `ctrl_banner` (`id`, `banner_id`, `title`, `alt`, `image_name`, `description`) VALUES
	(6, 11, 'CMS systém', 'cms', 'default4.jpg', ''),
	(7, 11, 'Ukážka', 'banner', 'default5.jpg', 'Toto je ukážkový slider'),
	(8, 11, 'Tretí', 'dobre', 'default3.jpg', 'Do tretice všetko dobré');
/*!40000 ALTER TABLE `ctrl_banner` ENABLE KEYS */;


-- Dumping structure for table rpj.ctrl_gallery
CREATE TABLE IF NOT EXISTS `ctrl_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `alt` varchar(200) DEFAULT NULL,
  `image_name` varchar(150) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `template` int(11) DEFAULT NULL,
  `comments` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.ctrl_gallery: ~8 rows (approximately)
/*!40000 ALTER TABLE `ctrl_gallery` DISABLE KEYS */;
INSERT INTO `ctrl_gallery` (`id`, `gallery_id`, `title`, `alt`, `image_name`, `description`, `template`, `comments`, `status`) VALUES
	(1, 13, 'Ukážka', 'sample', 'default0.jpg', 'Ukážkový obrázok', NULL, NULL, 1),
	(2, 13, '', 'sample', 'default6.jpg', '', NULL, NULL, 1),
	(3, 13, '', 'sample', 'default8.jpg', 'Nie vždy musia byť vyplnené všetky položky', NULL, NULL, 1),
	(4, 13, 'Titulok obrázka', 'alternativny popis', 'default1.jpg', 'A nakoniec popis obrázka', NULL, NULL, 1),
	(5, 14, '', '', 'sutaz.png', '', NULL, NULL, 1),
	(6, 14, 'titulok', 'alt', 'angry wolf.png', 'popis', NULL, NULL, 1),
	(7, 14, '', '', 'mountainssss.png', '', NULL, NULL, 1),
	(8, 14, '', '', 'pigeon.png', '', NULL, NULL, 1);
/*!40000 ALTER TABLE `ctrl_gallery` ENABLE KEYS */;


-- Dumping structure for table rpj.ctrl_menu
CREATE TABLE IF NOT EXISTS `ctrl_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(5) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `ext_address` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `class` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='tabulka pre control menu';

-- Dumping data for table rpj.ctrl_menu: ~8 rows (approximately)
/*!40000 ALTER TABLE `ctrl_menu` DISABLE KEYS */;
INSERT INTO `ctrl_menu` (`id`, `order`, `menu_id`, `type`, `action`, `address`, `ext_address`, `title`, `class`, `status`) VALUES
	(1, 1, 1, 'Homepage', NULL, NULL, NULL, 'Domov', '', 1),
	(2, 4, 1, 'Sign', NULL, NULL, '', 'Sign', '', 1),
	(3, 2, 1, 'Post', 'category', 'ukazkova-kategoria', '', 'Ukážka kategórie', '', 1),
	(4, 3, 1, 'Post', 'show', 'clanok-bez-kategorie', '', 'Článok v menu', '', 1),
	(5, 1, 16, 'external', NULL, NULL, 'https://twitter.com/nicool147', 'Twitter', 'white-text', 1),
	(6, 1, 17, 'Homepage', NULL, NULL, NULL, 'Domovská stránka', NULL, 1),
	(7, 2, 16, 'external', NULL, NULL, 'https://www.youtube.com/channel/UCNrna7wnTu4s8AQ5T', 'Youtube', 'white-text', 1),
	(8, 3, 16, 'external', NULL, NULL, 'https://www.instagram.com/nic00l147/', 'Instagram', 'white-text', 1);
/*!40000 ALTER TABLE `ctrl_menu` ENABLE KEYS */;


-- Dumping structure for table rpj.ctrl_text_block
CREATE TABLE IF NOT EXISTS `ctrl_text_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `control_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.ctrl_text_block: ~2 rows (approximately)
/*!40000 ALTER TABLE `ctrl_text_block` DISABLE KEYS */;
INSERT INTO `ctrl_text_block` (`id`, `control_id`, `title`, `text`) VALUES
	(1, 15, '', 'Vitajte na tejto stránke. Je to stránka CMS systému vytvoreného (nie len) pre ročníkový projekt na SPŠ v Bardejove. \nV menu môžte nájsť vzorové príspevky. Na stránkach sa nachádzajú vzorové komponenty.\nMôžte sa registrovať a vyskúšať ho na vlastnej koži.');
/*!40000 ALTER TABLE `ctrl_text_block` ENABLE KEYS */;


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
	(1, 'site_title', 'Titulok stránky', 'Ročníkový projekt', 1),
	(2, 'meta_description', 'Meta popis stránky', 'stránka ročníkového projektu', 1),
	(3, 'meta_keywords', 'Meta kľúčové slová ', 'rpj, rocnikovy, cms, rainbow dust', 1),
	(4, 'contact_email', 'Kontaktný email', 'nika.blanarova@gmail.com', 1),
	(5, 'contact_phone', 'Kontaktný telefón', NULL, 1),
	(6, 'site_url', 'Adresa stránky', 'http://nicool.6f.sk/', 1),
	(7, 'site_token', 'Token stránky', NULL, 0),
	(8, 'comment_post', 'Komentáre pre články', '1', 1),
	(9, 'comment_ctg', 'Komentáre pre kategórie', '1', 1),
	(10, 'comment_img', 'Komentáre pre obrázky', '1', 0),
	(11, 'comment_all', 'Pridávanie komentárov aj pre neregistrovaných', '1', 1),
	(12, 'comment_length', 'Dĺžka komentárov', '250', 1),
	(13, 'social_facebook', 'Odkaz na facebook', NULL, 1),
	(14, 'social_twitter', 'Odkaz na twitter', '', 1),
	(15, 'social_instagram', 'Odkaz na instagram', '', 1),
	(16, 'social_steam', 'Odkaz na steam', '', 1),
	(17, 'social_devaintart', 'Odkaz na DeviantArt', '', 1),
	(18, 'social_youtube', 'Odkaz na YouTube', '', 1);
/*!40000 ALTER TABLE `global_settings` ENABLE KEYS */;


-- Dumping structure for table rpj.post
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gallery_id` int(11) unsigned DEFAULT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.post: ~4 rows (approximately)
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` (`id`, `gallery_id`, `address`, `title`, `description`, `text`, `title_image`, `create_date`, `last_edit`, `status`, `template`, `comments`, `author`) VALUES
	(1, 13, 'ukazkovy-clanok', 'Ukážkový článok', '', 'Toto je ukážka, ako môže vyzerať článok s galériou. Toto je jedna z troch základných šablón pre článok. \nAj k článkom sa dajú pridávať komponenty. Musíme brať v úvahu, že komponenta, ktorú pridáme bude rovnaká pri všetkých článkoch.', 'default7.jpg', '2016-04-22 01:03:20', '2016-04-22 01:30:21', 1, 1, NULL, 1),
	(3, 14, 'galeria', 'Iba galéria', '', '', 'froggycorn.png', '2016-04-22 01:30:56', '2016-04-22 01:41:16', 1, 7, NULL, 1),
	(4, NULL, 'iba-clanok', 'Iba článok', '', 'Toto je ukážka ako môže vyzerať článok bez galérie. \nJe dosť nudný, iba text, bez obrázkov.\nMôžme ho oživiť pridaním titulného obrázka. Ten robí pekný prallax efekt.', 'default2.jpg', '2016-04-22 01:46:50', '2016-04-22 01:49:21', 1, 6, NULL, 1),
	(5, NULL, 'clanok-bez-kategorie', 'Článok bez kategórie', '', 'Ďalší ukážkový článok. Tento nie je priradený k žiadnej kategórií. Ani to nezistíte. \nAsi by sa tu zišlo viac textu, však? Čo tak použiť starý dobrý Lorem Ipsum?\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis nisl augue, et eleifend dolor pharetra nec. Nunc ut est egestas, faucibus erat ac, eleifend nisi. Aenean feugiat tortor vitae cursus sollicitudin. Donec sed tortor et sapien luctus venenatis a quis augue. Phasellus placerat, risus eu ultricies rhoncus, orci sem eleifend augue, quis lobortis risus massa ut odio. Pellentesque eu eros et risus feugiat ultricies sed nec ligula. Vestibulum ullamcorper elit vitae mauris placerat blandit.\n\nNullam fermentum volutpat velit, vel luctus lectus molestie at. In vel suscipit lacus. In hac habitasse platea dictumst. Vivamus et euismod nisi. Nulla facilisi. Curabitur id imperdiet felis, id accumsan velit. Sed tincidunt aliquam magna non sodales. Donec eleifend vitae sem eget molestie. Maecenas sed luctus felis, ac luctus nisl. Maecenas nec dui quis velit ornare blandit eu non neque. Morbi scelerisque ut lacus non placerat. Vivamus tempor, neque a rutrum congue, dolor justo mattis nunc, et egestas metus tortor in ex. Aenean dignissim, quam ut varius placerat, tellus massa rutrum lacus, nec bibendum ligula massa non est. Sed ut mauris turpis. Maecenas vulputate porttitor ipsum in viverra. Maecenas at magna a orci eleifend pulvinar volutpat vel magna.', '', '2016-04-22 02:07:53', '2016-04-22 02:13:42', 1, 1, NULL, 1);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='page_category';

-- Dumping data for table rpj.post_ctg: ~1 rows (approximately)
/*!40000 ALTER TABLE `post_ctg` DISABLE KEYS */;
INSERT INTO `post_ctg` (`id`, `address`, `title`, `description`, `status`, `item_per_page`, `template`, `comments`) VALUES
	(1, 'ukazkova-kategoria', 'Ukážková kategória', 'Toto je ukážka ako vyzerá kategória článkov', 1, 5, 2, NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='sorting pages to categories';

-- Dumping data for table rpj.post_ctg_sort: ~3 rows (approximately)
/*!40000 ALTER TABLE `post_ctg_sort` DISABLE KEYS */;
INSERT INTO `post_ctg_sort` (`id`, `ctg_id`, `post_id`) VALUES
	(4, 1, 1),
	(6, 1, 3),
	(9, 1, 4);
/*!40000 ALTER TABLE `post_ctg_sort` ENABLE KEYS */;


-- Dumping structure for table rpj.site_templates
CREATE TABLE IF NOT EXISTS `site_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '0',
  `file_name` varchar(120) DEFAULT '0',
  `title` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.site_templates: ~12 rows (approximately)
/*!40000 ALTER TABLE `site_templates` DISABLE KEYS */;
INSERT INTO `site_templates` (`id`, `type`, `file_name`, `title`, `status`) VALUES
	(1, 'post', 'post', 'Článok s galériou', 1),
	(2, 'post_ctg', 'category', 'Kategória', 1),
	(3, 'image', '', NULL, 1),
	(4, 'comment', 'commentsDefault.latte', 'Komentáre', 1),
	(5, 'menu', 'menuDefault', 'Horné menu', 0),
	(6, 'post', 'postNoGallery', 'Článok bez galérie', 1),
	(7, 'post', 'gallery', 'Galéria', 1),
	(8, 'banner', 'slider', 'Slider s textom', 1),
	(9, 'banner', 'carousel', 'Carousel s malými obrázkami', 1),
	(10, 'banner', 'carousel-slider', 'Carousel s veľkými obrázkami', 1),
	(11, 'textBlock', 'textBlockDefault', 'Základná šablóna', 1),
	(12, 'menu', 'simpleMenu', 'Jednoduché menu', 1),
	(13, 'gallery', 'simpleLightbox', 'Jednoduchá galéria s lightboxom', 1),
	(14, 'textBlock', 'textBlockCenter', 'Textový blok v strede', 1);
/*!40000 ALTER TABLE `site_templates` ENABLE KEYS */;


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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table rpj.users: ~2 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `role`, `username`, `password`, `display_name`, `email`, `about`, `profile_image`, `background_image`, `visible_mail`, `visible_content`, `token`) VALUES
	(1, 'admin', 'admin', '$2y$10$Grh/l29JuOhAyFTF73yDB.uCVuSjb6aGp9eRpJRu.zcnNuqhJSp16', 'niC00L', 'nika.blanarova@gmail.com', 'Kratky popis o mne', 'fb.jpg', 'froggycorn.png', 0, 0, ''),
	(2, 'admin', 'blanarovarpj', '$2y$10$tEAfOokNIOhg1cPbvoEZPuY9UBRYeyAbgNHDSias9r9nuX2ME/3ue', 'RPJ-ukazka', 'email@netreba.tu', NULL, NULL, NULL, 0, 0, 'LbGVjA17j2Gccskx89LO');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
