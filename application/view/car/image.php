<?php
//todo: check if the user has permission to access this image if other than owner
$file = Config::get('CAR_IMAGE_PATH').$this->user.'/'.$this->image;
//to avoid broken image picture we display empty gif if the file is not there
//todo: maybe delete the entry from the dbase
if (!file_exists($file)) {$file = Config::get('CAR_EMPTY_IMAGE') ;}
else {
      //see if it's an real image and not a pdf or smth
      $allowed_nonimage_ext = array('pdf','xls');
      if(!in_array(CarModel::get_extension($this->image),$allowed_nonimage_ext))
      { 
      
      


          //if the file is there we see if a resampled version is requested
          if (ctype_digit($this->size)) { 
             if (($this->size > 29) && ($this->size < 1201)) {
             $file = '/var/www/usrimg/'.$this->user.'/'.$this->size.'/'.$this->image;
             if (!file_exists($file)) {
                if (!CarModel::makeItSmaller($this->image, $this->user, $this->size))
                {$file = Config::get('CAR_EMPTY_IMAGE') ;} //if resample fails
             }
             }
          }
      
      
      } elseif (CarModel::get_extension($this->image) == 'pdf') //if it's a pdf we generate an image version
      {
        if (ctype_digit($this->size)) { 
             if (($this->size > 29) && ($this->size < 1201)) {
            $file =  basename($this->image, '.pdf').'.png';
            $file = '/var/www/usrimg/'.$this->user.'/'.$this->size.'/'.$file;
            if (!file_exists($file)) {
               if (!CarModel::makePdfImage($this->image, $this->user, $this->size))
                {$file = Config::get('CAR_EMPTY_IMAGE') ;} //if generation fails

            }
            }
          }  
      } else {
      $file = Config::get('CAR_EMPTY_IMAGE') ;
      }

}

    // Getting headers sent by the client.
    $headers = apache_request_headers();

    // Checking if the client is validating his cache and if it is current.
    if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($file))) {
        // Client's cache IS current, so we just respond '304 Not Modified'.
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($file)).' GMT', true, 304);
    } else {
        // Image not cached or cache outdated, we respond '200 OK' and output the image.

header("Content-length: ". filesize($file));
header("Content-type: ". mime_content_type($file));
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (5184000))); // 60 days
header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($file)).' GMT', true, 200);
header("Cache-Control: max-age=5184000"); // 60 days
header("Pragma: cache");
readfile($file);
//print_r($headers);
}
?>