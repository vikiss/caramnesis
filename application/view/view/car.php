<div class="container">

<?php       
       
       
         foreach ($this->car as $row) { //this runs only once
                       $car_name = $row['car_name'];
                       $car_make = $row['car_make'];
                       $car_model = $row['car_model'];
                       $car_vin = $row['car_vin']; 
                       $car_id = (array) $row['id']; 
                       $car_id = $car_id['uuid'];
                       $owner_id = (array) $row['owner']; 
                       $owner_id = $owner_id['uuid'];
                       $car_images = (array) $row['images'];
                         if (isset($car_images['values'])) {
                         $car_images = $car_images['values'];
                         } else {
                         $car_images = false; } 
                       $car_plates = (array) $row['car_plates'];}
                       $car_plates = reset($car_plates);  //get 1st element
       
       
        $units = Config::get('USER_UNITS'); //GET USERS UNITS
       $tags = Config::get('AVAILABLE_TAGS');
          
       ?>

   
    <div class="clearfix">
      <div class="left mr2"><?php
      if ($car_images) { foreach ($car_images as $car_image) { 
      print '<a href="'.Config::get('URL').'view/imagepg/'.$owner_id.'/'.$car_id.'_'.$car_image.'" ><img src="/view/image/'.$owner_id.'/'.$car_id.'_'.$car_image.'/120" /></a><br /> ';
      }}
       ?></div>
      <div class="icon-cab left mr2"> <strong><?= $car_name; ?></strong> 
      <span class="smallish"><?php print $car_make.' '.$car_model.' | '.$car_vin;
       if  ($car_plates[0]) print ' | '.$car_plates[0];
       ?></span>
             </div>
       <div class="right">
        <div id="car_data_container" class="small">
        <?php $car_data = $this->car_data; include ('car_data_bit_table.php');?>
        </div>
        <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
       </div>
       
       </div>
      
    
  
    
</div>




