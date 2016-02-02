<div class="container">

<?php
          $units = array(
          'distance' => 'km',
          'currency' => '&euro;',
          );
     
         foreach ($this->car as $row) { //this runs only once
                       $car_name = $row['car_name'];
                       $car_make = $row['car_make'];
                       $car_model = $row['car_model'];
                       $car_vin = $row['car_vin']; 
                       $car_id = (array) $row['id']; 
                       $car_id = $car_id['uuid'];
                       $car_plates = (array) $row['car_plates'];}
                       $car_plates = reset($car_plates);  //get 1st element                                            
       ?>



    <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
    
    
    <h1><?php echo $car_name; ?></h1>
    
      <div class="icon-cab"> <?php
       print $car_make.' '.$car_model.' | '.$car_vin.' | '.$car_plates[0]; 
       ?></div>
    
    
    <div class="bg-kclite p2 mt2 fauxfield relative"><?php include('event_form.php'); ?></div>
  
    <div>
     <?php 

     if ($this->events) {
     
     foreach ($this->events as $event) {
     
    // print '<pre>'; print_r($event); print '</pre>';
     $serialized_event_time = (array) $event['event_time'];
     $event_time = $serialized_event_time['seconds'];
     $event_microtime = $serialized_event_time['microseconds'];
     $event_id = urlencode(serialize(array('car' => $car_id, 'time' => $event_time, 'microtime' => $event_microtime)));
     
     $entry_time = (array) $event['event_entered'];
     $entry_time =  $entry_time['seconds'];
     
     $entry_data = unserialize($event['event_data']);
          
     $images = $event['images'];
     
     
      ?>
    
     
  <div class="bg-white p2 mt2 fauxfield clearfix relative">
    <div class="clearfix">
     <div class="col col-3 mr2">
       <?php if($event['event_type']) { ?>
       <ul class="tags list-reset pb2">
        <li><a href="#"><?= $event['event_type']; ?></a></li>
       </ul><?php }; ?> 
       <div class="bold"><?= date('Y-m-d', $event_time); ?></div>
       <?php if($event['event_odo']) { ?><div><?= $event['event_odo'].' '.$units['distance']; ?></div><?php }; ?>
       <?php if($entry_data['amount']) { ?><div><?= $entry_data['amount'].', '.$units['currency']; ?></div><?php }; ?>
       <div class="small absolute bottom-0 left-0 px2"><?= Text::get("EVENT_CREATED").' '.date('Y-m-d H:i:s', $entry_time); ?></div>
     </div>
     <div class="col col-5 mr2 ">
     <?= $event['event_content']; ?>
     </div>
     <div class="col border">
     
                   <?php
                   if ($images) {
                    foreach($images AS $image) {
                    print '<img src="/car/image/'.Session::get('user_uuid').'/'.$car_id.'_'.$image.'/120" /> '; 
              
                                                }
                                } ?>
     
     
     </div>
    </div>
      <a href="<?= Config::get('URL') . 'car/edit_event/' . $event_id; ?>" class="h5 btn btn-primary mt1 black bg-kcms right "><?php echo Text::get("EDIT"); ?></a>
  </div> 
           
                       
<?php }}            ?>
    </div>
</div>