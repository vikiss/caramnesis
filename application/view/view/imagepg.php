<?php  

$allowed_nonimage_ext = array('pdf','xls');
if(!in_array(CarModel::get_extension($this->image),$allowed_nonimage_ext)){
    
$file = Config::get('CAR_IMAGE_PATH').$this->user.'/'.$this->image;
header("Content-length: ". filesize($file));
header("Content-type: ". mime_content_type($file));
readfile($file);
?>
<!doctype html>
<html>
<head>
    <title>CARAMNESIS <?php echo $this->user.'/'.$this->image; ?></title>
</head>
<body> 
<img src="/view/image/<?php echo $this->user.'/'.$this->image; ?>" />
</body>
<?php }; ?>