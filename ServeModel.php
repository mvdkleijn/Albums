<?php

class Serve {

	const ALBUMS	=	"albums";
	const IMAGES	=	"albums_images";
	const LOGS		=	"albums_logs";

	function executeSql($sql) {
		global $__CMS_CONN__;
		$stmt = $__CMS_CONN__->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function serveImage($id) {

		$file = explode('.', $id);
		$count = count($file);

		switch($count) {
			case 2:
				$imageId = $file[0];
				$imageWidth = 0;
				$imageHeight = 0;				
				$imageExtension = $file[1];
				break;
			case 3:
				$imageId = $file[0];
				$imageWidth = $file[1];
				$imageHeight = 0;
				$imageExtension = $file[2];
				break;
			case 4:
				$imageId = $file[0];
				$imageWidth = $file[1];
				$imageHeight = $file[2];
				$imageExtension = $file[3];
				break;
		}
		$onFile = Albums::getImage($imageId);
		$fileOnDisk = '/home2/northern/public_html/wolf/plugins/albums/files/'.$imageId.'.'.$imageExtension.'';
		$imageInfo = getimagesize($fileOnDisk);
		if($imageWidth != 0) {
			if($imageWidth <= $imageInfo[0]) {
				$targetFile = str_replace('.'.$imageExtension.'', '.'.$imageWidth.'.'.$imageExtension.'', $fileOnDisk);
				if(!file_exists($targetFile)) self::resizeImage($fileOnDisk, $imageWidth, ''.$targetFile.'');
				$fileOnDisk = $targetFile;
			}
		}
		$img = self::LoadJpeg($fileOnDisk);
		imagejpeg($img);
		imagedestroy($img);
		$settings = Plugin::getAllSettings('albums');
		if($settings['logging'] == 'on') {
			$referrer ='';
			$referrer = $_SERVER['HTTP_REFERER'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$now = time();
			$uri = $_SERVER['REQUEST_URI'];
			Albums::addToLog($fileOnDisk, $uri, 'yes',  $referrer, $ip, $now);
		}
		exit;
	}

	private function LoadJpeg($imgname) {
		$im = @imagecreatefromjpeg($imgname);
		if(!$im) {
			$im  = imagecreatetruecolor(400, 100);
			$bgc = imagecolorallocate($im, 255, 255, 255);
			$tc  = imagecolorallocate($im, 0, 0, 0);
			imagefilledrectangle($im, 0, 0, 400, 100, $bgc);
			imagestring($im, 5, 80, 35, 'No such image', $tc);
		}
		return $im;
	}

	function resizeImage($img, $thumb_width, $newfilename) { 
		$max_width = $thumb_width;
		//Get Image size info
		list($width_orig, $height_orig, $image_type) = getimagesize($img);
		switch ($image_type) {
			case 1: $im = imagecreatefromgif($img); break;
			case 2: $im = imagecreatefromjpeg($img); break;
			case 3: $im = imagecreatefrompng($img); break;
			default:  trigger_error('Unsupported filetype!', E_USER_WARNING); break;
		}
		/*** calculate the aspect ratio ***/
		$aspect_ratio = (float) $height_orig / $width_orig;
		/*** calulate the thumbnail width based on the height ***/
		$thumb_height = round($thumb_width * $aspect_ratio);
		while($thumb_height>$max_width) {
			$thumb_width-=10;
			$thumb_height = round($thumb_width * $aspect_ratio);
		}
		$newImg = imagecreatetruecolor($thumb_width, $thumb_height);
		/* Check if this image is PNG or GIF, then set if Transparent*/  
		if(($image_type == 1) OR ($image_type==3)) {
			imagealphablending($newImg, false);
			imagesavealpha($newImg,true);
			$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
			imagefilledrectangle($newImg, 0, 0, $thumb_width, $thumb_height, $transparent);
		}
		imagecopyresampled($newImg, $im, 0, 0, 0, 0, $thumb_width, $thumb_height, $width_orig, $height_orig);
		//Generate the file, and rename it to $newfilename
		switch ($image_type) {
			case 1: imagegif($newImg,$newfilename); break;
			case 2: imagejpeg($newImg,$newfilename);  break;
			case 3: imagepng($newImg,$newfilename); break;
			default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
		}
			
		return $newfilename;
	}

}