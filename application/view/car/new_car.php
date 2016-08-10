<?php 

$selectlist='';
 foreach ($this->makes as $make) {
 $selectlist.='<div class="left mb1 mr1 px1 black bg-kclite field';
if ($make->rank == 'B') {$selectlist.=' hide';}
$selectlist.='"><a class="nwmake" data-make-id="'.$make->id.'" href="'.$make->make.'">'.$make->make.'</a></div>';
                                 }
                                 
$selectlist.='<button id="show-all-makes" class="btn btn-primary mb1 mt1 black bg-kcms">'._('ALL_MAKES').'</button>';                                 
                                 
?>

<div class="container">
  <div class="clearfix">

    <?php $this->renderFeedbackMessages();    ?>

              <!-- login box on left side -->
    <div class="sm-col md-col-12 lg-col-12">
    <div class="p4 bg-kclite m1 rounded">
    <h2><?php echo _("NEWCAR_ADD"); ?></h2>
          <form method="post" action="<?php echo Config::get('URL');?>car/create_car">
                <input type="text" name="car_name" placeholder="<?php echo _("NEWCAR_FRIENDLY_NAME").' ('._("NEWCAR_FRIENDLY_NAME_PLACEHOLDER").')'; ?>" class="col-12 field mt1 " required />
                <input type="text" name="car_vin" class="col-12 field mt1 " minlength="11" maxlength="17" placeholder="<?php echo _("NEWCAR_VIN"); ?>" />
                <div id="nwcardialog" class="center" title="<?php echo _("NEWCAR_MAKE"); ?>"><?= $selectlist; ?></div>
                <input type="text" name="car_make" readonly placeholder="<?php echo _("NEWCAR_MAKE"); ?>" id = "nwcaropener" class="col-12 field mt1 " />
                <input type="number" name="car_year" id="car_year" min="1900" max="<?php echo date('Y'); ?>" class="col-12 field mt1 " placeholder="<?php echo _("NEWCAR_YEAR"); ?>" />
                <div id="nwmodeldialog" class="center" title="<?php echo _("NEWCAR_MODEL"); ?>"><?php echo _("PICK_A_MAKE_AND_YEAR_FIRST"); ?></div>
                <input type="text" name="car_model" readonly class="col-12 field mt1 " id = "nwmodelopener" placeholder="<?php echo _("NEWCAR_MODEL"); ?>" />
                <div id="nwvariantdialog" class="center" title="<?php echo _("NEWCAR_VARIANT"); ?>"><?php echo _("PICK_A_MAKE_AND_MODEL_FIRST"); ?></div>
                <input type="text" name="car_variant" readonly class="col-12 field mt1 " id = "nwvariantopener" placeholder="<?php echo _("NEWCAR_VARIANT"); ?>" />
                <input type="text" name="car_plates" class="col-12 field mt1 " placeholder="<?php echo _("NEWCAR_PLATES"); ?>" />
                <input type="hidden" name="car_make_id" id = "car_make_id" />
                <input type="hidden" name="car_model_id" id = "car_model_id" />
                <input type="hidden" name="car_variant_id" id = "car_variant_id" />
                <input type="submit" value='<?php echo _("SAVE"); ?>' class="btn btn-primary mb1 mt1 black bg-kcms block" autocomplete="off" />
            </form>
            </div></div>


  </div>
</div>                            