<?php
//ALTER TABLE `users` ADD `user_phone` VARCHAR(50) NOT NULL , ADD `phone_activation` VARCHAR(10) NOT NULL , ADD `send_sms` TINYINT(1) NOT NULL ;
class SmsModel
{
 public static function SendSms($to, $message) //low-level send
    {
        if ((!$to) or (!is_numeric($to)) or (strlen($message) < 5)) {
            return false;
        }

        $params =  array('username' => Config::get('SMS_USER'),
                         'password' => Config::get('SMS_PW'),
                         'phoneno' => $to,
                         'message' => $message,
                         );

       $client = new SoapClient("http://smsgates.lt/beit/sms.asmx?WSDL");
       $sending =  $client->__soapCall("Send", array($params)); //stdClass Object ( [SendResult] => OK )
       if ($sending->SendResult == 'OK') return true;

      /*usage:
       *$kas = SmsModel::SendSms(37069967248, 'pas mal');
       */
        // default return

        return false;
    }

    public static function OkToSendSMS($country) // make sure it's not the middle of the night at the recipients place
    //2-letter country code, returns true if OK to send, false if not. OK to send - 9AM to 8PM
    //usage: if (SmsModel::OkToSendSMS('US'))
    {
     $diff = self::getTimeDifference($country);
     $diff = explode(':', $diff); //sign(plus/minus):hours:minutes
     $sign = $diff[0]; $hours = $diff[1]; $minutes = $diff[2];
     $timeoverthere = strtotime(gmdate('r').' '.$sign.' '.$hours.' hours '.$sign.' '.$minutes.' min');
     $houroverthere = intval(gmdate('G', $timeoverthere));
     if (($houroverthere > 9) && ($houroverthere < 20))
      {return true;}
      else
      {return false;}
    }

    public static function getTimeDifference($country) //read UTC offset field from countries table
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT offset FROM countries WHERE sortname = :country LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':country' => $country));
         if ($result = $query->fetch()) {
            return $result->offset;
        } else {return '+:00:00';}  //safe default return if country code not found
    }

    public static function sendSMSNotification($recipient, $message) //checks if it's ok to send SMS and sends it
    //always returns true; enters sms in deferredSMS table if it's not OkToSensSMS at the time
    {
     if ($phone_no = self::getUserPhoneForSms($recipient)) {
     if (self::OkToSendSMS(UserModel::getUserCountry($recipient))) {
       self::SendSms($phone_no, $message);
      } else {
       self::queueDeferredSMS($recipient, $message);
      }
     }
     return true;
    }


    public static function sendQueudSms() { //sends all queud SMS
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT * FROM deferredSMS;");
        $query->execute();
        $result = $query->fetchAll();
        foreach ($result AS $row) {
         self::sendSMSNotification($row->recipient, $row->content);
         self::deleteQueudSms($row->id);
        }

    }


    public static function getUserPhoneForSms($uuid) { //gets the phone number if the user has enabled SMS notification, false otherwise
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT user_phone, send_sms
                                     FROM users
                                     WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':user_uuid' => $uuid));
        if ($result = $query->fetch()) {
         $phone_no = $result->user_phone;
         $phone_no = filter_var($phone_no, FILTER_VALIDATE_INT);
         if ((strlen($phone_no) > 5) && ($result->send_sms == '1')) {
            return $phone_no;
         }
        }
        return false;
            }

      public static function queueDeferredSMS($recipient, $message)
       {
        $database = DatabaseFactory::getFactory()->getConnection();
      		$sql = "INSERT INTO deferredSMS	(recipient, content) VALUES	(:recipient, :content)";
        $query = $database->prepare($sql);
        $query->execute(array(':recipient' => $recipient,
                              ':content' => $message,
						  ));
        $count =  $query->rowCount();
      		if ($count == 1) {			return true;		}
      		return false;
       }



           public static function deleteQueudSms($sms_id)
    {
        if (!$sms_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM deferredSMS WHERE id = :sms_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':sms_id' => $sms_id));

        if ($query->rowCount() == 1) {
            return true;
        }

        return false;
    }

}
