<div class="container">
    <h1><?= _("EDIT_CAR"); ?></h1>

    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <?php if ($this->car) {
        
          $images_list = '';
                   
         foreach ($this->car as $row) { //this runs only once
          
        
                       $car_name = $row['car_name'];
                       $car_make = $row['car_make'];
                       $car_model = $row['car_model'];
                       $car_vin = $row['car_vin']; 
                       $car_id = (array) $row['id']; 
                       $car_id = $car_id['uuid'];
                       $car_plates = (array) $row['car_plates'];
                       $car_plates = reset($car_plates);  //get 1st element
                       $car_access = (array) $row['car_access'];
                       if ($car_access) $car_access = reset($car_access);
                       $car_data = unserialize($row['car_data']);
                       $car_images = (array) $row['images'];
                       if (isset($car_images['values'])) {
                       $car_images = $car_images['values'];
                       $images_list = implode(',', $car_images);} else {
                       $car_images = false; }  
                       
                        
                
             }
             
          
          if ($this->public_access) {$public_access=true;} else {$public_access=false;}
          
            $units = Config::get('USER_UNITS'); $tags = Config::get('AVAILABLE_TAGS'); $no_sho_tags = Config::get('NO_SHO_TAGS'); include('car_form.php');
         ?>   
            
        <?php } else { ?>
            <p>This car does not exist.</p>
        <?php } ?>
    </div>
</div>
            