<?php if ($this->car) {
include('car_data_prep.php'); 

    ?>
    <div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
    <h1><?= $car_name; ?></h1>
<div class="box">

<div class="clearfix">
	<div id="auth_user_result"><?php	include('authorised_users_table.php'); ?></div>
</div>
	

	    <div class="p4 bg-kclite rounded mt2">
    <h2><?= _("ADD_AUTHORISED_USER_TO_YOUR_CAR"); ?></h2>
    <div class="clearfix">
                <div class="lblgrp"><label for="service_provider_name"><?= _("PROVIDER_USER_NAME"); ?></label>
                <input type="text" name="service_provider_name" id="service_provider_name" class="block col-12 field mt1" value="<?= $this->user_to_auth; ?>"  />
				<input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                <input type="submit" value='<?= _("ADD"); ?>' id="add_auth_user_submit" class="btn btn-primary mb1 mt1 black bg-kcms block right" autocomplete="off" />
    </div>
     </div>
	<div id="auth_user_suggestion" class="clearfix mt2 mb2 fheight"></div>
		</div>
	
	
	
	
	
	
	<div id="authusrremovedlg" class="center" title="<?= _('DISABLE_ACCESS_FOR_THIS_USER'); ?>">
            <h3><?= _('DISABLE_ACCESS_FOR_THIS_USER'); ?></h3>
            <p id="authusrtoremove"></p>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a href="#" id="authusrremovebtn"><?= _('REMOVE'); ?></a></div>
</div>

</div>
    </div>
    <?php } ?>
	
	
	
