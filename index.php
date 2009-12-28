<?php

Plugin::setInfos(array(
	'id'					=>	'albums',
	'title'					=>	'Albums',
	'description'   		=>	'Albums plugin',
	'license'				=>	'MIT',
	'author'				=>	'Andrew Waters',
	'website'				=>	'http://www.band-x.org/',
	'update_url'			=>	'http://www.band-x.org/update.xml',
	'version'				=>	'1.0.0',
	'require_wolf_version'	=>	'0.6.0',
	'type'					=>	'both'
));

Plugin::addController('albums', 'Albums', 'administrator,developer,editor', TRUE);
include('classes/Albums.php');
include('classes/Serve.php');

$settings = Plugin::getAllSettings('albums');
if(count($settings) != 0) {
	$serveRoute = $settings['route'];
	if($settings['useStructure'] == 'both' || $settings['useStructure'] == 'yes') $serveRoute = $serveRoute . '/:any/:any';
} else {
	$serveRoute = 'default-defunct-route';
}

if(defined('CMS_BACKEND')) {
	Dispatcher::addRoute(array(
		'/'.$serveRoute.'/:any'					=>	'/plugin/albums/serve/$1',
		'/albums'								=>	'/plugin/albums/index',
		'/albums/'								=>	'/plugin/albums/index',
		'/albums/settings'						=>	'/plugin/albums/settings',
		'/albums/settings/'						=>	'/plugin/albums/settings',
		'/albums/saveSettings'					=>	'/plugin/albums/saveSettings',
		'/albums/saveSettings/'					=>	'/plugin/albums/saveSettings',
		'/albums/albums'						=>	'/plugin/albums/albums',
		'/albums/albums/'						=>	'/plugin/albums/albums',
		'/albums/categories'					=>	'/plugin/albums/categories',
		'/albums/categories/'					=>	'/plugin/albums/categories',
		'/albums/view/:num'						=>	'/plugin/albums/viewAlbum/$1',
		'/albums/view/:num/'					=>	'/plugin/albums/viewAlbum/$1',
		'/albums/image/:num'					=>	'/plugin/albums/viewImage/$1',
		'/albums/image/:num/'					=>	'/plugin/albums/viewImage/$1',
		'/albums/add'							=>	'/plugin/albums/addImage',
		'/albums/add/'							=>	'/plugin/albums/addImage',
		'/albums/add/:num'						=>	'/plugin/albums/addImage/$1',
		'/albums/add/:num/'						=>	'/plugin/albums/addImage/$1',
		'/albums/add-image/:num'				=>	'/plugin/albums/uploadImage/$1',
		'/albums/delete-album/:num'				=>	'/plugin/albums/deleteAlbum/$1',
		'/albums/delete-album/:num/'			=>	'/plugin/albums/deleteAlbum/$1',
		'/albums/confirm-album-delete/:num'		=>	'/plugin/albums/deleteAlbumConfirm/$1',
		'/albums/confirm-album-delete/:num/'	=>	'/plugin/albums/deleteAlbumConfirm/$1',
		'/albums/editHandler/:num'				=>	'/plugin/albums/editHandler/$1',
		'/albums/view/updateOrder/:any'			=>	'/plugin/albums/updateOrder/$1',
		'/albums/delete-image/:num'				=>	'/plugin/albums/deleteImage/$1',
		'/albums/delete-image/:num/'			=>	'/plugin/albums/deleteImage/$1',
		'/albums/confirm-image-delete/:num'		=>	'/plugin/albums/deleteImageConfirm/$1',
		'/albums/confirm-image-delete/:num/'	=>	'/plugin/albums/deleteImageConfirm/$1',
		'/albums/changeAlbum'					=>	'/plugin/albums/changeAlbum',
		'/albums/addAlbum'						=>	'/plugin/albums/addAlbum',
		'/albums/addAlbumHandler'				=>	'/plugin/albums/addAlbumHandler',
		'/albums/makeCover/:num'				=>	'/plugin/albums/makeCoverImage/$1',
		'/albums/changeAlbumPublishStatus'		=>	'/plugin/albums/changeAlbumPublishStatus',
		'/albums/changeView/:any'				=>	'/plugin/albums/changeView/$1'
	));
} else {
	if($settings['useStructure'] == 'both') {
		Dispatcher::addRoute(array(
			'/'.$settings['route'].'/:any'		=>	'/plugin/albums/serve/$1',
			'/'.$serveRoute.'/:any'				=>	'/plugin/albums/serve/$1/$2/$3',
		));	
	} elseif($settings['useStructure'] == 'yes') {
		Dispatcher::addRoute(array(
			'/'.$serveRoute.'/:any'				=>	'/plugin/albums/serve/$1/$2/$3',
		));	
	} elseif($settings['useStructure'] == 'no') {
		Dispatcher::addRoute(array(
			'/'.$settings['route'].'/:any'				=>	'/plugin/albums/serve/$1',
		));	
	}
}