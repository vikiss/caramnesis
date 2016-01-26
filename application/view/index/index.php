<?php //index-index-view ?><div class="container">
    <div class="box">
        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
	</div>
	<?php
//echo Text::get("FEEDBACK_LANGUAGE_TEST"); 
if ($this->car) { ?>
<h1><?php echo Text::get("HEADER_MY_CARS"); ?></h1>
<?php
foreach ($this->car as $row) {   
      $id = (array) $row['id']; //typecas cass uuid object into array otherwise cant get to values
      ?>
<div class="usercar"><a href="<?= Config::get('URL') . 'car/index/' . $id['uuid']; ?>"><?= $row['car_name']; ?> (<?= $row['car_make']; ?> <?= $row['car_model']; ?> <?= $row['car_vin']; ?>)</a></div>
                  
                 
<?php  } } else echo '<h1>No cars found</h1>' ?>
 
 <p><a href="<?php echo Config::get('URL'); ?>car/new_car"><?php echo Text::get("ADD_NEW_CAR"); ?></a></p>
 
 
</div>