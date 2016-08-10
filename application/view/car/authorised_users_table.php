<?php
//if ((isset($this->owner)) && ($this->owner)) { echo '<input type="hidden" id = "authusrsuccess" name="authusrsuccess" value="'.$this->owner.'" />';}
// tried to clear the input text field on success, decided to clear it anyway
if ($this->auth_users) { ?>
	<p><?= _("THESE_USERS_CAN_WORK_ON_YOUR_CAR"); ?></p>
	<?php
	foreach($this->auth_users as $auth_user) {
		$auth_user_name = UserModel::getUserNameByUUid($auth_user->user); ?>

<div class="mb1 mr1 p1 black bg-kclite left fauxfield square center truncate">
<a href="<?= Config::get('URL') . 'user/profile/' . $auth_user->user; ?>" title="<?= $auth_user_name; ?>"><?= $auth_user_name; ?></a>
<div><a class="authusrremovedlgopener" data-authusr="<?= $auth_user_name; ?>" href="#"><?= _('REMOVE'); ?></a></div>
</div>
		
	<?php }}	?>