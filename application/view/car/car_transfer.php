<?php if ($this->car) {
include('car_data_prep.php'); 

    ?>
    <div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
    <h1><?= $car_name; ?></h1>
<div class="box">

<form method="post" enctype="multipart/form-data" action="<?php echo Config::get('URL');?>car/confirm_transfer">


    <div class="p4 bg-kclite rounded mt2">
	<h3><?= _("TRANSFER_YOUR_CAR_TO_USER"); ?></h3>
    <p><?= _("TRANSFER_YOUR_CAR_TO_USER_WARNING"); ?></p>
                <div class="clearfix mt2">
                    <div class="lblgrp"><label for="new_owner_name_or_email"><?= _("NEW_OWNER_NAME_OR_EMAIL"); ?></label>
                    <input type="text" class="block col-12 field mt1" id="new_owner_name_or_email" name="new_owner_name_or_email" value=""  autocomplete="off">
                <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
				<input type="submit" value='<?= _("TRANSFER"); ?>' id="new_owner_submit" class="btn btn-primary mb1 mt1 black bg-kcms block right" autocomplete="off" />
				</div></div>
				
	<div id="new_user_result" class="clearfix mt2 mb2 fheight"></div>
    </div>	
</form>	

</div>
    </div>
    <?php } ?>
    
	
	
	
