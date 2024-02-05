-- Adminer 4.8.1 MySQL 11.2.2-MariaDB-1:11.2.2+maria~ubu2204 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `game`;
DROP TABLE IF EXISTS `played`;

CREATE TABLE `game`
(
    `id`   varchar(64)  NOT NULL,
    `name` varchar(255) NOT NULL,
    `city` varchar(255) NOT NULL,
    `sequence` JSON NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `played`
(
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `id_game` varchar(255) NOT NULL,
    `id_user`   varchar(255) NOT NULL,
    `score`     int(11) NOT NULL,
    `state`      int(11) NOT NULL,
    `date`      datetime NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_game_id` FOREIGN KEY (`id_game`) REFERENCES `game` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;