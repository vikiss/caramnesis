<?php

//todo: clear gravatar stuff from session

class CarModel
{
    
    public static function getCars()
    {
	     $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT * FROM cars WHERE owner = ?');
       $selectstuff = array('owner' => new Cassandra\Uuid(Session::get('user_uuid')));
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else return($result);
    }
 




    public static function createCar($car_name, $car_make, $car_model, $car_vin, $car_plates)
    {
    
    //todo: neleidziam registruoti dvieju masinu vienodu nickname
        if (!$car_name || strlen($car_name) == 0) {
            Session::add('feedback_negative', _('FEEDBACK_CAR_CREATION_FAILED'));
            return false;
        }

       $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('INSERT INTO cars (id, car_make, car_model, car_name, car_plates, car_vin, owner) VALUES (uuid(),?,?,?,?,?,?)');
       $plateslist = new Cassandra\Collection(Cassandra::TYPE_TEXT);
       $plateslist->add($car_plates);
       $insertstuff = array(
       'car_name' => $car_name,
       'car_make' => $car_make,
       'car_model' => $car_model,
       'car_vin' => $car_vin,
       'car_plates' => $plateslist,
       'owner' => new Cassandra\Uuid(Session::get('user_uuid'))
       );
       $options = new Cassandra\ExecutionOptions(array('arguments' => $insertstuff));
       if ($casssession->execute($statement, $options)   ) {
             
       return true; }

        // default return
        Session::add('feedback_negative', _('FEEDBACK_CAR_CREATION_FAILED'));
        return false;
    }
    
     public static function getCar($car_id)
    {
    /*
CREATE TABLE bxamnesis.cars (
    id uuid PRIMARY KEY,
    car_data text,
    car_make text,
    car_model text,
    car_name text,
    car_plates list<text>,
    car_vin text,
    images list<text>,
    owner uuid
)
    */
	     $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT id, car_access, car_data, car_make, car_model, car_name, car_plates, car_vin, images, owner FROM cars WHERE id = ?');
       $selectstuff = array('id' => new Cassandra\Uuid($car_id));
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else return($result);
    }
    
    public static function getCarPlates($car_id)
    {
	   $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT car_plates FROM cars WHERE id = ?');
       $selectstuff = array('id' => new Cassandra\Uuid($car_id));
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else {
       	   $plates='';
       foreach ($result as $row) {$plates = $row;}
       $result = $plates['car_plates'];
       		$plates=array();
       foreach ($result as $row) {$plates[] = $row;}
       return($plates);
       };
    }
    
     public static function getCarName($car_id)
    {
	   $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT car_name FROM cars WHERE id = ?');
       $selectstuff = array('id' => new Cassandra\Uuid($car_id));
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else {
          $result = $result[0];
       	   return $result['car_name'];
       };
    }
    
    
    public static function newAccessNode($car, $level) {
    
    $database = DatabaseFactory::getFactory()->getConnection();
		$query = $database->prepare("INSERT INTO car_access (user, car, level, user_name, car_name) VALUES (:user, :car, :level, :user_name, :car_name)");
		$query->execute(array(
			':user' => Session::get('user_uuid'),
			':car' => $car,
      ':level' => $level,
      ':user_name' => Session::get('user_name'),
      ':car_name' => self::getCarName($car)
		));
    	$count =  $query->rowCount();
		if ($count == 1) {
			return true;
		}

		return false;
    
    
    
    }
    
    
    
    
    public static function removeAccessNode($car, $level) {
    
    $database = DatabaseFactory::getFactory()->getConnection();
		$query = $database->prepare("DELETE FROM car_access WHERE user = :user AND car = :car AND level = :level LIMIT 1;");
		$query->execute(array(
			':user' => Session::get('user_uuid'),
			':car' => $car,
      ':level' => $level
		));
    
    if ($query->rowCount() == 1) {
            return true;
        }
    	
		return false;
    
    
    } 
    
    
    
    public static function getCarData($car_id)
    {
	   $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT car_data FROM cars WHERE id = ?');
       $selectstuff = array('id' => new Cassandra\Uuid($car_id));
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else {
       $entries = '';
          foreach($result as $entry) {  //only once
          $entry=$entry['car_data'];
          if ($entry) {
            $entries=unserialize($entry);
            } 
          }
       return($entries);
       };
    }
    
    
    
     public static function editCar($car_data)
     {
           
     if (!is_array($car_data)) {
            Session::add('feedback_negative', _('FEEDBACK_CAR_EDIT_FAILED'));
            return false;
        }
        
             $level = self::checkAccessLevel($car_data['car_id']);
             if ($level < 80) {
            Session::add('feedback_negative', _('ACCESS RESTRICTION'));
            return false;

             }
             
    if ($car_data['enable_car_access'] == $car_data['car_id']) {         
    if (self::newAccessNode($car_data['car_id'], 10)) {
        Session::add('feedback_positive', _('FEEDBACK_CAR_ACCESS_EDIT_SUCCESS'));
    }     else {
            Session::add('feedback_negative', _('FEEDBACK_CAR_ACCESS_EDIT_FAILED'));
    }}        
    
    if ($car_data['disable_car_access'] == $car_data['car_id']) {         
    if (self::removeAccessNode($car_data['car_id'], 10)) {
        Session::add('feedback_positive', _('FEEDBACK_CAR_ACCESS_EDIT_SUCCESS'));
    }     else {
            Session::add('feedback_negative', _('FEEDBACK_CAR_ACCESS_EDIT_FAILED'));
    }}         
      
            
        
       $existing_plates =  self::getCarPlates($car_data['car_id']);
               
       $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       
       $statement = $casssession->prepare('UPDATE cars SET car_access = ?, car_model = ?, car_name=?, car_vin=?, car_plates=?, images=? WHERE id = ?');
       $imagelist = new Cassandra\Collection(Cassandra::TYPE_TEXT);
         if ($car_data['images']) { 
       $uplimages = explode(',', $car_data['images']);
       foreach ($uplimages as $uplimage) {$imagelist->add($uplimage);}
       }
       $plateslist = new Cassandra\Collection(Cassandra::TYPE_TEXT);
       $plateslist->add($car_data['car_plates']);
       foreach ($existing_plates AS $existing_plate) {
       if ($existing_plate !== $car_data['car_plates']) {
       	   $plateslist->add($existing_plate);
       }}
       $accesslist = new Cassandra\Set(Cassandra::TYPE_TEXT);
         if ($access_tags = $car_data['public_tags']) {
      foreach($access_tags as $access_tag) {
              $accesslist->add($access_tag);
                                            }      
                                                        };
       $updatestuff = array(
       'car_access' => $accesslist,
       'car_model' => $car_data['car_model'],
       'car_name' => $car_data['car_name'],
       'car_vin' => $car_data['car_vin'],
       'car_plates' => $plateslist,
       'images' => $imagelist,
      'id' => new Cassandra\Uuid($car_data['car_id'])   
       );
       $options = new Cassandra\ExecutionOptions(array('arguments' => $updatestuff)); 
       if ($result = $casssession->execute($statement, $options)) 
        {
        Session::add('feedback_positive', _('FEEDBACK_CAR_EDIT_SUCCESS'));
        return true; 
        } else {
        Session::add('feedback_negative', _('FEEDBACK_CAR_EDIT_FAILED'));
        return false;
      };
     
     
     
     }
     
     
     
          public static function checkAccessLevel($car_id)
          //can this user read / write to this car?
    {
    $database = DatabaseFactory::getFactory()->getConnection();
		$query = $database->prepare("SELECT level FROM car_access WHERE user = :user AND car = :car");
		$query->execute(array(
			':user' => Session::get('user_uuid'),
			':car' => $car_id
		));
    if ($data = $query->fetchAll()) {
      $data= self::ConvertCassObject($data);
      $data = $data[0];
      return $data['level'];
    } else return false; 
    
    
    /*  CREATE TABLE IF NOT EXISTS `car_access` (
`id` int(11) NOT NULL,
  `user` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `car` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `level` char(4) COLLATE utf8_unicode_ci NOT NULL
)
    
    
    
    
    	     $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT * FROM cars WHERE owner = ?');
       $selectstuff = array('owner' => new Cassandra\Uuid(Session::get('user_uuid')));
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else return($result);

    */
	     
       return true;
    } 
    
    

    
     
       


     public static function getEvents($car_id)
    {
	     $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT * FROM events WHERE car = ?');
       $selectstuff = array('car' => new Cassandra\Uuid($car_id));
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else return($result);
    } 

        public static function createEvent($event_data)
    {
        if (!is_array($event_data)) {
            Session::add('feedback_negative', _('FEEDBACK_EVENT_CREATION_FAILED'));
            return false;
        }
        
         $level = self::checkAccessLevel($event_data['car_id']);
             if ($level < 80) {
            Session::add('feedback_negative', _('ACCESS RESTRICTION'));
            return false;

             }
        
        
       $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('INSERT INTO events (car, event_author, event_content, event_data, event_time, event_type, event_entered, event_odo, images) VALUES (?,?,?,?,?,?,?,?,?)');
       $timeofday = gettimeofday(); $microtime = $timeofday['usec'];
       $imagelist = new Cassandra\Collection(Cassandra::TYPE_TEXT);
       $eventtype = new Cassandra\Collection(Cassandra::TYPE_TEXT);
       $eventtype->add($event_data['event_type']);
       if (isset($event_data['event_entered'])) {
       	   $event_entered = explode('-', $event_data['event_entered']);
       			} else {
       		$event_entered[0] = $timeofday['sec']; $event_entered[1] = $timeofday['usec'];}
       	if (isset($event_data['oldversions'])) {$oldversions = $event_data['oldversions'];} else {$oldversions = '';};        		
       $event_blob = array(
       'amount' => self::Getfloat($event_data['event_amount']),
       'oldversions' => $oldversions,
       //'something else' => 'some future data'
       );
       if ($event_data['images']) { 
       $uplimages = explode(',', $event_data['images']);
       foreach ($uplimages as $uplimage) {$imagelist->add($uplimage);}
       }
       $insertstuff = array(
       'car' => new Cassandra\Uuid($event_data['car_id']),
       'event_author' => new Cassandra\Uuid(Session::get('user_uuid')),
       'event_content' => $event_data['event_content'],
       'event_data' => serialize($event_blob),
       'event_time' => new Cassandra\Timestamp(strtotime($event_data['event_date'].date(' H:i:s ')), $microtime),
       'event_type' => $eventtype,     
       'event_entered' => new Cassandra\Timestamp($event_entered[0] , $event_entered[1] ),
       'event_odo' => intval($event_data['event_odo']),
       'images' => $imagelist
       );
       $options = new Cassandra\ExecutionOptions(array('arguments' => $insertstuff));
       if ($casssession->execute($statement, $options)   ) {      
       return true; }

        // default return
        Session::add('feedback_negative', _('FEEDBACK_EVENT_CREATION_FAILED'));
        return false;
    }
    
    
         public static function getEvent($event_id)
    {
       $event_array = unserialize(urldecode($event_id));
	     $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));   
       $statement = $casssession->prepare('SELECT * FROM events WHERE car = ? and event_time = ?');
       $selectstuff = array(
       'car' => new Cassandra\Uuid($event_array['c']),
       'event_time' => new Cassandra\Timestamp($event_array['t'], $event_array['m'])
       );
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else return($result);
    } 



      public static function addNewCarBit($car_bit_data) {
        if (!is_array($car_bit_data)) {
            Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));
                
            return false;
        } else {
                  $data_arr = array();
         $existing = self::getCarData($car_bit_data['car_id']);
             if (is_array($existing)) {
             $data_arr = $existing;
             };
             
             array_unshift($data_arr, array($car_bit_data['new_car_data_bit'] => $car_bit_data['new_car_data_val']))   ;
             $serialized = serialize($data_arr);
             
        
        
        
      $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('UPDATE cars SET car_data = ? WHERE id = ?');
       $updatestuff = array(
       'car_data' => $serialized,
       'id' => new Cassandra\Uuid($car_bit_data['car_id'])
       );
       $options = new Cassandra\ExecutionOptions(array('arguments' => $updatestuff));
       if ($casssession->execute($statement, $options)) {
       return true;
       }
        
        
        
        }
      }


       public static function editEvent($event_data)
    {
        if (!is_array($event_data)) {
            Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));
                
            return false;
        }
        
           //turim teise rasyti i sita masina?
           $level = self::checkAccessLevel($event_data['car_id']);
             if ($level < 80) {
            Session::add('feedback_negative', _('ACCESS RESTRICTION'));
            return false;

             }
        
	
  
  //save current entry to a serialized array and store it in         
  $event_url =  urlencode(serialize(array('c' => $event_data['car_id'], 't' => $event_data['event_time'], 'm' => $event_data['event_microtime'])));
	$thisevent = self::getEvent($event_url);
	$thisevent = $thisevent[0];
  $thisevent = self::ConvertCassObject($thisevent);
	$microtime = gettimeofday(); $time_id = $microtime['sec'].'-'.$microtime['usec'];
	
  $casscluster   = Cassandra::cluster()  ->build();
  $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
  $statement = $casssession->prepare('INSERT INTO oldevents (car, time_id, event_data) VALUES (?,?,?)');
      $insertstuff = array(
       'car' => new Cassandra\Uuid($event_data['car_id']),
       'time_id' => $time_id,
       'event_data' => serialize($thisevent)
       );
	$options = new Cassandra\ExecutionOptions(array('arguments' => $insertstuff));
  if ($casssession->execute($statement, $options)   ) {      
       if (isset($event_data['oldversions'])) {$event_data['oldversions'].=','.$time_id;} else
       {$event_data['oldversions'] = $time_id;} 
  self::createEvent($event_data);     

       
       
       return true;
       } else {
            
       
       
       Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));
                
            return false;} 

       
	
	
	
     
    }
    
    
           public static function deleteEvent($event_data)
    {
        if (!is_array($event_data)) {
            Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));
                
            return false;
        }
        //patikrinam ar vartotojas turi teisę rašyti į šitą mašiną
           $level = self::checkAccessLevel($event_data['car_id']);
             if ($level > 98) {
            Session::add('feedback_negative', _('ACCESS RESTRICTION'));
            return false;

             }
        
       $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('DELETE FROM events WHERE car = ? AND event_time = ?');
       $updatestuff = array(
       'car' => new Cassandra\Uuid($event_data['c']),
       'event_time' => new Cassandra\Timestamp($event_data['t'], $event_data['m'])
       );
       $options = new Cassandra\ExecutionOptions(array('arguments' => $updatestuff));
       if ($casssession->execute($statement, $options)   ) {      
       if ($event_data['source'] == 'edit' )
       Session::add('feedback_positive', _('FEEDBACK_EVENT_SAVED'));
       if ($event_data['source'] == 'delete' )
       Session::add('feedback_positive', _('FEEDBACK_EVENT_DELETED'));
       return true; }

        // default return
        Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));
            
        return false;
    }
    
    
    
    
    
    
    public static function getCarMakeList($fragment)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        
        if ($fragment == 'all') {
        $sql = "SELECT * FROM car_makes;";
        $query = $database->prepare($sql);
        $query->execute();
        } else {
        $fragment = "%{$fragment}%";
        $sql = "SELECT * FROM car_makes WHERE make LIKE :fragment;";
        $query = $database->prepare($sql);
        $query->execute(array(':fragment' => $fragment));
        }

        $car_makes = array();
        if ($query->rowCount() > 0) {
        foreach ($query->fetchAll() as $car) {
            $car_makes[] = $car;
        }}
        return $car_makes;
    }
    


    
    
    private static function Getfloat($str) {
  if(strstr($str, ",")) {
    $str = str_replace(".", "", $str); // replace dots (thousand seps) with blancs
    $str = str_replace(",", ".", $str); // replace ',' with '.'
  }
 
  if(preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.'
    $res = floatval($match[0]);
  } else {
    $res = floatval($str); // take some last chances with floatval
  }
  return number_format((float)$res, 2, '.', '');
} 



  public static function makeItSmaller($image, $user, $size)
{
$ok = false;
if (!is_dir(Config::get('CAR_IMAGE_PATH').$user.'/'.$size)) {
  if (mkdir(Config::get('CAR_IMAGE_PATH').$user.'/'.$size, 0755)) {
  $ok = true;
  }
} else {$ok = true;}

if ($ok) {

$thumb = new Imagick();
$thumb->readImage(Config::get('CAR_IMAGE_PATH').$user.'/'.$image);
$thumb->resizeImage($size,$size,Imagick::FILTER_LANCZOS,1,true);
$thumb->writeImage(Config::get('CAR_IMAGE_PATH').$user.'/'.$size.'/'.$image);
$thumb->clear();
$thumb->destroy(); 
return true;
} else {return false;}

}




  public static function makePdfImage($image, $user, $size)
{
$ok = false;
if (!is_dir(Config::get('CAR_IMAGE_PATH').$user.'/'.$size)) {
  if (mkdir(Config::get('CAR_IMAGE_PATH').$user.'/'.$size, 0755)) {
  $ok = true;
  }
} else {$ok = true;}

if ($ok) {
$pdf = Config::get('CAR_IMAGE_PATH').$user.'/'.$image.'[0]';
$png =  basename($image, '.pdf').'.png';
$png = Config::get('CAR_IMAGE_PATH').$user.'/'.$size.'/'.$png;
$thumb = new Imagick();
//$thumb->readImageBlob($pdf->render());
//$thumb = $thumb->appendImages(false);
$thumb->setResolution($size,$size);
$thumb->readImage($pdf);
$thumb->setImageFormat( "png" );
$thumb->scaleImage($size,$size,true);
$thumb->writeImage($png);
$thumb->clear();
$thumb->destroy(); 
return true;
} else {return false;}

}






public static function get_extension($file_name){
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
}


private static function ConvertCassObject($object) {
//casts cassandra ojects to arrays, otherwise they won't unserialize properly
$result = array();
 foreach ($object as $key=>$row) {
if (is_object($row)) {
  $vars = get_object_vars($row);
$result[$key] = $vars;
  } else {
  $result[$key] = $row;
  }
 }; 
return $result;	      
}

    
}  
