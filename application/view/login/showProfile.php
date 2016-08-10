<?php $profile_data = $this->profile_data; $languages = $this->languages; $currencies = $this->currencies; $cons_units = $this->cons_units; $distance_units = $this->distance_units; ?>
<div class="container">
    <h1><?= _('YOUR_PROFILE'); ?></h1>
    <div class="box">
        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
<ul class="vlist list-reset">
        <li><label for="user_name"><?= _('USER_NAME'); ?></label><span id="user_name"><?= $profile_data->user_name; ?></span></li>
        <li><label for="user_email"><?= _('USER_EMAIL'); ?></label><span id="user_email"><?= $profile_data->user_email; ?></span></li>
        <li><label for="user_creation_timestamp"><?= _('USER_CREATION_TIMESTAMP'); ?></label><span id="user_creation_timestamp"><?= strftime("%c",$profile_data->user_creation_timestamp); ?></span></li>
        <li><label for="user_last_login_timestamp"><?= _('USER_LAST_LOGIN_TIMESTAMP'); ?></label><span id="user_last_login_timestamp"><?= strftime("%c",$profile_data->user_last_login_timestamp); ?></span></li>
        <li><label for="user_lang"><?= _('USER_LANGUAGE'); ?></label><span id="user_lang"><?= $profile_data->user_lang; ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="user_currency"><?= _('USER_CURRENCY'); ?></label><span id="user_currency"><?= _('CURRENCY_'.$profile_data->user_currency); ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="user_distance"><?= _('USER_DISTANCE_UNITS'); ?></label><span id="user_distance"><?= _('DISTANCE_'.$profile_data->user_distance); ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="user_cons"><?= _('USER_CONSUMPTION_UNITS'); ?></label><span id="user_cons"><?= _('CONSUMPTION_'.$profile_data->user_cons); ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="hometown"><?= _('USER_HOMETOWN'); ?></label><span id="hometown"><?= $profile_data->hometown; ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="state"><?= _('USER_STATE'); ?></label><span id="state"><?= $profile_data->state; ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="country"><?= _('USER_COUNTRY'); ?></label><span id="country"><?php if ($profile_data->country) echo _('COUNTRY_'.$profile_data->country); ?><i class="icon-cog pointer"></i></span></li>
</ul>
<input type="hidden" name = "country_id" id = "country_id" value="<?= $profile_data->country; ?>" />
<input type="hidden" name = "state_id" id = "state_id" value="<?= $profile_data->state_id; ?>" />
<input type="hidden" name = "city" id = "city" value="<?= $profile_data->hometown; ?>" />
<div id="userlangdialog" class="center" title="<?= _('PICK_A_LANGUAGE'); ?>"><?php
foreach ($languages AS $key=>$value) {
    print '<div class="left mb1 mr1 px1 black bg-kclite field"><a href="'.Config::get('URL').'login/setlang/'.$key.'">'._('LANG_'.strtoupper($key)).'</a></div>';
    };
?></div>
<div id="usercurrencydialog" class="center" title="<?= _('PICK_YOUR_CURRENCY'); ?>"><?php
foreach ($currencies AS $currency) {
print '<div class="left mb1 mr1 px1 black bg-kclite field"><a href="'.Config::get('URL').'login/setcurr/'.$currency.'">'._('CURRENCY_'.strtoupper($currency)).'</a></div>';    
};
?></div>
<div id="userdistancedialog" class="center" title="<?= _('PICK_YOUR_DISTANCE_UNIT'); ?>"><?php
foreach ($distance_units AS $distance_unit) {
print '<div class="left mb1 mr1 px1 black bg-kclite field"><a href="'.Config::get('URL').'login/setdist/'.$distance_unit.'">'._('DISTANCE_UNIT_'.$distance_unit).'</a></div>';    
};
?></div>
<div id="userconsdialog" class="center" title="<?= _('PICK_YOUR_CONSUMPTION_UNIT'); ?>"><?php
foreach ($cons_units AS $cons_unit) {
print '<div class="left mb1 mr1 px1 black bg-kclite field"><a href="'.Config::get('URL').'login/setcons/'.$cons_unit.'">'._('CONSUMPTION_UNIT_'.$cons_unit).'</a></div>';    
};
?></div>
<div id="hometowndialog" class="center" title="<?= _('PICK_YOUR_HOMETOWN'); ?>"><?= _('PICK_YOUR_COUNTRY_FIRST'); ?></div>
<div id="statedialog" class="center" title="<?= _('PICK_YOUR_STATE'); ?>"><?= _('PICK_YOUR_COUNTRY_FIRST'); ?></div>
<div id="countrydialog" class="center" title="<?= _('PICK_YOUR_COUNTRY'); ?>"></div>
<a href="<?php echo Config::get('URL'); ?>login/logout" class="btn btn-primary mb1 mt1 black bg-kcms"><?php echo _("MENU_LOGOUT"); ?></a>
    </div>
</div>
