<?php  

$allowed_nonimage_ext = array('pdf','xls');
if(!in_array(CarModel::get_extension($this->image),$allowed_nonimage_ext)){ 

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