-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_translation`
-- 

CREATE TABLE `tl_translation` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `langgroup` varchar(255) NOT NULL default '',
  `langvar` varchar(255) NOT NULL default '',
  `language` char(2) NOT NULL default '',
  `backend` char(1) NOT NULL default '',
  `frontend` char(1) NOT NULL default '',
  `content` blob NULL,
  PRIMARY KEY  (`id`),
  KEY `langgroup` (`langgroup`),
  KEY `langvar` (`langvar`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
