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
    $sent_id = ''; 
    $casscluster   = Cassandra::cluster()  ->build();
    $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
    $statement = $casssession->prepare('INSERT INTO messages (recipient, sender, time, message, status, subject) VALUES (?,?,now(),?,?,?);');
    $insertstuff = array
        (
       'recipient' => $to,
       'sender' => $from,
       'message' => nl2br($message),
       'status' => 'Q',
       'subject' => $subject
        );
    $options = new Cassandra\ExecutionOptions(array('arguments' => $insertstuff));
    if ($result = $casssession->execute($statement, $options) )
       {
        if ($sent_id = self::getLastMessageID($to)) { 
    self::sendMessageOutbox($sent_id, $from, $to, $subject); //copy of the sent message with the same exact timeuuid for the users outbox
    self::IncrementUnreadMessages($to); //increment unread messages counter for the recipient
    if ($notify) {Session::add('feedback_positive', _('MESSAGE_SEND_SUCCESSFUL'));}
       return true;
       } 
       }
    if ($notify) {Session::add('feedback_negative', _('MESSAGE_SEND_FAILED'));}
    return false;
        } else {
    if ($notify) {Session::add('feedback_negative', _('MESSAGE_USER_NOT_FOUND'));}
    return false;            
        }
    }
    
    
    public static function checkMessages($to)  //set limit somehow, eventually
    {
    
    $casscluster   = Cassandra::cluster()  ->build();
    $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
    $statement = $casssession->prepare('SELECT  sender, dateOf(time), time, message, status, subject FROM messages WHERE recipient = ?;');
    $selectstuff = array
    (
        'recipient' => $to,
    );
    $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
    $result = $casssession->execute($statement, $options); 
    if ($result->count() == 0)
        return false;
    else
        return $result;
    }
    

    public static function getMessage($uuid, $timeuuid, $recipient)  //get single message. if recipient is specified, this is the sender looking
    {
    $intermediate_result = '';
    $message_recipient = ($recipient ? $recipient : $uuid); //if this is the sender looking we still need to get the message by recipient, his outbox copy does not contain the message body
    $casscluster   = Cassandra::cluster()  ->build();
    $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
    $statement = $casssession->prepare('SELECT  sender, recipient, dateOf(time), time, message, status, subject FROM messages WHERE recipient = ? and time = ? LIMIT 1;');
    $selectstuff = array
    (
        'recipient' => $message_recipient,
        'time' => new Cassandra\Uuid($timeuuid)
    );
    $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
    $result = $casssession->execute($statement, $options); 
    if ($result->count() == 0)
        return false;
    else {
        if (!$recipient) { //if this is the recipient checking, we set the message status to read in all relevant tables
        $intermediate_result = $result[0];
        if ($intermediate_result['status'] == 'Q') { //if the message was unread, of course
        self::setMessageStatus($uuid, $timeuuid, 'R');
        self::DecrementUnreadMessages($uuid);
        self::setOutboxMessageStatus($intermediate_result['sender'], $timeuuid, 'R');
        };
        }
        return $result;
        }
    }
    
    public static function setMessageStatus($uuid, $timeuuid, $status)  //mark message with Read, Replied, etc.
    {
    if (!$status)
    {
        return false;
    } else { 
        $casscluster   = Cassandra::cluster()  ->build();
        $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
        $statement = $casssession->prepare('UPDATE messages SET status = ? WHERE recipient = ? and time = ?;');
        $selectstuff = array
        (
            'status' => $status,
            'recipient' => $uuid,
            'time' => new Cassandra\Uuid($timeuuid)
        );
        $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
        if ($casssession->execute($statement, $options)) return true; else return false;
    }
    }
    
    
    public static function setOutboxMessageStatus($uuid, $timeuuid, $status)  //mark message with Read, Replied, etc.
    {
    if (!$status)
    {
        return false;
    } else { 
        $casscluster   = Cassandra::cluster()  ->build();
        $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
        $statement = $casssession->prepare('UPDATE sent_messages SET status = ? WHERE sender = ? and time = ?;');
        $selectstuff = array
        (
            'status' => $status,
            'sender' => $uuid,
            'time' => new Cassandra\Uuid($timeuuid)
        );
        $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
        if ($casssession->execute($statement, $options)) return true; else return false;
    }
    }
    
    
     public static function IncrementUnreadMessages($uuid)  
    {
   
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET unread_messages = unread_messages + 1 WHERE user_uuid = :user_uuid LIMIT 1;");
        $query->execute(array(':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
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
   
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT unread_messages FROM users WHERE user_uuid = :user_uuid LIMIT 1;");
        $query->execute(array(':user_uuid' => $uuid));
        if ($result = $query->fetch()) {
            Session::set('unread_messages', intval($result->unread_messages));
            return intval($result->unread_messages);
        }
        return false;
    }
    
    
     public static function checkSentMessages($from)  
    {
    
    $casscluster   = Cassandra::cluster()  ->build();
    $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
    $statement = $casssession->prepare('SELECT  recipient, dateOf(time), time, subject, status FROM sent_messages WHERE sender = ?;');
    $selectstuff = array
    (
        'sender' => $from,
    );
    $options = new Cassandra\ExecutionOptions(array('arguments' => $selectstuff));
    $result = $casssession->execute($statement, $options); 
    if ($result->count() == 0)
        return false;
    else
        return $result;
    }
    
    public static function getLastMessageID($to)
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
    }
    
    public static function sendMessageOutbox($sent_id, $from, $to, $subject)
    {
    $casscluster   = Cassandra::cluster()  ->build();
    $casssession   = $casscluster->connect(Config::get('CASS_KEYSPACE'));
    $statement = $casssession->prepare('INSERT INTO sent_messages (sender, recipient, time, subject, status) VALUES (?,?,?,?,?);');
    $insertstuff = array
        (
       'sender' => $from,
       'recipient' => $to,
       'time' => new Cassandra\Uuid($sent_id),
       'subject' => $subject,
       'status' => 'Q'
        );
    $options = new Cassandra\ExecutionOptions(array('arguments' => $insertstuff));
    if ($result = $casssession->execute($statement, $options) ) return true; else return false;    
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
    


}      