-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generato il: Gen 25, 2014 alle 17:04
-- Versione del server: 5.6.14
-- Versione PHP: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `requisiti`
--
CREATE DATABASE IF NOT EXISTS `requisiti` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `requisiti`;

-- --------------------------------------------------------

--
-- Struttura della tabella `actor`
--

DROP TABLE IF EXISTS `actor`;
CREATE TABLE IF NOT EXISTS `actor` (
  `id_actor` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_actor`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `actor`
--

INSERT INTO `actor` (`id_actor`, `description`, `parent`) VALUES
(3, 'Utente', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `argument`
--

DROP TABLE IF EXISTS `argument`;
CREATE TABLE IF NOT EXISTS `argument` (
  `id_argument` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `type` varchar(128) NOT NULL,
  `direction` enum('in','out') NOT NULL DEFAULT 'in',
  `id_method` int(11) NOT NULL,
  `description` text NOT NULL,
  `order` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_argument`),
  KEY `fk_argument_method1` (`id_method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `association`
--

DROP TABLE IF EXISTS `association`;
CREATE TABLE IF NOT EXISTS `association` (
  `id_association` int(11) NOT NULL AUTO_INCREMENT,
  `aggregation_from` enum('none','aggregate','composite') NOT NULL,
  `class_from` int(11) NOT NULL,
  `aggregation_to` enum('none','aggregate','composite') NOT NULL,
  `class_to` int(11) NOT NULL,
  `id_attribute` int(11) NOT NULL,
  `multiplicity` enum('*','0..1','0..*','1..*','1') DEFAULT '*',
  PRIMARY KEY (`id_association`),
  KEY `fk_association_class1` (`class_from`),
  KEY `fk_association_class2` (`class_to`),
  KEY `fk_association_attribute1` (`id_attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `attribute`
--

DROP TABLE IF EXISTS `attribute`;
CREATE TABLE IF NOT EXISTS `attribute` (
  `id_attribute` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `type` varchar(128) NOT NULL,
  `const` tinyint(1) NOT NULL DEFAULT '0',
  `static` tinyint(1) NOT NULL DEFAULT '0',
  `access` enum('private','public','protected') NOT NULL DEFAULT 'private',
  `id_class` int(11) NOT NULL,
  `description` text NOT NULL,
  `getter` tinyint(1) NOT NULL,
  `setter` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_attribute`),
  KEY `fk_attribute_method1` (`id_class`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `chat`
--

DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `id_class` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `id_package` int(11) NOT NULL,
  `description` text NOT NULL,
  `usage` text NOT NULL,
  `qobject` tinyint(1) NOT NULL DEFAULT '0',
  `include` text NOT NULL,
  `type` enum('class','abstract','interface') NOT NULL DEFAULT 'class',
  `extra_declaration` text,
  `library` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_class`),
  KEY `id_package` (`id_package`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `class_requirement`
--

DROP TABLE IF EXISTS `class_requirement`;
CREATE TABLE IF NOT EXISTS `class_requirement` (
  `id_requirement` int(11) NOT NULL,
  `id_class` int(11) NOT NULL,
  PRIMARY KEY (`id_requirement`,`id_class`),
  KEY `fk_requirement_has_class_class1` (`id_class`),
  KEY `fk_requirement_has_class_requirement1` (`id_requirement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `connect`
--

DROP TABLE IF EXISTS `connect`;
CREATE TABLE IF NOT EXISTS `connect` (
  `signal` int(11) NOT NULL,
  `slot` int(11) NOT NULL,
  PRIMARY KEY (`signal`,`slot`),
  KEY `fk_method_has_method_method2` (`slot`),
  KEY `fk_method_has_method_method1` (`signal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `dependency`
--

DROP TABLE IF EXISTS `dependency`;
CREATE TABLE IF NOT EXISTS `dependency` (
  `id_dependency` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `description` text,
  `id_from` int(11) NOT NULL,
  `id_to` int(11) NOT NULL,
  PRIMARY KEY (`id_dependency`),
  KEY `fk_dependency_class1` (`id_from`),
  KEY `fk_dependency_class2` (`id_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `event_category`
--

DROP TABLE IF EXISTS `event_category`;
CREATE TABLE IF NOT EXISTS `event_category` (
  `id_category` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `event_category`
--

INSERT INTO `event_category` (`id_category`, `name`) VALUES
(1, 'Flusso principale'),
(2, 'Scenari Alternativi'),
(3, 'Estensione'),
(4, 'Inclusione');

-- --------------------------------------------------------

--
-- Struttura della tabella `external_source`
--

DROP TABLE IF EXISTS `external_source`;
CREATE TABLE IF NOT EXISTS `external_source` (
  `id_source` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id_source`),
  KEY `fk_external_source_source1` (`id_source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `inherit`
--

DROP TABLE IF EXISTS `inherit`;
CREATE TABLE IF NOT EXISTS `inherit` (
  `child` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  PRIMARY KEY (`child`,`parent`),
  KEY `fk_class_has_class_class2` (`parent`),
  KEY `fk_class_has_class_class1` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `integration_test`
--

DROP TABLE IF EXISTS `integration_test`;
CREATE TABLE IF NOT EXISTS `integration_test` (
  `id_test` int(11) NOT NULL,
  `id_package` int(11) NOT NULL,
  PRIMARY KEY (`id_test`),
  KEY `fk_test_has_package_package1` (`id_package`),
  KEY `fk_test_has_package_test1` (`id_test`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `method`
--

DROP TABLE IF EXISTS `method`;
CREATE TABLE IF NOT EXISTS `method` (
  `id_method` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `pre` text,
  `post` text,
  `description` text NOT NULL,
  `return` varchar(128) DEFAULT NULL,
  `access` enum('public','protected','private','signal') DEFAULT 'public',
  `virtual` tinyint(1) NOT NULL DEFAULT '0',
  `override` tinyint(1) NOT NULL DEFAULT '0',
  `final` tinyint(1) NOT NULL DEFAULT '0',
  `static` tinyint(1) NOT NULL DEFAULT '0',
  `const` tinyint(1) NOT NULL DEFAULT '0',
  `nothrow` tinyint(1) NOT NULL DEFAULT '0',
  `id_class` int(11) NOT NULL,
  `abstract` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_method`),
  KEY `fk_method_class1` (`id_class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `package`
--

DROP TABLE IF EXISTS `package`;
CREATE TABLE IF NOT EXISTS `package` (
  `id_package` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `virtual` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_package`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `package_requirement`
--

DROP TABLE IF EXISTS `package_requirement`;
CREATE TABLE IF NOT EXISTS `package_requirement` (
  `id_requirement` int(11) NOT NULL,
  `id_package` int(11) NOT NULL,
  PRIMARY KEY (`id_requirement`,`id_package`),
  KEY `id_package` (`id_package`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `requirement`
--

DROP TABLE IF EXISTS `requirement`;
CREATE TABLE IF NOT EXISTS `requirement` (
  `id_requirement` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `apported` tinyint(1) NOT NULL DEFAULT '0',
  `validation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_requirement`),
  KEY `fk_requirement_requirement_category1` (`category`),
  KEY `fk_requirement_requirement1` (`parent`),
  KEY `fk_requirement_1` (`priority`),
  KEY `validation` (`validation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `requirement_category`
--

DROP TABLE IF EXISTS `requirement_category`;
CREATE TABLE IF NOT EXISTS `requirement_category` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `latex_name` text NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `requirement_category`
--

INSERT INTO `requirement_category` (`id_category`, `name`, `latex_name`) VALUES
(1, 'Funzionale', 'Funzionale'),
(2, 'Qualità', 'Qualitativo'),
(3, 'Prestazionali', 'Prestazionale'),
(4, 'Vincolo', 'Vincolo');

-- --------------------------------------------------------

--
-- Struttura della tabella `requirement_priority`
--

DROP TABLE IF EXISTS `requirement_priority`;
CREATE TABLE IF NOT EXISTS `requirement_priority` (
  `id_priority` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id_priority`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `requirement_priority`
--

INSERT INTO `requirement_priority` (`id_priority`, `name`) VALUES
(1, 'Obbligatorio'),
(2, 'Opzionale'),
(3, 'Desiderabile');

-- --------------------------------------------------------

--
-- Struttura della tabella `requirement_validation`
--

DROP TABLE IF EXISTS `requirement_validation`;
CREATE TABLE IF NOT EXISTS `requirement_validation` (
  `id_validation` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id_validation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `secondary_actors`
--

DROP TABLE IF EXISTS `secondary_actors`;
CREATE TABLE IF NOT EXISTS `secondary_actors` (
  `id_event` int(11) NOT NULL,
  `id_actor` int(11) NOT NULL,
  PRIMARY KEY (`id_event`,`id_actor`),
  KEY `fk_use_case_has_actor_actor1` (`id_actor`),
  KEY `fk_use_case_has_actor_use_case1` (`id_event`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `source`
--

DROP TABLE IF EXISTS `source`;
CREATE TABLE IF NOT EXISTS `source` (
  `id_source` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_source`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `source_requirement`
--

DROP TABLE IF EXISTS `source_requirement`;
CREATE TABLE IF NOT EXISTS `source_requirement` (
  `id_source` int(11) NOT NULL,
  `id_requirement` int(11) NOT NULL,
  PRIMARY KEY (`id_source`,`id_requirement`),
  KEY `fk_source_has_requirement_requirement1` (`id_requirement`),
  KEY `fk_source_has_requirement_source1` (`id_source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `system_test`
--

DROP TABLE IF EXISTS `system_test`;
CREATE TABLE IF NOT EXISTS `system_test` (
  `id_test` int(11) NOT NULL,
  `id_requirement` int(11) NOT NULL,
  PRIMARY KEY (`id_test`),
  KEY `fk_test_has_requirement_requirement1` (`id_requirement`),
  KEY `fk_test_has_requirement_test1` (`id_test`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `test`
--

DROP TABLE IF EXISTS `test`;
CREATE TABLE IF NOT EXISTS `test` (
  `id_test` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('unimplemented','failed','success') NOT NULL DEFAULT 'unimplemented',
  `description` text NOT NULL,
  `jenkins_id` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_test`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `unit_test`
--

DROP TABLE IF EXISTS `unit_test`;
CREATE TABLE IF NOT EXISTS `unit_test` (
  `id_test` int(11) NOT NULL,
  `id_method` int(11) NOT NULL,
  PRIMARY KEY (`id_test`),
  KEY `fk_test_has_method_method1` (`id_method`),
  KEY `fk_test_has_method_test1` (`id_test`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `use_case`
--

DROP TABLE IF EXISTS `use_case`;
CREATE TABLE IF NOT EXISTS `use_case` (
  `id_use_case` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `pre` text NOT NULL,
  `post` text NOT NULL,
  PRIMARY KEY (`id_use_case`),
  KEY `fk_parent` (`parent`),
  KEY `fk_use_case_1` (`id_use_case`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `use_case_event`
--

DROP TABLE IF EXISTS `use_case_event`;
CREATE TABLE IF NOT EXISTS `use_case_event` (
  `id_event` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `use_case` int(11) NOT NULL,
  `description` text NOT NULL,
  `refers_to` int(11) DEFAULT NULL,
  `primary_actor` int(11) DEFAULT NULL,
  `order` double NOT NULL,
  PRIMARY KEY (`id_event`),
  KEY `fk_use_case_event_use_case1` (`use_case`),
  KEY `fk_use_case_event_actor1` (`primary_actor`),
  KEY `category` (`category`),
  KEY `refers_to` (`refers_to`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `validation_test`
--

DROP TABLE IF EXISTS `validation_test`;
CREATE TABLE IF NOT EXISTS `validation_test` (
  `id_test` int(11) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `id_requirement` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_test`),
  KEY `fk_validation_test_1` (`id_test`),
  KEY `fk_validation_test_validation_test1` (`parent`),
  KEY `fk_validation_test_requirement1` (`id_requirement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `actor`
--
ALTER TABLE `actor`
  ADD CONSTRAINT `actor_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `actor` (`id_actor`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `argument`
--
ALTER TABLE `argument`
  ADD CONSTRAINT `argument_ibfk_1` FOREIGN KEY (`id_method`) REFERENCES `method` (`id_method`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `association`
--
ALTER TABLE `association`
  ADD CONSTRAINT `association_ibfk_1` FOREIGN KEY (`class_from`) REFERENCES `class` (`id_class`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `association_ibfk_2` FOREIGN KEY (`class_to`) REFERENCES `class` (`id_class`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `association_ibfk_3` FOREIGN KEY (`id_attribute`) REFERENCES `attribute` (`id_attribute`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `attribute`
--
ALTER TABLE `attribute`
  ADD CONSTRAINT `fk_attribute_method1` FOREIGN KEY (`id_class`) REFERENCES `class` (`id_class`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`id_package`) REFERENCES `package` (`id_package`);

--
-- Limiti per la tabella `class_requirement`
--
ALTER TABLE `class_requirement`
  ADD CONSTRAINT `fk_requirement_has_class_class1` FOREIGN KEY (`id_class`) REFERENCES `class` (`id_class`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_requirement_has_class_requirement1` FOREIGN KEY (`id_requirement`) REFERENCES `requirement` (`id_requirement`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `connect`
--
ALTER TABLE `connect`
  ADD CONSTRAINT `fk_method_has_method_method1` FOREIGN KEY (`signal`) REFERENCES `method` (`id_method`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_method_has_method_method2` FOREIGN KEY (`slot`) REFERENCES `method` (`id_method`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `dependency`
--
ALTER TABLE `dependency`
  ADD CONSTRAINT `fk_dependency_class1` FOREIGN KEY (`id_from`) REFERENCES `class` (`id_class`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_dependency_class2` FOREIGN KEY (`id_to`) REFERENCES `class` (`id_class`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `external_source`
--
ALTER TABLE `external_source`
  ADD CONSTRAINT `fk_external_source_source1` FOREIGN KEY (`id_source`) REFERENCES `source` (`id_source`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `inherit`
--
ALTER TABLE `inherit`
  ADD CONSTRAINT `fk_class_has_class_class1` FOREIGN KEY (`child`) REFERENCES `class` (`id_class`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_class_has_class_class2` FOREIGN KEY (`parent`) REFERENCES `class` (`id_class`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `integration_test`
--
ALTER TABLE `integration_test`
  ADD CONSTRAINT `fk_test_has_package_package1` FOREIGN KEY (`id_package`) REFERENCES `package` (`id_package`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_test_has_package_test1` FOREIGN KEY (`id_test`) REFERENCES `test` (`id_test`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `method`
--
ALTER TABLE `method`
  ADD CONSTRAINT `fk_method_class1` FOREIGN KEY (`id_class`) REFERENCES `class` (`id_class`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `package`
--
ALTER TABLE `package`
  ADD CONSTRAINT `package_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `package` (`id_package`);

--
-- Limiti per la tabella `package_requirement`
--
ALTER TABLE `package_requirement`
  ADD CONSTRAINT `package_requirement_ibfk_1` FOREIGN KEY (`id_requirement`) REFERENCES `requirement` (`id_requirement`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `package_requirement_ibfk_2` FOREIGN KEY (`id_package`) REFERENCES `package` (`id_package`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `requirement`
--
ALTER TABLE `requirement`
  ADD CONSTRAINT `fk_requirement_1` FOREIGN KEY (`priority`) REFERENCES `requirement_priority` (`id_priority`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_requirement_requirement1` FOREIGN KEY (`parent`) REFERENCES `requirement` (`id_requirement`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_requirement_requirement_category1` FOREIGN KEY (`category`) REFERENCES `requirement_category` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `requirement_ibfk_1` FOREIGN KEY (`validation`) REFERENCES `requirement_validation` (`id_validation`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Limiti per la tabella `secondary_actors`
--
ALTER TABLE `secondary_actors`
  ADD CONSTRAINT `fk_use_case_has_actor_actor1` FOREIGN KEY (`id_actor`) REFERENCES `actor` (`id_actor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_use_case_has_actor_use_case1` FOREIGN KEY (`id_event`) REFERENCES `use_case_event` (`id_event`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `source_requirement`
--
ALTER TABLE `source_requirement`
  ADD CONSTRAINT `fk_source_has_requirement_requirement1` FOREIGN KEY (`id_requirement`) REFERENCES `requirement` (`id_requirement`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_source_has_requirement_source1` FOREIGN KEY (`id_source`) REFERENCES `source` (`id_source`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `system_test`
--
ALTER TABLE `system_test`
  ADD CONSTRAINT `fk_test_has_requirement_requirement1` FOREIGN KEY (`id_requirement`) REFERENCES `requirement` (`id_requirement`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_test_has_requirement_test1` FOREIGN KEY (`id_test`) REFERENCES `test` (`id_test`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `unit_test`
--
ALTER TABLE `unit_test`
  ADD CONSTRAINT `fk_test_has_method_method1` FOREIGN KEY (`id_method`) REFERENCES `method` (`id_method`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_test_has_method_test1` FOREIGN KEY (`id_test`) REFERENCES `test` (`id_test`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `use_case`
--
ALTER TABLE `use_case`
  ADD CONSTRAINT `use_case_ibfk_1` FOREIGN KEY (`id_use_case`) REFERENCES `source` (`id_source`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `use_case_ibfk_2` FOREIGN KEY (`parent`) REFERENCES `use_case` (`id_use_case`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `use_case_event`
--
ALTER TABLE `use_case_event`
  ADD CONSTRAINT `fk_use_case_event_actor1` FOREIGN KEY (`primary_actor`) REFERENCES `actor` (`id_actor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_use_case_event_use_case1` FOREIGN KEY (`use_case`) REFERENCES `use_case` (`id_use_case`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `use_case_event_ibfk_1` FOREIGN KEY (`category`) REFERENCES `event_category` (`id_category`) ON UPDATE CASCADE,
  ADD CONSTRAINT `use_case_event_ibfk_3` FOREIGN KEY (`refers_to`) REFERENCES `use_case` (`id_use_case`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `validation_test`
--
ALTER TABLE `validation_test`
  ADD CONSTRAINT `fk_validation_test_1` FOREIGN KEY (`id_test`) REFERENCES `test` (`id_test`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_validation_test_requirement1` FOREIGN KEY (`id_requirement`) REFERENCES `requirement` (`id_requirement`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_validation_test_validation_test1` FOREIGN KEY (`parent`) REFERENCES `validation_test` (`id_test`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
