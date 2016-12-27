<?php
$auth_car_list = unserialize(urldecode($this->auth_car_list));

if ($this->cars) { ?>


<?php
foreach ($this->cars as $row) {   
      $id = (array) $row['id']; //typecast cassandra object into array
                       
      print $id['uuid'];
      print '<br />';
      $plates = (array) $row['car_plates'];
      $flatplates = '';
      if (array_key_exists('values', $plates))
        {$plates = $plates['values'];
      
            $flatplates = implode(',',$plates);
            
            }
      
      print $flatplates.'<br />';
      print $row['car_vin'].'<br />';
      $count = CarModel::updateCarLookupEntry($id['uuid'], $flatplates, $row['car_vin']); print $count;
      print ' <hr />';
      
      
      ?>
                  
                 
<?php  } } else echo '<h1>'._("NO_CARS_FOUND").'</h1>' ?>



                                 

                       