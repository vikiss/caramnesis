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
 
 

                            public static function getCar($car_id)
    {

	     $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT id, car_access, car_data, car_make, car_model, car_name, car_plates, car_vin, images, owner FROM cars WHERE id = ?');
       $selectstuff = array('id' => new Cassandra\Uuid($car_id));
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options); 
       if ($result->count() == 0) return false; else return($result);
    }
    
    public static function getCarIdByImg($img) {
    $car_id=explode('_', $img);
    return $car_id[0];
    
    }
    
    
      
    public static function get_public_event_types ($car_id) {
    
    $casscluster   = Cassandra::cluster()  ->build();
       $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
       $statement = $casssession->prepare('SELECT car_access FROM cars WHERE id = ?');
       $selectstuff = array('id' => new Cassandra\Uuid($car_id));
       $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
       $result = $casssession->execute($statement, $options);
       $result = $result[0]; $result = (array) $result['car_access']; if (array_key_exists('values', $result)) $result = $result['values'];
       return($result);
    
    } 
    
    
}    

?>