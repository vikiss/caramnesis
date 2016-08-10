<div class="container">
  <div class="clearfix">
        <?php $this->renderFeedbackMessages(); ?>
    <div class="sm-col md-col-12 lg-col-12">
      <div class="p4 bg-kclite m1 rounded">
    <h1><?php echo _("LOGIN_VERIFICATION"); ?></h1>
        <?php $this->renderFeedbackMessages(); ?>    
         <a href="<?php echo Config::get('URL'); ?>"><?php echo _("LOGIN_BACK_TO_HOMEPAGE"); ?></a>
        </div>
      </div>
    </div>
</div>
