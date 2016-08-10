<?php
$auth_car_list = unserialize(urldecode($this->auth_car_list));

if ($this->cars) { ?>
<?php
foreach ($this->cars as $row) {   
      $id = (array) $row['id']; //typecast cassandra object into array
      $car_images = (array) $row['images'];
                         if (isset($car_images['values'])) {
                         $car_images = $car_images['values'];
                         $car_image =  reset($car_images);} else {
                         $car_images = false; $car_image = '';}
                          
                        
      
      $plates_or_vin = '';
      if ($plates_or_vin = (array) $row['car_plates']) {$plates_or_vin=$plates_or_vin['values'][0]; }
      if (is_array($plates_or_vin)) $plates_or_vin=$row['car_vin'];
      if (in_array($id['uuid'],$auth_car_list)) {$already_authorised = true;} else {$already_authorised = false;}
      if ($already_authorised) { 
      $link = ' href="#"'; } else {
      $link = ' href="#" class="request_car_access" data-id="'.$id['uuid'].'" title="'.$row['car_name'].' ('.$row['car_make'].' '.$row['car_model'].' '.$plates_or_vin.')"';            
      }
      ?>
<div class="mb1 mr1 p1 black bg-kclite left fauxfield square center truncate requestparent<?php if ($already_authorised) echo ' muted'; ?>">
      
<a<?= $link; ?>><?= $row['car_name']; ?></a>
      
<?php if ($car_images) { ?>
<div><a<?= $link; ?>>
<?php print '<img class="crop" src="/car/image/'.$this->owner.'/'.$id['uuid'].'_'.$car_image.'/120" />'; ?>
</a></div>
<?php }; ?>
</div>
                  
                 
<?php  } } else echo '<h1>'._("NO_CARS_FOUND").'</h1>' ?>



                                 

                       