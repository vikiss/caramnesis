<?php

$selectlist=''; $initial_letter = 'A'; $initial_make_letter = 'A';
 foreach ($this->makes as $make) {
     $initial_make_letter = substr($make->make, 0, 1);
     if ($initial_make_letter !== $initial_letter) {
//         $selectlist .= '<h3>'.$initial_make_letter.'</h3>';
$selectlist .= '<div class="mt1"> </div>';
         $initial_letter = $initial_make_letter;
     }
     if (!strpos(')', $make->make)) {
$selectlist.='<div class="smallish';
if ($make->rank == 'B') {$selectlist.=' hide';}
$selectlist.='"><a class="nwmake" data-make-id="'.$make->id.'" href="'.$make->make.'">'.$make->make.'</a></div>';
                                 }}

$selectlist.='<button id="show-all-makes" class="btn btn-primary mb1 mt1 black bg-kcms">'._('ALL_MAKES').'</button>';

?>

<div class="container">
  <div class="clearfix">

    <?php $this->renderFeedbackMessages();    ?>

              <!-- login box on left side -->
    <div class="sm-col md-col-12 lg-col-12">
    <div class="p4 bg-kclite m1 rounded">
    <h2><?= _("NEWCAR_ADD"); ?></h2>
          <form method="post" action="<?= Config::get('URL');?>car/create_car">
          <div class="lblgrp"><label for="car_name"><?= _("NEWCAR_FRIENDLY_NAME").' ('._("NEWCAR_FRIENDLY_NAME_PLACEHOLDER").')'; ?></label>
                <input type="text" name="car_name" id="car_name" class="block col-12 field mt1 " required />
          </div>
          <div class="lblgrp"><label for="car_vin"><?= _("NEWCAR_VIN"); ?></label>
                <input type="text" name="car_vin" id="car_vin" class="block col-12 field mt1 " minlength="11" maxlength="17" />
          </div>
                <div id="nwcardialog" class="columns" title="<?= _("NEWCAR_MAKE"); ?>"><?= $selectlist; ?></div>
          <div class="lblgrp"><label for="nwcaropener"><?= _("NEWCAR_MAKE"); ?></label>
                <input type="text" name="car_make" readonly id = "nwcaropener" class="block col-12 field mt1 " />
          </div>
          <div class="lblgrp"><label for="car_year"><?= _("NEWCAR_YEAR"); ?></label>
                <input type="number" name="car_year" id="car_year" min="1900" max="<?php echo date('Y'); ?>" class="block col-12 field mt1 "  />
          </div>
                <div id="nwmodeldialog" title="<?= _("NEWCAR_MODEL"); ?>"><?php echo _("PICK_A_MAKE_AND_YEAR_FIRST"); ?></div>
          <div class="lblgrp"><label for="nwmodelopener"><?php echo _("NEWCAR_MODEL"); ?></label>
                <input type="text" name="car_model" readonly class="block col-12 field mt1 " id = "nwmodelopener" />
          </div>
                <div id="nwvariantdialog" title="<?= _("NEWCAR_VARIANT"); ?>"><?php echo _("PICK_A_MAKE_AND_MODEL_FIRST"); ?></div>
          <div class="lblgrp"><label for="nwvariantopener"><?= _("NEWCAR_VARIANT"); ?></label>
                <input type="text" name="car_variant" readonly class="block col-12 field mt1 " id = "nwvariantopener" />
          </div>
          <div class="lblgrp"><label for="car_plates"><?= _("NEWCAR_PLATES"); ?></label>
                <input type="text" name="car_plates" id="car_plates" class="block col-12 field mt1 " />
          </div>
                <input type="hidden" name="car_make_id" id = "car_make_id" />
                <input type="hidden" name="car_model_id" id = "car_model_id" />
                <input type="hidden" name="car_variant_id" id = "car_variant_id" />
                <input type="submit" value='<?= _("SAVE"); ?>' class="btn btn-primary mb1 mt1 black bg-kcms block" autocomplete="off" />
            </form>
            </div></div>


  </div>
</div>
