<?php
/*
 *
 *dammit, we have to go dual-database approach here, cassandra to store all reminders ever issued for a particular car, and mysql for issuing actual reminders,
 *since the cron query needs to read pending reminders for all cars
cassandra:
CREATE TABLE bxamnesis.reminders (car_id uuid, time timestamp, content text, status text, PRIMARY KEY (car_id, time)) WITH CLUSTERING ORDER BY (time DESC);
mysql:
`id` int(11) `car_id` char(36)  `time` bigint(20)  `content` text  `status` enum('Q','S')
*/

class ReminderModel
{
    public static function setReminder($car_id, $timestamp, $microtime, $content, $status) { //sets reminder, used in edit event form

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO reminders (car_id, car_name, owner_id, time, microtime, content, status)
                    VALUES (:car_id, :car_name, :owner_id, :time, :microtime,  :content,  :status)";
        $query = $database->prepare($sql);
        $query->execute(array(':car_id' => $car_id,
                              ':car_name' => CarModel::getCarName($car_id),
                              ':owner_id' => CarModel::getCarOwner($car_id),
                              ':time' => $timestamp,
                              ':microtime' => $microtime,
                              ':content' => $content,
                              ':status' => $status,
                          ));
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        }


        Session::add('feedback_negative', _('FEEDBACK_REMINDER_CREATION_FAILED'));
		        return false;
        }


    public static function readPendingReminders($status) {  //read reminders than have timestamp lower than current

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT * FROM reminders WHERE time <= :time AND status = :status");
        $query->execute(array(':time' => time(), ':status' => $status ));
       $messages = array();
        foreach ($query->fetchAll() as $message) {
             array_walk_recursive($message, 'Filter::XSSFilter');
            $messages[] = array(
                                'car_id' => $message->car_id,
                                'car_name' => $message->car_name,
                                'owner_id' => $message->owner_id,
                                'timestamp' => $message->time,
                                'content' => $message->content,
                                'status' => $message->status,
                                'id' => $message->id,
                                );
        }
        return $messages;


    }


	public static function checkReminderStatus($car_id, $timestamp) {  //check if corresponding mysql reminder entry exists and has status of Q, meaning user has not been notified yet

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT status FROM reminders WHERE car_id = :car_id AND time <= :time LIMIT 1");
        $query->execute(array(':car_id' => $car_id, ':time' => $timestamp ));

        if ($result = $query->fetch()) {
            return $result->status;
        }

		return false;

    }



     public static function deleteReminder($id,$car_id, $timestamp) { //mysql

        if (!$id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM reminders WHERE `id` = :id AND `car_id` = :car_id AND `time` = :time LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(
            ':id' => $id,
            ':car_id' => $car_id,
            ':time' => $timestamp,
        ));
        if ($query->rowCount() == 1) {
            return true;
        }

        return false;
    }


	     public static function deleteReminderByCarTime($car_id, $timestamp) { //delete mysql reminder form cassandra view

        if ((!$car_id) or (!$timestamp)) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM reminders WHERE car_id = :car_id and `time` = :time LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':car_id' => $car_id, ':time' => $timestamp));
        if ($query->rowCount() == 1) {
            return true;
        }

        return false;
    }




	     public static function getReminderCount($uuid)  //mysql
    {
         $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT active_reminders FROM users WHERE user_uuid = :user_uuid LIMIT 1;");
        $query->execute(array(':user_uuid' => $uuid));
        if ($result = $query->fetch()) {
            Session::set('active_reminders', intval($result->active_reminders));
            return intval($result->active_reminders);
        }
        return false;
    }


	public static function getReminderById($id)  //mysql
    {
         $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT * FROM reminders WHERE id = :id LIMIT 1;");
        $query->execute(array(':id' => $id));
        if ($result = $query->fetch()) {
            return $result;
        }
        return false;
    }

	public static function getReminders($car_id, $time = 0) { //cassandra reminder list, time = 'all' or number of seconds how far back we want the reminders, 0 = now

		$gtorlt = '>'; $timestamp = 0 ;
		if ($time !== 'all') {
			$timestamp = time() - intval($time);
			if (intval($time) < 0 ) {
			$gtorlt = '<';
			}

		}



    $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT * FROM reminders WHERE car_id = :car_id and convert(`time`, decimal) $gtorlt :time ORDER BY convert(`time`, decimal) asc");
        //SLOW QUERY!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! need to sort
        $query->execute(array(
            ':car_id' => $car_id,
            ':time' => $timestamp,
        ));
        if ($data = $query->fetchAll()) {

            return $data;
        } else
            return false;





		return false;
	}


	public static function getReminder($car_id, $time, $microtime) {
    $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT * FROM reminders WHERE car_id = :car_id AND time = :time AND microtime = :microtime");
        $query->execute(array(
            ':car_id' => $car_id,
            ':time' => $time,
            ':microtime' => $microtime,
        ));
        if ($data = $query->fetch()) {
            return $data;
        }
		return false;
	}




	public static function MoveReminder($car_id, $old_reminder, $new_reminder, $content, $offset) {

			$timeofday = gettimeofday(); $microtime = $timeofday['usec'];
			$date = DateTime::createFromFormat ('Y-m-d H:i' , $new_reminder.' 11:00' );
			$timestamp = $date->getTimestamp();
			if (intval($offset) > 0) { $timestamp = $timestamp - (86400 * intval($offset)); }
			    self::setReminder($car_id, $timestamp, $microtime, $content, 'Q');
			if ($old_reminder) {
                self::deleteReminderByCarTime($car_id, $old_reminder);
				}

		return $timestamp.':'.$microtime;

	}






	     public static function IncrementActiveReminders($uuid)
    {

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET active_reminders = active_reminders + 1 WHERE user_uuid = :user_uuid LIMIT 1;");
        $query->execute(array(':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
			//Session::set('active_reminders', intval(Session::get('active_reminders')) + 1);
            return true;
        }
        return false;
    }


    public static function DecrementActiveReminders($uuid)
    {
   if (intval(Session::get('active_reminders')) > 0) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET active_reminders = active_reminders - 1 WHERE user_uuid = :user_uuid LIMIT 1;");
        $query->execute(array(':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
			$current_reminders = intval(Session::get('active_reminders'));
			if ($current_reminders > 0) {
            //Session::set('active_reminders', intval(Session::get('active_reminders')) - 1);
			};
            return true;
        }
    }
        return false;
    }


    public static function resetActiveReminders($uuid)
    {
   if (intval(Session::get('active_reminders')) > 0) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET active_reminders = 0 WHERE user_uuid = :user_uuid LIMIT 1;");
        $query->execute(array(':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            $current_reminders = intval(Session::get('active_reminders'));
            if ($current_reminders > 0) {
            //Session::set('active_reminders', 0);
            };
            return true;
        }
    }
        return false;
    }


	public static function formatDate($date) {

        //is it today :
		if (date('Ymd', $date) == date('Ymd')) {
            return _('TODAY');
        }elseif (date('Ymd', $date) == date('Ymd', time()+86400)) {
            return _('TOMORROW');
		}elseif (date('Ymd', $date) == date('Ymd', time()-86400)) {
            return _('YESTERDAY');
		} else {
            return strftime("%x",$date);
        }

    }



};
