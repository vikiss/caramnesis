
<?php
$zinute = 'Automobilių servisas Panerių 64A informuoja klientus, kad pasikeitė kontaktinis telefonas į +37061436003. Išsamiau https://motorgaga.com/aeromobilis';


$numeriai = array(
37069967248, //as
37061436005, //audrimantas
37065595886, //tablete
//37065855044, //ema
//37064484843, //berta
);

/*
--------------------- aktualus siuntimas
$database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT tel, sent  FROM sms_recipients WHERE sent = 0 LIMIT 20;");
        $query->execute();
        $result = $query->fetchAll();
        foreach ( $result AS $client) {
           print 'sending to '.$client->tel;
           if (SmsModel::SendSms($client->tel, $zinute)) {
           print ' sending ok';
                $sql = "UPDATE sms_recipients SET sent = 1 WHERE tel = :tel";
            		$query = $database->prepare($sql);
                    if	($query->execute(array(
            		                      ':tel' => $client->tel,
            						  )))
            		{print ' recorded ok<br />'; } else {print ' record phail<br />'; }
                } else {print ' sending fail<br />';}
            };
            
            
 $query = $database->prepare("SELECT count(*) AS kiekis FROM sms_recipients WHERE sent = 0;");
        $query->execute();
        $howmuch = $query->fetch();            
        print 'Lieka '.$howmuch->kiekis;
        
        
        ---------------------iki cian aktualus siuntimas
        */

      /*
foreach ($numeriai as $numeris) {

if (SmsModel::SendSms($numeris, $zinute))

 {
 print $numeris.' ok<br />';
 } else {
 print $numeris.' fail<br />';
 }
};
        */


//
//a:10:{i:0;s:2:"35";i:1;s:2:"40";i:2;s:2:"45";i:3;s:2:"50";i:4;s:2:"55";i:5;s:2:"60";i:6;s:2:"65";i:7;s:2:"70";i:8;s:2:"75";i:9;s:2:"80";}   
//a:139:{i:0;s:1:"8";i:1;s:1:"9";i:2;s:2:"10";i:3;s:2:"11";i:4;s:2:"12";i:5;s:2:"13";i:6;s:2:"14";i:7;s:2:"24";i:8;s:2:"45";i:9;s:3:"115";i:10;s:3:"125";i:11;s:3:"130";i:12;s:3:"135";i:13;s:3:"140";i:14;s:3:"145";i:15;s:3:"150";i:16;s:3:"155";i:17;s:3:"165";i:18;s:3:"170";i:19;s:3:"175";i:20;s:3:"180";i:21;s:3:"185";i:22;s:3:"190";i:23;s:3:"195";i:24;s:3:"200";i:25;s:3:"205";i:26;s:3:"210";i:27;s:3:"215";i:28;s:3:"220";i:29;s:3:"225";i:30;s:3:"235";i:31;s:3:"240";i:32;s:3:"245";i:33;s:3:"255";i:34;s:3:"265";i:35;s:3:"270";i:36;s:3:"275";i:37;s:3:"285";i:38;s:3:"295";i:39;s:3:"305";i:40;s:3:"315";i:41;s:3:"325";i:42;s:3:"335";i:43;s:3:"345";i:44;s:3:"355";i:45;s:3:"365";i:46;s:3:"375";i:47;s:3:"385";i:48;s:3:"395";i:49;s:3:"400";i:50;s:3:"425";i:51;s:3:"435";i:52;s:3:"445";i:53;s:3:"450";i:54;s:3:"455";i:55;s:3:"475";i:56;s:3:"520";i:57;s:3:"525";i:58;s:3:"550";i:59;s:3:"560";i:60;s:3:"640";i:61;s:3:"650";i:62;s:3:"670";i:63;s:3:"700";i:64;s:3:"750";i:65;s:3:"8.5";i:66;s:3:"820";i:67;s:3:"9.5";i:68;s:4:"4.00";i:69;s:4:"4.40";i:70;s:4:"4.50";i:71;s:4:"4.80";i:72;s:4:"5.00";i:73;s:4:"5.20";i:74;s:4:"5.50";i:75;s:4:"5.60";i:76;s:4:"5.90";i:77;s:4:"6.00";i:78;s:4:"6.40";i:79;s:4:"6.50";i:80;s:4:"6.70";i:81;s:4:"7.00";i:82;s:4:"7.25";i:83;s:4:"7.50";i:84;s:4:"8.00";i:85;s:4:"8.20";i:86;s:4:"8.25";i:87;s:4:"8.50";i:88;s:4:"9.00";i:89;s:4:"9.50";i:90;s:5:"10.00";i:91;s:5:"11.00";i:92;s:5:"12.00";i:93;s:5:"14.00";i:94;s:5:"15/16";i:95;s:5:"16.00";i:96;s:7:"130/140";i:97;s:7:"150/160";i:98;s:7:"440/450";i:99;s:7:"475/500";i:100;s:7:"500/525";i:101;s:7:"525/550";i:102;s:7:"600/650";i:103;s:9:"4.00/4.50";i:104;s:9:"4.40/4.50";i:105;s:9:"4.75/5.00";i:106;s:9:"4.75/5.25";i:107;s:9:"5.00/5.25";i:108;s:9:"5.25/5.50";i:109;s:9:"5.25/6.00";i:110;s:9:"5.50/6.00";i:111;s:9:"6.00/6.50";i:112;s:9:"6.40/7.00";i:113;s:9:"6.50/7.00";i:114;s:11:"525/550/600";i:115;s:10:"38.5x14.50";i:116;s:6:"17R400";i:117;s:6:"30x9.5";i:118;s:7:"27x8.50";i:119;s:7:"30x9.50";i:120;s:7:"31x10.5";i:121;s:8:"31x10.50";i:122;s:8:"31x11.50";i:123;s:8:"32x11.50";i:124;s:8:"33x10.50";i:125;s:8:"33x12.00";i:126;s:8:"33x12.50";i:127;s:8:"33x13.00";i:128;s:8:"33x13.50";i:129;s:8:"35x12.50";i:130;s:8:"35x13.00";i:131;s:8:"35x13.50";i:132;s:8:"37x12.50";i:133;s:8:"37x13.50";i:134;s:8:"37x14.50";i:135;s:8:"38x14.50";i:136;s:8:"40x13.50";i:137;s:8:"42x14.50";i:138;s:3:"H78";}
//a:13:{i:0;s:3:"R12";i:1;s:3:"R13";i:2;s:3:"R14";i:3;s:3:"R15";i:4;s:3:"R16";i:5;s:3:"R17";i:6;s:3:"R18";i:7;s:3:"R19";i:8;s:3:"R20";i:9;s:3:"R21";i:10;s:3:"R22";i:11;s:3:"R23";i:12;s:3:"R24";}

       //$kas = SmsModel::SendSms(37069967248, 'pas mal');
       //print_r($kas);
  /*
  
  kaip 5ra6iau numerius:
  
  $numbers = array();


$handle = fopen("/var/www/html/application/view/car/siuntimui_nr.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
    $formatted = '3706'.substr($line, 2, 7); 
        //print $line.' : 3706'.substr($line, 2, 7).'<br />';
    if (in_array($formatted, $numbers)) {echo $formatted.' duplicate <br />';} else
    {$numbers[] = $formatted;}
        
    }

    fclose($handle);
} else {
    // error opening the file.
} 

print 'total: '.count($numbers);

$qry = 'INSERT INTO sms_recipients (tel, sent) VALUES ';
foreach ($numbers as $number) {
       $qry.='('.$number.', 0), ';
}
$qry.= '(0, 0);';

      

$database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare($qry);
        $query->execute();
        
        
        
        
  
  
  
  

$ratio = array("35", "40", "45", "50", "55", "60", "65", "70", "75", "80");
$width = array("8", "9", "10", "11", "12", "13", "14", "24", "45", "115", "125", "130", "135", "140", "145", "150", "155", "165", "170", "175", "180", "185", "190", "195", "200", "205", "210", "215", "220", "225", "235", "240", "245", "255", "265", "270", "275", "285", "295", "305", "315", "325", "335", "345", "355", "365", "375", "385", "395", "400", "425", "435", "445", "450", "455", "475", "520", "525", "550", "560", "640", "650", "670", "700", "750", "8.5", "820", "9.5", "4.00", "4.40", "4.50", "4.80", "5.00", "5.20", "5.50", "5.60", "5.90", "6.00", "6.40", "6.50", "6.70", "7.00", "7.25", "7.50", "8.00", "8.20", "8.25", "8.50", "9.00", "9.50", "10.00", "11.00", "12.00", "14.00", "15/16", "16.00", "130/140", "150/160", "440/450", "475/500", "500/525", "525/550", "600/650", "4.00/4.50", "4.40/4.50", "4.75/5.00", "4.75/5.25", "5.00/5.25", "5.25/5.50", "5.25/6.00", "5.50/6.00", "6.00/6.50", "6.40/7.00", "6.50/7.00", "525/550/600", "38.5x14.50", "17R400", "30x9.5", "27x8.50", "30x9.50", "31x10.5", "31x10.50", "31x11.50", "32x11.50", "33x10.50", "33x12.00", "33x12.50", "33x13.00", "33x13.50", "35x12.50", "35x13.00", "35x13.50", "37x12.50", "37x13.50", "37x14.50", "38x14.50", "40x13.50", "42x14.50", "H78");
$diameter = array("R12", "R13","R14","R15","R16","R17","R18","R19","R20","R21","R22","R23","R24");
print serialize($width);  
       
UPDATE attr_names SET type = 'INCHOICE', ord = 3, items = 'a:139:{i:0;s:1:"8";i:1;s:1:"9";i:2;s:2:"10";i:3;s:2:"11";i:4;s:2:"12";i:5;s:2:"13";i:6;s:2:"14";i:7;s:2:"24";i:8;s:2:"45";i:9;s:3:"115";i:10;s:3:"125";i:11;s:3:"130";i:12;s:3:"135";i:13;s:3:"140";i:14;s:3:"145";i:15;s:3:"150";i:16;s:3:"155";i:17;s:3:"165";i:18;s:3:"170";i:19;s:3:"175";i:20;s:3:"180";i:21;s:3:"185";i:22;s:3:"190";i:23;s:3:"195";i:24;s:3:"200";i:25;s:3:"205";i:26;s:3:"210";i:27;s:3:"215";i:28;s:3:"220";i:29;s:3:"225";i:30;s:3:"235";i:31;s:3:"240";i:32;s:3:"245";i:33;s:3:"255";i:34;s:3:"265";i:35;s:3:"270";i:36;s:3:"275";i:37;s:3:"285";i:38;s:3:"295";i:39;s:3:"305";i:40;s:3:"315";i:41;s:3:"325";i:42;s:3:"335";i:43;s:3:"345";i:44;s:3:"355";i:45;s:3:"365";i:46;s:3:"375";i:47;s:3:"385";i:48;s:3:"395";i:49;s:3:"400";i:50;s:3:"425";i:51;s:3:"435";i:52;s:3:"445";i:53;s:3:"450";i:54;s:3:"455";i:55;s:3:"475";i:56;s:3:"520";i:57;s:3:"525";i:58;s:3:"550";i:59;s:3:"560";i:60;s:3:"640";i:61;s:3:"650";i:62;s:3:"670";i:63;s:3:"700";i:64;s:3:"750";i:65;s:3:"8.5";i:66;s:3:"820";i:67;s:3:"9.5";i:68;s:4:"4.00";i:69;s:4:"4.40";i:70;s:4:"4.50";i:71;s:4:"4.80";i:72;s:4:"5.00";i:73;s:4:"5.20";i:74;s:4:"5.50";i:75;s:4:"5.60";i:76;s:4:"5.90";i:77;s:4:"6.00";i:78;s:4:"6.40";i:79;s:4:"6.50";i:80;s:4:"6.70";i:81;s:4:"7.00";i:82;s:4:"7.25";i:83;s:4:"7.50";i:84;s:4:"8.00";i:85;s:4:"8.20";i:86;s:4:"8.25";i:87;s:4:"8.50";i:88;s:4:"9.00";i:89;s:4:"9.50";i:90;s:5:"10.00";i:91;s:5:"11.00";i:92;s:5:"12.00";i:93;s:5:"14.00";i:94;s:5:"15/16";i:95;s:5:"16.00";i:96;s:7:"130/140";i:97;s:7:"150/160";i:98;s:7:"440/450";i:99;s:7:"475/500";i:100;s:7:"500/525";i:101;s:7:"525/550";i:102;s:7:"600/650";i:103;s:9:"4.00/4.50";i:104;s:9:"4.40/4.50";i:105;s:9:"4.75/5.00";i:106;s:9:"4.75/5.25";i:107;s:9:"5.00/5.25";i:108;s:9:"5.25/5.50";i:109;s:9:"5.25/6.00";i:110;s:9:"5.50/6.00";i:111;s:9:"6.00/6.50";i:112;s:9:"6.40/7.00";i:113;s:9:"6.50/7.00";i:114;s:11:"525/550/600";i:115;s:10:"38.5x14.50";i:116;s:6:"17R400";i:117;s:6:"30x9.5";i:118;s:7:"27x8.50";i:119;s:7:"30x9.50";i:120;s:7:"31x10.5";i:121;s:8:"31x10.50";i:122;s:8:"31x11.50";i:123;s:8:"32x11.50";i:124;s:8:"33x10.50";i:125;s:8:"33x12.00";i:126;s:8:"33x12.50";i:127;s:8:"33x13.00";i:128;s:8:"33x13.50";i:129;s:8:"35x12.50";i:130;s:8:"35x13.00";i:131;s:8:"35x13.50";i:132;s:8:"37x12.50";i:133;s:8:"37x13.50";i:134;s:8:"37x14.50";i:135;s:8:"38x14.50";i:136;s:8:"40x13.50";i:137;s:8:"42x14.50";i:138;s:3:"H78";}' WHERE chapter = 'TYRE_DATA' AND entry = 'SECTION_WIDTH';  
UPDATE attr_names SET type = 'INCHOICE', ord = 4, items = 'a:10:{i:0;s:2:"35";i:1;s:2:"40";i:2;s:2:"45";i:3;s:2:"50";i:4;s:2:"55";i:5;s:2:"60";i:6;s:2:"65";i:7;s:2:"70";i:8;s:2:"75";i:9;s:2:"80";}' WHERE chapter = 'TYRE_DATA' AND entry = 'SIDEWALL_RATIO';
UPDATE attr_names SET type = 'INCHOICE', ord = 5, items = 'a:13:{i:0;s:3:"R12";i:1;s:3:"R13";i:2;s:3:"R14";i:3;s:3:"R15";i:4;s:3:"R16";i:5;s:3:"R17";i:6;s:3:"R18";i:7;s:3:"R19";i:8;s:3:"R20";i:9;s:3:"R21";i:10;s:3:"R22";i:11;s:3:"R23";i:12;s:3:"R24";}' WHERE chapter = 'TYRE_DATA' AND entry = 'RIM_DIAMETER';    
     
+---------+-------------+------+-----+---------+-------+
| Field   | Type        | Null | Key | Default | Extra |
+---------+-------------+------+-----+---------+-------+
| chapter | varchar(36) | NO   | PRI | NULL    |       |
| entry   | varchar(36) | NO   | PRI | NULL    |       |
| type    | varchar(36) | NO   |     | NULL    |       |
| ord     | tinyint(4)  | YES  |     | NULL    |       |
| items   | text        | YES  |     | NULL    |       |
+---------+-------------+------+-----+---------+-------+


+-----------+----------------+-------------+------+--------------------------------------------------------------------------------+
| chapter   | entry          | type        | ord  | items                                                                          |
+-----------+----------------+-------------+------+--------------------------------------------------------------------------------+
| TYRE_DATA | PICTURES       | PICTURES    |    0 | NULL                                                                           |
| TYRE_DATA | RIM_DIAMETER   | INCHES      |    3 | NULL                                                                           |
| TYRE_DATA | SECTION_WIDTH  | MILLIMETERS |    4 | NULL                                                                           |
| TYRE_DATA | SIDEWALL_RATIO | INT         |    5 | NULL                                                                           |
| TYRE_DATA | TYRE_SEASON    | CHOICE      |    2 | a:4:{i:0;s:6:"SUMMER";i:1;s:6:"WINTER";i:2;s:10:"ALL_SEASON";i:3;s:5:"OTHER";} |
| TYRE_DATA | TYRE_TYPE      | TEXT        |    1 | NULL                                                                           |
+-----------+----------------+-------------+------+--------------------------------------------------------------------------------+
  
    */   
       
       /*
       	$mail = new Mail;
		$mail_sent = $mail->sendMail('vikiss@gmail.com', 'no-reply@motorgaga.com',
			'motorgaga site', 'motorgaga subject line', 'this is the body of the message'
		);

		if ($mail_sent) {
			print 'ok';
		} else {
			print 'phail';
		}
    */
/*
$database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT sender, time  FROM sent_messages;");
        $query->execute();
        $result = $query->fetchAll();
        foreach ( $result AS $event) {
                   
                $time = CarModel::parsetimestamp($event->time);
           
           
       
                $sql = "UPDATE sent_messages SET timestamp = :timestamp WHERE sender = :sender AND time = :time";
            		$query = $database->prepare($sql);
                    if	($query->execute(array(
                                      ':timestamp' => $time['seconds'],
            		                      ':sender' => $event->sender,
            		                      ':time' => $event->time,
            						  )))
            		{print $event->time.' : '.$time['seconds'].' ok'; } else {print $event->time.' phail'; }
             
       
          
        }      */

 /*
 šitas įrašė timestampo kopiją į datetime laukelį rūšiavimui
 
$database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT car, event_time FROM events;");
        $query->execute();
        $result = $query->fetchAll();
        foreach ( $result AS $event) {
        
                $time = CarModel::parsetimestamp($event->event_time);
        
                $sql = "UPDATE events SET event_datetime = :event_datetime WHERE car = :car AND event_time = :event_time";
            		$query = $database->prepare($sql);
                    if	($query->execute(array(':event_datetime' => CarModel::timestampToMysql($time['seconds']),
            		                      ':car' => $event->car,
            		                      ':event_time' => $event->event_time,
            						  )))
            		{print $event->car.' ok'; } else {print $event->car.' phail'; }
        
        
        }

   */
/*
 *
 *šitas perrašė paveikslėlius į naują struktūrą
$database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT user_uuid FROM users;");
        $query->execute();
        $result = $query->fetchAll();
        foreach ( $result AS $user) {
            $path = Config::get('CAR_IMAGE_PATH');
            $userfolder = $path.$user->user_uuid;
            if (is_dir($userfolder)) {
                print '<h2>'.$user->user_uuid.'</h1>';
                $cars = CarModel::getCars($user->user_uuid);
                foreach($cars as $car) {
                    $car_id = (array) $car['id']; 
                    $car_id = $car_id['uuid'];
                    print ($car_id).'<br />';
                    
                    foreach (glob($userfolder.'/'.$car_id."*.*") as $filename) {
                        $fname = basename($filename);
                        $fname = str_replace($car_id, '', $fname);
                        $fname = str_replace('_', '', $fname);
                        echo $path.$car_id.'/'.$fname. "<br />";
                        //if (!file_exists($path.$car_id)) { mkdir($path.$car_id, 0775, true);  }
                        if (copy($filename, $path.$car_id.'/'.$fname)) {
                            echo $path.$car_id.'/'.$fname. "copied<br />";
                        } else {
                            echo $path.$car_id.'/'.$fname. "went wrong<br />";
                        }
                        }
                    
                    
                    if ($handle = opendir($userfolder)) {
                        while (false !== ($file = readdir($handle))) {
                                                print ($file).'<br />';
                                                }
                        closedir($handle);
                    
                    } 
                    
                    
                    
                    
                    print '<br />';
                }
                
                
                print '<hr />';
            }
           
        }

*/







?>