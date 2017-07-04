<?php

/*
CREATE TABLE messages (
    recipient text,
    sender text,
    time timeuuid,
    message text,
    subject text,
    status text,
    PRIMARY KEY (recipient, time)
) WITH CLUSTERING ORDER BY (time DESC);

CREATE TABLE sent_messages (
    sender text,
    recipient text,
    time timeuuid,
    subject text,
    status text,
    PRIMARY KEY (sender, time)
) WITH CLUSTERING ORDER BY (time DESC);

users - unread_messages
*/

class MessageModel
{
    public static function sendMessage($from, $to, $message, $subject, $notify=true)
    {
        if ($to) {
    $message = nl2br($message);
    $timestamp = time();
    $timeofday   = gettimeofday();
    $microtime   = $timeofday['usec'];
    $timeuuid = $timestamp.'.'.$microtime;
    $database = DatabaseFactory::getFactory()->getConnection();
 		$query = $database->prepare("INSERT INTO messages
        (recipient, sender, time, timestamp, message, status, subject) VALUES
        (:recipient, :sender, :time, :timestamp, :message, :status, :subject);");
        if	($query->execute(array(
                ':recipient' => $to,
                ':sender' => $from,
                ':time' => $timeuuid,
                ':timestamp' => $timestamp,
                ':message' => $message,
                ':status' => 'Q',
                ':subject' => $subject,
						  )))
       {

    self::sendMessageOutbox($timeuuid, $timestamp, $message, $from, $to, $subject); //copy of the sent message with the same exact timeuuid for the users outbox
    self::IncrementUnreadMessages($to); //increment unread messages counter for the recipient
    if ($notify) {Session::add('feedback_positive', _('MESSAGE_SEND_SUCCESSFUL'));}
    //send email notification if recipient is not currently online (last seen more than 4 min old)
    $last_seen = UserModel::getLastSeen($to);
    if ($last_seen < (time()+240)) {
        $subject = sprintf(_('NEW_MESSAGE_NOTIFICATION_FROM_%s'), UserModel::getUserNameByUUid($from));
        $body = $message.PHP_EOL._('NOTIFICATION_READ_AND_REPLY_LINK').' <a href="https://motorgaga.com/message">"https://motorgaga.com/message"</a>';
        NotificationModel::queueNotification ($to, $subject, $body);
    }

       return true;

       }
    if ($notify) {Session::add('feedback_negative', _('MESSAGE_SEND_FAILED'));}
    return false;
        } else {
    if ($notify) {Session::add('feedback_negative', _('MESSAGE_USER_NOT_FOUND'));}
    return false;
        }
    }


    public static function checkMessages($to, $limit = '')
    {
     $limiter = '';
      if (is_array($limit)) { $limiter = 'LIMIT '.intval($limit['offset']).','.intval($limit['records']); }
     $database = DatabaseFactory::getFactory()->getConnection();
    $query    = $database->prepare("SELECT SQL_CALC_FOUND_ROWS time, message, sender, status, subject FROM messages WHERE recipient = :recipient ORDER BY timestamp DESC {$limiter}");
        $query->execute(array(
            ':recipient' => $to,
        ));
        if ($data = $query->fetchAll()) {
              $thing = $database->prepare("SELECT FOUND_ROWS()");
              $thing->execute();
              $total = $thing->fetch();
              $data['pagination'] = $total;
            return $data;
        } else
            return false;

    }




    public static function getMessage($uuid, $timeuuid, $recipient)  //get single message. if recipient is specified, this is the sender looking
    {
    $message_recipient = ($recipient ? $recipient : $uuid); //if this is the sender looking we still need to get the message by recipient, his outbox copy does not contain the message body

    $database = DatabaseFactory::getFactory()->getConnection();
    $query    = $database->prepare("SELECT  sender, recipient, time, message, status, subject FROM messages WHERE recipient = :recipient and time = :time LIMIT 1;");
    $query->execute(array(
            ':recipient' => $message_recipient,
            ':time' => $timeuuid,
        ));
        if ($data = $query->fetch())
        {
        if (!$recipient)  //if this is the recipient checking, we set the message status to read in all relevant tables
          {
          if ($data->status == 'Q')  //if the message was unread, of course
            {
            self::setMessageStatus($uuid, $timeuuid, 'R');
            self::DecrementUnreadMessages($uuid);
            self::setOutboxMessageStatus($data->sender, $timeuuid, 'R');
            };
          }
        return $data;
        }
      return false;
    }

    public static function setMessageStatus($uuid, $timeuuid, $status)  //mark message with Read, Replied, etc.
    {
    if ($status)
      {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("UPDATE messages SET status = :status WHERE recipient = :recipient AND time = :time");
        if ($query->execute(array(
            ':status' => $status,
            ':recipient' => $uuid,
            ':time' => $timeuuid,
        ))) { return true; }
      }
    return false;
    }


    public static function setOutboxMessageStatus($uuid, $timeuuid, $status)  //mark message with Read, Replied, etc.
    {
    if ($status)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query    = $database->prepare("UPDATE sent_messages SET status = :status WHERE sender = :sender AND time = :time");
        if ($query->execute(array(
            ':status' => $status,
            ':sender' => $uuid,
            ':time' => $timeuuid,
        ))) { return true; }
    }
    return false;
    }


     public static function IncrementUnreadMessages($uuid)
    {

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET unread_messages = unread_messages + 1 WHERE user_uuid = :user_uuid LIMIT 1;");
        $query->execute(array(':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            Session::set('unread_messages', intval(Session::get('unread_messages')) + 1);
            return true;
        }
        return false;
    }


    public static function DecrementUnreadMessages($uuid)
    {

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET unread_messages = unread_messages - 1 WHERE user_uuid = :user_uuid LIMIT 1;");
        $query->execute(array(':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            Session::set('unread_messages', intval(Session::get('unread_messages')) - 1);
            return true;
        }
        return false;
    }

        public static function getUnreadMessages($uuid)
    {

   //update last seen
        UserModel::setLastSeen($uuid);

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT unread_messages FROM users WHERE user_uuid = :user_uuid LIMIT 1;");
        $query->execute(array(':user_uuid' => $uuid));
        if ($result = $query->fetch()) {
            Session::set('unread_messages', intval($result->unread_messages));
            return intval($result->unread_messages);
        }
        return false;
    }



        public static function checkSentMessages($from, $limit = '')
    {
     $limiter = '';
      if (is_array($limit)) { $limiter = 'LIMIT '.intval($limit['offset']).','.intval($limit['records']); }
     $database = DatabaseFactory::getFactory()->getConnection();
    $query  = $database->prepare("SELECT SQL_CALC_FOUND_ROWS time, recipient, status, subject FROM sent_messages WHERE sender = :sender ORDER BY timestamp DESC {$limiter}");
        $query->execute(array(
            ':sender' => $from,
        ));
        if ($data = $query->fetchAll()) {
        $thing = $database->prepare("SELECT FOUND_ROWS()");
        $thing->execute();
        $total = $thing->fetch();
        $data['pagination'] = $total;
            return $data;
        } else
            return false;

    }





    /*
    public static function getLastMessageID($to)  //6ito neberiekia dabar manau
    {
    $casscluster   = Cassandra::cluster()  ->build();
    $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
    $statement = $casssession->prepare('SELECT time FROM messages WHERE recipient = ? LIMIT 1;');
    $selectstuff = array
    (
        'recipient' => $to
    );
    $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
    $result = $casssession->execute($statement, $options);
    if ($result->count() == 0)
        return false;
    else
        $result = $result[0]; $result = (array) $result['time']; $result = $result['uuid'];
        return $result ;
    } */

    public static function sendMessageOutbox($timeuuid, $timestamp, $message, $from, $to, $subject)
    {

    $database = DatabaseFactory::getFactory()->getConnection();
 		$query = $database->prepare("INSERT INTO sent_messages
        (sender, time, timestamp, message, recipient, status, subject) VALUES
        (:sender, :time, :timestamp, :message, :recipient, :status, :subject);");
        if	($query->execute(array(
                ':sender' => $from,
                ':time' => $timeuuid,
                ':timestamp' => $timestamp,
                ':message' => $message,
                ':recipient' => $to,
                ':status' => 'Q',
                ':subject' => $subject,
						  )))
       { return true; }
    return false;
    }

    public static function formatDate($date) {
        if ($date > strtotime("-24 hours")) {
            return strftime("%R", $date);
        }elseif ($date > strtotime("-1 week")) {
            return strftime("%A", $date);
        } else {
            return strftime("%x",$date);
        }
    }

    /*


    yra dvi kopijos: messages ir sent_messages, jas turetu butio glaimsa istrinti nepriklausomai
    anksciau buvo message body tik pas gaveja
    reikia su omnipotenčiu nukopijuoti message bodzius senoms zinutems prie6 visdk1
    ir pakeisti sent messages single perziura, kad neimtu info is recipiento kopijos
    ir va tada galima idiegti zinuciu istrynima



        public static function deleteMessage($uuid, $timeuuid)  //delete single message from receivers inbox
    { //ŠITAS DAR NEPATIKRINTAS!!!!!!!
    $casscluster   = Cassandra::cluster()  ->build();
    $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
    $statement = $casssession->prepare('DELETE FROM messages WHERE recipient = ? and time = ? LIMIT 1;');
    $selectstuff = array
    (
        'recipient' => $uuid,
        'time' => new Cassandra\Uuid($timeuuid)
    );
    $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
    $result = $casssession->execute($statement, $options);
    return $result;

    }
      */


}
