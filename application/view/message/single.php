<div class="container">
                <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
             <div class="clearfix">

       <?php  foreach ($this->message as $message) { //this only runs once
                
                $message_time = (array) $message['system.dateof(time)'];
                
                ?>
       
       <p class="smallish"><?= _('MESSAGE_FROM'); ?>: <span class="bold"><?= UserModel::getUserNameByUUid($message['sender']); ?></span><br />
       <p class="smallish"><?= _('MESSAGE_TO'); ?>: <span class="bold"><?= UserModel::getUserNameByUUid($message['recipient']); ?></span><br />
       <p class="smallish"><?= strftime ("%c", $message_time['seconds']); ?></p>
       <p><span class="bold"><?= $message['subject']; ?></span></p><hr />
       <p class="textfield"><?= $message['message']; ?></p>
       
       <?php
        $timeuuid = (array) $message['time'];
       }; ?>

            <div>
                        <a href="<?= Config::get('URL') . 'message/reply_message/'.$timeuuid['uuid'] ?>" class="h5 btn btn-primary mt1 black bg-kcms right "><?php echo _("MESSAGE_REPLY"); ?></a>
            </div>
             </div>
</div>
