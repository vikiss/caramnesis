<?php if ($this->car) {
include('car_data_prep.php'); 

    ?>
    <div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>">
	<i class="icon-cancel white"> </i></a></div>
    <h1><?= $car_name; ?></h1>
<div class="box">
<?php
$new_user = $this->new_user;
?>
<form method="post" enctype="multipart/form-data" action="<?php echo Config::get('URL');?>car/send_transfer_request">
    <div class="clearfix mt2">
	<h3><?php printf(_("TRANSFER_YOUR_CAR_TO_USERNAME_%s"), $new_user->user_name); ?></h3>
    <p><?= _("TRANSFER_YOUR_CAR_TO_USER_FINAL_WARNING"); ?></p>
                <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                <input type="hidden" name="user_id" id="user_id" value = "<?= $new_user->user_uuid; ?>" />
             <div class="center">
                <div class="btn btn-primary mb1 mt1 black bg-kcms" >
					<a title="<?= _('CANCEL'); ?>" href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>"><?= _('CANCEL'); ?></a>
				</div>
				<input type="submit" value='<?= _("TRANSFER"); ?>' id="new_owner_submit" class="btn btn-primary mb1 mt1 black bg-kcms" autocomplete="off" />
             </div>
    </div>
</form>	

</div>
    </div>
    <?php } ?>
    
	
	
	
