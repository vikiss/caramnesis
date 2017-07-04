<?php
       $units = $this->units;
       $tags = Config::get('AVAILABLE_TAGS');

                      $row = $this->car[0];
                       $car_name = $row->car_name;
                       $car_make = $row->car_make;
                       $car_model = $row->car_model;
                       $car_variant = $row->car_variant;
                       $car_vin = $row->car_vin;
                       $car_year = $row->car_year;
                       $car_id = $row->id;
                       $car_plates = unserialize($row->car_plates);
                       $car_plates = reset($car_plates);  //get 1st element
                       $owner = $row->owner;
                       $owner !== Session::get('user_uuid') ? $thisowner=UserModel::getUserNameByUUid($owner) : $thisowner='';
                       $outstanding = unserialize($row->car_outstanding);
                       if (!is_array($outstanding)) { $outstanding = false;  }
                       $odometer = '';
                       $odo = $row->car_odo;
                       for ($k=0; $k<strlen($odo); $k++) {
                            $odometer.='<span class="bg-black white odo">'.$odo[$k].'</span>';
                       }









      $images = unserialize($row->images); $image_out=''; $image_meta=''; $picstrip_out = ''; $i=1; $initial_img = '#';
       $image_info =  false;
     if ($images)
       {
                                $initial_img  = Config::get('URL') .'view/image/'.$car_id.'/'.$images[0];
                                $image_info =  getimagesize(Config::get('CAR_IMAGE_PATH').$car_id.'/'.$images[0]);

        }





       ?>    <meta property="og:url" content="<?= Config::get('URL').'view/car/'.$car_id; ?>" />
    <meta property="og:title" content="<?= $car_name; ?>" />
    <meta property="og:site_name" content="MotorGaga" />
    <meta property="og:description" content="<?= _('CARAMNESIS_CAR_PROFILE'); ?>" />
    <?php if ( $image_info ) { ?>
    <meta property="og:image" content="<?= $initial_img; ?>" />
    <meta property="og:image:secure_url" content="<?= $initial_img; ?>" />
    <meta property="og:image:width" content="<?= $image_info[0] ?>" />
    <meta property="og:image:height" content="<?= $image_info[1] ?>" />
    <meta property="og:image:type" content="<?= $image_info['mime'] ?>" />
    <?php }; ?>
    <meta property="og:type" content="article" />
    <meta property="fb:app_id" content="176273039511303" />
