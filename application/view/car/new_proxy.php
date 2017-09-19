<div class="container">
  <div class="clearfix">

    <?php $this->renderFeedbackMessages();    ?>

    <div>

<?php

if ($this->cars) { ?>
<input type="hidden" name="auth_car_list" id = "auth_car_list" value="<?= urlencode(serialize($this->auth_car_list)); ?>" />
<?php

foreach ($this->cars as $car) {
  foreach ($car as $row) {
      $id = $row->id;
      $car_images = unserialize($row->images);
                         if (is_array($car_images)) {
                         $car_image =  reset($car_images);} else {
                         $car_images = false; $car_image = '';}



      $plates_or_vin = unserialize($row->car_plates);
      if (count($plates_or_vin)) {$plates_or_vin=$plates_or_vin[0]; }
      else { $plates_or_vin=$row->car_vin; }
      ?>
      <div class="mb1 mr1 clearfix bg-kclite rounded md-col md-col-5">
          <div class="left m1 " >

      <a href="<?= Config::get('URL') . 'car/index/' . $id; ?>" title="<?= $row->car_name; ?>">
      <?php if ($car_images) { ?>
      <div class="pic120width bg-white relative z1"  style="background-image: url(<?= Config::get('URL') .'car/image/'.$id.'/'.$car_image; ?>/120)"> </div>
      <?php } else { ?>
          <div class="pic120width bg-white relative center bg-kclite " >
                 &nbsp;<br />&nbsp;<br/>
                     <i class="icon-camera"> </i>
          </div>
      <?php }; ?>
      </a>

          </div>
          <div class="left">
              <a href="<?= Config::get('URL') . 'car/index/' . $id; ?>" title="<?= $row->car_name; ?>">
                  <span class="bold"><?= $row->car_name; ?></span><br />
                  <i class="icon-user"> </i><?= UserModel::getUserNameByUUid($row->owner); ?><br />
                  <span class="smallish"><?= $row->car_make; ?> <?= $row->car_model; ?><br /><?= $plates_or_vin; ?></span>
              </a>
          </div>

      </div>







<?php  } }




 } ?>


    </div>


      <div id="request_car_access_dlg" class="center" title="<?= _('REQUEST_ACCESS_FROM_CAR_OWNER'); ?>">
        <h3><?= _('SEND_REQUEST_TO_OWNER_?'); ?></h3>
        <p id="owner_distance"></p>
        <input type="hidden" id="car_owner" value="" /><input type="hidden" id="car_id" value="" />
        <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog " href="#"><?= _('CANCEL'); ?></a></div>
        <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog request_car_access" href="#"><?= _('SEND'); ?></a></div>
      </div>




    <div class="sm-col md-col-12 lg-col-12">
    <div class="p4 bg-kclite m1 rounded">
    <h2><?php echo _("PROXY_CAR_ADD"); ?></h2>
    <div class="clearfix">
                <div class="lblgrp"><label for="car_plates_or_vin"><?= _("CAR_PLATES_OR_VIN"); ?></label>
                <input type="text" name="car_plates_or_vin" id="car_plates_or_vin" class="block col-12 field mt1"  />
                <input type="submit" value='<?= _("FIND_CARS"); ?>' id="find_cars_submit" class="btn btn-primary mb1 mt1 black bg-kcms block right" autocomplete="off" />
    </div>
<div class="clearfix"><div id="cars_by_owner" class="my2"></div></div>
            </div></div>


  </div>
</div>
