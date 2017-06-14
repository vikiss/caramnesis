<?php
$vin = $this->vin;
$vin_data = $this->vin_data;
$readonly = 'readonly';
if ($vin_data == '') { ?> 
    <p><?= _("VIN_NOT_FOUND"); ?></p>
    <div class="btn mb1 mt1 black bg-kcms "><a id = "retest_vin" href="#"><?= _("RETEST_VIN"); ?></a></div>
    <div class="btn mb1 mt1 black bg-kcms "><a href="<?= Config::get('URL'); ?>car/new_car_manual"><?= _("ENTER_CAR_DATA_MANUALLY"); ?></a></div>
<?php
} else {
        
    //print $vin_data['Engine Displacement (ccm)'];
    //print $vin_data['Engine Power (kW)'];
    //WF0CXXGAKCEL87465

    ?>
    
             <div class="lblgrp"><label for="nwcaropener"><?= _("NEWCAR_MAKE"); ?></label>
                <input type="text" name="car_make" <?= $readonly; ?> class="block col-12 field mt1 " value="<?= $vin_data['Make']; ?>" />
          </div>
          <div class="lblgrp"><label for="car_year"><?= _("NEWCAR_YEAR"); ?></label>
                <input type="number" name="car_year" id="car_year" min="1900" max="<?php echo date('Y'); ?>" class="block col-12 field mt1 " value="<?= $vin_data['Model Year']; ?>"  />
          </div>
                
          <?php if (isset($vin_data['Model']) === true) { ?>
          
          <div class="lblgrp"><label for="nwmodelopener"><?php echo _("NEWCAR_MODEL"); ?></label>
                <input type="text" name="car_model" <?= $readonly; ?> class="block col-12 field mt1 " id = "nwmodelopener" value="<?= $vin_data['Model']; ?>"/>
          </div>
          
          <?php } else { ?>
          
          <div class="lblgrp"><label for="nwmodelopener"><?php echo _("NEWCAR_MODEL"); ?></label>
                <input type="text" name="car_model" class="block col-12 field mt1 " id = "nwmodelopener" value=""/>
          </div>
          
          
          <?php }; ?>
          
            <div class="lblgrp"><label for="nwvariantopener"><?= _("NEWCAR_VARIANT"); ?></label>  
                <input type="text" name="car_variant" class="block col-12 field mt1 " id = "nwvariantopener" />
          </div>
          <div class="lblgrp"><label for="car_plates"><?= _("NEWCAR_PLATES"); ?></label>
                <input type="text" name="car_plates" id="car_plates" class="block col-12 field mt1 " />
          </div>
                <input type="hidden" name="car_make_id" id = "car_make_id" />
                <input type="hidden" name="car_model_id" id = "car_model_id" />
                <input type="hidden" name="car_variant_id" id = "car_variant_id" />
                <input type="submit" value='<?= _("SAVE"); ?>' class="btn btn-primary mb1 mt1 black bg-kcms block" autocomplete="off" />
<?php }; ?>