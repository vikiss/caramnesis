
<div class="container">
  <div class="clearfix">

    <?php $this->renderFeedbackMessages();    ?>

    <div class="sm-col md-col-12 lg-col-12">
    <div class="p4 bg-kclite m1 rounded">
    <h2><?= _("NEWCAR_ADD"); ?></h2>
    <form method="post" action="<?= Config::get('URL');?>car/create_car">
        <div class="lblgrp"><label for="car_name"><?= _("NEWCAR_FRIENDLY_NAME").' ('._("NEWCAR_FRIENDLY_NAME_PLACEHOLDER").')'; ?></label>
                <input type="text" name="car_name" id="car_name" class="block col-12 field mt1 " required />
          </div>
          <div class="lblgrp"><label for="car_vin"><?= _("NEWCAR_VIN"); ?></label>
                <input type="text" name="car_vin" id="new_vin" class="block col-12 field mt1 " pattern="[a-zA-Z0-9]+" minlength="11" maxlength="17" />
          </div>
          <div id="new_car_data_by_vin" data-body="<?= _("CHECKING_VIN_DATA"); ?>"></div>
          
                <div class="btn mb1 mt1 black bg-kcms " id="submit_vin_container"><a id="submit_vin" href="#"><?= _("SAVE"); ?></a></div>
        </form>
            </div></div>


  </div>
</div>                            