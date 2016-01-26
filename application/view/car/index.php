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

    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

    
    </div>
    
      <div><?php
       print $car_make.' | '.$car_model.' | '.$car_vin.' | '.$car_plates[0]; 
       ?></div>
    
    
    <div>
    <form method="post" enctype="multipart/form-data" action="<?php echo Config::get('URL');?>car/create_event" id="new_event_form">
                <label><?php echo Text::get("EVENT_CONTENT"); ?>: </label><textarea name="event_content" class="block col-12 field mt1" required></textarea>
                <label><?php echo Text::get("EVENT_TYPE"); ?>: </label><input type="text" name="event_type" class="block col-4 field mt1 " />
                <label><?php echo Text::get("EVENT_ODO"); ?>: </label><input type="number" min="0" step="1" name="event_odo" class="block col-4 field mt1 " />
                <label><?php echo Text::get("EVENT_DATE"); ?>: </label><input type="date" name="event_date" class="block col-4 field mt1 " value="<?php echo date('Y-m-d'); ?>" />
                                                      
<script>
$.tools.dateinput.localize("lt",  {
   months:        'sausis,vasaris,kovas, balandis,gegužė,birželis,liepa,' +
                  'rugpjūtis,rugsėjis,spalis,lapkritis,gruodis',
   shortMonths:   'sau,vas,kov,bal,geg,bir,lie,' +
                  'rugp,rugs,lap,gru',
   days:          'sekmadienis,pirmadienis,antradienis,trečiadienis,ketvirtadienis,penktadienis,šeštadienis',
   shortDays:     'Sk,Pr,An,Tr,Kv,Pn,Št'
});


  $(":date").dateinput({
  lang: 'lt',
  format: 'yyyy-mm-dd',
  offset: [0, 0],
  selectors: true, 
  firstDay: 1
});
</script>

<input type="file" name="fileinput[]" id="fileinput" multiple="multiple"  accept="image/*" capture="camera" />
       

		<div id="dropbox">
			<span class="message">Drop images here to upload. <br /><i>(they will only be visible to you)</i></span>
		</div>

        <!-- Including The jQuery Library -->
		

		<!-- Including the HTML5 Uploader plugin -->
		<script src="/js/jquery.filedrop.js"></script>

		<!-- The main script file -->
        <script src="/js/script.js"></script>     
     
     


                <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                <input type="hidden" name="user_images" id="user_images" value = "" />
                <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value='<?php echo Text::get("SAVE"); ?>' autocomplete="off" />
            </form>
            
    </div>
    
    <div>
     <?php 

     if ($this->events) {
     
     foreach ($this->events as $event) {
     
   //  print '<pre>'; print_r($event); print '</pre>';
     $serialized_event_time = (array) $event['event_time'];
     $event_time = $serialized_event_time['seconds'];
     $event_microtime = $serialized_event_time['microseconds'];
     $event_id = urlencode(serialize(array('car' => $car_id, 'time' => $event_time, 'microtime' => $event_microtime)));
     
     $entry_time = (array) $event['event_entered'];
     $entry_time =  $entry_time['seconds'];
     
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
     <a href="<?= Config::get('URL') . 'car/edit_event/' . $event_id; ?>" class="h5 btn btn-primary mb1 bg-aqua"><?php echo Text::get("EDIT"); ?></a>
     </p>
     </div> 
           
                       
<?php }}            ?>
    </div>
    
</div>

