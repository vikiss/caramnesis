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

<div class="mt2 mb2">

<input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
    <div class="response right"></div>
    <input type="checkbox" id="set_car_public" name="set_car_public" value="yes" <?php
        if ($public_access) echo ' checked'; ?> /><?= _('CAR_PUBLIC'); ?><br />
    <div id="car_public_settings" <?php if (!$public_access) {echo ' class="display-hide"'; } ?>>
    <input type="checkbox" class="car-meta" id="meta_allow_public_vin" name="meta_allow_public_vin" data-key="allow_public_vin" value="yes" <?php
            if ($this->car_meta['allow_public_vin'] == 'yes') echo ' checked'; ?> /><?= _('ALLOW_VIN'); ?><br />
    <input type="checkbox" class="car-meta" id="meta_allow_public_plates" name="meta_allow_public_plates" data-key="allow_public_plates" value="yes" <?php
            if ($this->car_meta['allow_public_plates'] == 'yes') echo ' checked'; ?>/><?= _('ALLOW_PLATES'); ?><br />
    </div>
</div>
       <?php   $publiclink=Config::get('URL').'view/car/'.$car_id; ?>
       <div class="mt2 mb2 md-col-6 p1 bg-kcultralite border rounded">
         <div class="car_public_msg_priv smallish<?php if ($public_access) {echo ' display-hide'; } ?>"><?= _('PUBLIC_TAGS_INFO_PRIVATE'); ?></div>
         <div class="car_public_msg_pub smallish<?php if (!$public_access) {echo ' display-hide'; } ?>"><?= _('PUBLIC_TAGS_INFO_PUBLIC'); ?></div>
       </div>


<div class="car_public_msg_pub <?php if (!$public_access) {echo ' display-hide'; } ?>">
       <p><a href="<?= $publiclink; ?>" target="_blank"><?= $publiclink; ?></a></p>
		 <div class="mt2">
			<div id="shareBtn" class="btn btn-success clearfix border"><i class="icon-facebook-official blue"> </i><?= _('SHARE'); ?></div>
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
</div>

</div>
    </div>
    <?php } ?>
