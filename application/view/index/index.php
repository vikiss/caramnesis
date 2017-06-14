<div class="container">
    <div class="box">
        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
	</div>
	<?php
 // $what = CarModel::getCarImage('0cb71f32-b56a-49ae-8984-476eb5c0e3a9');
 // print_r($what);
  
if ($this->car) { 
foreach ($this->car as $row) {  
      $id = $row->id;
      $car_images = unserialize($row->images);
                         if (is_array($car_images)) {
                         $car_image =  reset($car_images);} else {
                         $car_images = false; $car_image = '';}
                          
                        
      
      $plates_or_vin = unserialize($row->car_plates);
      if (count($plates_or_vin)) {$plates_or_vin=$plates_or_vin[0]; }
      else { $plates_or_vin=$row->car_vin; }
      ?>
<div class="mb1 mr1 p1 black bg-kclite left fauxfield square center truncate">
<a href="<?= Config::get('URL') . 'car/index/' . $id; ?>" title="<?= $row->car_name; ?> (<?= $row->car_make; ?> <?= $row->car_model; ?> <?= $plates_or_vin; ?>)"><?= $row->car_name; ?></a>
<?php if ($car_images) { ?>
<div><a href="<?= Config::get('URL') . 'car/index/' . $id; ?>" title="<?= $row->car_name; ?> (<?= $row->car_make; ?> <?= $row->car_model; ?> <?= $plates_or_vin; ?>)">
<?php print '<img class="crop" src="/car/image/'.$id.'/'.$car_image.'/120" />'; ?>
</a></div>
<?php }; ?>
</div>
                  
                 
<?php  } } else echo '<h1>'._("NO_CARS_FOUND").'</h1>' ?>
 
 <div class="mb1 mr1 p1 black bg-kcms left fauxfield square center table"><div class="table-cell align-middle"><a href="<?php echo Config::get('URL'); ?>car/new_proxy" title="<?php echo _("OTHER_PEOPLES_CARS"); ?>"><?php echo _("OTHER_PEOPLES_CARS"); ?></a></div></div>
 <div class="mb1 mr1 p1 black bg-kcms left fauxfield square center table"><div class="table-cell align-middle"><a href="<?php echo Config::get('URL'); ?>car/new_car" title="<?php echo _("NEWCAR_ADD"); ?>"><?php echo _("NEWCAR_ADD"); ?></a></div></div>
 
 
</div>