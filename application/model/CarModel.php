<?php

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
//*********************************************************************    

    public static function createCar($car_name, $car_make, $car_model, $car_vin, $car_plates)
    {
        if (!$car_name || strlen($car_name) == 0) {
            Session::add('feedback_negative', Text::get('FEEDBACK_CAR_CREATION_FAILED'));
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
        Session::add('feedback_negative', Text::get('FEEDBACK_CAR_CREATION_FAILED'));
        return false;
    }
    
     public static function getCar($car_id)
    {
	     $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT id, car_name, car_make, car_model, car_vin, car_plates, owner FROM cars WHERE id = ?');
       $selectstuff = array('id' => new Cassandra\Uuid($car_id));
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else return($result);
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
            Session::add('feedback_negative', Text::get('FEEDBACK_EVENT_CREATION_FAILED'));
            return false;
        }
        //patikrinam ar vartotojas turi teisę rašyti į šitą mašiną!!!
       $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('INSERT INTO events (car, event_author, event_content, event_data, event_time, event_type, event_entered, event_odo, images) VALUES (?,?,?,?,?,?,dateOf(now()),?,?)');
       $microtime = gettimeofday(); $microtime = $microtime['usec'];
       $imagelist = new Cassandra\Collection(Cassandra::TYPE_TEXT);
       $eventtype = new Cassandra\Collection(Cassandra::TYPE_TEXT);
       $eventtype->add($event_data['event_type']);
       $event_blob = array(
       'amount' => self::Getfloat($event_data['event_amount']),
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
       'event_odo' => intval($event_data['event_odo']),
       'images' => $imagelist
       );
       $options = new Cassandra\ExecutionOptions(array('arguments' => $insertstuff));
       if ($casssession->execute($statement, $options)   ) {      
       return true; }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_EVENT_CREATION_FAILED'));
        return false;
    }
    
    
         public static function getEvent($event_id)
    {
       $event_array = unserialize(urldecode($event_id));
	     $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT * FROM events WHERE car = ? and event_time = ?');
       $selectstuff = array(
       'car' => new Cassandra\Uuid($event_array['car']),
       'event_time' => new Cassandra\Timestamp($event_array['time'], $event_array['microtime'])
       );
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else return($result);
    } 



       public static function editEvent($event_data)
    {
        if (!is_array($event_data)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_EVENT_EDIT_FAILED'));
                
            return false;
        }
        //patikrinam ar vartotojas turi teisę rašyti į šitą mašiną!!!
        //'car' => new Cassandra\Uuid($event_data['car_id'])
       $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('UPDATE events SET event_content = ?,  event_type = ? WHERE car = ? AND event_time = ?');
       $updatestuff = array(
       'event_content' => $event_data['event_content'],
       'event_type' => $event_data['event_type'],
       'car' => new Cassandra\Uuid($event_data['car_id']),
       'event_time' => new Cassandra\Timestamp($event_data['event_time'], $event_data['event_microtime'])
       );
       $options = new Cassandra\ExecutionOptions(array('arguments' => $updatestuff));
       if ($casssession->execute($statement, $options)   ) {      
       return true; }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_EVENT_EDIT_FAILED'));
            
        return false;
    }
    
    
           public static function deleteEvent($event_data)
    {
        if (!is_array($event_data)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_EVENT_EDIT_FAILED'));
                
            return false;
        }
        //patikrinam ar vartotojas turi teisę rašyti į šitą mašiną!!!
        //'car' => new Cassandra\Uuid($event_data['car_id'])
       $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('DELETE FROM events WHERE car = ? AND event_time = ?');
       $updatestuff = array(
       'car' => new Cassandra\Uuid($event_data['car_id']),
       'event_time' => new Cassandra\Timestamp($event_data['event_time'], $event_data['event_microtime'])
       );
       $options = new Cassandra\ExecutionOptions(array('arguments' => $updatestuff));
       if ($casssession->execute($statement, $options)   ) {      
       Session::add('feedback_positive', 'Event for '.$event_data['car_id'].' deleted.');
       return true; }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_EVENT_EDIT_FAILED'));
            
        return false;
    }




       /**
     * Set a note (create a new one)
     * @param string $note_text note text that will be created
     * @return bool feedback (was the note created properly ?)
     */
    public static function createNote($note_text)
    {
        if (!$note_text || strlen($note_text) == 0) {
            Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO notes (note_text, user_id) VALUES (:note_text, :user_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':note_text' => $note_text, ':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
        return false;
    }

    /**
     * Update an existing note
     * @param int $note_id id of the specific note
     * @param string $note_text new text of the specific note
     * @return bool feedback (was the update successful ?)
     */
    public static function updateNote($note_id, $note_text)
    {
        if (!$note_id || !$note_text) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE notes SET note_text = :note_text WHERE note_id = :note_id AND user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':note_id' => $note_id, ':note_text' => $note_text, ':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_EDITING_FAILED'));
        return false;
    }
    
    
    
    private function Getfloat($str) {
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

    
}  
