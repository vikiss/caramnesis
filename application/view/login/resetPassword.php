<div class="container">
  <div class="clearfix">
        <?php $this->renderFeedbackMessages(); ?>
    <div class="sm-col md-col-12 lg-col-12">
      <div class="p4 bg-kclite m1 rounded">
    <h1><?php echo Text::get("PWCHANGE_SET"); ?></h1>
            <form method="post" action="<?php echo Config::get('URL'); ?>login/setNewPassword" name="new_password_form">
            <input type='hidden' name='user_name' value='<?php echo $this->user_name; ?>' />
            <input type='hidden' name='user_password_reset_hash' value='<?php echo $this->user_password_reset_hash; ?>' />
            <label for="reset_input_password_new"><?php echo Text::get("PWCHANGE_NEW_PWD"); ?></label>
            <input id="reset_input_password_new" class="block col-12 field mt1 " type="password"
                   name="user_password_new" pattern=".{6,}" required autocomplete="off" />
            <label for="reset_input_password_repeat"><?php echo Text::get("PWCHANGE_NEW_REPEAT"); ?></label>
            <input id="reset_input_password_repeat" class="block col-12 field mt1 " type="password"
                   name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
            <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" name="submit_new_password" value="<?php echo Text::get("PWCHANGE_NEW_SUBMIT"); ?>" />
        </form>
         <a href="<?php echo Config::get('URL'); ?>login/index"><?php echo Text::get("PWCHANGE_BACK_TO_LOGIN"); ?></a>
        </div>
      </div>
    </div>
</div>