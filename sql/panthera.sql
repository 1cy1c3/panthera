# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.42)
# Datenbank: panthera
# Erstellungsdauer: 2015-09-06 7:33:19 nachm. +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle cms_activated_plugin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_activated_plugin`;

CREATE TABLE `cms_activated_plugin` (
  `Path` varchar(64) NOT NULL,
  PRIMARY KEY (`Path`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Export von Tabelle cms_css
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_css`;

CREATE TABLE `cms_css` (
  `Name` varchar(32) NOT NULL,
  `Path` varchar(64) NOT NULL,
  PRIMARY KEY (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `cms_css` WRITE;
/*!40000 ALTER TABLE `cms_css` DISABLE KEYS */;

INSERT INTO `cms_css` (`Name`, `Path`)
VALUES
	('{Admin}/dashboard','themes/default/css/dashboard');

/*!40000 ALTER TABLE `cms_css` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_dashboard
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_dashboard`;

CREATE TABLE `cms_dashboard` (
  `DashboardId` int(4) NOT NULL,
  `Col` int(1) NOT NULL,
  `Row` int(2) NOT NULL,
  `Path` varchar(255) NOT NULL,
  PRIMARY KEY (`DashboardId`,`Col`,`Row`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `cms_dashboard` WRITE;
/*!40000 ALTER TABLE `cms_dashboard` DISABLE KEYS */;

INSERT INTO `cms_dashboard` (`DashboardId`, `Col`, `Row`, `Path`)
VALUES
	(1,1,1,'prinzpi/Prinzpi.php'),
	(1,2,1,'casper/Casper.php');

/*!40000 ALTER TABLE `cms_dashboard` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_event
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_event`;

CREATE TABLE `cms_event` (
  `Event` varchar(64) NOT NULL,
  `File` varchar(64) NOT NULL,
  PRIMARY KEY (`Event`,`File`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `cms_event` WRITE;
/*!40000 ALTER TABLE `cms_event` DISABLE KEYS */;

INSERT INTO `cms_event` (`Event`, `File`)
VALUES
	('onContentAfterDelete','src/services/Page.php'),
	('onContentAfterInclude','src/services/Page.php'),
	('onContentAfterWrite','src/services/Page.php'),
	('onContentBeforeInclude','src/services/Page.php'),
	('onEntryAfterDelete','src/services/Menu.php'),
	('onEntryAfterInsert','src/services/Menu.php'),
	('onEntryAfterUpdate','src/services/Menu.php'),
	('onFileAfterUpload','src/services/Media.php'),
	('onFolderAfterInsert','src/services/Media.php'),
	('onHeaderAfterInclude','src/services/Page.php'),
	('onImageAfterRegister','src/services/Media.php'),
	('onMenuAfterDelete','src/services/Menu.php'),
	('onMenuAfterInsert','src/services/Menu.php'),
	('onPageAfterDelete','src/services/Page.php'),
	('onPageAfterInsert','src/services/Page.php'),
	('onPageAfterUpdate','src/services/Page.php');

/*!40000 ALTER TABLE `cms_event` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_global_meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_global_meta`;

CREATE TABLE `cms_global_meta` (
  `MetaName` varchar(128) NOT NULL,
  `Content` varchar(128) NOT NULL,
  PRIMARY KEY (`MetaName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cms_global_meta` WRITE;
/*!40000 ALTER TABLE `cms_global_meta` DISABLE KEYS */;

INSERT INTO `cms_global_meta` (`MetaName`, `Content`)
VALUES
	('author','Max Mustermann'),
	('robots','all');

/*!40000 ALTER TABLE `cms_global_meta` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_image
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_image`;

CREATE TABLE `cms_image` (
  `Value` varchar(255) NOT NULL DEFAULT '',
  `Title` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`Value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cms_image` WRITE;
/*!40000 ALTER TABLE `cms_image` DISABLE KEYS */;

INSERT INTO `cms_image` (`Value`, `Title`)
VALUES
	('/web/assets/img/media/xyz/test.png','Yes');

/*!40000 ALTER TABLE `cms_image` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_layout
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_layout`;

CREATE TABLE `cms_layout` (
  `LayoutId` int(4) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) NOT NULL,
  PRIMARY KEY (`LayoutId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cms_layout` WRITE;
/*!40000 ALTER TABLE `cms_layout` DISABLE KEYS */;

INSERT INTO `cms_layout` (`LayoutId`, `Name`)
VALUES
	(1,'default');

/*!40000 ALTER TABLE `cms_layout` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_local_meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_local_meta`;

CREATE TABLE `cms_local_meta` (
  `MetaName` varchar(128) NOT NULL,
  `Content` varchar(128) NOT NULL,
  `PageId` int(4) NOT NULL,
  PRIMARY KEY (`MetaName`,`PageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cms_local_meta` WRITE;
/*!40000 ALTER TABLE `cms_local_meta` DISABLE KEYS */;

INSERT INTO `cms_local_meta` (`MetaName`, `Content`, `PageId`)
VALUES
	('description','Dies ist eine Testseite',2),
	('keywords','Test, Seite',2);

/*!40000 ALTER TABLE `cms_local_meta` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_menu`;

CREATE TABLE `cms_menu` (
  `EntryId` int(4) unsigned NOT NULL,
  `MenuId` int(4) unsigned NOT NULL,
  `Title` varchar(128) NOT NULL,
  `Link` varchar(255) NOT NULL,
  PRIMARY KEY (`EntryId`,`MenuId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cms_menu` WRITE;
/*!40000 ALTER TABLE `cms_menu` DISABLE KEYS */;

INSERT INTO `cms_menu` (`EntryId`, `MenuId`, `Title`, `Link`)
VALUES
	(1,1,'Testseite','testseite'),
	(1,2,'Dashboard','dashboard'),
	(1,12,'hallo','hallo'),
	(1,13,'hallo','hallo'),
	(1,14,'test','dog'),
	(2,1,'Testseite 2','testseite2'),
	(2,2,'Settings','settings'),
	(3,2,'Menus','menus'),
	(4,2,'Pages','pages'),
	(5,2,'Media','media'),
	(6,2,'Plugins','plugins'),
	(7,2,'Database','database'),
	(8,2,'Users','users'),
	(9,2,'Logout','logout');

/*!40000 ALTER TABLE `cms_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_menu_name
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_menu_name`;

CREATE TABLE `cms_menu_name` (
  `MenuNameId` int(4) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) NOT NULL,
  PRIMARY KEY (`MenuNameId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cms_menu_name` WRITE;
/*!40000 ALTER TABLE `cms_menu_name` DISABLE KEYS */;

INSERT INTO `cms_menu_name` (`MenuNameId`, `Name`)
VALUES
	(2,'{Admin}'),
	(14,'test');

/*!40000 ALTER TABLE `cms_menu_name` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_page
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_page`;

CREATE TABLE `cms_page` (
  `PageId` int(4) NOT NULL AUTO_INCREMENT,
  `Alias` varchar(255) NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Owner` int(4) NOT NULL DEFAULT '-1',
  `MenuId` int(4) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`PageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cms_page` WRITE;
/*!40000 ALTER TABLE `cms_page` DISABLE KEYS */;

INSERT INTO `cms_page` (`PageId`, `Alias`, `Title`, `Owner`, `MenuId`)
VALUES
	(13,'dog','Dog and cat',-1,14);

/*!40000 ALTER TABLE `cms_page` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_setting
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_setting`;

CREATE TABLE `cms_setting` (
  `Property` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  `Activated` tinyint(1) NOT NULL DEFAULT '1',
  `Description` varchar(64) NOT NULL,
  PRIMARY KEY (`Property`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cms_setting` WRITE;
/*!40000 ALTER TABLE `cms_setting` DISABLE KEYS */;

INSERT INTO `cms_setting` (`Property`, `Value`, `Activated`, `Description`)
VALUES
	('globalMenuId','14',1,'Global menu'),
	('layoutbgcolor','93724a',1,'Background-color'),
	('layoutforecolor','3f3426',1,'Text-color'),
	('layouthighlight1','bc9e7c',1,'Highlight-color 1'),
	('layouthighlight2','ccab7a',1,'Highlight-color 2'),
	('layoutId','1',1,'Current skin'),
	('layoutsPath','../../res/views/layouts/frontend',1,'Path to layouts'),
	('title','Panthera',1,'Title of the cms');

/*!40000 ALTER TABLE `cms_setting` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_user`;

CREATE TABLE `cms_user` (
  `UserId` int(4) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) NOT NULL,
  `Password` varchar(40) NOT NULL,
  PRIMARY KEY (`UserId`,`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cms_user` WRITE;
/*!40000 ALTER TABLE `cms_user` DISABLE KEYS */;

INSERT INTO `cms_user` (`UserId`, `Name`, `Password`)
VALUES
	(1,'panthera','d090414f41effdfa135ddc39a5ed7ae6cc2e6bb0');

/*!40000 ALTER TABLE `cms_user` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle cms_widget
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_widget`;

CREATE TABLE `cms_widget` (
  `Path` varchar(255) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `Class` varchar(32) NOT NULL,
  PRIMARY KEY (`Path`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `cms_widget` WRITE;
/*!40000 ALTER TABLE `cms_widget` DISABLE KEYS */;

INSERT INTO `cms_widget` (`Path`, `Name`, `Class`)
VALUES
	('casper/Casper.php','Casper','Casper'),
	('prinzpi/Prinzpi.php','Prinzpi','Prinzpi');

/*!40000 ALTER TABLE `cms_widget` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
