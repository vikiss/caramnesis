<?php if ($this->car) {
include('car_data_prep.php'); 

    ?>
    <div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
    <h1><?= $car_name; ?></h1>
<div class="box">
	 
<div id="cardatadeletedlg" class="center" title="<?= _('ARE_YOU_REALLY_REALLY_SURE'); ?>">
            <h3><?= _('ARE_YOU_REALLY_REALLY_SURE'); ?></h3>
            <p><?= _('WARNING_NOT_UNDOABLE'); ?></p>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a href="#" id="cardatadeletebtn"><?= _('DELETE_CAR_DATA_BIT'); ?></a></div>
</div>
<div id="cardataeditdlg" class="center" title="<?= _('EDIT_CAR_DATA_BIT'); ?>">
            <div class="mb2"><input type="text" name="edit_car_data_val" id="edit_car_data_val" class=" field mt1 small-field " /></div>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a href="#" id="cardatasavebtn"><?= _('SAVE'); ?></a></div>
</div>



<div id="car_data_container">

<?php include('car_data_bit_table.php'); ?>
     </div>   

<div>
    <div class="col col-6">   
	<select id="new_car_data_bit" class=" field mt1 small-field ">
 				<?php foreach ($this->car_data_bits as $key => $bit) {
         if ((!in_array($bit, $key_already_exists)) or ($key == 'default'))  
          {
 						echo '<option value="'.$bit.'"';
 							if ($key == 'default') {echo ' selected';}
 								echo '>'._($bit).'</option>';
                }
 						}; ?></select>
            
    </div>
    <div class="col col-6">    
<input type="text" name="new_car_data_val" id="new_car_data_val" class="  field mt1 small-field " />
<a href="#" id="add_new_car_data" class="btn-primary black bg-kcms" /><?= _("SAVE"); ?></a>
<input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
<input type="hidden" name="car_data" id="car_data" value = "<?= $car_data; ?>" />
    </div>
</div>

	 
	 
	 
</div>
    </div>
    <?php } ?>
