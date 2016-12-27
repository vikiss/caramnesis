<?php if ($this->car) {
include('car_data_prep.php'); 
$this->public_access ? $public_access=true : $public_access=false;
    ?>
    <div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
    <h1><?= $car_name; ?></h1>
<div class="box">
   <h3><?php print _('PUBLIC_TAGS'); ?></h3>
	<form method="post" action="<?php echo Config::get('URL');?>car/save_car_access">
	<div class="mt2">
       <?php
       
       if ($public_access) { 
       
       print '<input type="checkbox" name="disable_car_access" value="'.$car_id.'" />'._('MAKE_CAR_PRIVATE');
       
       } else {
        
         print '<input type="checkbox" name="enable_car_access" value="'.$car_id.'" />'._('MAKE_CAR_PUBLIC');
        
        }
         ?>
</div>
			<input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
			<input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value='<?php echo _("SAVE"); ?>' autocomplete="off" />

</form> 

       <?php $publiclink=Config::get('URL').'view/car/'.$car_id; ?>
       
       
       <p class="smallish"><?php  if ($public_access) {print _('PUBLIC_TAGS_INFO_PUBLIC');} else {print _('PUBLIC_TAGS_INFO_PRIVATE');} ?></p>
       <?php if ($public_access) { ?>
       <p><a href="<?= $publiclink; ?>" target="_blank"><?= $publiclink; ?></a></p>
		 <div class="mt2">
			<div id="shareBtn" class="btn btn-success clearfix"><?= _('SHARE'); ?></div>
<script>
document.getElementById('shareBtn').onclick = function() {
  FB.ui({
    method: 'share',
    display: 'popup',
    href: '<?= Config::get('URL').$filename.'/'.$car_id; ?>',
  }, function(response){});
};
</script>
		 </div>
       <?php }; ?>
			 
</div>
    </div>
    <?php } ?>
			 