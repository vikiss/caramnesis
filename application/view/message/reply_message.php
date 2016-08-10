<div class="container">
  <div class="clearfix">
    <?php $this->renderFeedbackMessages(); ?>
    
    
    
       <?php  foreach ($this->message as $message) { //this only runs once ?>
       
   
       
       <?php
        $timeuuid = (array) $message['time'];
       }; ?><form method="post" action="<?php echo Config::get('URL'); ?>message/send">
            <input type="text" pattern="[a-zA-Z0-9]{2,64}" name="recipient" value="<?= UserModel::getUserNameByUUid($message['sender']); ?>" class="block col-12 field mt1 " readonly="readonly" />
            <input type="text" name="subject" value="<?= _("MESSAGE_RE").': '.$message['subject']; ?>" class="block col-12 field mt1 " required />
                <textarea name="message" class="block col-12 field mt1 textfield" ><?= "\r\n\r\n"._("MESSAGE_QUOTED").":\r\n".$message['message']; ?></textarea>
            <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value="<?php echo _("MESSAGE_SEND"); ?>" />
        </form>
        
  </div></div>