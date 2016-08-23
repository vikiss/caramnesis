<div class="bg-kclite p2 mt2 fauxfield relative">
<form method="post" enctype="multipart/form-data" action="<?php echo Config::get('URL');?>car/eventEditSave" id="new_event_form">
   <div id="container" class="clearfix">
                <select name="event_type" class="btn btn-primary mb1 mt1 black bg-kcms ">
<?php $event_type =''; 
      if ($event_types) {foreach ($event_types AS $type) {$event_type=$type;};}
 foreach ($tags as $key => $tag) { 
    echo '<option value="'.$tag.'"';
    if ($tag == $event_type) {echo ' selected';}
    echo '>'._($tag).'</option>';    
 }; ?></select>
                <input type="number" min="0" step="1" name="event_odo" class="field mt1 " <?php if($row['event_odo']) { ?>
value="<?= $row['event_odo']; ?>"<?php } else { ?>
placeholder="<?php echo _("EVENT_ODO");?>"<?php }; ?> />
                <label for="event_odo"><?= $units->user_distance; ?></label>
                <input type="number" min="0" step="0.01" name="event_amount" class="field mt1 " <?php if($event_data['amount']) { ?>
value="<?= $event_data['amount']; ?>"<?php } else { ?>
placeholder="<?= _('CURRENCY_'.$units->user_currency); ?>"<?php }; ?> />
                <label for="event_amount"><?= _('CURRENCY_'.$units->user_currency); ?></label>
                <input type="date" name="event_date" class="field mt1 " value="<?= date('Y-m-d', $event_time); ?>" />
                <textarea name="event_content" class="col-12 field mt1 inline" ><?= $event_content; ?></textarea>
                 <div id="dropbox">
                <div class="fileupl left mt1"><span class="fauxinput icon-camera"></span><input type="file" name="fileinput[]" id="fileinput" multiple="multiple" accept="image/*" capture="camera" /></div>
                <div class="small"><?= _("DROP_IMAGES"); ?></div>
                </div>
                 
                 
                 <input type="text" name="reminder_time" class="field mt1 " value="<?php $nextWeek = time() + (7 * 24 * 60 * 60); echo $nextWeek; ?>" />
                 <input type="text" name="reminder_content" class="field mt1 " value="reminder content" />
                   
        
                 
                 
                 
                <input type="submit" id="start-upload" class="btn btn-primary mb1 mt1 black bg-kcms right" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
    <div id="filelist" class="list-reset">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
    <div id="console"></div>
       <div class="col ">
     
                   <?php
                   if ($event_images) {
                    foreach($event_images AS $image) {
                    print '<a href="'.Config::get('URL').'/car/imagepg/'.Session::get('user_uuid').'/'.$car_id.'_'.$image.'" rel="#overlay"><img src="/car/image/'.Session::get('user_uuid').'/'.$car_id.'_'.$image.'/120" /></a> '; 
              
                                                }
                                } ?>
     
    
     </div>
    
    
 <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                <input type="hidden" name="user_images" id="user_images" value = "<?=  $images_list; ?>" />
                <input type="hidden" name="event_time" value = "<?= $event_time; ?>" />
                <input type="hidden" name="event_microtime" value = "<?= $event_microtime; ?>" />
                <input type="hidden" name="event_entered" value = "<?= $event_entered['seconds'].'-'.$event_entered['microseconds']; ?>" />
                <input type="hidden" name="oldversions" value = "<?php if (isset($event_data['oldversions'])) echo $event_data['oldversions']; ?>" />
                
</div>                
</form>
</div>
<?php include('img_uploader.php'); ?>