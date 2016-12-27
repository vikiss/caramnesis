<div class="container">
                <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
             <div class="clearfix">
                        <h2><?= _('RECEIVED_CAR_TRANSFERS'); ?></h2>
            
            
<?php            if ($this->transferred_cars) { 
            foreach ($this->transferred_cars as $row) {
                        ?>


   <div class="p2 bg-kclite rounded mt2">
    <div class="clearfix">
              <div class="square left">
              <?php
              if ($car_image = CarModel::getCarImage($row['car_id']))
              {
	print '<img class="crop" src="/car/image/'.$row['car_id'].'/'.$car_image.'/120" />';
              
              } ?>
              </div>
              <div class="px2 left">
              <p><strong><?= CarModel::getCarName($row['car_id']); ?></strong> <?= UserModel::getUserNameByUUid($row['old_owner']); ?></p>
              <p class="small"><?= _('TRANSFER_DATE:').' '.strftime("%x",$row['time']); ?></p>
	<div class="mt1">
		<form method="post" enctype="multipart/form-data" action="<?= Config::get('URL') . 'car/execute_transfer/'; ?>">
		<input type="hidden" name="old_owner" value="<?= $row['old_owner']; ?>" />
		<input type="hidden" name="new_owner" value="<?= $row['new_owner']; ?>" />
		<input type="hidden" name="car_id" value="<?= $row['car_id']; ?>" />
		<input type="hidden" name="transfer_id" value="<?= $row['id']; ?>" />
		<input type="submit" name="accept" class="btn mb1 mt1 black bg-kcms" value="<?= _('ACCEPT'); ?>" autocomplete="off">
		<input type="submit" name="decline" class="btn mb1 mt1 black bg-kcms" value="<?= _('DECLINE'); ?>" autocomplete="off">
		</form>
	</div>
              </div>
     </div>
		</div>




<?php }} ?>
     
             </div>
</div>