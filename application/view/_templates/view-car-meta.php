<?php
       $units = $this->units;
       $tags = Config::get('AVAILABLE_TAGS');
     
                      $row = $this->car[0];
                       $car_name = $row->car_name;
                       $car_make = $row->car_make;
                       $car_model = $row->car_model;
                       $car_variant = $row->car_variant;
                       $car_vin = $row->car_vin;
                       $car_id = $row->id;
                       $car_plates = unserialize($row->car_plates);
                       $car_plates = reset($car_plates);  //get 1st element
                       $owner = $row->owner;
                       $owner !== Session::get('user_uuid') ? $thisowner=UserModel::getUserNameByUUid($owner).': ' : $thisowner='';
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
       { //we only show the first one and count
                             if (CarModel::get_extension($images[0]) == 'pdf')
                        {
                                $image_meta.= '<div class="red-triangle absolute top-0 right-0"> </div> ';
                                $image_meta.= '<div class="icon-file-pdf white absolute top-0 right-0"> </div> ';
                        }
                                $image_out = Config::get('URL') .'view/image/'.$car_id.'/'.$images[0].'/240';
                                $initial_img  = Config::get('URL') .'view/image/'.$car_id.'/'.$images[0];
                                $image_info =  getimagesize(Config::get('CAR_IMAGE_PATH').$car_id.'/'.$images[0]);
                        if (count($images) > 1) {
                                $image_meta.= '<div class="white bg-darken-4 absolute bold px1 bottom-0 right-0">+'.(count($images)-1).'</div> ';
                                $first = true;
                                          foreach($images AS $image) {
                                                $imglink = Config::get('URL').'view/imagepg/'.$car_id.'/'.$image;
                                                 $imgurl = Config::get('URL').'view/image/'.$car_id.'/'.$image;
                                                    if (CarModel::get_extension($image) == 'pdf')
                                                    { 
                                                    $picstrip_out.='<div class="border rounded mr1 inline-block overflow-hidden relative">';
                                                    $picstrip_out.='<div class="red-triangle absolute top-0 right-0"> </div>';
                                                    $picstrip_out.='<div class="icon-file-pdf white absolute top-0 right-0"> </div>';
                                                    $picstrip_out.='<a href="'.$imglink.'"><img src="'.$imgurl.'/120'.'" class="crop" /></a></div>';
                                                        
                                                    } else {
                                                
                                                 if ($first) {$addclass = 'active'; $initial_img = $imgurl;  $first = false; } else { $addclass = ''; }
                                                    
                                                $picstrip_out.='<div class="imgbutton border rounded mr1 inline-block overflow-hidden '.$addclass.'">';
                                                $picstrip_out.='<a href="'.$imglink.'" data-url="'.$imgurl.'" data-ord="'.$i.'"><img src="'.$imgurl.'/120'.'" class="crop" /></a></div>';
                                                    }
                                                    $i++;
                                                }
                                
                                
                                
                                
                        }
        }
        
         
        
        
         
       ?>    <meta property="og:url" content="<?= Config::get('URL').'view/car/'.$car_id; ?>" />
    <meta property="og:title" content="<?= $car_name; ?>" />
    <meta property="og:site_name" content="CarAmnesis" />
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