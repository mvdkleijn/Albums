<?php

class Albums {

	const SETTINGS		=	"plugin_settings";

	const ALBUMS		=	"albums";
	const IMAGES		=	"albums_images";
	const LOGS			=	"albums_log";
	const ORDER			=	"albums_order";
	const TAGS			=	"albums_tags";
	const CATEGORIES	=	"albums_categories";

	function executeSql($sql) {
		global $__CMS_CONN__;
		$stmt = $__CMS_CONN__->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function changeView($target) {
		$sql = "UPDATE ".TABLE_PREFIX.self::SETTINGS." SET
					value='".filter_var($target, FILTER_SANITIZE_STRING)."'
				WHERE plugin_id='albums' AND name='defaultView'";
		self::executeSql($sql);
	}

	public function calculateSizeOnDisk($type, $id=NULL, $measure=NULL) {
		$size = 0;
		switch($type) {
			case 'image':
				$images = self::getImage($id);
				if(isset($images) && count($images) != 0) {
					foreach($images as $image) {
						$imageSize = filesize(PLUGINS_ROOT .'/albums/files/'.$image['id'].'.'.$image['extension']);
						$size = $size + $imageSize;
					}
				}
				break; 
			case 'album':
				$albums = self::getAlbum($id);
				foreach($albums as $album) {
					$images = self::getImagesByAlbum($album['id']);
				}
				if(isset($images) && count($images) != 0) {
					foreach($images as $image) {
						$imageSize = filesize(PLUGINS_ROOT .'/albums/files/'.$image['id'].'.'.$image['extension']);
						$size = $size + $imageSize;
					}
				}
				break; 
			case 'category':
				$images = array();
				$categories = self::getCategory($id);
				foreach($categories as $category) {
					$albums = self::getAlbumByCategory($category['id']);
					foreach($albums as $album) {
						$images = self::getImagesByAlbum($album['id']);
						foreach($images as $image) {
							$imageSize = filesize(PLUGINS_ROOT .'/albums/files/'.$image['id'].'.'.$image['extension']);
							$size = $size + $imageSize;
						}
					}
				}
				break; 
			case 'all':
				$images = self::getImages();
				if(isset($images) && count($images) != 0) {
					foreach($images as $image) {
						$imageSize = filesize(PLUGINS_ROOT .'/albums/files/'.$image['id'].'.'.$image['extension']);
						$size = $size + $imageSize;
					}
				}
				break; 
		}
		switch($measure) {
			case 'bytes':
				$size = $size;
			case 'kilobytes':
				$size = $size / 1024;
			case 'megabytes':
				$size = $size / 1024 / 1024;
		}
		return $size;
	}

	public function getAlbumByCategory($category) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ALBUMS." WHERE category='$category'";
		return self::executeSql($sql);
	}

	public function getImagesByAlbum($albumID) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::IMAGES." WHERE album='$albumID'";
		return self::executeSql($sql);
	}

	public function saveSettings($_POST) {
		foreach($_POST as $key=>$value) {
			$sql = "UPDATE ".TABLE_PREFIX.self::SETTINGS." SET
						value='".filter_var($value, FILTER_SANITIZE_STRING)."'
					WHERE plugin_id='albums' AND name='$key'";
			self::executeSql($sql);
		}
	}

	public function clearLog() {
		$sql = "DELETE FROM ".TABLE_PREFIX.self::LOGS."";
		self::executeSql($sql);
	}

	public function getLogs($order=NULL, $limit=NULL) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::LOGS."";
		if($order) $sql .= " ORDER BY $order";
		if($limit) $sql .= " LIMIT $limit";
		return self::executeSql($sql);
	}

	public function countLogs($key=NULL, $value=NULL) {
		$sql = "SELECT COUNT(*) FROM ".TABLE_PREFIX.self::LOGS."";
		if($key) $sql .= " WHERE $key='$value'";
		$count = self::executeSql($sql);
		$count = $count[0]['COUNT(*)'];
		return $count;
	}

	public function firstImpression() {
		$sql = "SELECT time FROM ".TABLE_PREFIX.self::LOGS." ORDER BY time ASC LIMIT 1";
		$result = self::executeSql($sql);
		if(count($result) != 0) { $result = $result[0]['time']; } else { $result = 0; }
		return $result;
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

	public function getAlbumBySlug($slug) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ALBUMS."";
		$sql .= " WHERE slug='$slug'";
		return self::executeSql($sql);
	}

	public function getCategoryBySlug($slug) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::CATEGORIES."";
		$sql .= " WHERE slug='$slug'";
		return self::executeSql($sql);
	}

	public function getCategory($id) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::CATEGORIES."";
		$sql .= " WHERE id='$id'";
		return self::executeSql($sql);
	}

	public function editCategory($_POST) {
		$sql = "UPDATE ".TABLE_PREFIX.self::CATEGORIES." SET
					name='".filter_var($_POST['name'], FILTER_SANITIZE_STRING)."',
					slug='".filter_var($_POST['slug'], FILTER_SANITIZE_STRING)."',
					description='".filter_var($_POST['description'], FILTER_SANITIZE_STRING)."'				
				";
		$sql .= " WHERE id='".filter_var($_POST['id'], FILTER_SANITIZE_STRING)."'";
		return self::executeSql($sql);
	}

	public function deleteCategory($id) {
		$sql = "DELETE FROM ".TABLE_PREFIX.self::CATEGORIES." WHERE id='".$id."'";
		self::executeSql($sql);
		self::removeAlbumsFromCategory($id);
	}

	public function removeAlbumsFromCategory($id) {
		$sql = "UPDATE ".TABLE_PREFIX.self::ALBUMS." SET
					category='1'
				WHERE category='$id'";
		self::executeSql($sql);
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

	public function getRandomImageFromAlbum($albumID) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::IMAGES." WHERE album='$albumID' ORDER BY RAND() LIMIT 1";
			return self::executeSql($sql);		
	}

	public function getImageTags($imageID) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::TAGS."";
		$sql .= " WHERE imageID='$imageID'";
		return self::executeSql($sql);
	}

	public function getTaggedImagesFromAlbum($album, $tag) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::IMAGES."";
		$sql .= " WHERE album='".$album."'";
		$albumImages = self::executeSql($sql);
		if(count($albumImages) > 0) {
			foreach($albumImages as $image) {
				$tags = self::getImageTags($image['id']);
				if(!empty($tags)) {
					if($tags[0]['tag'] == ''.$tag.'') return $image;
				}	
			}
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

	public function getImages() {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::IMAGES."";
		return self::executeSql($sql);
	}

	public function getImageByName($name) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::IMAGES."";
		$sql .= " WHERE name='$name'";
		return self::executeSql($sql);
	}

	public function getImageByMDFive($imageId) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::IMAGES."";
		$results = self::executeSql($sql);
		$settings = Plugin::getAllSettings('albums');
		foreach($results as $result) {
			$thisMD = md5($result['id'] . $settings['salt']);
			if($thisMD == $imageId) return($result);
		}
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

	public function urlToImage($pictureId, $size=NULL) {
		$settings = Plugin::getAllSettings('albums');
		$image = self::getImage($pictureId);
		if($settings['format'] == 'numeric') {
			$pictureId = $pictureId;
		} elseif($settings['format'] == 'hash') {
			$pictureId = md5($pictureId . $settings['salt']);
		} elseif($settings['format'] == 'name') {
			$pictureId = $image[0]['name'];
		}
		$modRewrite = '';
		if(USE_MOD_REWRITE == FALSE) $modRewrite = '?';
		$route = $settings['route'];
		$category = self::getImageCategoryFromAlbum($image[0]['album']);
		$album = self::getImageAlbumSlug($image[0]['album']);
		$route = $route . '/' . $category['slug'] . '/' . $album['slug'];
		if($size != NULL) { return URL_PUBLIC . $modRewrite . $route . '/' . $pictureId .'.' . $size . '.'.$image[0]['extension'].''; }
		else { return URL_PUBLIC . $modRewrite . $route . '/' . $pictureId .'.'.$image[0]['extension'].''; }
	}

	public function getImageAlbumSlug($albumID) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ALBUMS." WHERE id='$albumID'";
		$album = self::executeSql($sql);
		return $album[0];
	}

	public function getImageCategoryFromAlbum($albumID) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ALBUMS." WHERE id='$albumID'";
		$album = self::executeSql($sql);
		$categoryID = $album[0]['category'];
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::CATEGORIES." WHERE id='$categoryID'";
		$category = self::executeSql($sql);
		return $category[0];
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
		$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
		$name = self::checkImageNameIsUnique($name);
		$sql = "INSERT INTO ".TABLE_PREFIX.self::IMAGES."
				VALUES(
					'',
					'".$name."',
					'".filter_var($_POST['description'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['credits'], FILTER_SANITIZE_STRING)."',
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

	private function checkImageNameIsUnique($name) {
		$sql = "SELECT COUNT(*) FROM ".TABLE_PREFIX.self::IMAGES." WHERE name='$name'";
		$result = self::executeSql($sql);
		if($result[0]['COUNT(*)'] != 0) { return
			$name . '-' . time();
		}
		else { return $name; }
	}

	public function uploadImage($id, $_POST) {
		if($_POST['name'] == '') echo 'You must give the image a name<br />';
		if($_FILES['image']['name'] == '') echo 'You must select an image!';
		if($_POST['name'] == '' || $_FILES['image']['name'] == '') exit();
		$validImage = self::validateImage($_FILES);
		if($validImage == TRUE) {
			$insertID = self::insertImage($_POST, $_FILES);
			if($_POST['makeCoverImage'] == 'yes') self::makeCoverImage($_POST['album'], $insertID);
			$countImages = count(self::getImagesFromAlbum($_POST['album']));
			if($countImages < 1) self::makeCoverImage($_POST['album'], $insertID);
			self::moveFile($insertID, $_FILES['image']['tmp_name']);
			self::updateAlbumStamp($_POST['album']);
			echo 'This image has been uploaded. You can add another one now, or <a href="'.get_url('albums/view/'.$_POST['album'].'').'">go back to the album</a>';
		}
	}

	public function addCategoryHandler($_POST) {
		if($_POST['name'] == '') echo 'You must give the category a name<br />';
		if($_POST['name'] == '') exit();
		self::insertCategory($_POST['name'], $_POST['description'], $_POST['slug']);
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
		$insertID = $this->db->lastInsertId();
		echo 'Your category has been added.';
	}

	private function insertCategory($name, $description=NULL, $slug=NULL) {
		$sql = "INSERT INTO ".TABLE_PREFIX.self::CATEGORIES."
				VALUES(
					'',
					'".$name."',
					'".$slug."',
					'".$description."'
				)";
		self::executeSql($sql);
	}

	public function addAlbumHandler($_POST) {
		if($_POST['name'] == '') echo 'You must give the album a name<br />';
		if($_POST['name'] == '') exit();
		self::insertAlbum(filter_var($_POST['name'], FILTER_SANITIZE_STRING), filter_var($_POST['description'], FILTER_SANITIZE_STRING), filter_var($_POST['credits'], FILTER_SANITIZE_STRING), filter_var($_POST['category'], FILTER_SANITIZE_STRING), filter_var($_POST['slug'], FILTER_SANITIZE_STRING));
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
		$insertID = $this->db->lastInsertId();
		echo 'Your album has been added. You can add another album, or <a href="'.get_url('albums/add/'.$insertID.'').'">add images to your album</a>.';
	}

	private function insertAlbum($name, $description=NULL, $credits=NULL, $category=NULL, $slug=NULL) {
		$now = time();
		if($category == NULL) $category = '1';
		$sql = "INSERT INTO ".TABLE_PREFIX.self::ALBUMS."
				VALUES(
					'',
					'".$name."',
					'".$slug."',
					'".$description."',
					'".$credits."',
					'$now',
					'',
					'',
					'".$category."',
					'no'
				)";
		self::executeSql($sql);
	}

	public function getCategories() {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::CATEGORIES."";
		return self::executeSql($sql);
	}

	public function makeCoverImage($albumID, $imageID) {
		$sql = "UPDATE ".TABLE_PREFIX.self::ALBUMS." SET
					coverImage='".$imageID."'
				WHERE id='".$albumID."'
		";
		self::executeSql($sql);
	}

	public function updateAlbumCategory($_POST) {
		$sql = "UPDATE ".TABLE_PREFIX.self::ALBUMS." SET
					category='".filter_var($_POST['category'], FILTER_SANITIZE_STRING)."'
				WHERE id='".filter_var($_POST['album'], FILTER_SANITIZE_STRING)."'
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

	public function updateTags($_POST, $imageID) {
		$sql = "DELETE FROM ".TABLE_PREFIX.self::TAGS." WHERE imageID='$imageID'";
		self::executeSql($sql);
		$tags = explode(',', $_POST['content']);
		foreach($tags as $tag) {
			if($tag != '') {
				$sql = "INSERT INTO ".TABLE_PREFIX.self::TAGS." VALUES (
							'',
							'$imageID',
							'".filter_var(trim($tag), FILTER_SANITIZE_STRING)."'
						)";
				self::executeSql($sql);
			}
		}
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
		if($_POST['id'] == 'name') {
			$_POST['content'] = self::checkImageNameIsUnique(filter_var($_POST['content'], FILTER_SANITIZE_STRING));
		}
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