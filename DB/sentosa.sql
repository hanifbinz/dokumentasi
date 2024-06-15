/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.13-MariaDB : Database - sentosa1
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sentosa1` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `sentosa1`;

/*Table structure for table `angkutan` */

DROP TABLE IF EXISTS `angkutan`;

CREATE TABLE `angkutan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_angkutan` int(11) NOT NULL,
  `nama_angkutan` varchar(25) CHARACTER SET latin1 NOT NULL,
  `jenis_angkutan` enum('bongkar','muat') CHARACTER SET latin1 NOT NULL,
  `jenis_barang` enum('lokal','impor') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_angkutan` (`id_angkutan`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `angkutan` */

insert  into `angkutan`(`id`,`id_angkutan`,`nama_angkutan`,`jenis_angkutan`,`jenis_barang`) values 
(2,100002,'KLINE','bongkar','impor'),
(3,100003,'TRISINDO','bongkar','impor'),
(4,110001,'APLUS','bongkar','lokal'),
(6,200001,'KS','muat','lokal'),
(7,200002,'KME','muat','lokal'),
(8,200003,'SDM','muat','lokal');

/*Table structure for table `aplikasi` */

DROP TABLE IF EXISTS `aplikasi`;

CREATE TABLE `aplikasi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_owner` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `alamat` text CHARACTER SET utf8 DEFAULT NULL,
  `tlp` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `brand` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `title` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `nama_aplikasi` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `logo` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `copy_right` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `versi` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `nama_pengirim` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `aplikasi` */

insert  into `aplikasi`(`id`,`nama_owner`,`alamat`,`tlp`,`brand`,`title`,`nama_aplikasi`,`logo`,`copy_right`,`versi`,`tahun`,`email`,`nama_pengirim`,`password`) values 
(1,'hanifmajuterus','dunia-akhirat','085624941234','web','Bongkat Muat','Aplikasi Dokumentasi Bongkar Muat','bm.png','Copy Right ©','1.0.0.0',2023,'hanifbinz@gmail.com','hanifbinz','123456');

/*Table structure for table `barang` */

DROP TABLE IF EXISTS `barang`;

CREATE TABLE `barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(25) CHARACTER SET latin1 NOT NULL,
  `nama_supplier` varchar(25) CHARACTER SET latin1 NOT NULL,
  `jenis_barang` enum('lokal','impor') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `barang` */

insert  into `barang`(`id`,`id_barang`,`nama_barang`,`nama_supplier`,`jenis_barang`) values 
(2,101003,'MARLEX 50100','CHEVRON PHILLIPS','impor'),
(3,101008,'MARLEX 6007','CHEVRON PHILLIPS','impor'),
(5,101035,'LDF 260 GG','LOTTE CHEMICALS MALAYSIA','impor'),
(6,102033,'LDC 801 YY','LOTTE CHEMICALS MALAYSIA','impor'),
(9,204099,'PP PM 803','LOTTE CHEMICAL MALAYSIA','impor'),
(10,101028,'TITANVENE HD 5218 EA','LOTTE CHEMICAL INDONESIA','lokal');

/*Table structure for table `bongkar` */

DROP TABLE IF EXISTS `bongkar`;

CREATE TABLE `bongkar` (
  `id_bongkar` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `id_angkutan` int(11) DEFAULT NULL,
  `no_kontainer` varchar(12) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `kode_bongkar` varchar(13) CHARACTER SET latin1 NOT NULL,
  `foto_kontainer` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `foto_segel` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `foto_sj` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `foto_barang1` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `foto_barang2` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id_bongkar`),
  KEY `id_user` (`id_user`),
  KEY `id_angkutan` (`id_angkutan`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `bongkar_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id_user`),
  CONSTRAINT `bongkar_ibfk_2` FOREIGN KEY (`id_angkutan`) REFERENCES `angkutan` (`id_angkutan`),
  CONSTRAINT `bongkar_ibfk_3` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Data for the table `bongkar` */

insert  into `bongkar`(`id_bongkar`,`id_user`,`tanggal`,`id_angkutan`,`no_kontainer`,`id_barang`,`jumlah_barang`,`kode_bongkar`,`foto_kontainer`,`foto_segel`,`foto_sj`,`foto_barang1`,`foto_barang2`) values 
(19,38,'2024-01-14',100003,'TCLU 1111111',101035,1000,'','amik.png','amik.png','amik.png','amik.png','amik.png');

/*Table structure for table `customer` */

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_customer` int(11) NOT NULL,
  `nama_customer` varchar(25) CHARACTER SET latin1 NOT NULL,
  `alamat_customer` varchar(50) CHARACTER SET latin1 NOT NULL,
  `email_customer` varchar(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_customer` (`id_customer`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `customer` */

insert  into `customer`(`id`,`id_customer`,`nama_customer`,`alamat_customer`,`email_customer`) values 
(7,300001,'TIRTA HONCHUAN','cikarang - bekaSI','tirtajbk@gmail.com');

/*Table structure for table `muat` */

DROP TABLE IF EXISTS `muat`;

CREATE TABLE `muat` (
  `id_muat` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_angkutan` int(11) NOT NULL,
  `no_mobil` varchar(12) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `no_do` varchar(13) NOT NULL,
  `foto_mobil` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `foto_bak` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `foto_do` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `foto_barang1` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `foto_barang2` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `foto_barang3` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id_muat`),
  UNIQUE KEY `no_do` (`no_do`),
  KEY `id_user` (`id_user`),
  KEY `id_angkutan` (`id_angkutan`),
  KEY `id_barang` (`id_barang`),
  KEY `id_customer` (`id_customer`),
  KEY `id_user_2` (`id_user`,`id_angkutan`,`id_barang`,`id_customer`),
  CONSTRAINT `muat_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id_user`),
  CONSTRAINT `muat_ibfk_2` FOREIGN KEY (`id_angkutan`) REFERENCES `angkutan` (`id_angkutan`),
  CONSTRAINT `muat_ibfk_3` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  CONSTRAINT `muat_ibfk_4` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `muat` */

insert  into `muat`(`id_muat`,`id_user`,`tanggal`,`id_angkutan`,`no_mobil`,`id_barang`,`jumlah_barang`,`id_customer`,`no_do`,`foto_mobil`,`foto_bak`,`foto_do`,`foto_barang1`,`foto_barang2`,`foto_barang3`) values 
(11,38,'2024-01-14',200001,'B 1234 A',101028,600,7,'BBD1111111','amik.png','amik.png','amik.png','amik.png','amik.png','amik.png'),
(12,38,'2024-01-16',200003,'T 1234 T',101008,600,7,'BBD12345678','17052157967476308515264724793573.jpg','17052158073427116398258279805480.jpg','17052158380906522504373032552857.jpg','17052158456396112786098175539567.jpg','17052158521022386413320984625977.jpg','17052158612205252891150618658815.jpg');

/*Table structure for table `tbl_akses_menu` */

DROP TABLE IF EXISTS `tbl_akses_menu`;

CREATE TABLE `tbl_akses_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_level` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `view` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `add` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `edit` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `delete` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `print` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `upload` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `download` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `id_menu` (`id_menu`),
  KEY `id_level` (`id_level`),
  CONSTRAINT `tbl_akses_menu_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `tbl_userlevel` (`id_level`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_akses_menu_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `tbl_menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_akses_menu` */

insert  into `tbl_akses_menu`(`id`,`id_level`,`id_menu`,`view`,`add`,`edit`,`delete`,`print`,`upload`,`download`) values 
(1,1,1,'Y','Y','Y','Y','Y','Y','Y'),
(69,1,57,'Y','Y','Y','Y','Y','Y','Y'),
(94,1,61,'Y','Y','Y','Y','Y','Y','Y'),
(207,1,93,'Y','Y','Y','Y','Y','Y','Y'),
(410,6,1,'Y','Y','Y','Y','Y','Y','Y'),
(411,6,57,'N','N','N','N','N','N','N'),
(412,6,61,'Y','N','N','N','N','N','N'),
(413,6,93,'Y','Y','Y','Y','Y','Y','Y'),
(420,1,114,'Y','Y','Y','Y','Y','Y','Y'),
(421,6,114,'Y','Y','Y','Y','Y','Y','Y'),
(422,1,115,'Y','Y','Y','Y','Y','Y','Y'),
(423,6,115,'Y','Y','Y','Y','Y','Y','Y'),
(424,1,116,'Y','Y','Y','Y','Y','Y','Y'),
(425,6,116,'Y','Y','Y','Y','Y','Y','Y'),
(426,7,1,'Y','N','N','N','N','N','N'),
(427,7,57,'N','N','N','N','N','N','N'),
(428,7,61,'Y','N','N','N','N','N','N'),
(429,7,93,'N','N','N','N','N','N','N'),
(430,7,114,'Y','Y','N','Y','N','Y','Y'),
(431,7,115,'Y','Y','N','Y','N','Y','Y'),
(432,7,116,'N','N','N','N','N','N','N');

/*Table structure for table `tbl_akses_submenu` */

DROP TABLE IF EXISTS `tbl_akses_submenu`;

CREATE TABLE `tbl_akses_submenu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_level` int(11) NOT NULL,
  `id_submenu` int(11) NOT NULL,
  `view` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `add` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `edit` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `delete` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `print` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `upload` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `download` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `id_level` (`id_level`),
  KEY `id_submenu` (`id_submenu`),
  CONSTRAINT `tbl_akses_submenu_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `tbl_userlevel` (`id_level`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_akses_submenu_ibfk_2` FOREIGN KEY (`id_submenu`) REFERENCES `tbl_submenu` (`id_submenu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=347 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_akses_submenu` */

insert  into `tbl_akses_submenu`(`id`,`id_level`,`id_submenu`,`view`,`add`,`edit`,`delete`,`print`,`upload`,`download`) values 
(2,1,2,'Y','Y','Y','Y','Y','Y','Y'),
(4,1,1,'Y','Y','Y','Y','Y','Y','Y'),
(6,1,7,'Y','Y','Y','Y','Y','Y','Y'),
(9,1,10,'Y','Y','Y','Y','Y','Y','Y'),
(209,1,44,'Y','Y','Y','Y','Y','Y','Y'),
(284,6,1,'N','N','N','N','N','N','N'),
(285,6,2,'N','N','N','N','N','N','N'),
(286,6,7,'N','N','N','N','N','N','N'),
(287,6,10,'N','N','N','N','N','N','N'),
(288,6,44,'N','N','N','N','N','N','N'),
(289,1,52,'Y','Y','Y','Y','Y','Y','Y'),
(290,6,52,'Y','Y','Y','Y','Y','Y','Y'),
(327,1,71,'Y','Y','Y','Y','Y','Y','Y'),
(328,6,71,'Y','Y','Y','Y','Y','Y','Y'),
(329,1,72,'Y','Y','Y','Y','Y','Y','Y'),
(330,6,72,'Y','Y','Y','Y','Y','Y','Y'),
(331,7,1,'N','N','N','N','N','N','N'),
(332,7,2,'N','N','N','N','N','N','N'),
(333,7,7,'N','N','N','N','N','N','N'),
(334,7,10,'N','N','N','N','N','N','N'),
(335,7,44,'N','N','N','N','N','N','N'),
(336,7,52,'N','N','N','N','N','N','N'),
(337,7,71,'N','N','N','N','N','N','N'),
(338,7,72,'N','N','N','N','N','N','N');

/*Table structure for table `tbl_menu` */

DROP TABLE IF EXISTS `tbl_menu`;

CREATE TABLE `tbl_menu` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `link` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `icon` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `urutan` bigint(20) DEFAULT NULL,
  `is_active` enum('Y','N') CHARACTER SET utf8 DEFAULT 'Y',
  `parent` enum('Y') CHARACTER SET utf8 DEFAULT 'Y',
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_menu` */

insert  into `tbl_menu`(`id_menu`,`nama_menu`,`link`,`icon`,`urutan`,`is_active`,`parent`) values 
(1,'Dashboard','dashboard','fas fa-tachometer-alt',1,'Y','Y'),
(57,'Konfigurasi','#','fas fa-users-cog',15,'Y','Y'),
(61,'Ganti Password','ganti_password','fas fa-key',9,'Y','Y'),
(93,'Data Master','#','fas fa-database',5,'Y','Y'),
(114,'Dokumentasi Bongkar','bongkar','fas fa-camera',2,'Y','Y'),
(115,'Dokumentasi Muat','muat','fas fa-camera',3,'Y','Y'),
(116,'Laporan','laporan','fas fa-book',4,'Y','Y');

/*Table structure for table `tbl_submenu` */

DROP TABLE IF EXISTS `tbl_submenu`;

CREATE TABLE `tbl_submenu` (
  `id_submenu` int(11) NOT NULL AUTO_INCREMENT,
  `nama_submenu` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `link` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `icon` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `is_active` enum('Y','N') CHARACTER SET utf8 DEFAULT 'Y',
  `urutan` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_submenu`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_submenu` */

insert  into `tbl_submenu`(`id_submenu`,`nama_submenu`,`link`,`icon`,`id_menu`,`is_active`,`urutan`) values 
(1,'Menu','menu','far fa-circle',57,'Y',8),
(2,'Sub Menu','submenu','far fa-circle',57,'Y',7),
(7,'Aplikasi','aplikasi','far fa-circle',57,'N',6),
(10,'User Level','userlevel','far fa-circle',57,'Y',5),
(44,'Data Pengguna','user','far fa-circle',57,'Y',4),
(52,'Barang','barang','far fa-circle',93,'Y',1),
(71,'Angkutan','angkutan','far fa-circle',93,'Y',2),
(72,'Customer','customer','far fa-circle',93,'Y',3);

/*Table structure for table `tbl_user` */

DROP TABLE IF EXISTS `tbl_user`;

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `full_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `id_level` int(11) DEFAULT NULL,
  `image` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `nohp` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `is_active` enum('Y','N') CHARACTER SET utf8 DEFAULT 'Y',
  PRIMARY KEY (`id_user`),
  KEY `id_level` (`id_level`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_user` */

insert  into `tbl_user`(`id_user`,`username`,`full_name`,`password`,`id_level`,`image`,`nohp`,`email`,`is_active`) values 
(34,'han','Muhammad Hanif','$2y$05$.CLedg1GLZhal22YK4so8OgOYAhH47pPD.UbHZf1pAwkQTGGlLCUG',1,NULL,NULL,NULL,'Y'),
(35,'admin','Admin','$2Y$05$YD16D89MTHtuU0pVk3j1.e.zEra6wTLSIBa.xDuDaDPVIS7fHHshK',6,NULL,NULL,NULL,'Y'),
(38,'checker','Checker','$2y$05$nKLeJ6BLVzwO6y8VPlV0AuiK/OMqjaox2E2CExPaPv2QTPGEL.yZ2',7,NULL,NULL,NULL,'Y');

/*Table structure for table `tbl_userlevel` */

DROP TABLE IF EXISTS `tbl_userlevel`;

CREATE TABLE `tbl_userlevel` (
  `id_level` int(11) NOT NULL AUTO_INCREMENT,
  `nama_level` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_userlevel` */

insert  into `tbl_userlevel`(`id_level`,`nama_level`) values 
(1,'super admin'),
(6,'admin'),
(7,'checker');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
