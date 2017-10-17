<div class="container">
                <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
             <div class="clearfix pb2">
                        <h2><?= _('REMINDER'); ?></h2>
<?php
$reminder = $this->reminder;
     $car_id = $reminder->car_id;
        print '<div>'.strftime("%c",$reminder->time).'</div>';
        print '<h4>'.$reminder->content.'</h4>';

?></div>
<div><a href="<?= Config::get('URL') . 'message/reminders' ?>" class="h5 btn btn-primary mt1 black bg-kcms "><?= _("POSTPONE_REMINDER"); ?></a></div>

</div>
