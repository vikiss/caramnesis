<?php 

$selectlist='';
 foreach ($this->makes as $make) { 
$selectlist.='<div class="left mb1 mr1 px1 black bg-kclite field';
if ($make->rank == 'B') {$selectlist.=' hide';}
$selectlist.='"><a class="nwmake" href="'.$make->make.'">'.$make->make.'</a></div>';
                                 }
                                 
$selectlist.='<button id="show-all-makes" class="btn btn-primary mb1 mt1 black bg-kcms">'._('ALL_MAKES').'</button>';                                 
                                 
?>

<div class="container">
  <div class="clearfix">

    <?php $this->renderFeedbackMessages(); ?>

              <!-- login box on left side -->
    <div class="sm-col md-col-12 lg-col-12">
    <div class="p4 bg-kclite m1 rounded">
    <h2><?php echo _("NEWCAR_ADD"); ?></h2>
          <form method="post" action="<?php echo Config::get('URL');?>car/create_car">
                <input type="text" name="car_name" placeholder="<?php echo _("NEWCAR_FRIENDLY_NAME").' ('._("NEWCAR_FRIENDLY_NAME_PLACEHOLDER").')'; ?>" class="col-6 field mt1 " required />
                <input type="text" name="car_vin" class="col-5 field mt1 " placeholder="<?php echo _("NEWCAR_VIN"); ?>" />
                <div id="nwcardialog" title="<?php echo _("NEWCAR_MAKE"); ?>"><?= $selectlist; ?></div>
                <input type="text" name="car_make" readonly placeholder="<?php echo _("NEWCAR_MAKE"); ?>" id = "nwcaropener" class="col-3 field mt1 " />
                <input type="text" name="car_model" class="col-5 field mt1 " placeholder="<?php echo _("NEWCAR_MODEL"); ?>" />
                <input type="text" name="car_plates" class="col-3 field mt1 " placeholder="<?php echo _("NEWCAR_PLATES"); ?>" />
                <input type="submit" value='<?php echo _("SAVE"); ?>' class="btn btn-primary mb1 mt1 black bg-kcms block" autocomplete="off" />
            </form>
            </div></div>

           
           

        
    
  </div>
</div>                             