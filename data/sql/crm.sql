CREATE DATABASE IF NOT EXISTS `crm`;

USE `crm`;


CREATE TABLE IF NOT EXISTS `clients` (
 `id`       INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 `username` VARCHAR(255) NOT NULL,
 `pasport`  VARCHAR(255) NOT NULL,
 `birthday` DATE NOT NULL,
 `nomber_d` VARCHAR(255) NOT NULL,
 `data_d`   DATE NOT NULL,
 `trafic`   ENUM('Base', 'Family') NOT NULL,
 `coments`  VARCHAR(1023),
 `status`   VARCHAR(20) NOT NULL,
 `date_ad`  DATETIME NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `username` (`username`),
 UNIQUE KEY `status` (`status`),
 KEY `nomber_d` (`nomber_d`)
) ENGINE=InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;
