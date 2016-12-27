<div class="container">
                <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
             <div class="clearfix pb2">
                        <h2><?= _('REMINDER'); ?></h2>
<?php
foreach ($this->reminder as $reminder) { //this only runs once
     $car_id = (array) $reminder['car_id'];
     $car_id = $car_id['uuid'];
     $time = (array) $reminder['time'];
   //$microtime = $time['microseconds'];
     $time = $time['seconds'];
        //print '<div>'.$car_id.'</div>';
        print '<div>'.strftime("%c",$time).'</div>';
        print '<h4>'.$reminder['content'].'</h4>';
};
?></div>
<div><a href="<?= Config::get('URL') . 'message/reminders' ?>" class="h5 btn btn-primary mt1 black bg-kcms "><?= _("POSTPONE_REMINDER"); ?></a></div>
        
</div>