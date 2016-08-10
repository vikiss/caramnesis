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