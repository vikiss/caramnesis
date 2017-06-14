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
                       $outstanding = unserialize($car->car_outstanding);
                       if (!is_array($outstanding)) { $outstanding = false;  }
                       $odometer = '';
                       $odo = $car->car_odo;
                       for ($k=0; $k<strlen($odo); $k++) {
                            $odometer.='<span class="bg-black white odo">'.$odo[$k].'</span>';
                       }

      $images = unserialize($car->images); $image_out=''; $image_meta=''; $picstrip_out = ''; $i=1; $initial_img = '#';
     if (count($images))
       { //we only show the first one and count
                             if (CarModel::get_extension($images[0]) == 'pdf')
                        {
                                $image_meta.= '<div class="red-triangle absolute top-0 right-0"> </div> ';
                                $image_meta.= '<div class="icon-file-pdf white absolute top-0 right-0"> </div> ';
                        }
                                $image_out = Config::get('URL') .'car/image/'.$car_id.'/'.$images[0].'/240';
                                $initial_img  = Config::get('URL') .'car/image/'.$car_id.'/'.$images[0];
                                $image_meta.= '<div class="white bg-darken-4 absolute px1 top-0 right-0 nestedlink pointer jqtooltip" data-href="'.Config::get('URL').'car/edit_car_pictures/'.$car_id.'" title = "'._('CAR_PICTURES').'"><i class="icon-cog"> </i></div> ';
                        if (count($images) > 1) {
                                $image_meta.= '<div class="white bg-darken-4 absolute bold px1 bottom-0 right-0">+'.(count($images)-1).'</div> ';
                                $first = true;
                                          foreach($images AS $image) {
                                                $imglink = Config::get('URL').'car/imagepg/'.$car_id.'/'.$image;
                                                 $imgurl = Config::get('URL').'car/image/'.$car_id.'/'.$image;
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
?>
  
  <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
  <div class="clearfix relative">
                     <div class="absolute top-0 right-0">
                            <div id="carindex_expiry_list" class="smallish right-align"></div>
                            <div class="mono smallish mt1 pointer" id="odo_dlg_opnr"><?= $odometer; ?></div>
                     </div>


       <?php if ($image_out) {  ?>
              <a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= $car_name; ?>">
                     <div class="pic120width bg-white relative"  style="background-image: url(<?= $image_out; ?>)"><?= $image_meta; ?></div>
              </a>
       <?php } else { ?>
                     <div class="pic120width bg-white relative center bg-kclite " >
                            &nbsp;<br />&nbsp;<br /><a class="py4" href="<?= Config::get('URL') . 'car/edit_car_pictures/' . $car_id; ?>" title="<?= _('CAR_PICTURES'); ?>"><i class="icon-camera"> </i></a>
                     </div>
       <?php }; ?>

                            <div class="bold mt2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= $car_name; ?>"><?= $thisowner.$car_name; ?></a></div>
                            <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= $car_name; ?>"><?= $car_make.' '.$car_model.' '.$car_variant; ?></a></div>
                            <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= $car_name; ?>"><?= $car_vin; ?></a></div>
                           <?php $car_plates = (array)$car_plates;
                           if  ($car_plates[0]) print '<div class="smallish mt1"><a href="'.Config::get('URL') . 'car/index/' . $car_id.'" title="'.$car_name.'">'.$car_plates[0].'</a></div>'; ?>


                  <?php
                  if (CarModel::checkAccessLevel($car_id ,Session::get('user_uuid')) >= 98) { ?>

                            <div class="mt2">
                             <p class= "bold m0"><?= _('TODO_LIST_HEADER'); ?></p>
                             <ul class="smallish list-reset">
                             <?php if ($outstanding) {
                                   foreach ($outstanding as $key => $value) {
                            $tstamp = explode(':', $key);
                            $link_event = urlencode(serialize(array('c' => $car_id, 't' => $tstamp[0], 'm' => $tstamp[1])));
                            $link_event = Config::get('URL') . 'car/event/' . $link_event;
                            print '<li><a href='.$link_event.'>'.CarModel::trunc($value, 5).'</a></li>';
                                   } } ?>
                              <li><a href="#" class="new_event_todo_opener"><?= _('ADD_NEW_TODO_LIST_ITEM'); ?></a></li>
                             </ul>
                            </div>
                            <div class="mt2 hide" id="carindex_my_car_list"></div>
                   <?php }; ?>
              </div>