<?php
//todo: check if the user has permission to access this image if other than owner
$file = Config::get('CAR_IMAGE_PATH').$this->user.'/'.$this->image;
//to avoid broken image picture we display empty gif if the file is not there
//todo: maybe delete the entry from the dbase
if (!file_exists($file)) {$file = Config::get('CAR_EMPTY_IMAGE') ;}
else {
//if the file is there we see if a resampled version is requested
if (ctype_digit($this->size)) { 
   if (($this->size > 29) && ($this->size < 1201)) {
   $file = '/var/www/usrimg/'.$this->user.'/'.$this->size.'/'.$this->image;
   if (!file_exists($file)) {
      if (!CarModel::makeItSmaller($this->image, $this->user, $this->size))
      {$file = Config::get('CAR_EMPTY_IMAGE') ;} //if resample fails
   }
   }
};

}


header("Content-length: ". filesize($file));
header("Content-type: ". mime_content_type($file));
readfile($file);



?>              