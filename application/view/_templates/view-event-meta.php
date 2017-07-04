        <?php if ($this->event) {
$tags = Config::get('AVAILABLE_TAGS'); $units = $this->units;
foreach ($this->event as $row) { //this runs only once

                 $event_content = $row->event_content;
                 $event_types = unserialize($row->event_type);
                 $event_types_out = '';
                 if($event_types) {
                     foreach ($event_types AS $event_type) {
                         $event_types_out.= '<span class="smallish bg-kcms white px1 mx1 rounded ">'._($event_type).'</span>';
                     };
                 };

                 $event_id = $row->event_time;
                 $serialized_event_time =  CarModel::parsetimestamp($event_id);
                 $event_time = $serialized_event_time['seconds'];
                 $event_microtime = $serialized_event_time['microseconds'];
                 //$event_id = urlencode(serialize(array('c' => $car_id, 't' => $event_time, 'm' => $event_microtime)));

                 $event_entered = $row->event_entered;
                 $serialized_entry_time =  CarModel::parsetimestamp($event_entered);
                 $entry_time =  $serialized_entry_time['seconds'];
                 $entry_microtime = $serialized_entry_time['microseconds'];

                 $car_id = $row->car;
                 $event_data = unserialize($row->event_data);
                 $car_name = CarModel::getCarName($car_id);

                 $event_images = unserialize($row->images);
                 if (is_array($event_images)) {
                 $images_list = '';
                 $images_list = implode(',', $event_images);} else {
                 $event_images = false; }

                 $oldversions = ''; $oldversion = array();
                 if ((key_exists('oldversions', $event_data)) && ($event_data['oldversions'])) {
                         $oldversions = ' '._("EVENT_REVISIONS").": ";
                         $oversions = explode(',', $event_data['oldversions']);
                         foreach ($oversions as $oversion) {
                                $oversion = explode('-', $oversion);
                                if ($oversion[0]) {$oldversion[]=strftime('%x', intval($oversion[0]));}
                         }
                         $oldversions.=implode(", ", $oldversion);
                 }

                 $entrytime = '';
                 if ($entry_time !== $event_time) {
                 $entrytime = ' '._("EVENT_CREATED").': '.strftime('%x', $entry_time)."|";
                 }

        $this->owner !== Session::get('user_uuid') ? $thisowner=UserModel::getUserNameByUUid($this->owner) : $thisowner=false;

}

        //prepare pic strip
                      $initial_img = '';  $picstrip_out = ''; $i=1;
                   if ($event_images) { $first = true;
                    foreach($event_images AS $image) {
                    $imglink = Config::get('URL').'view/imagepg/'.$car_id.'/'.$image;
                     $imgurl = Config::get('URL').'view/image/'.$car_id.'/'.$image;
                        if (CarModel::get_extension($image) == 'pdf')
                        {
                        $picstrip_out.='<div class="border rounded mr1 inline-block overflow-hidden relative">';
                        $picstrip_out.='<div class="red-triangle absolute top-0 right-0"> </div>';
                        $picstrip_out.='<div class="icon-file-pdf white absolute top-0 right-0"> </div>';
                        $picstrip_out.='<a href="'.$imglink.'"><img src="'.$imgurl.'/120'.'" class="crop" /></a></div>';

                        } else {

                     if ($first) {
                        $addclass = 'active'; $initial_img = $imgurl;  $first = false;
                          $image_info =  getimagesize(Config::get('CAR_IMAGE_PATH').$car_id.'/'.$image);
                     } else { $addclass = ''; }

                    $picstrip_out.='<div class="imgbutton border rounded mr1 inline-block overflow-hidden '.$addclass.'">';
                    $picstrip_out.='<a href="'.$imglink.'" data-url="'.$imgurl.'" data-ord="'.$i.'"><img src="'.$imgurl.'/120'.'" class="crop" /></a></div>';
                        }
                        $i++;
                    }
                    if (count($event_images) < 2) {$picstrip_out = '';}
                                }
        };
        ?>
<meta property="og:url" content="<?= Config::get('URL').'view/event/'.$event_id; ?>" />
    <meta property="og:title" content="<?= $car_name; ?>" />
    <meta property="og:site_name" content="MotorGaga" />
    <meta property="og:description" content="<?= ViewModel::limit_teaser($event_content); ?>" />
    <?php if ( $event_images ) { ?>
    <meta property="og:image" content="<?= $initial_img; ?>" />
    <meta property="og:image:secure_url" content="<?= $initial_img; ?>" />
    <meta property="og:image:width" content="<?= $image_info[0] ?>" />
    <meta property="og:image:height" content="<?= $image_info[1] ?>" />
    <meta property="og:image:type" content="<?= $image_info['mime'] ?>" />
    <?php }; ?>
    <meta property="og:type" content="article" />
    <meta property="fb:app_id" content="176273039511303" />
