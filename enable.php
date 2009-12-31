<?php

	global $__CMS_CONN__;

	/**
		Sanity Check - decide whether we're enabling for the first time or after a disable
	**/

	$sql = "SELECT * FROM `".TABLE_PREFIX."plugin_settings` WHERE plugin_id='albums';";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();
	$rowCount = $pdo->rowCount();

	if($rowCount == 0) {
		$sql =	"
					INSERT INTO `".TABLE_PREFIX."plugin_settings` (`plugin_id`,`name`,`value`)
					VALUES
						('albums','defaultView','detail'),
						('albums','displaySize','yes'),
						('albums','logging','off'),
						('albums','logCollection','0'),
						('albums','format','numeric'),
						('albums','route','albums'),
						('albums','salt','".time()."')
				;";
	}
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();


	/**
		Let's create the tables. If they exist, they won't be overwritten
	**/

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."albums` (
					`id` int(11) NOT NULL auto_increment,
					`name` varchar(128) default NULL,
					`slug` varchar(128) default NULL,
					`description` varchar(4096) default NULL,
					`credits` varchar(1024) default NULL,
					`created` int(16) NOT NULL default '0',
					`updated` int(16) NOT NULL default '0',
					`coverImage` int(11) default NULL,
					`category` int(11) NOT NULL,
					`published` enum('yes','no') default NULL,
					PRIMARY KEY	(`id`)
				) AUTO_INCREMENT=0;
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."albums_images` (
					`id` int(11) NOT NULL auto_increment,
					`name` varchar(128) default NULL,
					`description` varchar(2096) default NULL,
					`credits` varchar(1024) default NULL,
					`published` enum('yes','no') NOT NULL default 'yes',
					`album` int(11) default NULL,
					`timeAdded` int(16) default NULL,
					`filetype` varchar(64) default NULL,
					`extension` varchar(8) default NULL,
					`filesize` int(32) default NULL,
					`width` int(11) default NULL,
					`height` int(11) default NULL,
					PRIMARY KEY	(`id`)
				) AUTO_INCREMENT=0;
			";

	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."albums_log` (
					`id` int(11) NOT NULL auto_increment,
					`image` varchar(512) default NULL,
					`uri` varchar(512) default NULL,
					`served` enum('yes','no') default NULL,
					`referrer` varchar(512) default NULL,
					`ip` varchar(24) default NULL,
					`time` int(16) default NULL,
					PRIMARY KEY (`id`)
				) AUTO_INCREMENT=0;
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."albums_order` (
					`album` int(11) default NULL,
					`order` varchar(512) default NULL
				);
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."albums_categories` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`name` varchar(128) DEFAULT NULL,
					`slug` varchar(128) default NULL,
					`description` varchar(4096) DEFAULT NULL,
					PRIMARY KEY (`id`)
				);
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."albums_tags` (
					`id` int(11) NOT NULL auto_increment,
					`imageID` int(11) default NULL,
					`tag` varchar(64) default NULL,
					PRIMARY KEY  (`id`)
				);
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"SELECT COUNT(*) FROM ".TABLE_PREFIX."albums_categories";
	$pdo = $__CMS_CONN__->prepare($sql);
	$count = $pdo->execute();
	if($count != 0) {
		$sql =	"
					INSERT INTO ".TABLE_PREFIX."albums_categories
					VALUES (
						'',
						'Uncategorised',
						'uncategorised',
						'This is the default category'
					)
				";
		$pdo = $__CMS_CONN__->prepare($sql);
		$pdo->execute();
	}

	exit();