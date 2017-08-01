<div class="bg-kclite p2 mt2 fauxfield relative" id="odo_dlg" title="<?php echo _("ENTER_CURRENT_ODO"); ?>">
   <form method="post" enctype="multipart/form-data" id="odo_warning_form" action="<?php echo Config::get('URL');?>car/save_odo">
              <div class="lblgrp"><label for="event_odo"><?= _("EVENT_ODO").', '.$units->user_distance; ?></label>
                        <input type="number" min="0" step="1" name="this_event_odo" id="this_event_odo" class="block field mt1 col-12" value="<?= $odo; ?>" />
               </div>
             <input type="hidden" name="this_car_id" id="this_car_id" value = "<?= $car_id; ?>" />
             <input type="hidden" name="this_car_odo" id="this_car_odo" value = "<?= $odo; ?>" />
             <input type="hidden" name="return_to" id="return_to" value = "<?= $return_to; ?>" />
             <div class = "center">
              <p class="mt2 mb2 hide warning" id="odo_warning_box">
              <input type="checkbox" class="cbrad px2" name="odo_warning_checkbox" id="odo_warning_checkbox" value="true" />
              <?= _("ODOMETER_ROLL_BACK_WARNING"); ?></p>
               <div class="btn mb1 mt1 black bg-kcms " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
               <input type="submit" class="btn mb1 mt1 black bg-kcms" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
            </div>
    </form>
</div>
