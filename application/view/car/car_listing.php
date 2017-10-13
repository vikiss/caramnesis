<?php foreach ($this->cars as $row) {
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
    <div class="pic120mxht mt1 mr1 mb1">
        <a href="<?= Config::get('URL') . 'car/index/' . $id; ?>" title="<?= $row->car_name; ?>">
            <span class="bold"><?= $row->car_name; ?></span><br />
            <span class="smallish"><?= $row->car_make; ?> <?= $row->car_model; ?><br /><?= $plates_or_vin; ?></span>
        </a>
    </div>

</div>
<?php }; ?>
