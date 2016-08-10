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
      $id = (array) $row['id']; //typecast cassandra object into array
      $car_images = (array) $row['images'];
                         if (isset($car_images['values'])) {
                         $car_images = $car_images['values'];
                         $car_image =  reset($car_images);} else {
                         $car_images = false; $car_image = '';}
      $owner = (array) $row['owner'];                     
                        
      
      $plates_or_vin = '';
      if ($plates_or_vin = (array) $row['car_plates']) {$plates_or_vin=$plates_or_vin['values'][0]; }
      if (is_array($plates_or_vin)) $plates_or_vin=$row['car_vin'];
      ?>
<div class="mb1 mr1 p1 black bg-kclite left fauxfield square center truncate">
<a href="<?= Config::get('URL') . 'car/index/' . $id['uuid']; ?>" title="<?= UserModel::getUserNameByUUid($owner['uuid']); ?>: <?= $row['car_name']; ?> (<?= $row['car_make']; ?> <?= $row['car_model']; ?> <?= $plates_or_vin; ?>)"><?= $row['car_name']; ?> (<?= UserModel::getUserNameByUUid($owner['uuid']); ?>)</a>
<?php if ($car_images) { ?>
<div><a href="<?= Config::get('URL') . 'car/index/' . $id['uuid']; ?>" title="<?= $row['car_name']; ?> (<?= $row['car_make']; ?> <?= $row['car_model']; ?> <?= $plates_or_vin; ?>)">
<?php print '<img class="crop" src="/car/image/'.$owner['uuid'].'/'.$id['uuid'].'_'.$car_image.'/120" />'; ?>
</a></div>
<?php }; ?>
</div>
                  
                 
<?php  } } } ?>      
      
      
    </div>
    
    

     
    <div class="sm-col md-col-12 lg-col-12">
    <div class="p4 bg-kclite m1 rounded">
    <h2><?php echo _("PROXY_CAR_ADD"); ?></h2>
    <div class="clearfix">
                <input type="text" name="car_owner_name" id="car_owner_name" placeholder="<?= _("CAR_OWNER"); ?>" class="col-12 field mt1 "  />
                <input type="submit" value='<?= _("FIND_CARS"); ?>' id="find_cars_submit" class="btn btn-primary mb1 mt1 black bg-kcms block right" autocomplete="off" />
    </div>
<div class="clearfix"><div id="cars_by_owner" class="my2"></div></div>
            </div></div>


  </div>
</div>                            