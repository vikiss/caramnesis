<?php

/*
CREATE TABLE IF NOT EXISTS `notifications` (
`id` int(11) NOT NULL,
  `recipient` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'email',
  `subject` text COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `language` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `flag` enum('Q','F') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Queued or Failed'
)
*/


class NotificationModel
{
    public static function getNotifications() {  
        
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT * FROM notifications WHERE flag = :flag OR flag = :otherflag");
        $query->execute(array(':flag' => 'Q', ':otherflag' => 'M')); //M flag = don't send email per user preferences
       $messages = array();
        foreach ($query->fetchAll() as $message) {
             array_walk_recursive($message, 'Filter::XSSFilter');           
            $messages[] = array(
                                'recipient' => $message->recipient,
                                'subject' => $message->subject,
                                'body' => $message->body,
                                'language' => $message->language,
                                'id' => $message->id
                                );
        }
        return $messages;
    }
    
    public static function deleteNotification($id) {
        
        if (!$id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM notifications WHERE id = :id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':id' => $id));
        if ($query->rowCount() == 1) {
            return true;
        }

        return false;
    }
  
    public static function queueNotification ($recipient, $subject, $body) {
    
		$database = DatabaseFactory::getFactory()->getConnection();

		$sql = "INSERT INTO notifications (recipient,  subject,  body,  language,  flag)
                    VALUES (:recipient,  :subject,  :body,  :language,  :flag)";
		$query = $database->prepare($sql);
		$query->execute(array(':recipient' => UserModel::getEmailByUUid($recipient),
		                      ':subject' => $subject,
		                      ':body' => $body,
		                      ':language' => strtoupper(UserModel::getUserLanguageByUUid($recipient)),
		                      ':flag' => UserModel::getUserEmailSetting($recipient),
						  ));
		$count =  $query->rowCount();
		if ($count == 1) {
			return true;
		}

		return false;
	
        
    }
    
}