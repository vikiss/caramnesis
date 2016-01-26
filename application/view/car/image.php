<?php
$file = '/var/www/usrimg/'.Session::get('user_uuid').'/'.$this->image;
//to avopid broken image picture we display empty gif
//todo: maybe delete the entry from the dbase
if (!file_exists($file)) {$file = '/var/www/html/public/img/empty.gif';}
header("Content-length: ". filesize($file));
header("Content-type: ". mime_content_type($file));
readfile($file);
?>