<?php $profile_data = $this->profile_data; $languages = $this->languages; $currencies = $this->currencies; $cons_units = $this->cons_units; $distance_units = $this->distance_units;
//CarModel::ExecuteTransfer('0cb71f32-b56a-49ae-8984-476eb5c0e3a9', '', 'c1d79520-8dfe-11e5-8145-001cc07ade33');
?>
<div id="responsebox" class="right p1"> </div>
<div class="container">
    <h1><?= _('YOUR_PROFILE'); ?></h1>
    <div class="box">
        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
<ul class="vlist list-reset">
        <li><label for="user_name"><?= _('USER_NAME'); ?></label><span id="user_name"><?= $profile_data->user_name; ?></span></li>
        <li><label for="user_email"><?= _('USER_EMAIL'); ?></label><span id="user_email"><?= $profile_data->user_email; ?></span></li>
        <li><label for="email_checkbox"><?= _('ENABLE_EMAIL_NOTIFICATIONS'); ?></label><span id="email_checkbox_cont">
        <input type="checkbox" class="cbrad" name="email_checkbox" id="email_checkbox" value="send_email" <?php if ($profile_data->send_email == 1) echo ' checked'; ?> /></span></li>
        <li><label for="user_phone"><?= _('USER_PHONE'); ?></label><span id="user_phone"><?= $profile_data->user_phone; ?><i class="icon-cog pointer"></i></span></li>
        <?php //sms in LT only for now
        if (strtoupper($profile_data->country) == 'LT') { ?>
        <li><label for="sms_checkbox"><?= _('ENABLE_SMS_NOTIFICATIONS'); ?></label><span id="sms_checkbox_cont">
        <input type="checkbox" class="cbrad" name="sms_checkbox" id="sms_checkbox" value="send_sms" <?php if ($profile_data->send_sms == 1) echo ' checked'; ?> /></span></li>
        <?php }; ?>
        <li><label for="user_creation_timestamp"><?= _('USER_CREATION_TIMESTAMP'); ?></label><span id="user_creation_timestamp"><?= strftime("%c",$profile_data->user_creation_timestamp); ?></span></li>
        <li><label for="user_last_login_timestamp"><?= _('USER_LAST_LOGIN_TIMESTAMP'); ?></label><span id="user_last_login_timestamp"><?= strftime("%c",$profile_data->user_last_login_timestamp); ?></span></li>
        <li><label for="user_lang"><?= _('USER_LANGUAGE'); ?></label><span id="user_lang"><?= $profile_data->user_lang; ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="user_currency"><?= _('USER_CURRENCY'); ?></label><span id="user_currency"><?= _('CURRENCY_'.$profile_data->user_currency); ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="user_distance"><?= _('USER_DISTANCE_UNITS'); ?></label><span id="user_distance"><?= _('DISTANCE_'.$profile_data->user_distance); ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="user_cons"><?= _('USER_CONSUMPTION_UNITS'); ?></label><span id="user_cons"><?= _('CONSUMPTION_'.$profile_data->user_cons); ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="hometown"><?= _('USER_HOMETOWN'); ?></label><span id="hometown"><?= $profile_data->hometown; ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="state"><?= _('USER_STATE'); ?></label><span id="state"><?= $profile_data->state; ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="country"><?= _('USER_COUNTRY'); ?></label><span id="country"><?php if ($profile_data->country) echo _('COUNTRY_'.$profile_data->country); ?><i class="icon-cog pointer"></i></span></li>
        <li><label for="pubpage_checkbox"><?= _('ENABLE_PUBLIC_PAGE'); ?></label><span id="pubpage_checkbox_cont">
        <input type="checkbox" class="cbrad" name="pubpage_checkbox" id="pubpage_checkbox" value="public_page" <?php if ($profile_data->public_page == 1) echo ' checked'; ?> /><br />
        <a href="<?= Config::get('URL') . Session::get('user_name'); ?>" target="_blank" title="<?= _('PUBLIC_PAGE_URL'); ?>"><?= _('PUBLIC_PAGE_URL').': '.Config::get('URL') . Session::get('user_name'); ?> </a><br />
        <a href="<?= Config::get('URL') . 'profile/index' ?>" title="<?= _('PREVIEW_PUBLIC_PAGE'); ?>"><?= _('PREVIEW_PUBLIC_PAGE'); ?> </a><br />
        <a href="<?= Config::get('URL') . 'profile/edit' ?>" title="<?= _('EDIT_PUBLIC_PAGE'); ?>"><?= _('EDIT_PUBLIC_PAGE'); ?> </a>
        </span></li>
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
