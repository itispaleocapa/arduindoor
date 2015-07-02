/**
 * Arduindoor - http://arduindoor.altervista.org/
 * Copyright © 2015 Antonio Chioda <antonio.chioda@gmail.com>
 * Copyright © 2015 Bruno Palazzi <brunopalazzi0@gmail.com>
 * Copyright © 2015 Giacomo Perico <giacomo.perico@hotmail.it>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 13, 2015 alle 13:43
-- Versione del server: 5.1.71-community-log
-- PHP Version: 5.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_arduindoor`
--
CREATE DATABASE IF NOT EXISTS `my_arduindoor` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `my_arduindoor`;

-- --------------------------------------------------------

--
-- Struttura della tabella `arduindoor_stato`
--

DROP TABLE IF EXISTS `arduindoor_stato`;
CREATE TABLE IF NOT EXISTS `arduindoor_stato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `temperatura` int(11) NOT NULL,
  `umidita` int(11) NOT NULL,
  `irrigazione` int(11) NOT NULL,
  `stato_luce` tinyint(1) NOT NULL,
  `indirizzo` bigint(20) unsigned NOT NULL,
  `id_utente` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `indirizzo_arduindoor` (`indirizzo`),
  KEY `id_utente` (`id_utente`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=190 ;

--
-- Dump dei dati per la tabella `arduindoor_stato`
--

INSERT INTO `arduindoor_stato` (`id`, `temperatura`, `umidita`, `irrigazione`, `stato_luce`, `indirizzo`, `id_utente`) VALUES
(187, 19, 58, 1023, 1, 222173190239254237, 1),
(3, 12, 54, 33, 0, 22, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `arduindoor_update`
--

DROP TABLE IF EXISTS `arduindoor_update`;
CREATE TABLE IF NOT EXISTS `arduindoor_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indirizzo` varchar(29) NOT NULL,
  `ora_accensione` int(2) unsigned zerofill NOT NULL,
  `minuto_accensione` int(2) unsigned zerofill NOT NULL,
  `ora_spegnimento` int(2) unsigned zerofill NOT NULL,
  `minuto_spegnimento` int(2) unsigned zerofill NOT NULL,
  `umidita_aria` int(2) unsigned zerofill NOT NULL,
  `temperatura_aria` int(2) unsigned zerofill NOT NULL,
  `umidita_terra` int(4) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dump dei dati per la tabella `arduindoor_update`
--

INSERT INTO `arduindoor_update` (`id`, `indirizzo`, `ora_accensione`, `minuto_accensione`, `ora_spegnimento`, `minuto_spegnimento`, `umidita_aria`, `temperatura_aria`, `umidita_terra`) VALUES
(6, '222173190239254237', 02, 01, 03, 03, 54, 19, 1023);

-- --------------------------------------------------------

--
-- Struttura della tabella `fioritura`
--

DROP TABLE IF EXISTS `fioritura`;
CREATE TABLE IF NOT EXISTS `fioritura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `temperatura_min` int(11) NOT NULL,
  `temperatura_max` int(11) NOT NULL,
  `umidita_min` int(11) NOT NULL,
  `umidita_max` int(11) NOT NULL,
  `ore_luce` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dump dei dati per la tabella `fioritura`
--

INSERT INTO `fioritura` (`id`, `temperatura_min`, `temperatura_max`, `umidita_min`, `umidita_max`, `ore_luce`) VALUES
(3, 14, 18, 25, 65, 24),
(4, 12, 24, 36, 48, 4),
(5, 14, 16, 11, 17, 15),
(6, 25, 40, 40, 60, 15);

-- --------------------------------------------------------

--
-- Struttura della tabella `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `members`
--

INSERT INTO `members` (`id`, `username`, `password`) VALUES
(1, 'test', '$1$fn4.6d0.$OJUGX5xB0aw7FfFvrJIaq.');

-- --------------------------------------------------------

--
-- Struttura della tabella `pianta`
--

DROP TABLE IF EXISTS `pianta`;
CREATE TABLE IF NOT EXISTS `pianta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `tipo` int(11) NOT NULL,
  `vegetativa` int(11) NOT NULL,
  `fioritura` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`),
  KEY `tipo` (`tipo`,`vegetativa`,`fioritura`),
  KEY `tipo_2` (`tipo`),
  KEY `vegetativa` (`vegetativa`),
  KEY `fioritura` (`fioritura`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dump dei dati per la tabella `pianta`
--

INSERT INTO `pianta` (`id`, `nome`, `tipo`, `vegetativa`, `fioritura`) VALUES
(3, 'Pino', 3, 3, 3),
(4, 'salvia', 3, 4, 4),
(5, 'magnolia', 4, 3, 5),
(6, 'rosmarino', 5, 5, 5),
(7, 'olivo', 6, 6, 6);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo`
--

DROP TABLE IF EXISTS `tipo`;
CREATE TABLE IF NOT EXISTS `tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_tipo` varchar(20) NOT NULL,
  `zona` varchar(30) NOT NULL,
  `note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dump dei dati per la tabella `tipo`
--

INSERT INTO `tipo` (`id`, `nome_tipo`, `zona`, `note`) VALUES
(3, 'sempreverde', 'nord', 'no freddo'),
(4, 'fiore', 'america', ''),
(5, 'profumata', 'mediterranea', 'nessuna'),
(6, 'aromatica', 'mediterranea', 'domenica delle palme');

-- --------------------------------------------------------

--
-- Struttura della tabella `vegetativa`
--

DROP TABLE IF EXISTS `vegetativa`;
CREATE TABLE IF NOT EXISTS `vegetativa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `temperatura_min` int(11) NOT NULL,
  `temperatura_max` int(11) NOT NULL,
  `umidita_min` int(11) NOT NULL,
  `umidita_max` int(11) NOT NULL,
  `ore_luce` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dump dei dati per la tabella `vegetativa`
--

INSERT INTO `vegetativa` (`id`, `temperatura_min`, `temperatura_max`, `umidita_min`, `umidita_max`, `ore_luce`) VALUES
(3, 10, 15, 20, 60, 12),
(4, 44, 55, 33, 66, 2),
(5, 15, 16, 17, 18, 14),
(6, 18, 30, 30, 50, 12);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
