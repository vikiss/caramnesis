        <?php if ($this->event) { 
$tags = Config::get('AVAILABLE_TAGS'); $units = $this->units;             
foreach ($this->event as $row) { //this runs only once
          
       //  print '<pre>'; print_r($row); print '</pre>';
           
    $event_content = $row['event_content'];
    $event_types = (array)$row['event_type'];
        $event_types = $event_types['values']; $event_types_out = '';
        if($event_types) { 
            foreach ($event_types AS $event_type) {
                $event_types_out.= '<span class="smallish bg-kcms white px1 mx1 rounded ">'._($event_type).'</span>';
            };
        };
    
    $car_id = (array) $row['car']; 
    $car_id = $car_id['uuid'];
    $car_name = CarModel::getCarName($car_id);
    
    $serialized_event_time = (array) $row['event_time'];
     $event_time = $serialized_event_time['seconds'];
     $event_microtime = $serialized_event_time['microseconds'];
     $event_id = urlencode(serialize(array('c' => $car_id, 't' => $event_time, 'm' => $event_microtime)));
    
    
    
    $entry_time = (array) $row['event_entered'];
    $entry_time =  $entry_time ['seconds'];
    
    
    $entry_data = unserialize($row['event_data']);
        $oldversions = ''; $oldversion = array();
        if ((key_exists('oldversions', $entry_data)) && ($entry_data['oldversions'])) {
                $oldversions = ' '._("EVENT_REVISIONS").": "; 	   
                $oversions = explode(',', $entry_data['oldversions']);
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
                      $initial_img = '';  $picstrip_out = ''; $i=1;
                   if ($event_images) { $first = true; 
                    foreach($event_images AS $image) {
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
                    if (count($event_images) < 2) {$picstrip_out = '';}
                                } ?>
<div class="container">
    <div class="box">        

<div class="absolute left-0 top-48 display-hide bg-darken-4 left-arrow py2 z4" id="previous-event-link"><a href="#"><i class="icon-left-open white"> </i></a></div>
<div class="absolute right-0 top-48 display-hide bg-darken-4 right-arrow py2 z4" id="next-event-link"><a href="#"><i class="icon-right-open white"> </i></a></div>
<div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>                
            
            
<div id="container" class="clearfix">
    
    <div class="py2 clearfix">
    <a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= $car_name; ?>"><?php
    if ($thisowner) { echo $thisowner.': '; } ?><strong><?= $car_name; ?></strong></a>
      <a href="#" class="jqtooltip" title="<?= $entrytime.$oldversions; ?>"><?= strftime('%x', $event_time); ?></a><span class="smallish"></span>
        <a href="<?= Config::get('URL') . 'car/edit_event/' . $event_id; ?>" title="<?= _("EDIT"); ?>"><i class="icon-edit"> </i></a>
    <div class="inline "><?= $event_types_out; ?></div>  
    </div>
    
     <div class=" ">
        <?php if ($initial_img) { ?>
        <div id="heroimg-container" class="relative "><a id="heroimg-link" href="<?= $initial_img; ?>"><img src="<?= $initial_img; ?>"  id="heroimg"  /></a>
            <div id="heroimgcenter" class="absolute abscenter"></div>        
        </div>
        <?php }; ?>
    <div id="picstrip"><?= $picstrip_out; ?></div>
    <div id="piccount" class="hide"><?= count($event_images); ?></div>
    </div>

    <div class=" ">
        
        
        <?php if($row['event_odo']) { ?>
        <div class="inline"><?= $row['event_odo'].' '.$units->user_distance; ?></div>
        <?php } ?>
        <?php if(intval($entry_data['amount']) > 0) { ?>
        <div class="inline"><?= $entry_data['amount'].' '._('CURRENCY_'.$units->user_currency); ?></div>
        <?php } ?>
        <div class=" "><?= nl2br($event_content); ?></div>
    </div>
    
    
      <?php
                  if ($this->outstanding) {  ?>
                      <div class="btn btn-primary mb1 mt4 black bg-kcms " ><a href="<?= Config::get('URL') . 'car/remove_todo/' . $event_id; ?>"><?= _('MARK_THIS_AS_DONE'); ?></a></div>
                  <?php } ; ?> 
       
</div>
        <?php } else { ?>
            <p>This event does not even exist.</p>
        <?php } ?>
    </div>
</div>
<?php include 'fsgallery.php'; ?>

<script>
/* <![CDATA[ */
var event_id = '<?= $event_id; ?>'; var event_car_id = '<?= $car_id; ?>';
/* ]]> */
</script>
            