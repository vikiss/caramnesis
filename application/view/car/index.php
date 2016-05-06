<div class="container">

<?php

        $units = Config::get('USER_UNITS');
       $tags = Config::get('AVAILABLE_TAGS');
     
         foreach ($this->car as $row) { //this runs only once
                       $car_name = $row['car_name'];
                       $car_make = $row['car_make'];
                       $car_model = $row['car_model'];
                       $car_vin = $row['car_vin']; 
                       $car_id = (array) $row['id']; 
                       $car_id = $car_id['uuid'];
                       $car_images = (array) $row['images'];
                         if (isset($car_images['values'])) {
                         $car_images = $car_images['values'];
                         $car_image =  reset($car_images);} else {
                         $car_images = false; } 
                       $car_plates = (array) $row['car_plates'];}
                       $car_plates = reset($car_plates);  //get 1st element
                       
         
       ?>

    <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
    
    <div class="clearfix">
      <div class="left mr2"><?php
      if ($car_images) 
      print '<a href="'.Config::get('URL').'car/imagepg/'.Session::get('user_uuid').'/'.$car_id.'_'.$car_image.'" rel="#overlay"><img src="/car/image/'.Session::get('user_uuid').'/'.$car_id.'_'.$car_image.'/120" /></a> ';
       ?></div>
      <div class="icon-cab left mr2"> <strong><?= $car_name; ?></strong> 
      <span class="smallish"><?php print $car_make.' '.$car_model.' | '.$car_vin;
       if  ($car_plates[0]) print ' | '.$car_plates[0];
       ?></span>
       <a href="<?= Config::get('URL') . 'car/edit_car/' . $car_id; ?>" title="<?= _("EDIT"); ?>"><i class="icon-cog"> </i></a>
       </div>
       <div class="right">
        <div id="car_data_container" class="small">
        <?php $car_data = $this->car_data; include ('car_data_bit_table.php');?>
        </div>
        <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
       </div>
       
       </div>
      
    
      <?php include('event_form.php'); ?>
  
    <div>
     <?php 

     if ($this->events) {
     
     foreach ($this->events as $event) {
     
     //print '<pre>'; print_r($event); print '</pre>';
     $serialized_event_time = (array) $event['event_time'];
     $event_time = $serialized_event_time['seconds'];
     $event_microtime = $serialized_event_time['microseconds'];
   //  $event_id = urlencode(serialize(array('car' => $car_id, 'time' => $event_time, 'microtime' => $event_microtime)));
     
     $serialized_entry_time = (array) $event['event_entered'];
     $entry_time =  $serialized_entry_time['seconds'];
     $entry_microtime = $serialized_entry_time['microseconds'];
    $event_id = urlencode(serialize(array('c' => $car_id, 't' => $event_time, 'm' => $event_microtime)));
                                         //car           //time               /microtime
     $entry_data = unserialize($event['event_data']);
          
     $images = $event['images'];
     $event_type = '';
     $event_types = $event['event_type'];
     if ($event_types) {foreach ($event_types AS $type) {$event_type=$type;};}
     
     
     
     $oldversions = ''; 
                       if ((key_exists('oldversions', $entry_data)) && ($entry_data['oldversions'])) {
                      $oldversions = ' '._("EVENT_REVISIONS"); 	   
                       $oversions = explode(',', $entry_data['oldversions']);
                       foreach ($oversions as $oversion) {
                       $oversion = explode('-', $oversion);
                       if ($oversion[0]) {$oldversions.=' '.date('Y-m-d H:i:s', intval($oversion[0]));
                      }
                       }
                       }
     
     
      ?>
    
     
  <div class="bg-white p2 mt2 fauxfield clearfix relative">
    <div class="clearfix">
     <div class="col col-3 mr2">
       <?php if($event_type) { ?>
       <ul class="tags list-reset pb2">
        <li><a href="#"><?= _($event_type); ?></a></li>
       </ul><?php }; ?> 
       <div class="bold"><?= date('Y-m-d', $event_time); ?></div>
       <?php if($event['event_odo']) { ?><div><?= $event['event_odo'].' '.$units['distance']; ?></div><?php }; ?>
       <?php if($entry_data['amount']) { ?><div><?= $entry_data['amount'].' '.$units['currency']; ?></div><?php }; ?>
       <div class="small absolute bottom-0 left-0 px2"><?= _("EVENT_CREATED").' '.date('Y-m-d H:i:s', $entry_time).$oldversions; ?> </div>
     </div>
     <div class="col col-5 mr2 ">
     <?= $event['event_content']; ?>
     </div>
     <div class="col ">
     
                   <?php
                   if ($images) {
                    foreach($images AS $image) {
                    
                  if (CarModel::get_extension($image) == 'pdf') {
                  print '<a href="'.Config::get('URL').'car/imagepg/'.Session::get('user_uuid').'/'.$car_id.'_'.$image.'" rel="#overlay"><div class="icon-file-pdf"> </div></a> ';
                  print '<a href="'.Config::get('URL').'car/imagepg/'.Session::get('user_uuid').'/'.$car_id.'_'.$image.'" rel="#overlay"><img src="/car/image/'.Session::get('user_uuid').'/'.$car_id.'_'.$image.'/120" /></a> ';
                  } else {  
                    
                    
                    print '<a href="'.Config::get('URL').'car/imagepg/'.Session::get('user_uuid').'/'.$car_id.'_'.$image.'" rel="#overlay"><img src="/car/image/'.Session::get('user_uuid').'/'.$car_id.'_'.$image.'/120" /></a> ';
                          } 
              
                                                }
                                } ?>
     
    
     </div>
    </div>
      <a href="<?= Config::get('URL') . 'car/edit_event/' . $event_id; ?>" class="h5 btn btn-primary mt1 black bg-kcms right "><?php echo _("EDIT"); ?></a>
  </div> 
           
                       
<?php }}            ?>
    </div>
</div>


<div class="apple_overlay" id="overlay">
  <div class="contentWrap"></div>
</div>

