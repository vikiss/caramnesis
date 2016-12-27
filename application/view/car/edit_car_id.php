<?php if ($this->car) {
 include('car_data_prep.php');
 if ($this->public_access) {$public_access=true;} else {$public_access=false;}
                      $selectlist='';
                            foreach ($this->makes as $make) {
                                $selectlist.='<div class="left mb1 mr1 px1 black bg-kclite field';
                                if ($make->rank == 'B') {$selectlist.=' hide';}
                                $selectlist.='"><a class="nwmake" data-make-id="'.$make->id.'" href="'.$make->make.'">'.$make->make.'</a></div>';
                                                            }
                                $selectlist.='<button id="show-all-makes" class="btn btn-primary mb1 mt1 black bg-kcms">'._('ALL_MAKES').'</button>';                                 
         ?>
<div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
    <h1><?= $car_name; ?></h1>
<div class="box">
<form method="post" action="<?php echo Config::get('URL');?>car/save_car_id">

                <div class="bg-kclite p2 mt2 fauxfield relative">
        <div id="container" class="clearfix">
           <div class="lblgrp"><label for="car_name"><?= _("NEWCAR_FRIENDLY_NAME").' ('._("NEWCAR_FRIENDLY_NAME_PLACEHOLDER").')'; ?></label>
                <input type="text" name="car_name" id="car_name" value="<?= $car_name; ?>" class="block col-12 field mt1 " required />
          </div>
          <div class="lblgrp"><label for="car_vin"><?= _("NEWCAR_VIN"); ?></label>
                <input type="text" name="car_vin" id="car_vin" value="<?= $car_vin; ?>" class="block col-12 field mt1 " minlength="11" maxlength="17" />
          </div>
                <div id="nwcardialog" class="center" title="<?= _("NEWCAR_MAKE"); ?>"><?= $selectlist; ?></div>
          <div class="lblgrp"><label for="nwcaropener"><?= _("NEWCAR_MAKE"); ?></label>
                <input type="text" name="car_make" readonly value="<?= $car_make; ?>" id = "nwcaropener" class="block col-12 field mt1 " />
          </div>
          <div class="lblgrp"><label for="car_year"><?= _("NEWCAR_YEAR"); ?></label>
                <input type="number" name="car_year" id="car_year" value="<?= $car_year; ?>" min="1900" max="<?= date('Y'); ?>" class="block col-12 field mt1 "  />
          </div>
                <div id="nwmodeldialog" class="center" title="<?= _("NEWCAR_MODEL"); ?>"><?= _("PICK_A_MAKE_AND_YEAR_FIRST"); ?></div>
          <div class="lblgrp"><label for="nwmodelopener"><?php echo _("NEWCAR_MODEL"); ?></label>
                <input type="text" name="car_model" readonly value="<?= $car_model; ?>" class="block col-12 field mt1 " id = "nwmodelopener" />
          </div>
                <div id="nwvariantdialog" class="center" title="<?= _("NEWCAR_VARIANT"); ?>"><?= _("PICK_A_MAKE_AND_MODEL_FIRST"); ?></div>
          <div class="lblgrp"><label for="nwvariantopener"><?= _("NEWCAR_VARIANT"); ?></label>  
                <input type="text" name="car_variant" readonly value="<?= $car_variant; ?>" class="block col-12 field mt1 " id = "nwvariantopener" />
          </div>
          <div class="lblgrp"><label for="car_plates"><?= _("NEWCAR_PLATES"); ?></label>
                <input type="text" name="car_plates" id="car_plates" value="<?= $car_plates[0]; ?>" class="block col-12 field mt1 " />
          </div>
                
                        <input type="hidden" name="car_make_id" id = "car_make_id" value="<?= $car_make_id; ?>" />
                        <input type="hidden" name="car_model_id" id = "car_model_id" value="<?= $car_model_id; ?>" />
                        <input type="hidden" name="car_variant_id" id = "car_variant_id" value="<?= $car_variant_id; ?>" />
                       
        <?php
        if (CarModel::checkAccessLevel($car_id, Session::get('user_uuid')) > 98) {
            ?>
            <div id="deleteopener" class="small mb1 mt1 pointer" /><?= _("DELETE_CAR"); ?></div>
            <div class="small mb1 mt1 pointer" /><a href="<?= Config::get('URL'); ?>car/car_transfer/<?= $car_id; ?>" title="<?= _("TRANSFER_YOUR_CAR_TO_ANOTHER_USER"); ?>"><?= _("TRANSFER_YOUR_CAR_TO_ANOTHER_USER"); ?></a></div>
            <div id="deletedialog" class="center" title="<?= _('ARE_YOU_REALLY_REALLY_SURE'); ?>"><h3><?= _('ARE_YOU_REALLY_REALLY_SURE'); ?></h3><p><?= _('WARNING_NOT_UNDOABLE'); ?></p>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a href="<?= Config::get('URL'); ?>car/delete_car/<?= $car_id; ?>"><?= _('DELETE_CAR'); ?></a></div>
            </div>
            <?php } ?>    
                        <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                        <input type="hidden" name="car_data" id="car_data" value = "<?= $car_data; ?>" />
             
<input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms right" value='<?php echo _("SAVE"); ?>' autocomplete="off" />        
</div>       <!-- fauxfield -->
</form>         

<?php } else { ?>
            <p><?= _("CAR_DOES_NOT_EXIST"); ?></p>
<?php } ?>
        
    </div> <!-- .box -->
</div> <!-- .container -->
            























































    