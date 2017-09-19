<div class="container">
    <div class="box">
        <?php $this->renderFeedbackMessages();
        //$this->auth_car_list
        //print_r($this->otherscars);
        ?>


	</div>
<div class="pb2">


            <a href="<?php echo Config::get('URL'); ?>car/new_car" class="h5 btn btn-primary mt1 black bg-kcms " title="<?php echo _("NEWCAR_ADD"); ?>">
                <i class="plus-circled"> </i> <?php echo _("NEWCAR_ADD"); ?>
            </a>
           <a href="<?php echo Config::get('URL'); ?>car/new_proxy" class="h5 btn btn-primary mt1 black bg-kcms " title="<?php echo _("OTHER_PEOPLES_CARS"); ?>">
               <i class="plus-circled"> </i> <?php echo _("OTHER_PEOPLES_CARS"); ?>
           </a>
</div>


	<?php

if ($this->cars) {

include(realpath(dirname(__FILE__).'/../') . '/car/car_listing.php' );

} else echo '<h1>'._("NO_CARS_FOUND").'</h1>' ?>

</div>
