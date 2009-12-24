<?php

class AlbumsController extends PluginController {

	public function __construct() {
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/albums/views/backend/sidebar'));
	}

	public function index() {
		$settings = Plugin::getAllSettings('albums');
		$this->display('../plugins/albums/views/backend/index', array('albums' => Albums::getAlbumList()));
	}

	public function changeView($target) {
		Albums::changeView($target);
		redirect(get_url('albums'));
	}

	public function editHandler($id) {
		if($_POST['type'] == 'view' && $_POST['content'] != 'add a description' && $_POST['id'] != 'tags') {
			Albums::updateAlbum($_POST, $id);
		} else if($_POST['type'] == 'image' && $_POST['id'] == 'tags') {
			Albums::updateTags($_POST, $id);
		} else if($_POST['type'] == 'image' && $_POST['content'] != 'add a description') {
			Albums::updateImage($_POST, $id);
		}
		if($_POST['content'] == '') echo 'add a description';
		echo strip_tags($_POST['content']);
	}

	public function serve($id) {
		Serve::serveImage($id);
	}

	public function viewAlbum($id) {
		$this->display('../plugins/albums/views/backend/viewAlbum', array('album' => Albums::getAlbum($id), 'images' => Albums::getImagesFromAlbum($id)));
	}

	public function viewImage($id) {
		$this->display('../plugins/albums/views/backend/viewImage', array('image' => Albums::getImage($id), 'album' => Albums::getAlbumFromImageId($id), 'previousNext' => Albums::getPreviousNext($id), 'albums' => Albums::getAlbumList(), 'tags' => Albums::getImageTags($id)));
	}

	public function editImage($id) {
		$this->display('../plugins/albums/views/backend/editImage', array('image' => Albums::getImage($id), 'album' => Albums::getAlbumFromImageId($id)));
	}

	public function editAlbum($id) {
		$this->display('../plugins/albums/views/backend/editAlbum', array('image' => Albums::getAlbum($id)));
	}

	public function updateOrder($string) {
		$items = explode('o', $string);
		Albums::updateOrder($items[0], $items[1]);
	}

	public function makeCoverImage($imageID) {
		$albumID = Albums::getAlbumFromImageId($imageID);
		Albums::makeCoverImage($albumID[0]['id'], $imageID);
		Flash::set('success', __('This image has been set as the Album Cover'));
		redirect(get_url('albums/image/'.$imageID.''));
	}

	public function uploadImage($id) {
		Albums::uploadImage($id, $_POST);
	}

	public function addAlbumHandler() {
		Albums::addAlbumHandler($_POST);
	}

	public function addImage($id=NULL) {
		$this->display('../plugins/albums/views/backend/addImage', array('album' => Albums::getAlbum($id), 'albums' => Albums::getAlbumList(), 'id' => $id));
	}

	public function addAlbum($id=NULL) {
		$this->display('../plugins/albums/views/backend/addAlbum');
	}

	public function changeAlbum() {
		Albums::updateImageAlbum($_POST);
		Flash::set('success', __('This image has been moved'));
		redirect(get_url('albums/image/'.$_POST['image'].''));
	}

	public function deleteAlbum($id) {
		$this->display('../plugins/albums/views/backend/deleteAlbum', array('album' => Albums::getAlbum($id)));
	}

	public function deleteImage($id) {
		$this->display('../plugins/albums/views/backend/deleteImage', array('image' => Albums::getImage($id)));
	}

	public function deleteAlbumConfirm($id) {
		Albums::deleteAlbum($id);
		Flash::set('success', __('Your albums have been updated'));
		redirect(get_url('albums'));
	}

	public function deleteImageConfirm($id) {
		Albums::deleteImage($id);
		Flash::set('success', __('Your album has been updated'));
		redirect(get_url('albums'));
	}

	public function changeAlbumPublishStatus() {
		Albums::changeAlbumPublishStatus($_POST['album'], $_POST['published']);
		Flash::set('success', __('This album has been updated'));
		redirect(get_url('albums/view/'.$_POST['album'].''));
	}

}