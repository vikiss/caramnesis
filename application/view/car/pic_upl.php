<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// 5 minutes execution time
@set_time_limit(5 * 60);
//image storage: folder = username, filename = car id _ microtime
//don't want .[EN] and ,[LT]  in filename
$fnamend =  str_replace(array('.',','),'-',microtime(true));
$car_id = $_GET['car_id'];

if  (
		(
			($owner_id = CarModel::getCarOwner($car_id))  && (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) >= 80)
		) or (
			(Session::get('user_uuid') == $car_id)
		)
	)

 {


if (!preg_match('/^[A-Za-z0-9-]+$/',$car_id)) {
exit_status('Error! Wrong car id!');
}
$upload_dir = Config::get('CAR_IMAGE_PATH').$car_id.'/';
$allowed_ext = array('jpg','jpeg','png','gif','pdf');
$allowed_nonimage_ext = array('pdf','xls');

if (!file_exists($upload_dir)) { mkdir($upload_dir, 0775, true);  }



if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit_status('Error! Wrong HTTP method!');
}

if(array_key_exists('file',$_FILES) && $_FILES['file']['error'] == 0 ){

  $pic = $_FILES['file'];

if(!in_array(CarModel::get_extension($pic['name']),$allowed_nonimage_ext)){
  $info = getimagesize($pic['tmp_name']);
  if ($info === FALSE) {
  	exit_status('Unable to determine image type of uploaded file!');
} }

  if(!in_array(CarModel::get_extension($pic['name']),$allowed_ext)){
		exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');
	}


$ext = pathinfo($pic['name'], PATHINFO_EXTENSION);

  if(move_uploaded_file($pic['tmp_name'], $upload_dir.$fnamend.'.'.$ext)){
		exit_status('File was uploaded successfuly!', $fnamend.'.'.$ext);
    //exit_status('File was uploaded successfuly! user: '.Session::get('user_uuid').' car: '.$car_id);
	}

}
} else {exit_status('Error! Wrong car id or insufficient permissions!');
}





exit_status('Something went wrong with your upload!');

// Helper functions

function exit_status($str, $fname=''){
	echo json_encode(array('status'=>$str, 'name'=>$fname));
	exit;
}





?>
