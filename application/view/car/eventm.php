<div class="container">
    <div class="box">

        <?php if ($this->event) { 
$tags = Config::get('AVAILABLE_TAGS'); $units = $this->units;             
foreach ($this->event as $row) { //this runs only once
          
       //  print '<pre>'; print_r($row); print '</pre>';
           
    $event_content = $row['event_content'];
    $event_types = (array)$row['event_type'];
        $event_types = $event_types['values']; $event_types_out = '';
        if($event_types) { 
            foreach ($event_types AS $event_type) {
                $event_types_out.= '<span class="smallish bold kcms mr1 ">['._($event_type).']</span>';
            };
        };
        
    $event_time = (array) $row['event_time']; 
    $event_time = $event_time['seconds'];
    $entry_time = (array) $row['event_entered'];
    $entry_time =  $entry_time ['seconds'];
    
    
    $entry_data = unserialize($row['event_data']);
        $oldversions = ''; $oldversion = array();
        if ((key_exists('oldversions', $entry_data)) && ($entry_data['oldversions'])) {
                $oldversions = ' '._("EVENT_REVISIONS").': '; 	   
                $oversions = explode(',', $entry_data['oldversions']);
                foreach ($oversions as $oversion) {
                       $oversion = explode('-', $oversion);
                       if ($oversion[0]) {$oldversion[]=strftime('%x', intval($oversion[0]));}
                }
                $oldversions.=implode(', ', $oldversion);
        }
        
        $entrytime = '';
        if ($entry_time !== $event_time) {
        $entrytime = ' '._("EVENT_CREATED").': '.strftime('%x', $entry_time); 
        }
    
    
    
    $car_id = (array) $row['car']; 
    $car_id = $car_id['uuid'];
    $event_images = (array) $row['images'];
        if (isset($event_images['values'])) {
            $event_images = $event_images['values'];
            $images_list = '';
            $images_list = implode(',', $event_images);
        } else {
            $event_images = false;
        }
        
        $this->owner !== Session::get('user_uuid') ? $thisowner=UserModel::getUserNameByUUid($this->owner) : $thisowner=false;
                       
}                                            
        
         ?>
         
         
         <?php //prepare pic strip
                      $initial_img = ''; $initial_link = ''; $picstrip_out = ''; $i=1;
                   if ($event_images) { $first = true; 
                    foreach($event_images AS $image) {
                    $imglink = Config::get('URL').'car/imagepg/'.$this->owner.'/'.$car_id.'_'.$image;
                     $imgurl = Config::get('URL').'car/image/'.$this->owner.'/'.$car_id.'_'.$image;
                        if (CarModel::get_extension($image) == 'pdf')
                        { 
                        $picstrip_out.='<div class="border rounded mb1 overflow-hidden relative">';
                        $picstrip_out.='<div class="red-triangle absolute top-0 right-0"> </div>';
                        $picstrip_out.='<div class="icon-file-pdf white absolute top-0 right-0"> </div>';
                        $picstrip_out.='<a href="'.$imglink.'"><img src="'.$imgurl.'/120'.'" class="crop" /></a></div>';
                            
                        } else {
                    
                     if ($first) {$addclass = 'active'; $initial_img = $imgurl; $initial_link = Config::get('URL').'car/event/'.$this->event_id.'/1';
                     $first = false; } else { $addclass = ''; }
                        
                    $picstrip_out.='<div class="imgbutton border rounded mr1 inline-block overflow-hidden '.$addclass.'">';
                    $picstrip_out.='<a href="'.$imglink.'" data-url="'.$imgurl.'" data-ord="'.$i.'"><img src="'.$imgurl.'/120'.'" class="crop" /></a></div>';
                        }
                        $i++;
                    }
                    if (count($event_images) < 2) {$picstrip_out = '';}
                                } ?>
        
            
            
            
<div id="container" class="clearfix">
     <div class="col">
        <?php if ($initial_img) { ?>
        <div id="heroimg-container" class="relative border"><a id="heroimg-link" href="<?= $initial_img /*$initial_link*/; ?>"><img src="<?= $initial_img; ?>"  id="heroimg" /></a>
            <div id="heroimgcenter" class="absolute abscenter"></div>        
        </div>
        <?php }; ?>
    <div id="picstrip"><?= $picstrip_out; ?></div>
    <div id="piccount" class="hide"><?= count($event_images); ?></div>
    </div>

    <div class="col ml2">
        <?php if ($thisowner) { ?>
        <div><?= $thisowner; ?></div>
        <?php } ?>
        <div class=" "><?= strftime('%x', $event_time); ?><span class="smallish"><?= $entrytime.$oldversions; ?></span></div>
        <div class=" "><?= $event_types_out; ?></div>
        <?php if($row['event_odo']) { ?>
        <div class="inline"><?= $row['event_odo'].' '.$units->user_distance; ?></div>
        <?php } ?>
        <?php if($entry_data['amount']) { ?>
        <div class="inline"><?= $entry_data['amount'].' '._('CURRENCY_'.$units->user_currency); ?></div>
        <?php } ?>
        <div class=" "><?= $event_content; ?></div>
    </div>
       
</div>
        <?php } else { ?>
            <p>This event does not even exist.</p>
        <?php } ?>
    </div>
</div>
            