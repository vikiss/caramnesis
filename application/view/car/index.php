<div class="container">

<?php

     
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



    <h1><?php echo $car_name; ?></h1>

    <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
    
      <div><?php
       print $car_make.' | '.$car_model.' | '.$car_vin.' | '.$car_plates[0]; 
       ?></div>
    
    
    <div><?php include('event_form.php'); ?></div>
    
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
     $entry_amount = $entry_data['amount'];
     
     
     $images = $event['images'];
     if ($images) {
      foreach($images AS $image) {
      print '<img src="/car/image/'.$car_id.'_'.$image.'" /> ';
      }
     
     }
     
      
     
     
      /*[images] => Cassandra\Collection Object
        (
            [values] => Array
                (
                    [0] => 1452385425-981.jpg
                    [1] => 1452385430-093.jpg
                )

        )*/
     
      ?>
     
     <div><h3><?= $event['event_content']; ?></h3>
     <p><?= $event['event_type']; ?>
     <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('Y-m-d', $event_time); ?></span>
     <span>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo date('Y-m-d H:i:s', $entry_time); ?></span>
     <span>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $event['event_odo']; ?></span>
     <span>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $entry_amount; ?></span>
     <a href="<?= Config::get('URL') . 'car/edit_event/' . $event_id; ?>" class="h5 btn btn-primary mb1 bg-aqua"><?php echo Text::get("EDIT"); ?></a>
     </p>
     </div> 
           
                       
<?php }}            ?>
    </div>
    
</div>

