<?php
//view public records - for unregistered users

 //car_access: id 	user 	car 	level 	user_name 	car_name


 //level 10 - public car
class ViewModel
{

public static function getCarAccessByCarId($car_id, $level) {

       $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user FROM car_access WHERE car = :car_id AND level = :level LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':car_id' => $car_id, ':level' => $level));

        // fetch() is the PDO method that gets a single result
        return $query->fetch();


 }


 public static function getCarAccessByCarName($user_name, $car_name, $level) {

  $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user, car FROM car_access WHERE user_name = :user_name AND car_name = :car_name AND level = :level LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_name' => $user_name, ':car_name' => $car_name, ':level' => $level));

        // fetch() is the PDO method that gets a single result
        return $query->fetch();


 }


    public static function getCarIdByImg($img) {
    $car_id=explode('_', $img);
    return $car_id[0];

    }




    public static function limit_teaser($text, $length = 300) {
	$result = $text;
	if (mb_strlen($text) > intval($length)) {
		$result = mb_substr($text, 0, intval($length)).'[...]';
	}
	return $result;

    }

    public static function getEvents($car_id)
    {

        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("SELECT * FROM events WHERE car = :car AND visibility = 'pub' ORDER BY event_datetime DESC");
        $query->execute(array(
            ':car' => $car_id,
        ));
        if ($data = $query->fetchAll()) {
            return $data;
        } else
            return false;
    }


}

?>
