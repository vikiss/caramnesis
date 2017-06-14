<div class="container">
                <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
             <div class="clearfix">

       <?php  $message = $this->message;
               
    
            $serialized_message_time =  CarModel::parsetimestamp($message->time);
            $message_time = $serialized_message_time['seconds'];
            $message_microtime = $serialized_message_time['microseconds'];
                
                ?>
       
       <p class="smallish"><?= _('MESSAGE_FROM'); ?>: <span class="bold"><?= UserModel::getUserNameByUUid($message->sender); ?></span><br />
       <p class="smallish"><?= _('MESSAGE_TO'); ?>: <span class="bold"><?= UserModel::getUserNameByUUid($message->recipient); ?></span><br />
       <p class="smallish"><?= strftime ("%c", $message_time); ?></p>
       <p><span class="bold"><?= $message->subject; ?></span></p><hr />
       <p class="textfield"><?= $message->message; ?></p>
       

            <div>
                        <a href="<?= Config::get('URL') . 'message/reply_message/'.$message->time; ?>" class="h5 btn btn-primary mt1 black bg-kcms right "><?php echo _("MESSAGE_REPLY"); ?></a>
            </div>
             </div>
</div>
