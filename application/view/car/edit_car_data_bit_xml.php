<?php if ($this->car) {
include('car_data_prep.php');
//print '<pre>'; print_r(unserialize($attr_images)); print '</pre>';
$this->xml_databits ? $structure = simplexml_load_string($this->xml_databits) : $structure = simplexml_load_string($this->xml_structure);

   ?>
    <div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
    <h1><?= $car_name; ?></h1>
<div class="box">
	 
<div id="car_data_container">

<?php include('car_data_bit_tree.php'); ?>
     </div>   
<input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />

	 
</div>
    </div>
    <?php }
    include('multi_img_uploader.php'); 
    ?>
