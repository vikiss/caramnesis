<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// 5 minutes execution time
@set_time_limit(5 * 60);
//image storage: folder = username, filename = car id _ microtime
//don't want to . dots . in filename
$fnamend =  str_replace('.','-',microtime(true));
$car_id = $_GET['car_id'];
if (!preg_match('/^[A-Za-z0-9-]+$/',$car_id)) {
exit_status('Error! Wrong car id!');
}

$upload_dir = '/var/www/usrimg/'.Session::get('user_uuid').'/';
$allowed_ext = array('jpg','jpeg','png','gif');

if (!file_exists($upload_dir)) { mkdir($upload_dir, 0775, true);  }



if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit_status('Error! Wrong HTTP method!');
}

if(array_key_exists('file',$_FILES) && $_FILES['file']['error'] == 0 ){

  $pic = $_FILES['file'];
  
  $info = getimagesize($pic['tmp_name']);
  if ($info === FALSE) {
  	exit_status('Unable to determine image type of uploaded file!');
}

  if(!in_array(get_extension($pic['name']),$allowed_ext)){
		exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');
	}	

  
$ext = pathinfo($pic['name'], PATHINFO_EXTENSION);
  
  if(move_uploaded_file($pic['tmp_name'], $upload_dir.$car_id.'_'.$fnamend.'.'.$ext)){
		exit_status('File was uploaded successfuly!', $fnamend.'.'.$ext);
    //exit_status('File was uploaded successfuly! user: '.Session::get('user_uuid').' car: '.$car_id);
	}           

}





exit_status('Something went wrong with your upload!');

// Helper functions

function exit_status($str, $fname=''){
	echo json_encode(array('status'=>$str, 'name'=>$fname));
	exit;
}

function get_extension($file_name){
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
}



?>
