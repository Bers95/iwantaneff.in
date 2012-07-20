<?php

require_once 'common.php';

$path=realpath(dirname(dirname($_SERVER['SCRIPT_FILENAME'])));

if(isset($_GET['d'])){
	$status=0;
	$images=array();
	$imgs=array();
	$dirs=array();
	$cdir='';
	$cdirs='';
	$thumb='';
	while( !empty($_GET['d']) && $_GET['d'][0] == '/' ){
		$_GET['d']=substr($_GET['d'],1);
	}
	if(str_replace('\\','/',realpath(dirname($path).'/'.stripslashes(rawurldecode($_GET['d']))))==$path){
		$_GET['d']='';
	}
	if($realpath=str_replace('\\','/',realpath($path.'/'.stripslashes(rawurldecode($_GET['d']))))){
		if(substr($realpath,0,strlen($path))!=$path){
			$status=100;
		}else{
			if(!empty($_GET['d'])){
				$cdir=stripslashes(rawurldecode($_GET['d']));
				while( ($cdir[strlen($cdir) -1 ]) == '/' ){
					$cdir=substr($cdir,0,-1);
				}
				$dirch=@chdir('../'.$cdir);
			}else{
				$cdir='';
				$dirch=@chdir('../');
			}
			if(!empty($cdir) && $cdir[strlen($cdir)-1] != '/'){
				$cdir.='/';
			}
			$cdirs=explode('/','/'.$cdir);
			if($cdirs[0]=='') $cdirs[0]='/';
			if(empty($cdirs[count($cdirs)-1])) array_pop($cdirs);
			if($dirch){ 
				foreach($ext as $e){
					$g1=glob("*.".$e);
					$g2=glob("*.".strtoupper($e));
					if($g1 && count($g1)>0){
						foreach($g1 as $f){
							if(!is_dir($f)){
								$images[]=$f;
							}
						}
					}
					if($g2 && count($g2)>0){
						foreach($g2 as $f){
							if(!is_dir($f)){
								$images[]=$f;
							}
						}
					}
				}
				foreach(glob('*',GLOB_ONLYDIR) as $dirr){	
					if($dirr!=$thumbdir && $dirr!='_tastygallery'){
						foreach($ext as $e){
							if( (count(glob($dirr.'/*.'.$e)) > 0) || (count(glob($dirr.'/*.'.strtoupper($e))) > 0) ){
								if(!in_array($dirr,$dirs)){
									$dirs[]=$dirr;
								}
							}
						}
					}
				}
				sort($images);
			}
			if(file_exists($path.'/'.$cdir.'/'.$thumbdir)){
				$thumb=0;
				if(count(glob($path.'/'.$cdir.'/'.$thumbdir.'/*'))==0){
					$thumb=1;
				}
			}else{
				if(@mkdir($path.'/'.$cdir.'/'.$thumbdir)){
					$thumb=1;
				}else{
					$thumb=2;
				}
			}
			foreach($images as $image){
				$size=getimagesize($image);
				$imgs[]=array(
					'n'=>$image,
					'w'=>$size[0],
					'h'=>$size[1],
					't'=>filemtime($image)
				);
			}
		}
	}
	$arr=array('jsonlock'=>$_GET['lock'],'images'=>$imgs,'dirs'=>$dirs,'cdir'=>$cdir,'cdirs'=>$cdirs,'thumb'=>$thumb,'status'=>$status);
	echo $_GET['cb']."(".json_encode($arr).");";
	die();
}












