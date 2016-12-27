<?php

/*
 *
 *šitas perrašė paveikslėlius į naują struktūrą
$database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT user_uuid FROM users;");
        $query->execute();
        $result = $query->fetchAll();
        foreach ( $result AS $user) {
            $path = Config::get('CAR_IMAGE_PATH');
            $userfolder = $path.$user->user_uuid;
            if (is_dir($userfolder)) {
                print '<h2>'.$user->user_uuid.'</h1>';
                $cars = CarModel::getCars($user->user_uuid);
                foreach($cars as $car) {
                    $car_id = (array) $car['id']; 
                    $car_id = $car_id['uuid'];
                    print ($car_id).'<br />';
                    
                    foreach (glob($userfolder.'/'.$car_id."*.*") as $filename) {
                        $fname = basename($filename);
                        $fname = str_replace($car_id, '', $fname);
                        $fname = str_replace('_', '', $fname);
                        echo $path.$car_id.'/'.$fname. "<br />";
                        //if (!file_exists($path.$car_id)) { mkdir($path.$car_id, 0775, true);  }
                        if (copy($filename, $path.$car_id.'/'.$fname)) {
                            echo $path.$car_id.'/'.$fname. "copied<br />";
                        } else {
                            echo $path.$car_id.'/'.$fname. "went wrong<br />";
                        }
                        }
                    
                    
                    if ($handle = opendir($userfolder)) {
                        while (false !== ($file = readdir($handle))) {
                                                print ($file).'<br />';
                                                }
                        closedir($handle);
                    
                    } 
                    
                    
                    
                    
                    print '<br />';
                }
                
                
                print '<hr />';
            }
           
        }

*/







?>