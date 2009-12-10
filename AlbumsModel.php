<?php

class Albums {

	const ALBUMS	=	"albums";
	const IMAGES	=	"albums_images";
	const LOGS		=	"albums_log";
	const ORDER		=	"albums_order";

	function executeSql($sql) {
		global $__CMS_CONN__;
		$stmt = $__CMS_CONN__->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAlbumList($order=NULL) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ALBUMS."";
		if($order) $sql .= " ORDER BY $order";
		return self::executeSql($sql);
	}

	public function getAlbum($id) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ALBUMS."";
		$sql .= " WHERE id='$id'";
		return self::executeSql($sql);
	}

	public function getImagesFromAlbum($albumId) {
		$order = self::getOrder($albumId);
		if(count($order) != 0) {
			$images = explode(',', ($order[0]['order']));
			$output = array();
			$i = 0;
			foreach($images as $image) {
				if($image != '') {
					$sql = "SELECT * FROM ".TABLE_PREFIX.self::IMAGES." WHERE id='".$image."' AND published='yes'";
					$result = self::executeSql($sql);
					$output[$i] = $result[0];
					$i = $i+1;
				}
			}
			return $output;
		} else {
			$sql = "SELECT * FROM ".TABLE_PREFIX.self::IMAGES."";
			$sql .= " WHERE album='$albumId' AND published='yes' ORDER BY id";
			return self::executeSql($sql);
		}
	}

	public function updateOrder($albumID, $order) {
		$sql = "DELETE FROM ".TABLE_PREFIX.self::ORDER." WHERE album='".$albumID."'";
		self::executeSql($sql);
		$sql = "INSERT INTO ".TABLE_PREFIX.self::ORDER."
				VALUES(
					'".$albumID."',
					'".$order."'
				)";
		self::executeSql($sql);
	}

	public function removeFromOrder($albumID, $removeID) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ORDER." WHERE album='".$albumID."'";
		$current = self::executeSql($sql);
		$sql = "DELETE FROM ".TABLE_PREFIX.self::ORDER." WHERE album='".$albumID."'";
		self::executeSql($sql);
		$current = $current[0]['order'];
		$current = str_replace($removeID, '', $current);
		$current = str_replace(',,', ',', $current);
		$sql = "INSERT INTO ".TABLE_PREFIX.self::ORDER."
				VALUES(
					'".$albumID."',
					'".$current."'
				)";
		self::executeSql($sql);
	}

	public function addToOrder($albumID, $addID) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ORDER." WHERE album='".$albumID."'";
		$current = self::executeSql($sql);
		$sql = "DELETE FROM ".TABLE_PREFIX.self::ORDER." WHERE album='".$albumID."'";
		self::executeSql($sql);
		if(count($current) != 0) { $current = $current[0]['order']; } else { $current = ''; }
		$current .= ','.$addID.'';
		$sql = "INSERT INTO ".TABLE_PREFIX.self::ORDER."
				VALUES(
					'".$albumID."',
					'".$current."'
				)";
		self::executeSql($sql);
	}


	public function getAlbumFromImageId($imageId) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::IMAGES."";
		$sql .= " WHERE id='$imageId'";
		$result = self::executeSql($sql);
		return self::getAlbum($result[0]['album']);
	}

	public function getOrder($albumID) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ORDER."";
		$sql .= " WHERE album='$albumID'";
		return self::executeSql($sql);
	}

	public function getImage($imageId) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::IMAGES."";
		$sql .= " WHERE id='$imageId'";
		return self::executeSql($sql);
	}

	public function getCoverImage($albumId) {
		$sql = "SELECT coverImage FROM ".TABLE_PREFIX.self::ALBUMS."";
		$sql .= " WHERE id='$albumId'";
		return self::executeSql($sql);
	}

	public function countImagesinAlbum($albumid) {
		$sql = "SELECT COUNT(*) FROM ".TABLE_PREFIX.self::IMAGES." WHERE album='$albumid'";
		$count = self::executeSql($sql);
		$count = $count[0]['COUNT(*)'];
		return $count;
	}

	public function urlToImage($pictureId, $size) {
		$settings = Plugin::getAllSettings('albums');
		$image = self::getImage($pictureId);
		return URL_PUBLIC . $settings['route'] . '/' . $pictureId .'.' . $size . '.'.$image[0]['extension'].'';
	}

	public function deleteAlbum($id) {
		$sql = "DELETE FROM ".TABLE_PREFIX.self::ALBUMS."";
		$sql .= " WHERE id='$id'";
		return self::executeSql($sql);
	}

	public function deleteImage($id) {
		$sql = "DELETE FROM ".TABLE_PREFIX.self::IMAGES."";
		$sql .= " WHERE id='$id'";
		$albumID = self::getAlbumFromImageId($id);
		self::removeFromOrder($albumID[0]['id'], $id);
		self::updateAlbumStamp($albumID[0]['id']);
		return self::executeSql($sql);
	}

	private function insertImage($_POST, $_FILES) {
		$fileInfo = getimagesize($_FILES['image']['tmp_name']);
		$now = time();
		$extension = strtolower(end(explode('.', $_FILES['image']['name'])));
		$sql = "INSERT INTO ".TABLE_PREFIX.self::IMAGES."
				VALUES(
					'',
					'".filter_var($_POST['name'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['description'], FILTER_SANITIZE_STRING)."',
					'yes',
					'".filter_var($_POST['album'], FILTER_SANITIZE_STRING)."',
					'$now',
					'".filter_var($_FILES['image']['type'], FILTER_SANITIZE_STRING)."',
					'$extension',
					'".filter_var($_FILES['image']['size'], FILTER_SANITIZE_STRING)."',
					'".$fileInfo[0]."',
					'".$fileInfo[1]."'
				)";
		self::executeSql($sql);
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
		$insertID = $this->db->lastInsertId();
		self::addToOrder($_POST['album'], $insertID);
		return($insertID);
	}

	public function uploadImage($id, $_POST) {
		if($_POST['name'] == '') echo 'You must give the image a name<br />';
		if($_FILES['image']['name'] == '') echo 'You must select an image!';
		if($_POST['name'] == '' || $_FILES['image']['name'] == '') exit();
		$validImage = self::validateImage($_FILES);
		if($validImage == TRUE) {
			$insertID = self::insertImage($_POST, $_FILES);
			if($_POST['makeCoverImage'] == 'yes') self::makeCoverImage($_POST['album'], $insertID);
			if(count(self::getImagesFromAlbum($_POST['album']) == 0)) self::makeCoverImage($_POST['album'], $insertID);
			self::moveFile($insertID, $_FILES['image']['tmp_name']);
			self::updateAlbumStamp($_POST['album']);
			echo 'This image has been uploaded. You can add another one now, or <a href="'.get_url('albums/view/'.$_POST['album'].'').'">go back to the album</a>';
		}
	}

	public function addAlbumHandler($_POST) {
		if($_POST['name'] == '') echo 'You must give the album a name<br />';
		if($_POST['name'] == '') exit();
		self::insertAlbum($_POST['name'], $_POST['description']);
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
		$insertID = $this->db->lastInsertId();
		echo 'Your album has been added. You can add another album, or <a href="'.get_url('albums/add/'.$insertID.'').'">add images to your album</a>.';
	}

	private function insertAlbum($name, $description) {
		$now = time();
		$sql = "INSERT INTO ".TABLE_PREFIX.self::ALBUMS."
				VALUES(
					'',
					'".$name."',
					'".$description."',
					'$now',
					'',
					'',
					'yes'
				)";
		self::executeSql($sql);
	}


	public function makeCoverImage($albumID, $imageID) {
		$sql = "UPDATE ".TABLE_PREFIX.self::ALBUMS." SET
					coverImage='".$imageID."'
				WHERE id='".$albumID."'
		";
		self::executeSql($sql);
	}

	private function updateAlbumStamp($albumID) {
		$now = time();
		$sql = "UPDATE ".TABLE_PREFIX.self::ALBUMS." SET
					updated='$now'
				WHERE id='".$albumID."'
		";
		self::executeSql($sql);
	}

	private function moveFile($id, $tmp) {
		$image = self::getImage($id);
		$targetFile = CORE_ROOT . '/plugins/albums/files/' . $id . '.'.$image[0]['extension'].'';
		if (is_uploaded_file($tmp)) {
			if(move_uploaded_file($tmp, $targetFile)) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
		else {
			return FALSE;
		}
	}

	private function validateImage($_FILES) {
		$fileInfo = getimagesize($_FILES['image']['tmp_name']);
		$allowedMime = array('image/jpeg', 'image/png', 'image/gif');
		if(!in_array($fileInfo['mime'], $allowedMime)) {
			echo 'This file is not a valid image. Please use jpg, gif or png formats';
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function updateImage($_POST, $imageID) {
		$sql = "UPDATE ".TABLE_PREFIX.self::IMAGES." SET
					".$_POST['id']."='".filter_var($_POST['content'], FILTER_SANITIZE_STRING)."'
				WHERE id='".$imageID."'";
		self::executeSql($sql);
	}

	public function updateAlbum($_POST, $imageID) {
		$sql = "UPDATE ".TABLE_PREFIX.self::ALBUMS." SET
					".$_POST['id']."='".filter_var($_POST['content'], FILTER_SANITIZE_STRING)."'
				WHERE id='".$imageID."'";
		self::executeSql($sql);
	}

	public function updateImageAlbum($_POST) {
		$oldAlbum = self::getAlbumFromImageId($_POST['image']);
		$oldAlbum = $oldAlbum[0]['id'];
		$sql = "UPDATE ".TABLE_PREFIX.self::IMAGES." SET
					album='".filter_var($_POST['album'], FILTER_SANITIZE_STRING)."'
				WHERE id='".$_POST['image']."'";
		self::executeSql($sql);
		self::removeFromOrder($oldAlbum, $_POST['image']);
		self::addToOrder($_POST['album'], $_POST['image']);
	}

	public function getPreviousNext($imageID) {
		$albumID = self::getAlbumFromImageId($imageID);
		$albumID = $albumID[0]['id'];
		$images = self::getImagesFromAlbum($albumID);
		$imagesLength = count($images);
		for ($i = 0; $i < $imagesLength; $i += 1) {
			if($images[$i]['id'] == $imageID) {
				$currentArrayPosition = $i;
			}
		}
		$previous = '';
		$next = '';
		if(($currentArrayPosition-1) >= 0) {
			$previous = $images[$currentArrayPosition-1]['id'];		
		}
		if(($currentArrayPosition+1) < $imagesLength) {
			$next = $images[$currentArrayPosition+1]['id'];
		}
		return(array('previous' => $previous, 'next' => $next));
	}

	public function changeAlbumPublishStatus($albumID, $status) {
		$sql = "UPDATE ".TABLE_PREFIX.self::ALBUMS." SET
					published='".filter_var($status, FILTER_SANITIZE_STRING)."'
				WHERE id='".$albumID."'";
		self::executeSql($sql);
	}

	public function addToLog($target, $uri, $served, $referrer=NULL, $ip, $now) {
		$sql = "INSERT INTO ".TABLE_PREFIX.self::LOGS."
				VALUES(
					'',
					'$target',
					'$uri',
					'$served',
					'$referrer',
					'$ip',
					'$now'
				)";
		self::executeSql($sql);
	}

}