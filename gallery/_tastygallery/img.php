<?php

require_once 'common.php';

$path=dirname(dirname($_SERVER['SCRIPT_FILENAME']));

switch($quality){
	case 1:
		$quality_jpg=20;
		$quality_png=9;
		break;
	case 2:
		$quality_jpg=40;
		$quality_png=7;
		break;
	case 3:
	default:
		$quality_jpg=60;
		$quality_png=5;
		break;
	case 4:
		$quality_jpg=80;
		$quality_png=3;
		break;
	case 5:
		$quality_jpg=100;
		$quality_png=0;
		break;
}

if(!empty($_GET['i'])){
	if($img=realpath('../'.$_GET['i'])){
		
		$name=basename($img);
		$dir=dirname($img);
		$fext=strtolower(substr($img,strrpos($img,'.')+1));
		$mime='image/'.str_replace('jpg','jpeg',str_replace('apng','png',$fext));
		$makethumbplz=false;
		
		if(!in_array($fext,$ext)){
			echo 'Stop being silly, you can\'t download anything that isn\'t an image!';
			die();
		}

		list($width_orig, $height_orig) = getimagesize($img);
		if(empty($_GET['w']) && empty($_GET['h'])){
			///////////////////////////////
			// if the user requested the original version of the image
			// we set the image to never expire, because if it changes, it will be served with a different filename, based on the time it was last modified (thanks, andersonmat!)
			/////////////////
			header('Content-Disposition: inline; filename="'.$name.'"');
			header('Content-Type: '.$mime);
			header('Cache-Control: max-age=31556926, must-revalidate');
			header('Expires: Fri, 1 Jan 2020 10:10:10 GMT');
			header('Pragma: public');
			readfile($img);
			die();
		}else if(!empty($_GET['w']) && empty($_GET['h'])){
			$width = $_GET['w'];
			$height = ceil($height_orig*($width/$width_orig));
		}else if(empty($_GET['w']) && !empty($_GET['h'])){
			$height = $_GET['h'];
			$width = ceil($width_orig*($height/$height_orig));
		}else if(!empty($_GET['w']) && !empty($_GET['h'])){
			$width = $_GET['w'];
			$height = $_GET['h'];
		}
		
		$thumb=glob($dir.'/'.$thumbdir.'/'.md5($name).'_'.$width.'x'.$height.'*');
		if(count($thumb)>0 && (filemtime($img) < filemtime($thumb[0])) ){
			///////////////////////////////
			// if the thumbnail exists
			/////////////////
			$tname=basename($thumb[0]);
			
			$text=strtolower(substr($thumb[0],strrpos($thumb[0],'.')+1));
			$tmime='image/'.str_replace('jpg','jpeg',str_replace('apng','png',$text));

			header('Content-Disposition: inline; filename="'.$tname.'"');
			header('Content-Type: '.$tmime);
			header('Cache-Control: max-age=31556926, must-revalidate');
			header('Expires: Fri, 1 Jan 2020 10:10:10 GMT');
			header('Pragma: public');
			readfile($thumb[0]);
			die();
		}else{
			///////////////////////////////
			// if the thumbnail doesn't exist, but the _thumb folder does, we remember to make the thumbnail
			/////////////////
			if(file_exists($dir.'/'.$thumbdir)){
				$makethumbplz=true;
			}
		}
		
		///////////////////////////////
		// create the thumbnail, serve it, cache it
		/////////////////
		
		$ratio = $width/$height;
		$ratio_orig = $width_orig/$height_orig;
		
		// width, height:				306  x 182		1,68
		// width_orig, height_orig:		2560 x 1600		1,6
		
		if ($ratio < $ratio_orig) {
			$width_new = $height*$ratio_orig;
			$height_new = $height;
		} else {
			$width_new = $width;
			$height_new = $width/$ratio_orig;
		}

		$image_p = imagecreatetruecolor($width, $height);
		if($mime=='image/jpeg'){
			$image = imagecreatefromjpeg($img);
		}else if($mime=='image/png'){
			$image = imagecreatefrompng($img);
		}else if($mime=='image/gif'){
			$image = imagecreatefromgif($img);
		}
		imagecopyresampled($image_p, $image, ($width-$width_new)/2, ($height-$height_new)/2, 0, 0, $width-($width-$width_new), $height-($height-$height_new), $width_orig, $height_orig);
		
		header('Content-Disposition: inline; filename="'.$name.'"');
		header('Content-Type: '.$mime);
		header('Cache-Control: max-age=31556926, must-revalidate');
		header('Expires: Fri, 1 Jan 2020 10:10:10 GMT');
		header('Pragma: public');
		if($mime=='image/jpeg'){
			imagejpeg($image_p, null, $quality_jpg);
		}else if($mime=='image/png'){
			imagepng($image_p, null, $quality_png);
		}else if($mime=='image/gif'){
			imagegif($image_p);
		}
		if($makethumbplz){
			imagejpeg($image_p, $dir.'/'.$thumbdir.'/'.md5($name).'_'.$width.'x'.$height.'.'.'jpg', $quality_jpg);
		}
		
		die();
	}
}



















