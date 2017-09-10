<?php
$public_access = ($this->public_access) ? true : false;
$units = $this->units;
$tags = Config::get('AVAILABLE_TAGS');
         foreach ($this->car as $row)
         { //this runs only once
                       $car_name = $row->car_name;
                       $car_make = $row->car_make;
                       $car_model = $row->car_model;
                       $car_variant = $row->car_variant;
                       $car_vin = $row->car_vin;
                       $car_id = $row->id;
                       $car_plates = unserialize($row->car_plates);
        }
$car_plates = reset($car_plates);  //get 1st element
$owner = $row->owner;
$thisowner = ($owner !== Session::get('user_uuid')) ? UserModel::getUserNameByUUid($owner).': ' : '';
    $outstanding = unserialize($row->car_outstanding);
    if (!is_array($outstanding)) { $outstanding = false;  }
$odometer = '';
$odo = $row->car_odo;
for ($k=0; $k<strlen($odo); $k++) {$odometer.='<span class="bg-black white odo">'.$odo[$k].'</span>';}

$images = unserialize($row->images);
if ($images)
{
    $image_out='';    $script =  '';
    $image_meta='<div class="white bg-darken-4 absolute px1 top-0 right-0 nestedlink pointer jqtooltip z4" data-href="'.Config::get('URL').'car/edit_car_pictures/'.$car_id.'" title = "'._('CAR_PICTURES').'"><i class="icon-cog"> </i></div> ';
    $pic_dir = Config::get('CAR_IMAGE_PATH').$car_id.'/';
    $image_out = Config::get('URL') .'car/image/'.$car_id.'/'.$images[0];
    if (count($images) > 1)
    {
        $image_meta.= '<div class="white bg-darken-4 absolute bold px1 bottom-0 right-0">+'.(count($images)-1).'</div> ';
    }

    foreach($images AS $image) {
        if (file_exists($pic_dir.$image)) {
        $is_pdf = ($fullsize = getimagesize ($pic_dir.$image)) ? false : true;
        }

        if (!$is_pdf) {$script.="{
        src: '/car/image/$car_id/$image',
        w: {$fullsize[0]},
        h: {$fullsize[1]},
        msrc: '/car/image/$car_id/$image/120',
        },";} else {
            $image_meta.= '<div class="red-triangle absolute top-0 right-0"> </div> ';
            $image_meta.= '<div class="icon-file-pdf white absolute top-0 right-0"> </div> ';
        }
    }
}

$reminders = '';

 if($this->reminders) {
       $reminders.='<ul class="list-reset py1 border-bottom">';
       foreach ($this->reminders AS $reminder)
       {
       $reminders.='<li><b>'.strftime('%x', $reminder->time).'</b> '.$reminder->content.'</li>';
       }
       $reminders.='</ul>';
 }


?>
<div class="container">
<div class="box"><?php $this->renderFeedbackMessages(); ?></div>
<div class="mt1 clearfix"><?php include ('car_header.php'); ?></div>
<div><?php include ('car_events.php'); ?></div>

</div>

<?php $return_to = 'index'; include('odo_dlg.php'); ?>
