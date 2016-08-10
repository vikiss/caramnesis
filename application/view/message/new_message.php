<div class="container">
  <div class="clearfix">
    <?php $this->renderFeedbackMessages(); ?>

        <form method="post" action="<?php echo Config::get('URL'); ?>message/send">
            <input type="text" pattern="[a-zA-Z0-9]{2,64}" name="recipient" placeholder="<?= _("MESSAGE_RECIPIENT"); ?>" class="block col-12 field mt1 " required />
            <input type="text" name="subject" placeholder="<?= _("MESSAGE_SUBJECT"); ?>" class="block col-12 field mt1 " required />
                <textarea name="message" class="block col-12 field mt1 textfield" value=""></textarea>
            <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value="<?php echo _("MESSAGE_SEND"); ?>" />
        </form>
        
  </div></div>