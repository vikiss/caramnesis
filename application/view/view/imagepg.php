<?php  

$allowed_nonimage_ext = array('pdf','xls');
if(in_array(CarModel::get_extension($this->image),$allowed_nonimage_ext)){ 

$file = Config::get('CAR_IMAGE_PATH').$this->car.'/'.$this->image;
header("Content-length: ". filesize($file));
header("Content-type: ". mime_content_type($file));
readfile($file);






} else {
?>
<!doctype html>
<html>
<head>
    <title>CARAMNESIS <?= $this->car.'/'.$this->image; ?></title>
</head>
<body> 
<img src="/view/image/<?= $this->car.'/'.$this->image; ?>" />
</body>
<?php }; ?>