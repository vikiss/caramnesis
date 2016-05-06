<div class="bg-kclite p2 mt2 fauxfield relative">
<form method="post" enctype="multipart/form-data" action="<?php echo Config::get('URL');?>car/create_event" id="new_event_form">
   <div id="container" class="clearfix">
                <select name="event_type" class="btn btn-primary mb1 mt1 black bg-kcms ">
<?php foreach ($tags as $key => $tag) { 
    echo '<option value="'.$tag.'"';
    if ($key == 'default') {echo ' selected';}
    echo '>'._($tag).'</option>';
 }; ?></select>
                <input type="number" min="0" step="1" name="event_odo" class="field mt1 " placeholder="<?php echo _("EVENT_ODO").', '.$units['distance']; ?>" />
                <input type="number" min="0" step="0.01" name="event_amount" class="field mt1 " placeholder="<?php echo _("EVENT_AMOUNT").', '.$units['currency']; ?>" />   
                <input type="date" name="event_date" class="field mt1 " value="<?php echo date('Y-m-d'); ?>" />
                <textarea name="event_content" class="col-12 field mt1 inline" placeholder="<?php echo _("EVENT_CONTENT"); ?>" ></textarea>
                <div id="dropbox">
                <div class="fileupl left mt1"><span class="fauxinput icon-camera"></span><input type="file" name="fileinput[]" id="fileinput" multiple="multiple" accept="image/*|application/pdf" capture="camera" /></div>
                <div class="small"><?= _("DROP_IMAGES"); ?></div>
                </div>
                <input type="submit" id="start-upload" class="btn btn-primary mb1 mt1 black bg-kcms right" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
    <div id="filelist" class="list-reset">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
    <div id="console"></div>
 <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                <input type="hidden" name="user_images" id="user_images" value = "" />
</div>                
</form>                                                  
</div>
<?php include('img_uploader.php'); ?>

    