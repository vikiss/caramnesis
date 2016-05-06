<?php //index-index-view ?><div class="container">
    <div class="box">
        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
	</div>
	<?php

if ($this->car) { ?>
<?php
foreach ($this->car as $row) {   
      $id = (array) $row['id']; //typecast cassandra object into array
      $car_images = (array) $row['images'];
                         if (isset($car_images['values'])) {
                         $car_images = $car_images['values'];
                         $car_image =  reset($car_images);} else {
                         $car_images = false; $car_image = '';}
                          
                        
      
      $plates_or_vin = '';
      if ($plates_or_vin = (array) $row['car_plates']) {$plates_or_vin=$plates_or_vin['values'][0]; }
      if (is_array($plates_or_vin)) $plates_or_vin=$row['car_vin'];
      ?>
<div class="mb1 mr1 p1 black bg-kclite left fauxfield square center truncate">
<a href="<?= Config::get('URL') . 'car/index/' . $id['uuid']; ?>" title="<?= $row['car_name']; ?> (<?= $row['car_make']; ?> <?= $row['car_model']; ?> <?= $plates_or_vin; ?>)"><?= $row['car_name']; ?></a>
<?php //temporarily so that every owners cars get populated with 99 entries 
$level = CarModel::checkAccessLevel($id['uuid']); if (!$level) {CarModel::newAccessNode($id['uuid'], 99);};
?>
<?php if ($car_images) { ?>
<br /><a href="<?= Config::get('URL') . 'car/index/' . $id['uuid']; ?>" title="<?= $row['car_name']; ?> (<?= $row['car_make']; ?> <?= $row['car_model']; ?> <?= $plates_or_vin; ?>)">
<?php print '<img src="/car/image/'.Session::get('user_uuid').'/'.$id['uuid'].'_'.$car_image.'/120" />'; ?>
</a>
<?php }; ?>
</div>
                  
                 
<?php  } } else echo '<h1>'._("NO_CARS_FOUND").'</h1>' ?>
 
 <div class="mb1 mr1 p1 black bg-kcms left fauxfield square center truncate"><a href="<?php echo Config::get('URL'); ?>car/new_car" title="<?php echo _("NEWCAR_ADD"); ?>"><?php echo _("NEWCAR_ADD"); ?></a></div>
 
 
</div>