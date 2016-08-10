<div class="clearfix">
	<div id="auth_user_result"><?php	include('authorised_users_table.php'); ?></div>
</div>
	
    <div class="clearfix mt2">
	<p><?= _("ADD_AUTHORISED_USER_TO_YOUR_CAR"); ?></p>
	<?php if ($this->user_to_auth) { ?>
	<p><?php printf(_("USER_%s_HAS_REQUESTED_ACCESS_TO_THIS_CAR_HIT_ADD_IF_OK"), $this->user_to_auth); ?></p>
	<?php } ?>
                <input type="text" name="service_provider_name" id="service_provider_name" placeholder="<?= _("PROVIDER_USER_NAME"); ?>" class="col-12 field mt1 " value="<?= $this->user_to_auth; ?>"  />
                <input type="submit" value='<?= _("ADD"); ?>' id="add_auth_user_submit" class="btn btn-primary mb1 mt1 black bg-kcms block right" autocomplete="off" />
    </div>
	<div id="authusrremovedlg" class="center" title="<?= _('DISABLE_ACCESS_FOR_THIS_USER'); ?>">
            <h3><?= _('DISABLE_ACCESS_FOR_THIS_USER'); ?></h3>
            <p id="authusrtoremove"></p>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a href="#" id="authusrremovebtn"><?= _('REMOVE'); ?></a></div>
</div>
	