<?php

//todo: clear gravatar stuff from session
//https://developer.android.com/training/basics/firstapp/creating-project.html


//image_meta field in cars
//image_meta field in events

class CarModel
{

    public static function getCars($owner)
    {

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT * FROM cars WHERE owner = :owner");
        $query->execute(array(
            ':owner' => $owner,
        ));
        if ($data = $query->fetchAll()) {
            return $data;
        } else
            return false;

    }


    public static function getCarsByPlatesOrVin($plates_or_vin)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT car_id FROM car_lookup WHERE car_plates LIKE :plates_or_vin or car_vin LIKE :plates_or_vin");
        if ($query->execute(array(':plates_or_vin' => "%$plates_or_vin%")))
        {
            $cars = array();
            foreach ($query->fetchAll() as $row) {
                $cars[] = $row->car_id;
            }
            return $cars;

        }
        return false;
    }


    public static function updateCarLookupEntry($car_id, $plates, $vin, $owner, $car_name) //mysql
    {

        $database = DatabaseFactory::getFactory()->getConnection();

		$sql = "INSERT INTO car_lookup	(car_id, car_plates, car_vin, owner, car_name)
                    VALUES
		(:car_id, :car_plates, :car_vin, :owner, :car_name)
                    ON DUPLICATE KEY UPDATE
        car_plates = :car_plates, car_vin = :car_vin, owner = :owner, car_name = :car_name";
		$query = $database->prepare($sql);
        if	($query->execute(array(':car_id' => $car_id,
		                      ':car_plates' => $plates,
		                      ':car_vin' => $vin,
							  ':owner' => $owner,
							  ':car_name' => $car_name,
						  )))
		{$count =  $query->rowCount();

			return $count; } else {return false;}


    }


    public static function checkForDuplicateCarName($car_name)
    {
        //returns true if there is already a car with the same name under current user
        $database = DatabaseFactory::getFactory()->getConnection();
    		$query = $database->prepare("SELECT * FROM cars WHERE owner = :owner AND car_name = :car_name ;");
        if	($query->execute(array(
							  ':owner' => Session::get('user_uuid'),
							  ':car_name' => $car_name,
						  )))
		{
      $count =  $query->rowCount();
			if ($count > 0) {return true;} else {return false;}
      }
        return false;
    }


    public static function createCar($car_name, $car_make, $car_model, $car_vin, $car_plates, $car_year, $car_make_id, $car_model_id, $car_variant_id, $car_variant)
    {

        //todo: neleidziam registruoti dvieju masinu vienodu nickname
        if (CarModel::checkForDuplicateCarName($car_name)) {
            Session::add('feedback_negative', _('FEEDBACK_CAR_NAME_ALREADY_EXISTS'));
            Session::add('feedback_negative', _('FEEDBACK_CAR_CREATION_FAILED'));
            return false;
        }

        if (!$car_name || strlen($car_name) == 0) {
            Session::add('feedback_negative', _('FEEDBACK_CAR_CREATION_FAILED'));
            return false;
        }

        $plateslist  = array();
        $plateslist[] = $car_plates;


        $database = DatabaseFactory::getFactory()->getConnection();
    		$query = $database->prepare("INSERT INTO cars
         (id, car_make, car_model, car_name, car_plates, car_vin, owner, car_year, car_make_id, car_model_id, car_variant, car_variant_id) VALUES
        (uuid(), :car_make, :car_model, :car_name, :car_plates, :car_vin, :owner, :car_year, :car_make_id, :car_model_id, :car_variant, :car_variant_id);");
        if	($query->execute(array(
                ':car_make' => $car_make,
                ':car_model' => $car_model,
                ':car_name' => $car_name,
                ':car_plates' => serialize($plateslist),
                ':car_vin' => strtoupper($car_vin),
                ':owner' => Session::get('user_uuid'),
                ':car_year' => $car_year,
                ':car_make_id' => $car_make_id,
                ':car_model_id' => $car_model_id,
                ':car_variant' => $car_variant,
                ':car_variant_id' => $car_variant_id,
						  ))) { return true; }


        //cia pridedam jei dar ka gauname is modelio/varianto duomenu, greiciu dezes tipa, kuro tipa ir pan
        //$cardata = array();
        //$cardata[] = array('car_variant' => $car_variant );
        //$cardata = serialize($cardata);
        //$cardata = '';

        // default return
        Session::add('feedback_negative', _('FEEDBACK_CAR_CREATION_FAILED'));
        return false;
    }

    public static function getCar($car_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT * FROM cars WHERE id = :id");
        $query->execute(array(
            ':id' => $car_id,
        ));
        if ($data = $query->fetchAll()) {
            return $data;
        } else
            return false;
    }

    public static function getCarVin($car_id)
    {

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT car_vin FROM cars WHERE id = :id");
        $query->execute(array(
            ':id' => $car_id,
        ));
        if ($data = $query->fetch()) {
            return $data->car_vin;
        } else
            return false;

    }

	    public static function getCarImage($car_id)
    {

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT images FROM cars WHERE id = :id");
        $query->execute(array(
            ':id' => $car_id,
        ));
        if ($data = $query->fetch()) {
            $images =  unserialize($data->images);
            $image = reset($images);
            return $image;


        }

           return false;


    }


    public static function deleteCar($car_id, $delete_events = true)
    {

        $database = DatabaseFactory::getFactory()->getConnection();

        if ($delete_events) {

            $query    = $database->prepare("DELETE FROM events WHERE car = :car");
            if (!$query->execute(array(
            ':car' => $car_id,
                ))) {
                Session::add('feedback_negative', _('FEEDBACK_CAR_DELETION_FAILED'));
                return false;
                }
        }



        $query    = $database->prepare("DELETE FROM cars WHERE id = :car");
            if ($query->execute(array(
            ':car' => $car_id,
                ))) {

			self::deleteCarAccessNodes($car_id); //delete all entries of other users who could look at this car
            Session::add('feedback_positive', _('FEEDBACK_CAR_DELETION_SUCCESS'));
            return true;
        } else {
            Session::add('feedback_negative', _('FEEDBACK_CAR_DELETION_FAILED'));
            return false;
        }

    }



	public static function deleteCarAccessNodes($car_id)
    {

		   if (!$car_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM car_access WHERE car = :car_id;";
        $query = $database->prepare($sql);
        $query->execute(array(':car_id' => $car_id));

        if ($query->rowCount() == 1) {
            return true;
        }

        return false;

    }

	public static function deleteCarAccessNodesForUser($car_id, $user_id)
    {

		   if ((!$car_id) or (!$user_id)) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM car_access WHERE car = :car_id AND user = :user_id AND level < 99;";
        $query = $database->prepare($sql);
        $query->execute(array(
							  ':car_id' => $car_id,
							  ':user_id' => $user_id,
							  ));

        if ($query->rowCount() == 1) {
            return true;
        }

        return false;

    }





    public static function getCarPlates($car_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT car_plates FROM cars WHERE id = :id");
        $query->execute(array(
            ':id' => $car_id,
        ));
        if ($data = $query->fetch()) {
            $plates=unserialize($data->car_plates);
            return $plates;
        }

           return false;

    }

    public static function getCarName($car_id)
    {
        if (!$car_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT car_name FROM cars WHERE id = :id");
        $query->execute(array(
            ':id' => $car_id,
        ));
        if ($data = $query->fetch()) {

            return $data->car_name;
        } else
            return false;

    }

    public static function getCarOwner($car_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT owner FROM cars WHERE id = :id");
        $query->execute(array(
            ':id' => $car_id,
        ));
        if ($data = $query->fetch()) {

            return $data->owner;
        } else
            return false;

    }


    public static function newAccessNode($car, $level, $user)
    {

        if ($user_name = UserModel::getUserNameByUUid($user)) {
            if (!self::checkAccessNode($car, $level, $user)) { //if this node does not already exist
                $database = DatabaseFactory::getFactory()->getConnection();
                $query    = $database->prepare("INSERT INTO car_access (user, car, level, user_name, car_name) VALUES (:user, :car, :level, :user_name, :car_name)");
                $query->execute(array(
                    ':user' => $user,
                    ':car' => $car,
                    ':level' => $level,
                    ':user_name' => $user_name, //we don't really need those names here, they can change over tiume, unlike ids
                    ':car_name' => self::getCarName($car) //applies to both user and car names
                ));
                $count = $query->rowCount();
                if ($count == 1) {
                    return true;
                }

                return false;

            } else
                return false;

        } else
            return false;

    }


    private static function checkAccessNode($car, $level, $user) //check if this access node already exists
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT id FROM car_access WHERE car = :car AND level = :level AND user = :user");
        $query->execute(array(
            ':car' => $car,
            ':level' => $level,
            ':user' => $user
        ));
        if ($data = $query->fetchAll()) {
            return true;
        } else
            return false;

    }




    public static function removeAccessNode($car, $level, $user)
    {

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("DELETE FROM car_access WHERE user = :user AND car = :car AND level = :level LIMIT 1;");
        $query->execute(array(
            ':user' => $user,
            ':car' => $car,
            ':level' => $level
        ));

        if ($query->rowCount() == 1) {
            return true;
        }

        return false;


    }



	public static function getCarXmlData($car_id)
    {
        if ($car_id) {

            $database = DatabaseFactory::getFactory()->getConnection();
            $query = $database->prepare("SELECT car_data_xml FROM cars WHERE id = :car_id");
            if ($query->execute(array(':car_id' => $car_id))) {
                if ($data = $query->fetch()) {
                return $data->car_data_xml;
                }
            }
        }
        return false;
    }







    public static function editCarId($car_data)
    {

        if (!is_array($car_data)) {
            Session::add('feedback_negative', _('FEEDBACK_CAR_EDIT_FAILED'));
            return false;
        }

        $level = self::checkAccessLevel($car_data['car_id'], Session::get('user_uuid'));
        if ($level < 80) {
            Session::add('feedback_negative', _('ACCESS_RESTRICTION'));
            return false;
        }


        array_walk_recursive($car_data, 'Filter::XSSFilter');

        $plates = array();
        $existing_plates = self::getCarPlates($car_data['car_id']);
        $current_plate =  reset($existing_plates);
        if ($current_plate !== $car_data['car_plates']) {
          $plates[] = $car_data['car_plates'];
          foreach ($existing_plates AS $existing_plate)
          {
            if ($existing_plate !== $car_data['car_plates'])
              {
                $plates[] = $existing_plate;
              }
          }
        } else {

        $plates = $existing_plates;
        }

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("UPDATE cars
										  SET
										  car_make = :car_make, car_model = :car_model, car_name = :car_name, car_plates = :car_plates,
										  car_vin = :car_vin, car_year = :car_year, car_make_id = :car_make_id, car_model_id = :car_model_id,
										  car_variant = :car_variant, car_variant_id = :car_variant_id WHERE id = :id");

    if ($query->execute(array(
            ':car_make' => $car_data['car_make'],
            ':car_model' => $car_data['car_model'],
            ':car_name' => $car_data['car_name'],
            ':car_plates' => serialize($plates),
            ':car_vin' => $car_data['car_vin'],
            ':car_year' => $car_data['car_year'],
            ':car_make_id' => $car_data['car_make_id'],
            ':car_model_id' => $car_data['car_model_id'],
            ':car_variant' => $car_data['car_variant'],
            ':car_variant_id' => $car_data['car_variant_id'],
            ':id' => $car_data['car_id'],
        ))) {

           self::updateCarLookupEntry($car_data['car_id'], implode(',', $plates), $car_data['car_vin'], self::getCarOwner($car_data['car_id']), $car_data['car_name']);
			self::updateCarAccessName($car_data['car_id'], $car_data['car_name']);
            Session::add('feedback_positive', _('FEEDBACK_CAR_EDIT_SUCCESS'));
        return true;
        } else {
            Session::add('feedback_negative', _('FEEDBACK_CAR_EDIT_FAILED'));
            return false;
        };
   }



    public static function editCarAccess($car_data)
    {

        if (!is_array($car_data)) {
            Session::add('feedback_negative', _('FEEDBACK_CAR_EDIT_FAILED'));
            return false;
        }

        $level = self::checkAccessLevel($car_data['car_id'], Session::get('user_uuid'));
        if ($level < 80) {
            Session::add('feedback_negative', _('ACCESS_RESTRICTION'));
            return false;

        }

        if ($car_data['enable_car_access'] == $car_data['car_id']) {
            if (self::newAccessNode($car_data['car_id'], 10, Session::get('user_uuid'))) {
                Session::add('feedback_positive', _('FEEDBACK_CAR_ACCESS_EDIT_SUCCESS')); return true;
            } else {
                Session::add('feedback_negative', _('FEEDBACK_CAR_ACCESS_EDIT_FAILED')); return false;
            }
        }

        if ($car_data['disable_car_access'] == $car_data['car_id']) {
            if (self::removeAccessNode($car_data['car_id'], 10, Session::get('user_uuid'))) {
                Session::add('feedback_positive', _('FEEDBACK_CAR_ACCESS_EDIT_SUCCESS')); return true;
            } else {
                Session::add('feedback_negative', _('FEEDBACK_CAR_ACCESS_EDIT_FAILED')); return false;
            }
        }



    }





    public static function editCarImages($car_data)
    {

        if (!is_array($car_data)) {
            Session::add('feedback_negative', _('FEEDBACK_CAR_EDIT_FAILED'));
            return false;
        }
        $level = self::checkAccessLevel($car_data['car_id'], Session::get('user_uuid'));
        if ($level < 80) {
            Session::add('feedback_negative', _('ACCESS_RESTRICTION'));
            return false;
        }

        $imagelist   = array();
        if ($car_data['images']) {
            $imagelist  = explode(',', $car_data['images']);
        }

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("UPDATE cars SET images = :images, image_meta = :image_meta WHERE id = :id");
        if ($query->execute(array(
            ':images' => serialize($imagelist),
            ':image_meta' => '',
            ':id' => $car_data['car_id'],
        ))) { return true; }
         else {
            Session::add('feedback_negative', _('FEEDBACK_CAR_EDIT_FAILED'));
            return false;
        };

    }







    public static function checkAccessLevel($car_id, $user_id)    //can this user read / write to this car?
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT level FROM car_access WHERE user = :user AND car = :car");
        $query->execute(array(
            ':user' => $user_id,
            ':car' => $car_id
        ));
        if ($data = $query->fetchAll()) {
            $data = self::ConvertCassObject($data);
            $data = $data[0];
            return $data['level'];
        } else
            return false;

        return true;
    }

    /*
    public static function getNeighboringEvents($car_id, $event_id)
    {
        $casscluster = Cassandra::cluster()->build();
        $casssession = $casscluster->connect(Config::get('CASS_KEYSPACE'));
        $statement   = $casssession->prepare('SELECT event_time FROM events WHERE car = ?');
        $selectstuff = array(
            'car' => new Cassandra\Uuid($car_id)
        );
        $options     = new Cassandra\ExecutionOptions(array(
            'arguments' => $selectstuff
        ));
        $result      = $casssession->execute($statement, $options);

        $all_event_ids = array();
        $i             = 0;

        if ($result->count() == 0)
            return false;
        else {
            foreach ($result as $event) {
                $serialized_event_time = (array) $event['event_time'];
                $event_time            = $serialized_event_time['seconds'];
                $event_microtime       = $serialized_event_time['microseconds'];
                $all_event_ids[$i]     = urlencode(serialize(array(
                    'c' => $car_id,
                    't' => $event_time,
                    'm' => $event_microtime
                )));
                $i++;
            }

            $event_no = array_search(urlencode($event_id), $all_event_ids);
            $event_no == 0 ? $prev = 0 : $prev = $all_event_ids[$event_no - 1];
            $event_no >= ($result->count() - 1) ? $next = 0 : $next = $all_event_ids[$event_no + 1];
            return (array(
                'prev' => $prev,
                'next' => $next,
                'current_no' => $event_no
            ));
        }
    }
    */








    public static function getEvents($car_id)
    {

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT * FROM events WHERE car = :car ORDER BY event_datetime DESC");
        $query->execute(array(
            ':car' => $car_id,
        ));
        if ($data = $query->fetchAll()) {
            return $data;
        } else
            return false;
    }

    public static function createEvent($event_data, $setReminders = true) // we don't want to set a double reminder when this is called from editEvent
    {
        if (!is_array($event_data)) {
            Session::add('feedback_negative', _('FEEDBACK_EVENT_CREATION_FAILED'));
            return false;
        }

        $level = self::checkAccessLevel($event_data['car_id'], Session::get('user_uuid'));
        if ($level < 80) {
            Session::add('feedback_negative', _('ACCESS_RESTRICTION'));
            return false;

        }

      $timeofday   = gettimeofday();
      $microtime   = $timeofday['usec'];
	    $macrotime   = substr($event_data['event_date'], 0, strlen($event_data['event_date']) - 3);
		  $event_time  = $macrotime.'.'.$microtime;

      $imagelist   = '';
      if ($event_data['images']) {
            $uplimages = explode(',', $event_data['images']);
            $imagelist = serialize($uplimages);
      }

      $eventtype   = '';
        if ($event_data['event_type']) {
            $all_event_types = explode(',', $event_data['event_type']);
            $eventtype = serialize($all_event_types);
        } else { //if event type has not been entered we give it default type
            $tags = Config::get('AVAILABLE_TAGS');
            $all_event_types = array();
            $all_event_types[] = $tags['default'];
            $eventtype = serialize($all_event_types);
        }

     if (isset($event_data['event_entered'])) {
            $event_entered = explode('-', $event_data['event_entered']);
        } else {
            $event_entered[0] = $timeofday['sec'];
            $event_entered[1] = $timeofday['usec'];
        }

     if (isset($event_data['oldversions'])) {
            $oldversions = $event_data['oldversions'];
        } else {
            $oldversions = '';
        }

     $event_blob = array(
            'amount' => self::Getfloat($event_data['event_amount']),
            'oldversions' => $oldversions,
            'oil_type' => $event_data['oil_type'],
            'new_oil' => $event_data['new_oil'],
            'oil_filter' => $event_data['oil_filter'],
            'air_filter' => $event_data['air_filter'],
            'fuel_filter' => $event_data['fuel_filter'],
            'cabin_filter' => $event_data['cabin_filter'],
            'timing_belt' => $event_data['timing_belt'],
            'idler_pulley' => $event_data['idler_pulley'],
            'tensioner_pulley' => $event_data['tensioner_pulley'],
            'water_pump' => $event_data['water_pump'],
        );

      $database = DatabaseFactory::getFactory()->getConnection();
      $query = $database->prepare("INSERT INTO events (car, event_author, event_content, event_data, event_time, event_type, event_entered, event_odo, images, event_datetime) VALUES (:car, :event_author, :event_content, :event_data, :event_time, :event_type, :event_entered, :event_odo, :images, :event_datetime)");
      if	($query->execute(array(
      ':car' => $event_data['car_id'],
      ':event_author' => Session::get('user_uuid'),
      ':event_content' => $event_data['event_content'],
      ':event_data' => serialize($event_blob),
      ':event_time' => $event_time,
      ':event_type' => $eventtype,
      ':event_entered' => $event_entered[0].'.'.$event_entered[1],
      ':event_odo' => intval($event_data['event_odo']),
      ':images' => $imagelist,
      ':event_datetime' => date("Y-m-d H:i:s", $macrotime),
						  )))		{

        //reminder
        if (($setReminders) && ($event_data['reminder_toggle'] == 'on')) {
                $reminder_time = substr($event_data['reminder_time'], 0, strlen($event_data['reminder_time']) - 3); //we added 3 extra zeroes at the end due to the way jqueryui datepicker interprets a timestamp
				$timeofday = gettimeofday(); $microtime = $timeofday['usec'];
                ReminderModel::setReminder($event_data['car_id'], $reminder_time, $microtime, $event_data['reminder_content'], 'Q');
            }

            //update car odo
			self::updateOdoReading($event_data['car_id'], intval($event_data['event_odo']), false);
            // register oil change
            if ($event_data['new_oil'] == 'Y') {
                $oildata = array(
                  'car_id' =>  $event_data['car_id'],
                  'expiry' => 'OIL',
                  'odo' => intval($event_data['event_odo']),
                  'prev_odo' => intval($event_data['next-oil-change']),
                  'description' => $event_data['oil_type'],
                  'reference' => $macrotime.':'.$microtime,
                );

            ExpiriesModel::writeExpiry($oildata);
            }


        return $macrotime.':'.$microtime;
		           }


        // default return
        Session::add('feedback_negative', _('FEEDBACK_EVENT_CREATION_FAILED'));
        return false;
    }


    public static function getEvent($event_id)
    {
        $event_array = unserialize(urldecode($event_id));

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT * FROM events WHERE car = :car AND event_time = :event_time;");
        $query->execute(array(
            ':car' => $event_array['c'],
            ':event_time' => $event_array['t'].'.'.$event_array['m'],
        ));
        if ($data = $query->fetchAll()) {
            return $data;
        } else
            return false;
    }





	public static function editXmlCarBit($data) {
		//'key'          'value'         'car_id'   'chapter'        'chapterno'       'entry'   'unit' 'validate'
		if (is_array($data)) {
			array_walk_recursive($data, 'Filter::XSSFilter');
			if (($data['car_id']) &&
			(self::checkAccessLevel($data['car_id'],Session::get('user_uuid')) >= 80))
			{

				if (!$xmlstring = self::getCarXmlData($data['car_id'])){ $xmlstring = Config::get('DATA_BITS');} //load default array if this is empty
				$xml = simplexml_load_string($xmlstring);

			$entrieswithunits = array('WEIGHT', 'POWER', 'VOLUME', 'INCHES', 'MILLIMETERS');
			if ($data['chapterno'] == 'NEW')
			{
					//add a new chapter
				foreach ($xml->chapter as $chapter) {
					if (((string)$chapter['name'] == $data['chapter']) &&
					   ((string)$chapter->number == '1'))
					{
						$newchapter = $xml->addChild('chapter');
						$newchapter->addAttribute('name', $data['chapter']);
						$newchapter->addAttribute('type', 'MULTIPLE');
						$newchapter->addChild('number', time());  //so that they sort older to newer
							foreach($chapter->entry as $entry) {
								$newentry = $newchapter->addChild('entry');
								$newentry->addAttribute('name', $entry['name']);
								$newentry->addAttribute('type', $entry['type']);
								if ($data['entry'] == (string)$entry['name']) //if this is the entry we're inserting
									$newentry->addChild('value');
									if ($entry->unit) {
									$unitval = ''; if ((string)$entry['type'] == 'MILLIMETERS') {$unitval='mm';} if ($entry['type'] == 'INCHES') {$unitval='in';}
									$newentry->addChild('unit', $unitval);
									}
								if ((string)$entry['type'] == 'CHOICE') { //tack on a list of choice items if it's there regardless if it's a new entry or empty copy
									foreach ($entry->item as $item) {
										$newentry->addChild('item', $item);
									}
								}


							}
						}
				}
			} else {
				// edit entry in existing chapter


				foreach ($xml->chapter as $chapter) {
					if ((string)$chapter['name'] == $data['chapter']) {
						if ($data['chapterno'] == (int)$chapter->number) {
							foreach($chapter->entry as $entry) {
								if ((string)$entry['name'] == $data['entry']) {
									if ($value = self::ValidateEntry($data['value'], $data['validate'])) {
									$entry->value = $value;
									$entry->unit = $data['unit'];
									} else {return false;}
								}
							}
						}
					}
				}

			}

				if (self::updateCarsField('car_data_xml', $xml->asXML(), $data['car_id'])) {
					return $value;
					}

				return false;

			}
		}
		return false;
	}


	public static function  addXmlCarBit($data) {
		if (is_array($data)) {
			array_walk_recursive($data, 'Filter::XSSFilter');
			if (($data['car_id']) &&
			(self::checkAccessLevel($data['car_id'],Session::get('user_uuid')) >= 80))
			{

				if (!$xmlstring = self::getCarXmlData($data['car_id'])){ $xmlstring = Config::get('DATA_BITS');} //load default array if this is empty
				$xml = simplexml_load_string($xmlstring);

//xmlbit-33 : vertė : 42b2767c-7672-4d4e-ab87-26117c1af257 : CUSTOM_DATA :  : vardas :  : text
//'key'          'value'         'car_id'   'chapter'        'chapterno'       'entry'   'unit' 'validate'


				foreach ($xml->chapter as $chapter) {
					if ((string)$chapter['name'] == $data['chapter']) {
						if ($data['chapterno'] == (int)$chapter->number) {


							$newentry = $chapter->addChild('entry');
								$newentry->addAttribute('name', self::ValidateEntry($data['entry'], 'text'));
								$newentry->addAttribute('type', 'TEXT');
								$value = self::ValidateEntry($data['value'], $data['validate']);
								$newentry->addChild('value', $value);
						}
					}
				}



				if (self::updateCarsField('car_data_xml', $xml->asXML(), $data['car_id'])) {return $value;}

				return 'false';

			}
		}
		return 'false';
	}

	public static function delXmlCarBit($data) {
				if (is_array($data)) {
			array_walk_recursive($data, 'Filter::XSSFilter');
			if (($data['car_id']) &&
			(self::checkAccessLevel($data['car_id'],Session::get('user_uuid')) >= 80))
			{

				if (!$xmlstring = self::getCarXmlData($data['car_id'])){ $xmlstring = Config::get('DATA_BITS');} //load default array if this is empty
				$xml = simplexml_load_string($xmlstring);

//xmlbit-33 : vertė : 42b2767c-7672-4d4e-ab87-26117c1af257 : CUSTOM_DATA :  : vardas :  : text
//'key'          'value'         'car_id'   'chapter'        'chapterno'       'entry'   'unit' 'validate'

				$chapter_count = 0; $entry_count = 0;
				foreach ($xml->chapter as $chapter) {
					if ((string)$chapter['name'] == $data['chapter']) {
						if ($data['chapterno'] == (int)$chapter->number) {
								foreach($chapter->entry as $entry) {
								if (((string)$entry['name'] == $data['entry']) &&
								($entry->value == $data['value']))
								{
									$chapter_to_del = $chapter_count;
									$entry_to_del = $entry_count;
								}
								$entry_count++;
							}
						}
					}
					$chapter_count++;
				}
				if ((isset($chapter_to_del)) && (isset($entry_to_del))) {
					unset($xml->chapter[$chapter_to_del]->entry[$entry_to_del]);
				}



				if (self::updateCarsField('car_data_xml', $xml->asXML(), $data['car_id'])) {return true;}

				return false;

			}
		}
		return false;
	}


	public static function updateOdoReading($car_id, $new_reading, $allow_lower) {  // do we want to save a reading that is lower than the current? we don't if the user is editing an old event
		$new_reading = intval($new_reading);
		if ($new_reading > 10) {
		$old_reading=intval(self::getCarsField('car_odo', $car_id));
    if ($oil_change = ExpiriesModel::readExpiry($car_id, 'OIL')) {
        $oil_interval =  CarModel::readCarMeta($car_id, 'oil_interval');
        $oil_change = intval($oil_change->odo + $oil_interval);
        if ($new_reading > $oil_change) {
        Session::add('feedback_negative', _('OIL_CHANGE_DUE'));
        ReminderModel::MoveReminder($car_id, '', date('Y-m-d'), _('OIL_CHANGE_DUE'), 1);
        }

    }

        if (($new_reading > $old_reading) || ($allow_lower)) {
			self::updateCarsField('car_odo', (string)$new_reading, $car_id);
		}

		}
		return true;
	}




    public static function updateCarsField($field, $value, $car_id) {

		$database = DatabaseFactory::getFactory()->getConnection();
    $query    = $database->prepare("UPDATE cars SET $field = :value WHERE id = :id");
    if ($query->execute(array(
            ':value' => $value,
            ':id' => $car_id,
        ))) { return true; }

		return false;
	}


	public static function getCarsField($field, $car_id) {


        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT $field FROM cars WHERE id = :id");
        $query->execute(array(
            ':id' => $car_id,
        ));
        if ($data = $query->fetch()) {
            return $data->$field;
        } else
            return false;

	}




    public static function editEvent($event_data)
    {
        if (!is_array($event_data)) {
            Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));

            return false;
        }

        //turim teise rasyti i sita masina?
        $level = self::checkAccessLevel($event_data['car_id'], Session::get('user_uuid'));
        if ($level < 80) {
            Session::add('feedback_negative', _('ACCESS_RESTRICTION'));
            return false;

        }



        //save current entry to a serialized array and store it in
        $event_url = urlencode(serialize(array(
            'c' => $event_data['car_id'],
            't' => $event_data['event_time'],
            'm' => $event_data['event_microtime']
        )));
        $thisevent = self::getEvent($event_url);
        $thisevent = $thisevent[0];

        $microtime = gettimeofday(); //timestamp of whent the old event was overwritten
        $time_id   = $microtime['sec'] . '-' . $microtime['usec'];

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("INSERT INTO oldevents (car, time_id, event_data) VALUES (:car,:time_id,:event_data)");
        if ($query->execute(array(
            ':car' => $event_data['car_id'],
            ':time_id' => $time_id,
            ':event_data' => serialize($thisevent),
        )))  {
            if (isset($event_data['oldversions'])) {
                $event_data['oldversions'] .= ',' . $time_id;
            } else {
                $event_data['oldversions'] = $time_id;
            }
            $new_event = self::createEvent($event_data, false);

            //reminder

            if ($event_data['reminder_toggle'] == 'on') {
                $reminder_time = substr($event_data['reminder_time'], 0, strlen($event_data['reminder_time']) - 3); //we added 3 extra zeroes at the end due to the way jqueryui datepicker interprets a timestamp
                ReminderModel::setReminder($event_data['car_id'], $reminder_time, $microtime['usec'], $event_data['reminder_content'], 'Q');

            }

            //update odo reading
			//self::updateOdoReading($event_data['car_id'], intval($event_data['event_odo']));

            return $new_event;
        } else {



            Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));

            return false;
        }






    }


    public static function deleteEvent($event_data)
    {
        if (!is_array($event_data)) {
            Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));

            return false;
        }
        //patikrinam ar vartotojas turi teisę rašyti į šitą mašiną
        $level = self::checkAccessLevel($event_data['c'], Session::get('user_uuid'));
        if ($level < 98) {
            Session::add('feedback_negative', _('ACCESS_RESTRICTION'));
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("DELETE FROM events WHERE car = :car AND event_time = :event_time");
        if ($query->execute(array(
            ':car' => $event_data['c'],
            ':event_time' => $event_data['t'].'.'.$event_data['m'],
        )))  {
            if ($event_data['source'] == 'edit')
                Session::add('feedback_positive', _('FEEDBACK_EVENT_SAVED'));
            if ($event_data['source'] == 'delete')
                Session::add('feedback_positive', _('FEEDBACK_EVENT_DELETED'));
            return true;
        }

        // default return
        Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));

        return false;
    }






    public static function getCarMakeList($fragment)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        if ($fragment == 'all') {
            //$sql   = "SELECT * FROM car_makes ORDER BY make;";
            $sql   = "SELECT * FROM car_makes WHERE make NOT LIKE '%(%' ORDER BY make;";
            $query = $database->prepare($sql);
            $query->execute();
        } else {
            $fragment = "%{$fragment}%";
            $sql      = "SELECT * FROM car_makes WHERE make LIKE :fragment;";
            $query    = $database->prepare($sql);
            $query->execute(array(
                ':fragment' => $fragment
            ));
        }

        $car_makes = array();
        if ($query->rowCount() > 0) {
            foreach ($query->fetchAll() as $car) {
                $car_makes[] = $car;
            }
        }
        return $car_makes;
    }




    public static function getCarModelList($make_id, $year)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql      = "
	   SELECT * FROM car_models
	   WHERE make_id = :make_id
		AND production_start <= :yearfrom
		AND ((production_end >= :yearto) OR (production_end IS NULL))
	   ORDER BY text;
	   ";
        $query    = $database->prepare($sql);
        $query->execute(array(
            ':make_id' => $make_id,
            ':yearfrom' => $year . '12',
            ':yearto' => $year . '12'
        ));
        $car_models = array();
        if ($query->rowCount() > 0) {
            foreach ($query->fetchAll() as $model) {
                $car_models[] = $model;
            }
        }
        return $car_models;
    }


    public static function getCarVariantList($model_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql   = "SELECT * FROM `car_variants` WHERE model_id = :model_id ORDER BY sort;";
        $query = $database->prepare($sql);
        $query->execute(array(
            ':model_id' => $model_id
        ));
        $car_variants = array();
        if ($query->rowCount() > 0) {
            foreach ($query->fetchAll() as $variant) {
                $car_variants[] = $variant;
            }
        }
        return $car_variants;
    }



    private static function Getfloat($str)
    {
        if (strstr($str, ",")) {
            $str = str_replace(".", "", $str); // replace dots (thousand seps) with blancs
            $str = str_replace(",", ".", $str); // replace ',' with '.'
        }

        if (preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.'
            $res = floatval($match[0]);
        } else {
            $res = floatval($str); // take some last chances with floatval
        }
        return number_format((float) $res, 2, '.', '');
    }



    public static function makeItSmaller($image, $car, $size)
    {
        $ok = false;
        if (!is_dir(Config::get('CAR_IMAGE_PATH') . $car . '/' . $size)) {
            if (mkdir(Config::get('CAR_IMAGE_PATH') . $car . '/' . $size, 0755)) {
                $ok = true;
            }
        } else {
            $ok = true;
        }

        if ($ok) {

            $thumb = new Imagick();
            $thumb->readImage(Config::get('CAR_IMAGE_PATH') . $car . '/' . $image);
            $thumb->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1, true);
            $thumb->writeImage(Config::get('CAR_IMAGE_PATH') . $car . '/' . $size . '/' . $image);
            $thumb->clear();
            $thumb->destroy();
            return true;
        } else {
            return false;
        }

    }



	    public static function rotateImage($event_data)    {
			if (CarModel::checkAccessLevel($event_data['car_id'],Session::get('user_uuid')) < 98) { return false; }
        if (($event_data['angle'] % 90) == 0) {
			$upload_dir = Config::get('CAR_IMAGE_PATH').$event_data['car_id'].'/';
			//need to give file a new name because it will cache in browsers
			$fnamend =  str_replace(array('.',','),'-',microtime(true));
			$ext = pathinfo($event_data['img'], PATHINFO_EXTENSION);
			$newfname = $fnamend.'.'.$ext;
			$imagick = new Imagick($upload_dir.$event_data['img']);
			$imagick->rotateimage('#00000000', $event_data['angle']);
			$imagick->writeImage($upload_dir.$fnamend.'.'.$ext);
            $imagick->clear();
            $imagick->destroy();

			//remove thumbnails to regenerate them rotated
			if (file_exists($upload_dir.$event_data['img'])) { @unlink($upload_dir.$event_data['img']); }
			$subdirs = glob($upload_dir . '*' , GLOB_ONLYDIR);
				foreach ($subdirs AS $subdir) {
					if (file_exists($subdir.'/'.$event_data['img'])) { @unlink($subdir.'/'.$event_data['img']); }
				}

			$user_images = explode(',', $event_data['user_images'])	;
			if (($key = array_search($event_data['img'], $user_images)) !== FALSE) { $user_images[$key] = $newfname;}
			$user_images = implode(',', $user_images);

				if ($event_data['wherefrom'] == 'event') {
			self::updateEventImages($user_images, $event_data['car_id'], $event_data['event_time'], $event_data['event_microtime']);
			};
			if ($event_data['wherefrom'] == 'car') {
			self::editCarImages(array('car_id' => $event_data['car_id'], 'images' => $user_images,));
			};
      if ($event_data['wherefrom'] == 'attribute') {
			self::writeAttribute($event_data['car_id'], $event_data['chapter'], 'PICTURES', $user_images, '');
			};
            return $newfname;
        } else {
            return 'false';
        }
    }



    public static function makePdfImage($image, $car, $size)
    {
        $ok = false;
        if (!is_dir(Config::get('CAR_IMAGE_PATH') . $car . '/' . $size)) {
            if (mkdir(Config::get('CAR_IMAGE_PATH') . $car . '/' . $size, 0755)) {
                $ok = true;
            }
        } else {
            $ok = true;
        }

        if ($ok) {
            $pdf   = Config::get('CAR_IMAGE_PATH') . $car . '/' . $image . '[0]';
            $png   = basename($image, '.pdf') . '.png';
            $png   = Config::get('CAR_IMAGE_PATH') . $car . '/' . $size . '/' . $png;
            $thumb = new Imagick();
            //$thumb->readImageBlob($pdf->render());
            //$thumb = $thumb->appendImages(false);
			$thumb->setGravity ( 2 );
            $thumb->setResolution($size, $size);
            $thumb->readImage($pdf);
            $thumb->setImageFormat("png");
            $thumb->scaleImage($size, $size, true);
            $thumb->writeImage($png);
            $thumb->clear();
            $thumb->destroy();
            return true;
        } else {
            return false;
        }

    }






    public static function get_extension($file_name)
    {
        $ext = explode('.', $file_name);
        $ext = array_pop($ext);
        return strtolower($ext);
    }


    private static function ConvertCassObject($object)
    {
        //casts cassandra ojects to arrays, otherwise they won't unserialize properly
        $result = array();
        foreach ($object as $key => $row) {
            if (is_object($row)) {
                $vars         = get_object_vars($row);
                $result[$key] = $vars;
            } else {
                $result[$key] = $row;
            }
        }
        ;
        return $result;
    }

    public static function getAuthUsrForCar($car_id, $owner_id, $level_from, $level_to)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT user FROM car_access WHERE car = :car_id AND user <> :owner_id AND level >= :level_from AND level <= :level_to;");
        $query->execute(array(
            ':car_id' => $car_id,
            ':owner_id' => $owner_id,
            ':level_from' => $level_from,
            ':level_to' => $level_to
        ));
        if ($data = $query->fetchAll()) {
            return $data;
        } else
            return false;




        return true;
    }


    public static function getAuthCarsForUser($user, $level_from, $level_to)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT car FROM car_access WHERE user = :user_id AND level >= :level_from AND level <= :level_to;");
        $query->execute(array(
            ':user_id' => $user,
            ':level_from' => $level_from,
            ':level_to' => $level_to
        ));
        if ($data = $query->fetchAll()) {
            return $data;
        } else
            return false;

    }


	public static function getOwnerDistance($local_owner_id, $remote_owner_id, $units)
	{
		if ($local_owner_id == $remote_owner_id) {return 0;}

		if (
			($local_coords = UserModel::getGeoCoords($local_owner_id))
			&&
			($remote_coords = UserModel::getGeoCoords($remote_owner_id))
		   )

		{
			//geolat > 0) && ($result->geolng
			return intval(self::calculateGeoDistance($local_coords->geolat, $local_coords->geolng, $remote_coords->geolat, $remote_coords->geolng, $units));

		} else {
		return '';

		}

	}

	private static function calculateGeoDistance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;

  if ($unit == "km") {
    return ($miles * 1.609344);
  } else {
        return $miles;
      }
}

private static function ValidateEntry($data, $validate, $return_empty = false) {

	//date text dec2 int
	switch($validate) {
    case 'date':
		if (preg_match('/\d{4}-\d{1,2}-\d{1,2}/', $data))
        {return $data;} else {
			if ($return_empty) {return '';}
			return false;
			}
        break;
	case 'text':
        return strip_tags(htmlspecialchars_decode($data));
    break;
	case 'hidden': //text copy, ised in expiries
        return strip_tags(htmlspecialchars_decode($data));
    break;
	case 'dec2':
		return round(floatval(str_replace(",", ".", $data)), 2);
	break;
	case 'int':
		return intval($data);
	break;
	case 'choice': //text copy, used in expiries
        return strip_tags(htmlspecialchars_decode($data));
    break;
	}

	if ($return_empty) {
		if ($validate == 'int') {return 0;}
		return '';
		} else { return false; }

}

public static function updateXmlBits($model_structure, $user_structure, $car_id) {
	// compares users xml data with the default model based on version number and updates with new fields and chapters if default model is of greater version

		if (($xml_structure = simplexml_load_string($model_structure)) &&
		($xml_user = simplexml_load_string($user_structure))) {
			if ((string)$xml_structure->version == (string)$xml_user->version) {
				return 'Structure up-to-date';
				} else {
				$xml_user->version = (string)$xml_structure->version;
				$report = '';

				foreach ($xml_structure->chapter as $structure_chapter) {
					$chapter_name = (string)$structure_chapter['name'];
					$chapter_found = false;
					if  ((((string)$structure_chapter['type'] == 'MULTIPLE') &&
						 ((string)$structure_chapter->number == '1')) or
						(!$structure_chapter['type']))  //filter out copies of multiple type, having number other than 1
					{
						foreach ($xml_user->chapter as $user_chapter) {
							if ((string)$user_chapter['name'] == $chapter_name) {
								if  ((((string)$user_chapter['type'] == 'MULTIPLE') &&
									((string)$user_chapter->number == '1')) or
									(!$user_chapter['type']))
										{
										$chapter_found = true;
										$report.='<br /><b>'.$chapter_name.'</b> found';
												foreach($structure_chapter->entry as $structure_entry) {
													$entry_name = (string)$structure_entry['name'];
													$entry_type = (string)$structure_entry['type'];
													$entry_found = false;
														foreach ($user_chapter->entry as $user_entry) {
															if (((string)$user_entry['name'] == $entry_name) &&
																((string)$user_entry['type'] == $entry_type))
															{
																$entry_found = true;
																$report.='<br />&nbsp;&nbsp;'.$entry_name.' found';
															}
														}
													if (!$entry_found) {
																$report.='<br />&nbsp;&nbsp;adding '.$entry_name;



																$newentry = $user_chapter->addChild('entry');
																$newentry->addAttribute('name', $structure_entry['name']);
																$newentry->addAttribute('type', $structure_entry['type']);
																$newentry->addChild('value');
																if ($structure_entry->unit) {
																$unitval = ''; if ((string)$structure_entry['type'] == 'MILLIMETERS') {$unitval='mm';} if ($structure_entry['type'] == 'INCHES') {$unitval='in';}
																$newentry->addChild('unit', $unitval);
																	}
																if ((string)$structure_entry['type'] == 'CHOICE') { //tack on a list of choice items if it's there regardless if it's a new entry or empty copy
																foreach ($structure_entry->item as $item) {
																	$newentry->addChild('item', $item);
																	}
																}



														}

												}
										}
							}
						}
					}

						if (!$chapter_found) {
								$report.='<br />adding chapter '.$chapter_name;

								$newchapter = $xml_user->addChild('chapter');
								$newchapter->addAttribute('name', $chapter_name);
								if ((string)$structure_chapter['type'] == 'MULTIPLE') {
									$newchapter->addAttribute('type', 'MULTIPLE');
									$newchapter->addChild('number', '1');
								}
									foreach($structure_chapter->entry as $structure_entry) {
									$newentry = $newchapter->addChild('entry');
									$newentry->addAttribute('name', $structure_entry['name']);
									$newentry->addAttribute('type', $structure_entry['type']);
									$newentry->addChild('value');
									if ($structure_entry->unit) {
									$unitval = ''; if ((string)$structure_entry['type'] == 'MILLIMETERS') {$unitval='mm';} if ($structure_entry['type'] == 'INCHES') {$unitval='in';}
									$newentry->addChild('unit', $unitval);
										}
									if ((string)$structure_entry['type'] == 'CHOICE') { //tack on a list of choice items if it's there regardless if it's a new entry or empty copy
									foreach ($structure_entry->item as $item) {
										$newentry->addChild('item', $item);
										}
									}
								}






							}

				}

				if (self::updateCarsField('car_data_xml', $xml_user->asXML(), $car_id)) {

				return 'Updating structure: versions '.$xml_structure->version.' vs '.$xml_user->version.'<br />'.$report;
				//return '<pre>'.htmlspecialchars($xml_user->asXML()).'</pre>';
						}
				}

		} else {return false;}

	}

    public static function delImage($event_data) {
		if (CarModel::checkAccessLevel($event_data['car_id'],Session::get('user_uuid')) >= 98) {

			if ($event_data['wherefrom'] == 'event') {
			self::updateEventImages($event_data['user_images'], $event_data['car_id'], $event_data['event_time'], $event_data['event_microtime']);
			};
			if ($event_data['wherefrom'] == 'car') {
			self::editCarImages(array(
          'car_id' => $event_data['car_id'],
          'images' => $event_data['user_images'],
          ));
			};
      if ($event_data['wherefrom'] == 'attribute') {
			self::writeAttribute($event_data['car_id'], $event_data['chapter'], 'PICTURES', $event_data['user_images'], '');
			};

			$upload_dir = Config::get('CAR_IMAGE_PATH').$event_data['car_id'].'/';

			if (file_exists($upload_dir.$event_data['img'])) { @unlink($upload_dir.$event_data['img']); }

			$subdirs = glob($upload_dir . '*' , GLOB_ONLYDIR);
				foreach ($subdirs AS $subdir) {
					if (file_exists($subdir.'/'.$event_data['img'])) { @unlink($subdir.'/'.$event_data['img']); }
				}

		return '<i class="icon-trash"> </i>';
		} else {
			return 'false';
		}
	}

	public static function updateEventImages($images, $car_id, $time, $microtime) {


		if (!$car_id) {
            Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));
            return false;
        }
        //patikrinam ar vartotojas turi teisę rašyti į šitą mašiną
        if (self::checkAccessLevel($car_id, Session::get('user_uuid')) < 98) {
            Session::add('feedback_negative', _('ACCESS_RESTRICTION'));
            return false;
        }

		 $imagelist   = array();

        if ($images) {
            $imagelist   = explode(',', $images);

			}

      $database = DatabaseFactory::getFactory()->getConnection();
    $query    = $database->prepare("UPDATE events SET images = :images WHERE car = :car AND event_time = :event_time");
    if ($query->execute(array(
            ':images' => serialize($imagelist),
            ':car' => $car_id,
            ':event_time' => $time.'.'.$microtime
        ))) { return true; }

        // default return
        Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));
        return false;
	}


	public static function saveExpiries($post_data, $xml_data)
    {
		$posted_values = array();
		foreach($post_data AS $key=>$value) {
			if (substr($key, 0, 6) == 'expbit') {
			 $keyparts	= explode('-', $key);
			 $posted_values[$keyparts[1]][$keyparts[2]] = $value;
			}
		}


		if ($xml = simplexml_load_string($xml_data)) {

				foreach ($xml->expiry as $expiry) {


					$valid_until          = self::ValidateEntry($posted_values[(string)$expiry['name']]['VALID_UNTIL'], 'date', true);
					$previous_valid_until = self::ValidateEntry($posted_values[(string)$expiry['name']]['PREVIOUS_VALID_UNTIL'], 'date', true);
					$regular_reminder     = self::ValidateEntry($posted_values[(string)$expiry['name']]['REMINDER'], 'hidden', true);
					$early_reminder       = self::ValidateEntry($posted_values[(string)$expiry['name']]['EARLY_REMINDER'], 'hidden', true);

					$belt_until = ''; $belt_old = ''; $belt_previous = '';

					if ((string)$expiry['name'] == 'TIMING_BELT') {
						$belt_until       = self::ValidateEntry($posted_values[(string)$expiry['name']]['ODOMETER'], 'int', true);
						$belt_old         = self::ValidateEntry($posted_values[(string)$expiry['name']]['OLD_ODOMETER'], 'int', true);
						$belt_previous    = self::ValidateEntry($posted_values[(string)$expiry['name']]['PREVIOUS_ODOMETER'], 'int', true);

						if ($belt_previous !== $belt_until) {
						$belt_old = $belt_previous;
						};
					}




					if ($valid_until !== $previous_valid_until) {
						//if the user has changed the date we move the reminders:
						//MoveReminder(car_id, old regular reminder, new regular reminder, content, offset); offset in days, e.g. set reminder 14 days in advance
						$date = DateTime::createFromFormat ('Y-m-d H:i' , $valid_until.' 11:00' );
						$timestamp = $date->getTimestamp();
						$content = sprintf(_('%s_EXPIRES_ON_%s'), (string)$expiry['name'], strftime('%x', $timestamp));
						$regular_reminder = ReminderModel::MoveReminder($post_data['car_id'],$regular_reminder, $valid_until, $content, 1);
						$early_reminder = ReminderModel::MoveReminder($post_data['car_id'],$early_reminder, $valid_until, $content, 14);
						//early reminder - 14 days, regular - 1 day
										}



						foreach($expiry->entry as $entry) {
									if ((string)$entry['name'] == 'PREVIOUS_VALID_UNTIL' ) { $value_to_enter = $valid_until;} //enter the current date in the previous date field to detect the next date change
								elseif ((string)$entry['name'] == 'REMINDER' ) { $value_to_enter = $regular_reminder;}
								elseif ((string)$entry['name'] == 'EARLY_REMINDER' ) { $value_to_enter = $early_reminder;}
								elseif ((string)$entry['name'] == 'PREVIOUS_ODOMETER' ) { $value_to_enter = $belt_until;}
								elseif ((string)$entry['name'] == 'OLD_ODOMETER' ) { $value_to_enter = $belt_old;}
									else {
									$value_to_enter = self::ValidateEntry($posted_values[(string)$expiry['name']][(string)$entry['name']], strtolower($entry['type']), true);
									}
									$entry->value = $value_to_enter;
								//	print $entry['name'].' entering value '.$value_to_enter.'<br>';






							}

				}

		if (self::updateCarsField('car_expiries', $xml->asXML(), $post_data['car_id'])) {
			Session::add('feedback_positive', _('FEEDBACK_EXPIRY_EDIT_SUCCESSFUL'));
			return true;
			}




		}
		Session::add('feedback_negative', _('FEEDBACK_EXPIRY_EDIT_FAILED'));
		return false;

	}


	public static function updateExpiryBits($model_structure, $user_structure, $car_id) {
	// compares expiry xml data with the default model based on version number and updates with new fields and chapters if default model is of greater version

		if (($xml_structure = simplexml_load_string($model_structure)) &&
		($xml_user = simplexml_load_string($user_structure))) {
			$old_version = $xml_user->version;
			if ((string)$xml_structure->version == (string)$xml_user->version) {
				return 'Version '.$old_version.', structure up-to-date';
				} else {
				$xml_user->version = (string)$xml_structure->version;
				$report = '';

				foreach ($xml_structure->expiry as $structure_chapter) {
					$chapter_name = (string)$structure_chapter['name'];
					$chapter_found = false;

						foreach ($xml_user->expiry as $user_chapter) {
							if ((string)$user_chapter['name'] == $chapter_name) {

										$chapter_found = true;
										$report.='<br /><b>'.$chapter_name.'</b> found';
												foreach($structure_chapter->entry as $structure_entry) {
													$entry_name = (string)$structure_entry['name'];
													$entry_type = (string)$structure_entry['type'];
													$entry_found = false;
														foreach ($user_chapter->entry as $user_entry) {
															if (((string)$user_entry['name'] == $entry_name) &&
																((string)$user_entry['type'] == $entry_type))
															{
																$entry_found = true;
																$report.='<br />&nbsp;&nbsp;'.$entry_name.' found';
															}
														}
													if (!$entry_found) {
																$report.='<br />&nbsp;&nbsp;adding '.$entry_name;



																$newentry = $user_chapter->addChild('entry');
																$newentry->addAttribute('name', $structure_entry['name']);
																$newentry->addAttribute('type', $structure_entry['type']);
																$newentry->addChild('value');
																if ((string)$structure_entry['type'] == 'CHOICE') { //tack on a list of choice items if it's there regardless if it's a new entry or empty copy
																foreach ($structure_entry->item as $item) {
																	$newentry->addChild('item', $item);
																	}
																}



														}

												}
							}
						}

						if (!$chapter_found) {
								$report.='<br />adding chapter '.$chapter_name;

								$newchapter = $xml_user->addChild('expiry');
								$newchapter->addAttribute('name', $chapter_name);

									foreach($structure_chapter->entry as $structure_entry) {
									$newentry = $newchapter->addChild('entry');
									$newentry->addAttribute('name', $structure_entry['name']);
									$newentry->addAttribute('type', $structure_entry['type']);
									$newentry->addChild('value');
									if ($structure_entry->unit) {
									$unitval = ''; if ((string)$structure_entry['type'] == 'MILLIMETERS') {$unitval='mm';} if ($structure_entry['type'] == 'INCHES') {$unitval='in';}
									$newentry->addChild('unit', $unitval);
										}
									if ((string)$structure_entry['type'] == 'CHOICE') { //tack on a list of choice items if it's there regardless if it's a new entry or empty copy
									foreach ($structure_entry->item as $item) {
										$newentry->addChild('item', $item);
										}
									}
								}






							}

				}

				if (self::updateCarsField('car_expiries', $xml_user->asXML(), $car_id)) {
		//	print '<pre>'.htmlspecialchars($xml_user->asXML()).'</pre>';

				return 'Updating structure: versions '.$xml_structure->version.' vs '.$old_version.'<br />'.$report;
				//return '<pre>'.htmlspecialchars($xml_user->asXML()).'</pre>';
						}
				}

		} else {return false;}

	return $report;
	}

	public static function addTodoItem($event_id, $car_id, $event_content)
	{
		$current_todos = self::getCarsField('car_outstanding', $car_id);
		$todos = unserialize($current_todos);
		if (!is_array($todos)) { $todos = array();  }
		$todos[$event_id] = $event_content;
		$updated_todos = serialize($todos);
		if (self::updateCarsField('car_outstanding', $updated_todos, $car_id)) {return true;}
		return false;
	}


	public static function getEventOutstandingStatus($event_id) //checks if this event has a todo item set
    {
        $event_array = unserialize(urldecode($event_id));
		$current_todos = self::getCarsField('car_outstanding', $event_array['c']);
		$todos = unserialize($current_todos);
		if (!is_array($todos)) { return false;  }
		$event_key = $event_array['t'].':'.$event_array['m'];
		if (array_key_exists($event_key, $todos)) {return true;}
		return false;
    }

		public static function removeTodoItem($event_timestamp, $car_id)
	{
		$current_todos = self::getCarsField('car_outstanding', $car_id);
		$todos = unserialize($current_todos);
		if (!is_array($todos)) { $todos = array();  }
		if (array_key_exists($event_timestamp, $todos)) {
			unset($todos[$event_timestamp]);
			}
		$updated_todos = serialize($todos);
		if (self::updateCarsField('car_outstanding', $updated_todos, $car_id)) {return true;}
		return false;
	}

		public static function trunc($phrase, $max_words) { //truncate long phrase
		$phrase_array = explode(' ',$phrase);
	   if(count($phrase_array) > $max_words && $max_words > 0)
      $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
	   return $phrase;
	}


	public static function ExecuteTransfer($car_id, $old_owner, $new_owner) {


    $database = DatabaseFactory::getFactory()->getConnection();
    $query    = $database->prepare("UPDATE cars SET owner = :owner WHERE id = :id AND owner = :old_owner");
        if ($query->execute(array(
            ':owner' => $new_owner,
            ':id' => $car_id,
            ':old_owner' => $old_owner,
        ))) {

		self::transferCarAccessEntry($car_id, $old_owner, $new_owner, UserModel::getUserNameByUUid($new_owner));

		Session::add('feedback_positive', _('FEEDBACK_CAR_TRANSFER_SUCCESS'));
		return true;
	}
		Session::add('feedback_negative', _('FEEDBACK_CAR_TRANSFER_FAILED'));
		return false;

	}


	public static function OKToTransferCar($car_id, $old_owner, $new_owner)
	{
		if ($new_owner == Session::get('user_uuid')) {
			$database = DatabaseFactory::getFactory()->getConnection();
			$sql = "SELECT * FROM car_transfers WHERE car_id = :car_id AND old_owner = :old_owner AND new_owner = :new_owner";
			$query = $database->prepare($sql);
			if	($query->execute(array( ':car_id' => $car_id,
										':old_owner' => $old_owner,
										':new_owner' => $new_owner,
							  )))
			{

				return $query->fetch();

				 }

		}
			return false;


	}


	    /*
    `car_transfers` (
`id` int(11) NOT NULL,
  `car_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `old_owner` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `new_owner` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `time` bigint(20) NOT NULL
    */



	public static function transferCarAccessEntry($car_id, $old_owner, $new_owner, $new_owner_name) //mysql
    {


		self::deleteCarAccessNodesForUser($car_id, $new_owner); //deletes entries <99, ie non-owner

        $database = DatabaseFactory::getFactory()->getConnection();

		$sql = "UPDATE car_access SET user = :new_user, user_name = :new_user_name WHERE car = :car_id AND user = :old_user AND level = 99";
		$query = $database->prepare($sql);
        if	($query->execute(array(
								':new_user' => $new_owner,
								':new_user_name' => $new_owner_name,
								':old_user' => $old_owner,
								':car_id' => $car_id,

						  )))
		{$count =  $query->rowCount();

			return $count; } else {return false;}


    }


	    public static function updateCarAccessName($car_id, $new_name)    //when saving car id with editCarId update copies of car name in car_access table
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("UPDATE car_access SET car_name = :new_name WHERE car = :car");
        $query->execute(array(
            ':new_name' => $new_name,
            ':car' => $car_id
        ));
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

	public static function queueCarTransfer($car_id, $old_owner, $new_owner)
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		$sql = "INSERT INTO car_transfers	(car_id, old_owner, new_owner, time)
                    VALUES (:car_id, :old_owner, :new_owner, :time)";
		$query = $database->prepare($sql);
        if	($query->execute(array( ':car_id' => $car_id,
									':old_owner' => $old_owner,
									':new_owner' => $new_owner,
									':time' => time(),
						  )))
		{$count =  $query->rowCount();

			return $count; } else {return false;}


	}


	public static function unqueueCarTransfer($transfer_id)
    {

		   if (!$transfer_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM car_transfers WHERE id = :transfer_id;";
        $query = $database->prepare($sql);
        $query->execute(array(':transfer_id' => $transfer_id));

        if ($query->rowCount() == 1) {
            return true;
        }

        return false;

    }

	public static function getTransferRequests($uuid)
	{
		     $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT * FROM car_transfers WHERE new_owner = :uuid");
        $query->execute(array(':uuid' => $uuid));
       $rows = array();
        foreach ($query->fetchAll() as $row) {
            $rows[] = array(
                                'id' => $row->id,
                                'car_id' => $row->car_id,
                                'old_owner' => $row->old_owner,
                                'new_owner' => $row->new_owner,
                                'time' => $row->time
                                );
        }
        return $rows;

	}

	public static function addAttrPicture($car_id, $picture, $chapter)
	{
		$attr_images=self::getCarsField('attr_images', $car_id);
		if ($attr_images) {$attr_images = unserialize($attr_images);} else {$attr_images = array(); }
		if (($key = array_search($picture, $attr_images[$chapter])) == false) {	$attr_images[$chapter][] = $picture;}
		if (self::updateCarsField('attr_images', serialize($attr_images), $car_id)) { return true; }
		return false;
	}

	public static function removeAttrPicture($car_id, $picture, $chapter)
	{
		$attr_images=self::getCarsField('attr_images', $car_id);
		if ($attr_images) {$attr_images = unserialize($attr_images);} else {$attr_images = array(); }
		if (($key = array_search($picture, $attr_images[$chapter])) !== false) { unset($attr_images[$chapter][$key]);
}


		if (self::updateCarsField('attr_images', serialize($attr_images), $car_id)) { return true; }

		return false;
	}

	public static function vindecode($action, $vin='') {
		$apiPrefix = Config::get('VINDECODER_PREFIX');
		$apikey    = Config::get('VINDECODER_APIKEY');
		$secretkey = Config::get('VINDECODER_SECRETKEY');
		switch ($action) {
    		case 'info':
				if (strlen($vin) == 17) {
					$actionid = "info-".$vin;
					$controlsum = substr(sha1("{$actionid}|{$apikey}|{$secretkey}"), 0, 10);
					$data = file_get_contents("{$apiPrefix}/{$apikey}/{$controlsum}/decode/info/{$vin}.json", false);
					$result = json_decode($data);
					return $result;
				} else return false;
				break;
			case 'decode':
				if (strlen($vin) == 17) {
					$controlsum = substr(sha1("{$vin}|{$apikey}|{$secretkey}"), 0, 10);
					$data = file_get_contents("{$apiPrefix}/{$apikey}/{$controlsum}/decode/{$vin}.json", false);
					$result = json_decode($data);
					return $result;
				} else return false;
				break;
			case 'balance':
					$controlsum = substr(sha1("balance|{$apikey}|{$secretkey}"), 0, 10);
					$data = file_get_contents("{$apiPrefix}/{$apikey}/{$controlsum}/balance.json", false);
					$result = json_decode($data);
					return $result;
		}
	}

	public static function checkvin($vin) { //returns true if vin lists Model

		if (self::readvincache($vin)) {return true;}
		if ($vindata = self::vindecode('info', $vin)) {
			if(property_exists($vindata, "decode")) {
			$vindata = $vindata->decode;
				};
			};
        if ((is_array($vindata)) && (in_array('Model', $vindata))) {return true;}

		return false;
	}

	public static function capturevin($vin) { //reads VIN info from API and saves it to a new db entry

		if ($vindata = self::readvincache($vin)) {return $vindata;}

		$vindata = self::vindecode('decode', $vin);

		$database = DatabaseFactory::getFactory()->getConnection();

		$sql = "INSERT INTO vin_cache (vin, data) VALUES (:vin, :data);";
		$query = $database->prepare($sql);
        if	($query->execute(array(':vin' => $vin,
		                      ':data' => serialize($vindata),
						  )))
		{ return $vindata; }



		return false;
	}

	private static function readvincache($vin) {

		$database = DatabaseFactory::getFactory()->getConnection();
		$sql = "SELECT data FROM vin_cache WHERE vin = :vin";
			$query = $database->prepare($sql);
			if	($query->execute(array( ':vin' => $vin,
							  )))
			{
				if ($result = $query->fetch())
				{ return unserialize($result->data); }
				 }

				 return false;

	}

	public static function parsevindata($data) {
		$decode = $data->decode;
		$result = array();
		foreach($decode as $object) {
			$result[$object->label] = $object->value;
		}
		return $result;
	}

    public static function setEventVisibility($car_id, $time, $microtime, $visibility = 'prv') { //pub or orv


		if (!$car_id) {
            Session::add('feedback_negative', _('FEEDBACK_EVENT_EDIT_FAILED'));
            return 'false';
        }
        //patikrinam ar vartotojas turi teisę rašyti į šitą mašiną
        if (self::checkAccessLevel($car_id, Session::get('user_uuid')) < 98) {
            Session::add('feedback_negative', _('ACCESS_RESTRICTION'));
            return 'false';
        }

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "UPDATE events SET visibility = :visibility WHERE car = :car AND event_time = :event_time";
			  $query = $database->prepare($sql);
			  if	($query->execute(array
                ( ':visibility' => $visibility,
                  ':car' => $car_id,
                  ':event_time' => $time.'.'.$microtime,
                  )
            )) {
            return 'true';
        }

        // default return

        return 'false';
	}


	public static function is_valid_car_id($string) {
		if (strlen($string) == 36) {
			if( preg_match("/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i",$string)){
					return true;
				}else{
					return false;
				}
		}
		return false;
	}

	  public static function getCarIdFiltered($car_name, $owner_id) //filtered, to be used only when no lookup table results are produced
    {


    	$database = DatabaseFactory::getFactory()->getConnection();
			$sql = "SELECT id FROM cars WHERE owner = :owner AND car_name = :car_name";
			$query = $database->prepare($sql);
			if	($query->execute(array(
										':owner' => $owner_id,
										':car_name' => $car_name,
							  )))
			{

        	if ($result = $query->fetch())
				{
         return $result->id;
         }

				 }


        return false;
    }



	public static function getCarId($car_name, $owner_id) //filtered, cassandra, top be used only when no lookup table results are produced
		{
		$database = DatabaseFactory::getFactory()->getConnection();
		$sql = "SELECT car_id FROM car_lookup WHERE car_name = :car_name and owner = :owner LIMIT 1";
			$query = $database->prepare($sql);
			if	($query->execute(array( ':car_name' => $car_name,
									   ':owner' => $owner_id,
							  )))
			{
				if ($result = $query->fetch())
				{
         return $result->car_id;
         }
			}

				if ($filtered = self::getCarIdFiltered($car_name, $owner_id)) {return $filtered;}
				//if lookup table does not have the data, we look in the cass table


				 return false;

	}




  public static function parsetimestamp($data) {
    if (strpos($data, '.') > 2) {
    $explosion =  explode('.', $data);
    $result = array();
		$result['seconds'] = $explosion[0];
    $result['microseconds'] = $explosion[1];
		return $result;

    } else return false;

	}

  public static function timestampToMysql($timestamp) {
    return date("Y-m-d H:i:s", $timestamp);
  }

  //*****NEW_ATTRIBUTES*********

  public static function writeAttribute($car_id, $chapter, $attribute, $itemvalue, $unit)   //writes a single attribute
    {
        $database = DatabaseFactory::getFactory()->getConnection();
		$sql = "INSERT INTO attributes (car_id, chapter, attribute, item, unit) VALUES (:car_id, :chapter, :attribute, :item, :unit) ON DUPLICATE KEY UPDATE item = :item, unit = :unit";
		$query = $database->prepare($sql);
        if	($query->execute(array(
        ':car_id' => $car_id,
		    ':chapter' => $chapter,
		    ':attribute' => $attribute,
        ':item' => $itemvalue,
        ':unit' => $unit,
						  )))
		{ return true; }

    return false;
    }

     public static function readAttribute($car_id, $chapter, $attribute) //reads a single attribute
    {
        $database = DatabaseFactory::getFactory()->getConnection();
		$sql = "SELECT item, unit FROM attributes WHERE car_id = :car_id AND chapter = :chapter AND attribute = :attribute;";
		$query = $database->prepare($sql);
        $query->execute(array(
        ':car_id' => $car_id,
		    ':chapter' => $chapter,
		    ':attribute' => $attribute,
						  ));
        if ($data = $query->fetch()) {
            return $data->item;
        }

    return false;
    }

         public static function readAttributes($car_id, $chapter) //reads attribute values for a chapter
    {
        $database = DatabaseFactory::getFactory()->getConnection();
		$sql = "SELECT attribute, item, unit FROM attributes WHERE car_id = :car_id AND chapter = :chapter;";
		$query = $database->prepare($sql);
        $query->execute(array(
        ':car_id' => $car_id,
		    ':chapter' => $chapter,
		    					  ));
        if ($data = $query->fetchAll()) {
        $result = array();
            foreach ($data as $item) {
            $result[$item->attribute]['value'] = $item->item;
            $result[$item->attribute]['unit'] = $item->unit;
            };
         return $result;

        }

      return false;
      }

        public static function readAllAttributes($car_id) //reads attribute values for all chapter
    {
        $database = DatabaseFactory::getFactory()->getConnection();
		$sql = "SELECT chapter, attribute, item FROM attributes WHERE car_id = :car_id;";
		$query = $database->prepare($sql);
        $query->execute(array(
        ':car_id' => $car_id,
		    					  ));
        if ($data = $query->fetchAll()) {
            return $data;
        }

    return false;
    }


    public static function getAttributeChapters()  //gets all preset chapters
    {
        $database = DatabaseFactory::getFactory()->getConnection();
		$sql = "SELECT DISTINCT chapter FROM attr_names;";
		$query = $database->prepare($sql);
        $query->execute();
        $response = array();
        if ($data = $query->fetchAll()) {
            foreach ($data as $item) {
              $response[] = $item->chapter;
            }
            return $response;
        }

    return false;


    }



    public static function getAttributeChapterItems($chapter)  //gets all items for a chapter
    {
        $database = DatabaseFactory::getFactory()->getConnection();
		$sql = "SELECT * FROM attr_names WHERE chapter = :chapter ORDER BY ord;";
		$query = $database->prepare($sql);
        $query->execute(array(
        ':chapter' => $chapter,
        ));
        if ($data = $query->fetchAll()) {
            return $data;
        }

    return false;


    }


      public static function commitAttribute($data) {
    //'key'          'value'         'car_id'   'chapter'      'entry'   'validate' 'unit'
    if (is_array($data)) {
			array_walk_recursive($data, 'Filter::XSSFilter');
			if (($data['car_id']) &&
			(self::checkAccessLevel($data['car_id'],Session::get('user_uuid')) >= 80))
			{
         if ($value = self::ValidateEntry($data['value'], $data['validate'])) {
         $unit = (isset($data['unit'])) ? substr(strip_tags($data['unit']), 0, 3) : '';
        if (self::writeAttribute($data['car_id'], $data['chapter'], $data['entry'], $value, $data['unit']))
        {
        return $value;
        }
        }
      }
      }

      return false;
  }



  public static function readCarMeta($car_id, $meta_key)
{
    //this is  readable by an outsider
 $database = DatabaseFactory::getFactory()->getConnection();
 if (is_array($meta_key))
 {
     array_walk_recursive($meta_key, 'Filter::XSSFilter');
     $pieces = array();
    foreach ($meta_key as $thiskey)
    {
        $pieces[] = 'meta_key = "'.$thiskey.'"';
    }
    $buffer = '('.implode(' OR ', $pieces).')';
     $sql = "SELECT meta_key, meta_value FROM car_meta WHERE car_id = :car_id AND $buffer ;";
     $query = $database->prepare($sql);
      $query->execute(array(':car_id' => $car_id));
      if ($data = $query->fetchAll()) {
          $response = array();
          foreach ($data as $row) {
              $response[$row->meta_key] = $row->meta_value;
          }
          return $response;
      }
          return false;


 } else {
$sql = "SELECT meta_value FROM car_meta WHERE car_id = :car_id AND meta_key = :meta_key;";

$query = $database->prepare($sql);
 $query->execute(array(
 ':car_id' => $car_id,
   ':meta_key' => $meta_key,
                         ));
                         if ($data = $query->fetch()) {
                             return $data->meta_value;
                         }
};

return false;
}

public static function writeCarMeta($car_id, $meta_key, $meta_value)
  {
      if (self::checkAccessLevel($car_id, Session::get('user_uuid')) < 98) {
          Session::add('feedback_negative', _('ACCESS_RESTRICTION'));
          return 'false';
      }
      $database = DatabaseFactory::getFactory()->getConnection();
      $sql = "INSERT INTO car_meta (car_id, meta_key, meta_value) VALUES (:car_id, :meta_key, :meta_value) ON DUPLICATE KEY UPDATE meta_value = :meta_value";
      $query = $database->prepare($sql);
      if	($query->execute(array(
      ':car_id' => $car_id,
          ':meta_key' => $meta_key,
          ':meta_value' => $meta_value,
                        )))
      { return true; }

  return false;
  }


}
