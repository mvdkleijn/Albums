<?php

	global $__CMS_CONN__;

	/**
		Sanity Check - decide whether we're enabling for the first time or after a disable
	**/

	$sql = "
				SELECT * FROM `".TABLE_PREFIX."plugin_settings` WHERE plugin_id='albums'
			;";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();
	$rowCount = $pdo->rowCount();

	if($rowCount == 0) {
		$sql =	"
					INSERT INTO `".TABLE_PREFIX."plugin_settings` (`plugin_id`,`name`,`value`)
					VALUES
						('albums','route','album-images'),
						('albums','maxHeight','1000'),
						('albums','maxWidth','1000'),
						('albums','defaultView','detail'),
						('albums','logging','off')
				;";
	}
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();


	/**
		Let's create the tables. If they exist, they won't be overwritten
	**/

	$sql =	"
				CREATE TABLE `ns_albums` (
					`id` int(11) NOT NULL auto_increment,
					`name` varchar(128) default NULL,
					`description` varchar(1024) default NULL,
					`created` int(16) NOT NULL default '0',
					`updated` int(16) NOT NULL default '0',
					`coverImage` int(11) default NULL,
					`published` enum('yes','no') default NULL,
					PRIMARY KEY	(`id`)
				) AUTO_INCREMENT=0;
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"
				CREATE TABLE `ns_albums_images` (
					`id` int(11) NOT NULL auto_increment,
					`name` varchar(128) default NULL,
					`description` varchar(2048) default NULL,
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
				CREATE TABLE `ns_albums_log` (
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
				CREATE TABLE `ns_albums_order` (
					`album` int(11) default NULL,
					`order` varchar(512) default NULL
				);
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	exit();