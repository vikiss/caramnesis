<?php if ($this->car) {
include('car_data_prep.php'); //if this is not the owner looking we are only showing this section

    ?>
    <div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <h1><?= $car_name.' / '._("EDIT_CAR"); ?></h1>
<div class="box">
	 <?php include ('edit_car_data_bit.php');?>
	</div>
    </div>
    <?php } ?>