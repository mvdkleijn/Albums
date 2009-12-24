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
include('AlbumsModel.php');
include('ServeModel.php');

$settings = Plugin::getAllSettings('albums');
if(count($settings) != 0) { $serveRoute = $settings['route']; } else { $serveRoute = 'default-defunct-route'; }

if(defined('CMS_BACKEND')) {
	Dispatcher::addRoute(array(
		'/'.$serveRoute.'/:any'					=>	'/plugin/albums/serve/$1',
		'/albums'								=>	'/plugin/albums/index',
		'/albums/'								=>	'/plugin/albums/index',
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
		'/albums/image/editHandler/:num'		=>	'/plugin/albums/editImageHandler/$1',
		'/albums/view/editHandler/:num'			=>	'/plugin/albums/editAlbumHandler/$1',
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
	Dispatcher::addRoute(array(
		'/'.$serveRoute.'/:any'					=>	'/plugin/albums/serve/$1',
	));
}