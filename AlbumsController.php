<?php

class AlbumsController extends PluginController {

	public function __construct() {
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/albums/views/backend/sidebar'));
	}

	public function index() {
		$this->display('../plugins/albums/views/backend/index', array('categories' => Albums::getCategories(), 'albums' => Albums::getAlbumList(), 'images' => Albums::getImages()));
	}

	public function albums() {
		$settings = Plugin::getAllSettings('albums');
		$this->display('../plugins/albums/views/backend/albums', array('albums' => Albums::getAlbumList()));
	}

	public function categories() {
		$this->display('../plugins/albums/views/backend/categories', array('categories' => Albums::getCategories()));
	}

	public function images() {
		$this->display('../plugins/albums/views/backend/images', array('images' => Albums::getImages()));
	}

	public function settings() {
		$this->display('../plugins/albums/views/backend/settings', array('settings' => Plugin::getAllSettings('albums')));
	}

	public function saveSettings() {
		Albums::saveSettings($_POST);
		Flash::set('success', __('Your settings have been updated'));
		redirect(get_url('albums/settings'));
	}

	public function changeView($target) {
		Albums::changeView($target);
		redirect(get_url('albums/albums'));
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

	public function serve($id, $idTwo=NULL, $idThree=NULL) {
		if($idTwo != NULL && $idThree != NULL) {
			$isValid = self::checkRouteIsValid($id, $idTwo, $idThree);
			if($isValid == TRUE) {
				Serve::serveImage($idThree);
			}
		} else {
			Serve::serveImage($id);
		}
	}

	public function checkRouteIsValid($category, $album, $image) {
		$image = explode('.', $image);
		$settings = Plugin::getAllSettings('albums');
		if($settings['format'] == 'numeric') {
			$image = Albums::getImage($image[0]);
			$image = $image[0];
		} elseif($settings['format'] == 'hash') {
			$image = Albums::getImageByMDFive($image[0]);
		} elseif($settings['format'] == 'name') {
			$image = Albums::getImageByName($image[0]);
			$image = $image[0];
		}
		$album = Albums::getAlbumBySlug($album);
		$category = Albums::getCategoryBySlug($category);
		if(!empty($image) && !empty($album) && !empty($category)) {
			if($image['album'] == $album[0]['id']) {
				if($album[0]['category'] == $category[0]['id']) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
			else {
				return FALSE;
			}
		}
	}

	public function viewCategory($id) {
		$this->display('../plugins/albums/views/backend/viewCategory', array('category' => Albums::getCategory($id)));
	}

	public function editCategory() {
		Albums::editCategory($_POST);
		Flash::set('success', __('This category has been updated'));
		redirect(get_url('albums/categories/'.$_POST['id'].''));
	}

	public function viewAlbum($id) {
		$this->display('../plugins/albums/views/backend/viewAlbum', array('album' => Albums::getAlbum($id), 'images' => Albums::getImagesFromAlbum($id)));
	}

	public function viewImage($id) {
		$this->display('../plugins/albums/views/backend/viewImage', array('image' => Albums::getImage($id), 'album' => Albums::getAlbumFromImageId($id), 'previousNext' => Albums::getPreviousNext($id), 'albums' => Albums::getAlbumList(), 'tags' => Albums::getImageTags($id), 'settings' => Plugin::getAllSettings('albums')));
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
		$this->display('../plugins/albums/views/backend/addAlbum', array('categories' => Albums::getCategories()));
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

	public function addCategoryHandler() {
		Albums::addCategoryHandler($_POST);
	}

	public function addCategory($id=NULL) {
		$this->display('../plugins/albums/views/backend/addCategory');
	}

}