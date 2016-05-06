<div class="bg-kclite p2 mt2 fauxfield relative">
<form method="post" enctype="multipart/form-data" action="<?php echo Config::get('URL');?>car/save_car" id="save_car_form">
<div id="container" class="clearfix">

                <input type="text" name="car_name" class="col-6 field mt1 " value="<?= $car_name; ?>" required />
                <input type="text" name="car_vin" class="col-5 field mt1 " value="<?= $car_vin; ?>"  />
                <input type="text" name="car_make" readonly class="col-3 field mt1 " value="<?= $car_make; ?>" />
                <input type="text" name="car_model" class="col-5 field mt1 " value="<?= $car_model; ?>" />
                <input type="text" name="car_plates" class="col-3 field mt1 " value="<?= $car_plates[0]; ?>" />
                <div id="dropbox">
                <div class="fileupl left mt1"><span class="fauxinput icon-camera"></span><input type="file" name="fileinput[]" id="fileinput" multiple="multiple" accept="image/*" capture="camera" /></div>
                <div class="small"><?= _("DROP_IMAGES"); ?></div>
                </div>
                <input type="submit" id="start-upload" class="btn btn-primary mb1 mt1 black bg-kcms right" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
                <div id="filelist" class="list-reset">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
                  <div id="console"></div>
                  <div >
                  <?php
                   if ($car_images) {
                    foreach($car_images AS $image) {   
                    print '<a href="'.Config::get('URL').'/car/imagepg/'.Session::get('user_uuid').'/'.$car_id.'_'.$image.'" rel="#overlay"><img src="/car/image/'.Session::get('user_uuid').'/'.$car_id.'_'.$image.'/120" /></a> '; 
              
                                                }
                                } ?>
     
    
     </div>
         <div id="access_tags">
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
       
       
       <p><?php print _('PUBLIC_TAGS_INFO'); ?></p>
       <?php if ($public_access) { ?>
       <p><a><?= $publiclink; ?></a></p>
       <p><a href="<?= $publiclink2; ?>" target="_blank"><?= $publiclink2; ?></a></p>
       <?php }; ?>
       </div>         
                
    
                
                
                <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                <input type="hidden" name="car_data" id="car_data" value = "<?= $car_data; ?>" />
                <input type="hidden" name="user_images" id="user_images" value = "<?=  $images_list; ?>" />
</div>                
</form>
</div>
<?php include('img_uploader.php'); ?>