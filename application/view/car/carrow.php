<?php
  $car = $this->car[0];
    $car_name = $car->car_name;
                       $car_make = $car->car_make;
                       $car_model = $car->car_model;
                       $car_variant = $car->car_variant;
                       $car_vin = $car->car_vin;
                       $car_id = $car->id;
                       $car_plates = unserialize($car->car_plates);
                       $car_plates = reset($car_plates);  //get 1st element
                       $owner = $car->owner;
                       $owner !== Session::get('user_uuid') ? $thisowner=UserModel::getUserNameByUUid($owner).': ' : $thisowner='';
                       $odometer = '';
                       $odo = $car->car_odo;
                       for ($k=0; $k<strlen($odo); $k++) {
                            $odometer.='<span class="bg-black white odo">'.$odo[$k].'</span>';
                       }


      $images = unserialize($car->images); $image_out=''; $image_meta=''; $picstrip_out = ''; $i=1; $initial_img = '#';
     if (count($images))
       {

                                $image_out = Config::get('URL') .'car/image/'.$car_id.'/'.$images[0].'/240';



        }
?>

  <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
<div class="clearfix border center bg-kcms p1 sm-hide">
        <div class="bold"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= $car_name; ?>"><?= $thisowner.$car_name; ?></a></div>

       <?php if ($image_out) {  ?>
              <a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= $car_name; ?>">
                     <img src="<?= $image_out; ?>" alt="<?= $car_name; ?>" />
              </a>
       <?php }; ?>


                    <div class="smallish mt2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= $car_name; ?>"><?= $car_make.' '.$car_model.' '.$car_variant; ?></a></div>
                    <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= $car_name; ?>"><?= $car_vin; ?></a></div>
                   <?php $car_plates = (array)$car_plates;
                           if  ($car_plates[0]) print '<div class="smallish mt1"><a href="'.Config::get('URL') . 'car/index/' . $car_id.'" title="'.$car_name.'">'.$car_plates[0].'</a></div>'; ?>
<div class="smallish mt1 pointer" id="odo_dlg_opnr"><?= $odometer; ?></div>



</div>
