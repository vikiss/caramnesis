<?php         $images_list = ''; 
         foreach ($this->car as $row) { //this runs only once
                       $car_name = $row->car_name;
                       $car_make = $row->car_make;
                       $car_model = $row->car_model;
                       $car_vin = $row->car_vin;
                       $car_year = $row->car_year;
                       $car_id =  $row->id; 
                       $car_plates = unserialize($row->car_plates);
                       $car_make_id = $row->car_make_id; 
                       $car_model_id = $row->car_model_id; 
                       $car_variant_id = $row->car_variant_id;
                       $car_variant = $row->car_variant;
                       $attr_images = $row->attr_images;
                      // $car_access = (array) $row['car_access']; //deprecated
                      // if ($car_access) $car_access = reset($car_access);
                       $car_images = unserialize($row->images);
                       if (is_array($car_images)) {
                       $images_list = implode(',', $car_images);} else {
                       $car_images = false; }
                       $image_ord = '';
                       $owner = $row->owner;
                       $expiries = $row->car_expiries;
                       $outstanding = $row->car_outstanding;
                                        }
                      $units = $this->units; $tags = Config::get('AVAILABLE_TAGS'); $no_sho_tags = Config::get('NO_SHO_TAGS');
                      ?>