CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_requirements` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_requirements`;
SET character_set_client = {$CHARSET} ;
CREATE TABLE `requirements_requirement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `status` varchar(64) CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} NOT NULL,
  `priority` int(10) unsigned NOT NULL,
  `title` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `description` longtext CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `mailKey` binary(20) NOT NULL,
  `ownerOrdering` varchar(64) CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} DEFAULT NULL,
  `originalEmailSource` varchar(255) CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} DEFAULT NULL,
  `subpriority` double NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  `properties` longtext CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} NOT NULL,
  `points` double DEFAULT NULL,
  `bridgedObjectPHID` varbinary(64) DEFAULT NULL,
  `subtype` varchar(64) CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} NOT NULL,
  `closedEpoch` int(10) unsigned DEFAULT NULL,
  `closerPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  UNIQUE KEY `key_bridgedobject` (`bridgedObjectPHID`),
  KEY `priority` (`priority`,`status`),
  KEY `status` (`status`),
  KEY `ownerPHID` (`ownerPHID`,`status`),
  KEY `authorPHID` (`authorPHID`,`status`),
  KEY `ownerOrdering` (`ownerOrdering`),
  KEY `priority_2` (`priority`,`subpriority`),
  KEY `key_dateCreated` (`dateCreated`),
  KEY `key_dateModified` (`dateModified`),
  KEY `key_title` (`title`(64)),
  KEY `key_subtype` (`subtype`),
  KEY `key_closed` (`closedEpoch`),
  KEY `key_closer` (`closerPHID`,`closedEpoch`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

/* Allow versionning of requirements */
USE `{$NAMESPACE}_requirements`;
SET NAMES utf8 ;
SET character_set_client = {$CHARSET} ;
CREATE TABLE `requirements_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} NOT NULL,
  `oldValue` longtext CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} NOT NULL,
  `newValue` longtext CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} NOT NULL,
  `contentSource` longtext CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} NOT NULL,
  `metadata` longtext CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};
