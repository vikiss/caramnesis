<?php if ($this->car) {
include('car_data_prep.php'); 
$this->public_access ? $public_access=true : $public_access=false;
    ?>
    <div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
    <h1><?= $car_name; ?></h1>
<div class="box">
<form method="post" action="<?php echo Config::get('URL');?>car/save_car_access">

   <h3><?php print _('PUBLIC_TAGS'); ?></h3>       
       <?php
       
       if ($public_access) { 
       
       print '<input type="checkbox" name="disable_car_access" value="'.$car_id.'" />'._('MAKE_CAR_PRIVATE').'<br /><hr />';
       
       } else {
        
         print '<input type="checkbox" name="enable_car_access" value="'.$car_id.'" />'._('MAKE_CAR_PUBLIC').'<br />';
        
        }
         ?>
           <div<?php if(!$public_access) echo ' class="hide"'; ?>>
         <?php
       foreach ($tags as $key => $tag) { 
       	   if (!in_array($tag, $no_sho_tags)) {
			   print '<input type="checkbox" name="tag_access[]" value="'.$tag.'"';
			   if (in_array($tag, $car_access)) print ' checked';
		   print ' /> '._($tag).'<br />';}
 				}; 
        
        ?></div>
        
        
       <?php $publiclink=Config::get('URL').'view/car/'.urlencode(Session::get('user_name')).'/'.urlencode($car_name); ?>
       <?php $publiclink2=Config::get('URL').'view/car/'.$car_id; ?>
       
       
       <p><?php  if ($public_access) {print _('PUBLIC_TAGS_INFO_PUBLIC');} else {print _('PUBLIC_TAGS_INFO_PRIVATE');} ?></p>
       <?php if ($public_access) { ?>
       <p><a><?= $publiclink; ?></a></p>
       <p><a href="<?= $publiclink2; ?>" target="_blank"><?= $publiclink2; ?></a></p>
       <?php }; ?>
			  <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
			 <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms right" value='<?php echo _("SAVE"); ?>' autocomplete="off" />

</form> 
			 
			 
</div>
    </div>
    <?php } ?>
			 