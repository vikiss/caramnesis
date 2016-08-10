<div class="bg-kclite p2 mt2 fauxfield relative">
        <div id="container" class="clearfix">
                        <input type="text" name="car_name" value="<?= $car_name; ?>" class="col-12 field mt1 " placeholder="<?php echo _("NEWCAR_FRIENDLY_NAME").' ('._("NEWCAR_FRIENDLY_NAME_PLACEHOLDER").')'; ?>" required />
                        <input type="text" name="car_vin" class="col-12 field mt1 " minlength="11" maxlength="17" placeholder="<?php echo _("NEWCAR_VIN"); ?>" value="<?= $car_vin; ?>" />
                        <div id="nwcardialog" title="<?php echo _("NEWCAR_MAKE"); ?>"><?= $selectlist; ?></div>
                        <input type="text" name="car_make" readonly value="<?= $car_make; ?>" placeholder="<?php echo _("NEWCAR_MAKE"); ?>" id = "nwcaropener" class="col-12 field mt1 " />
                        <input type="number" name="car_year" id="car_year" min="1900" max="<?php echo date('Y'); ?>" class="col-12 field mt1 " placeholder="<?php echo _("NEWCAR_YEAR"); ?>"  value="<?= $car_year; ?>" />
                        <div id="nwmodeldialog" title="<?php echo _("NEWCAR_MODEL"); ?>"><?php echo _("PICK_A_MAKE_AND_YEAR_FIRST"); ?></div>
                        <input type="text" name="car_model" readonly class="col-12 field mt1 " id = "nwmodelopener"  placeholder="<?php echo _("NEWCAR_MODEL"); ?>" value="<?= $car_model; ?>" />
                        <div id="nwvariantdialog" title="<?php echo _("NEWCAR_VARIANT"); ?>"><?php echo _("PICK_A_MAKE_AND_MODEL_FIRST"); ?></div>
                        <input type="text" name="car_variant" placeholder="<?php echo _("NEWCAR_VARIANT"); ?>" readonly class="col-12 field mt1 " id = "nwvariantopener" value="<?= $car_variant; ?>" />
                        <input type="text" name="car_plates" placeholder="<?php echo _("NEWCAR_PLATES"); ?>" class="col-12 field mt1 " value="<?= $car_plates[0]; ?>" />
                        <input type="hidden" name="car_make_id" id = "car_make_id" value="<?= $car_make_id; ?>" />
                        <input type="hidden" name="car_model_id" id = "car_model_id" value="<?= $car_model_id; ?>" />
                        <input type="hidden" name="car_variant_id" id = "car_variant_id" value="<?= $car_variant_id; ?>" />
                        <div id="dropbox">
                        <div class="fileupl left mt1"><span class="fauxinput icon-camera"></span><input type="file" name="fileinput[]" id="fileinput" multiple="multiple" accept="image/*" capture="camera" /></div>
                        <div class="small"><?= _("DROP_IMAGES"); ?></div>
                        </div>
                        <div id="filelist" class="list-reset">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
                          <div id="console"></div>
                          <div>
                          <?php
                           if ($car_images) {
                            foreach($car_images AS $image) {   
                            print '<a href="'.Config::get('URL').'/car/imagepg/'.$owner['uuid'].'/'.$car_id.'_'.$image.'"><img src="/car/image/'.$owner['uuid'].'/'.$car_id.'_'.$image.'/120" /></a> '; 
                                                           }
                                             } ?>
                          </div>
                        
        <?php
        if (CarModel::checkAccessLevel($car_id, Session::get('user_uuid')) > 98) {
            ?>
            <div id="deleteopener" class="small mb1 mt1 pointer" /><?= _("DELETE_CAR"); ?></div>
            <div id="deletedialog" class="center" title="<?= _('ARE_YOU_REALLY_REALLY_SURE'); ?>"><h3><?= _('ARE_YOU_REALLY_REALLY_SURE'); ?></h3><p><?= _('WARNING_NOT_UNDOABLE'); ?></p>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a href="<?= Config::get('URL'); ?>car/delete_car/<?= $car_id; ?>"><?= _('DELETE_CAR'); ?></a></div>
            </div>
            <?php } ?>    
                        <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                        <input type="hidden" name="car_data" id="car_data" value = "<?= $car_data; ?>" />
                        <input type="hidden" name="user_images" id="user_images" value = "<?=  $images_list; ?>" />
             
        
        
        
        
        <?php include('img_uploader.php');  ?>
        
</div>       <!-- fauxfield -->
    </div> <!-- container -->
    