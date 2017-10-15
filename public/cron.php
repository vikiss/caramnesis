<?php //production only, do not run this on dev server
$processUser = posix_getpwuid(posix_geteuid());
//$processUser['name'] : www-data for web user, admin for cli, root for cron
if (($processUser['name'] == 'admin') or ($processUser['name'] == 'root')) { //if someone stumbles upon this on the web do 404

class Config
{
    public static $config;
    public static function get($key)
    {
        if (!self::$config) {
	        $config_file = '/var/www/html/application/config/config.production.php';
			if (!file_exists($config_file)) {				return false;			}
	        self::$config = require $config_file;
        }
        return self::$config[$key];
    }
}

require '/var/www/html/application/core/DatabaseFactory.php';
require '/var/www/html/application/core/Filter.php';
require '/var/www/html/application/model/NotificationModel.php';
require '/var/www/html/application/model/UserModel.php';
require '/var/www/html/application/model/ReminderModel.php';
require '/var/www/html/application/model/SmsModel.php';
require '/var/www/html/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
require '/var/www/html/application/core/Mail.php';
$log = '';

$available_locales = Config::get('AVAILABLE_LOCALES');

//send queued SMS from deferred SMS table that got there to avoid sending them in the night
$log .= SmsModel::sendQueudSms().PHP_EOL;

//check reminders, set up notifications if found
if ($reminders = ReminderModel::readPendingReminders('Q')) {  // reads reminders that have date past current
															  // and moves them to notification queue
	foreach ($reminders AS $reminder) {
		//we need to properly gettext body and subject in the language of the particular recipient
		if (($language = strtoupper(UserModel::getUserLanguageByUUid($reminder['owner_id']))) && (array_key_exists(strtolower($language), $available_locales))) {
$locale = $available_locales[strtolower($language)];
        } else { $locale = $available_locales['en'];                    }
putenv("LANGUAGE=".$locale); putenv("LANG=".$locale); putenv("LC_ALL=".$locale); setlocale(LC_ALL, $locale); $domain="messages";
bindtextdomain($domain, '/var/www/html/application/Locale'); bind_textdomain_codeset($domain, 'UTF-8'); textdomain($domain);

		$subject = sprintf(_("%s_REMINDER_FOR_%s"), strftime("%x",$reminder['timestamp']), $reminder['car_name']);
		$body = $reminder['content'];

		if (NotificationModel::queueNotification ($reminder['owner_id'], $subject, $body)) {
			$log.=' :reminder queued for '.$reminder['car_name'].PHP_EOL;
			ReminderModel::IncrementActiveReminders($reminder['owner_id']);
			ReminderModel::deleteReminder($reminder['id']);
		}
	}
}

//send out notifications
if ($messages = NotificationModel::getNotifications()) {
	$mail = new Mail;
  foreach ($messages AS $message) {
      if (($message['language']) && (array_key_exists(strtolower($message['language']), $available_locales))) {
      $locale = $available_locales[strtolower($message['language'])];
              } else {
      $locale = $available_locales['en'];
              }
      putenv("LANGUAGE=".$locale);
      putenv("LANG=".$locale);
      putenv("LC_ALL=".$locale);
      setlocale(LC_ALL, $locale);
      $domain="messages";
      bindtextdomain($domain, '/var/www/html/application/Locale');
      bind_textdomain_codeset($domain, 'UTF-8');
      textdomain($domain);

        $mail_sent = $mail->sendMail($message['recipient'], Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
			_('EMAIL_NOTIFICATION_FROM_NAME'), $message['subject'] , $message['body']
		);

    //send out SMS notifications, if they come at a bad time they will be tried to send at next cron via Send QueudSMS
		$log.= SmsModel::sendSMSNotification
			(UserModel::getUuidByEmail
				($message['recipient']),
				mb_substr($message['subject'].' '.$message['body'], 0, 69
						  )).PHP_EOL
			;
									  //70 char limit for unicode SMS


		if ($mail_sent) {
	NotificationModel::deleteNotification($message['id']);
    $log.='email sent to '.$message['recipient'].PHP_EOL;
		} else {
			$log.=$mail->getError();
      $log.=PHP_EOL;
		}
    }
}

		if ($log) {
			$log = date('r').' '.$processUser['name'].':'.PHP_EOL.$log.PHP_EOL.PHP_EOL;
		}
file_put_contents('/var/www/cronlog.txt', $log, FILE_APPEND);
} else {
  header("HTTP/1.0 404 Not Found");  exit;
}
?>
