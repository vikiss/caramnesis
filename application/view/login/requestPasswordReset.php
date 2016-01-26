<div class="container">
  <div class="clearfix">
        <?php $this->renderFeedbackMessages(); ?>
    <div class="sm-col md-col-12 lg-col-12">
      <div class="p4 bg-kclite m1 rounded">
    <h1><?php echo Text::get("PWCHANGE_REQUEST"); ?></h1>
        <form method="post" action="<?php echo Config::get('URL'); ?>login/requestPasswordReset_action">
            <label>
                <?php echo Text::get("PWCHANGE_ENTER_YOUR_DATA"); ?>
                <input type="text" name="user_name_or_email" class="block col-12 field mt1 " required />
            </label>
            <input type="submit" value="<?php echo Text::get("PWCHANGE_SEND_IT_TO_ME"); ?>" class="btn btn-primary mb1 mt1 black bg-kcms" />
        </form>
        </div>
      </div>
    </div>
</div>
