<div class="container">
  <div class="clearfix">

    <?php $this->renderFeedbackMessages(); ?>

              <!-- login box on left side -->
    <div class="sm-col md-col-12 lg-col-12">
    <div class="p4 bg-kclite m1 rounded">
    <h2><?php echo _("NEWCAR_ADD"); ?></h2>
          <form method="post" action="<?php echo Config::get('URL');?>car/create_car">
                <label><?php echo _("NEWCAR_FRIENDLY_NAME"); ?>: </label><input type="text" name="car_name" placeholder="<?php echo _("NEWCAR_FRIENDLY_NAME_PLACEHOLDER"); ?>" class="block col-12 field mt1 " required />
                <label><?php echo _("NEWCAR_VIN"); ?>: </label><input type="text" name="car_vin" class="block col-12 field mt1 " required />
                <label><?php echo _("NEWCAR_MAKE"); ?>: </label><input type="text" name="car_make" class="block col-12 field mt1 " />
                <label><?php echo _("NEWCAR_MODEL"); ?>: </label><input type="text" name="car_model" class="block col-12 field mt1 " />
                <label><?php echo _("NEWCAR_PLATES"); ?>: </label><input type="text" name="car_plates" class="block col-12 field mt1 " required />
                <input type="submit" value='<?php echo _("SAVE"); ?>' class="btn btn-primary mb1 mt1 black bg-kcms" autocomplete="off" />
            </form>
            </div></div>

           
           

        
    
  </div>
</div>      