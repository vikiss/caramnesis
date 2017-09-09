<?php
/*
CREATE TABLE `expiries` (
`car_id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
`expiry` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
`expiration` bigint(20) NULL,
`prev_expiration` bigint(20) NULL,
`description` mediumtext COLLATE utf8_unicode_ci,
`reference` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
`ord` tinyint(4) NULL,
 PRIMARY KEY (`car_id`,`expiry`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 `reminder`, early_reminder (timestamp:microtime)
odo, prev_odo int(8)






$saved->car_id
$saved->expiry
$saved->expiration
$saved->prev_expiration
$saved->description
$saved->reference
$saved->ord
$saved->reminder
$saved->early_reminder
$saved->odo
$saved->prev_odo

*/


class ExpiriesModel
{

    public static function structure()   //expiry data structure
{
$structure = array(    //display title => db field
  'INSURANCE' => array('VALID_UNTIL' => 'expiration', 'DESCRIPTION' => 'description', 'POLICY_NO' => 'reference', 'PREVIOUS_VALID_UNTIL' => 'prev_expiration'),
  'INSPECTION'  => array('VALID_UNTIL' => 'expiration', 'DESCRIPTION' => 'description', 'PREVIOUS_VALID_UNTIL' => 'prev_expiration'),
  'TAXES'  => array('VALID_UNTIL' => 'expiration', 'PREVIOUS_VALID_UNTIL' => 'prev_expiration', 'DESCRIPTION' => 'description'),
  'OIL' => array('ODOMETER' => 'odo', 'DESCRIPTION' => 'description', 'PREVIOUS_ODOMETER' => 'prev_odo', 'LAST_CHANGE_DATE' => 'prev_expiration'),
  'TIMING_BELT'  => array('ODOMETER' => 'odo', 'DESCRIPTION' => 'description', 'PREVIOUS_ODOMETER' => 'prev_odo', 'LAST_CHANGE_DATE' => 'prev_expiration'),
);

return $structure;
}




     public static function writeExpiry($data)   //writes a single expiry
    {


    if (is_array($data)) {
			array_walk_recursive($data, 'Filter::XSSFilter');
			if (($data['car_id']) &&
			(CarModel::checkAccessLevel($data['car_id'],Session::get('user_uuid')) >= 80))
			{
           $saved = self::readExpiry($data['car_id'], $data['chapter']);
           $data_to_write = array(
                'car_id' => $data['car_id'],
                'expiry' => $data['chapter'],
          );


 switch($data['chapter'])
        {
        case 'INSURANCE':
        if ($data['entry'] == 'expiration') //user entered a new date, set reminders, move current entry if any to previous date field
        {

        $regular_reminder = ''; $early_reminder = '';
        if ($saved) {
          if ($saved->reminder) {$regular_reminder = $saved->reminder; };
          if ($saved->early_reminder) {$early_reminder = $saved->early_reminder; };
          if ($saved->expiration) {$data_to_write['prev_expiration'] = $saved->expiration; };
        }
        		$date = DateTime::createFromFormat ('Y-m-d H:i' , $data['value'].' 11:00' );
						$timestamp = $date->getTimestamp();
						$content = sprintf(_('%s_EXPIRES_ON_%s'), $data['chapter'], strftime('%x', $timestamp));
						$regular_reminder = ReminderModel::MoveReminder($data['car_id'],$regular_reminder, $data['value'], $content, 1);
						$early_reminder = ReminderModel::MoveReminder($data['car_id'],$early_reminder, $data['value'], $content, 14);						//early reminder - 14 days, regular - 1 day

            $data_to_write['expiration'] = $timestamp;
            $data_to_write['reminder'] = $regular_reminder;
            $data_to_write['early_reminder'] = $early_reminder;
        } else { //user entered a textual field
            $data_to_write[$data['entry']] = $data['value'];
        }




        break;
        case 'INSPECTION':

         if ($data['entry'] == 'expiration') //user entered a new date, set reminders, move current entry if any to previous date field
        {

        $regular_reminder = ''; $early_reminder = '';
        if ($saved) {
          if ($saved->reminder) {$regular_reminder = $saved->reminder; };
          if ($saved->early_reminder) {$early_reminder = $saved->early_reminder; };
          if ($saved->expiration) {$data_to_write['prev_expiration'] = $saved->expiration; };
        }
        		$date = DateTime::createFromFormat ('Y-m-d H:i' , $data['value'].' 11:00' );
						$timestamp = $date->getTimestamp();
						$content = sprintf(_('%s_EXPIRES_ON_%s'), $data['chapter'], strftime('%x', $timestamp));
						$regular_reminder = ReminderModel::MoveReminder($data['car_id'],$regular_reminder, $data['value'], $content, 1);
						$early_reminder = ReminderModel::MoveReminder($data['car_id'],$early_reminder, $data['value'], $content, 14);						//early reminder - 14 days, regular - 1 day

            $data_to_write['expiration'] = $timestamp;
            $data_to_write['reminder'] = $regular_reminder;
            $data_to_write['early_reminder'] = $early_reminder;
        } else { //user entered a textual field
            $data_to_write[$data['entry']] = $data['value'];
        }


        break;
        case 'TAXES':


                if ($data['entry'] == 'expiration') //user entered a new date, set reminders, move current entry if any to previous date field
        {

        $regular_reminder = ''; $early_reminder = '';
        if ($saved) {
          if ($saved->reminder) {$regular_reminder = $saved->reminder; };
          if ($saved->early_reminder) {$early_reminder = $saved->early_reminder; };
          if ($saved->expiration) {$data_to_write['prev_expiration'] = $saved->expiration; };
        }
        		$date = DateTime::createFromFormat ('Y-m-d H:i' , $data['value'].' 11:00' );
						$timestamp = $date->getTimestamp();
						$content = sprintf(_('%s_EXPIRES_ON_%s'), _($data['chapter']), strftime('%x', $timestamp));
						$regular_reminder = ReminderModel::MoveReminder($data['car_id'],$regular_reminder, $data['value'], $content, 1);
						$early_reminder = ReminderModel::MoveReminder($data['car_id'],$early_reminder, $data['value'], $content, 14);						//early reminder - 14 days, regular - 1 day

            $data_to_write['expiration'] = $timestamp;
            $data_to_write['reminder'] = $regular_reminder;
            $data_to_write['early_reminder'] = $early_reminder;
        } else { //user entered a textual field
            $data_to_write[$data['entry']] = $data['value'];
        }


        break;
        case 'OIL':

        if ($data['entry'] == 'odo') //move current entry if any to previous odo field
        {

        if ($saved) {
          if ($saved->odo) {$data_to_write['prev_odo'] = $saved->odo; };
        }

            $data_to_write['odo'] = intval($data['value']);
        } else { //user entered a textual field
            $data_to_write[$data['entry']] = $data['value'];
        }



        break;
        case 'TIMING_BELT':

         if ($data['entry'] == 'odo') //move current entry if any to previous odo field
        {

        if ($saved) {
          if ($saved->odo) {$data_to_write['prev_odo'] = $saved->odo; };
        }

            $data_to_write['odo'] = intval($data['value']);
        } else { //user entered a textual field
            $data_to_write[$data['entry']] = $data['value'];
        }

        break;
        }
  if (self::CommitExpiry($data_to_write))  return $data['value'];

               /*



  key : expitem-3
  value : aaa
  car_id : 0cb71f32-b56a-49ae-8984-476eb5c0e3a9
  chapter : INSURANCE
  entry : reference
  validate : text
  siblings : [{"expiration":"sometihng"},{"description":"sometihng"},{"reference":"aaa"},{"prev_expiration":""}]


}

  $dis = '[{"expiration":"sometihng"},{"description":"sometihng"},{"reference":"aaa"},{"prev_expiration":""}]';
$dat = json_decode($dis, true);
foreach ($dat as $piece) {
  foreach ($piece as $key => $value) {
    print $key.' :: '.$value;
  }
}



        $database = DatabaseFactory::getFactory()->getConnection();
		$sql = "INSERT INTO expiries (car_id, expiry, expiration, prev_expiration, description, reference, ord, reminder, early_reminder, odo, prev_odo) VALUES
     (:car_id, :expiry, :expiration, :prev_expiration, :description, :reference, :ord) ON DUPLICATE KEY UPDATE expiration = :expiration, prev_expiration = :prev_expiration, description = :description, reference = :reference, ord = :ord";
		$query = $database->prepare($sql);
        if	($query->execute(array(
        ':car_id' => $data['car_id'],
        ':expiry' => $data['expiry'],
        ':expiration' => $data['expiration'],
        ':prev_expiration' => $data['prev_expiration'],
        ':description' => $data['description'],
        ':reference' => $data['reference'],
        ':ord' => $data['ord'],
        ':reminder' => $data['reminder'],
        ':early_reminder' => $data['early_reminder'],
        ':odo' => $data['odo'],
        ':prev_odo' => $data['prev_odo'],
						  )))
		{ return true; }

             */
      }
      }
    return false;
    }

     public static function readExpiry($car_id, $chapter) //reads a single expiry
    {
        $database = DatabaseFactory::getFactory()->getConnection();
		$sql = "SELECT * FROM expiries WHERE car_id = :car_id AND expiry = :expiry;";
		$query = $database->prepare($sql);
        $query->execute(array(
        ':car_id' => $car_id,
		    ':expiry' => $chapter,
						  ));
        if ($data = $query->fetch()) {
            return $data;
        }

    return false;
    }


        public static function readAllExpiries($car_id, $visibleonly = false) //reads all expiry data for a car
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sqlbit = ($visibleonly) ? " AND status = 'V' " : '';
		$sql = "SELECT * FROM expiries WHERE car_id = :car_id $sqlbit ORDER BY ord;";
		$query = $database->prepare($sql);
        $query->execute(array(
        ':car_id' => $car_id,
		    					  ));
        if ($data = $query->fetchAll()) {
        $response = array();
            foreach ($data as $row) {
              $response[$row->expiry] = $row;
            };
          return $response;
        }

    return false;
    }





      public static function commitExpiry($data) {
      if (is_array($data)) {
      	if (($data['car_id']) &&
			(CarModel::checkAccessLevel($data['car_id'],Session::get('user_uuid')) >= 80))
			{
			 $database = DatabaseFactory::getFactory()->getConnection();
       $field_list_arr = array();
       $field_colon_list_arr = array ();
       $argument_list = array();
       $on_duplicate_list_arr = array();
       foreach ($data as $key => $value) {
       $field_list_arr[] = $key;
       $field_colon_list_arr[] = ':'.$key;
       $argument_list[':'.$key] = $data[$key];
       $on_duplicate_list_arr[] = $key.' = :'.$key;
       }
       $field_list = implode(', ', $field_list_arr);
       $field_colon_list = implode(', ', $field_colon_list_arr);
       $on_duplicate_list = implode(', ', $on_duplicate_list_arr);
		$sql = "INSERT INTO expiries ($field_list) VALUES
     ($field_colon_list) ON DUPLICATE KEY UPDATE $on_duplicate_list";
		$query = $database->prepare($sql);
        if	($query->execute($argument_list))
		{ return true; }
      }}
      return false;
  }


}


?>
