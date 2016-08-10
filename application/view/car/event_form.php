<?php
$eventtypelist = '';
foreach ($tags as $key => $tag) {
   $eventtypelist.='<div class="left mb1 mr1 px1 black bg-kclite field';
   if ($key == 'default') $eventtypelist.= ' hilite';
   $eventtypelist.=' "><a class="evtype" href="'.$tag.'">'._($tag).'</a></div>';
 }; ?>

<div class="bg-kclite p2 mt2 fauxfield relative" id="new_event_dialog" title="<?php echo _("NEW_RECORD"); ?>">
<form method="post" enctype="multipart/form-data" action="<?php echo Config::get('URL');?>car/create_event" id="new_event_form">
   <div id="container" class="clearfix">
      <div id="event_type_dialog" title="<?= _("EVENT_TYPE"); ?>"><?= $eventtypelist; ?></div>
   <div id="event_type_dialog_opener" class="btn btn-primary mb1 mt1 black bg-kcms "><?= _("EVENT_TYPE"); ?></div>
   <ul class="tags list-reset pb2" id="taglisting"></ul>
                <input type="number" min="0" step="1" name="event_odo" class="field mt1 " placeholder="<?php echo _("EVENT_ODO").', '.$units->user_distance; ?>" />
                <input type="number" min="0" step="0.01" name="event_amount" class="field mt1 " placeholder="<?php echo _("EVENT_AMOUNT").', '._('CURRENCY_'.$units->user_currency); ?>" />   
                <input type="date" name="event_date" class="field mt1 " value="<?php echo date('Y-m-d'); ?>" />
                <textarea name="event_content" class="col-12 field mt1 inline" placeholder="<?php echo _("EVENT_CONTENT"); ?>" ></textarea>
                <div id="dropbox"><?php //capture="camera" nuemiau nuo fileinput ?>
                <div class="fileupl left mt1"><span class="fauxinput icon-camera"></span><input type="file" name="fileinput[]" id="fileinput" multiple="multiple" accept="image/*|application/pdf" /></div>
                <div class="small"><?= _("DROP_IMAGES"); ?></div>
                </div>
                <input type="submit" id="start-upload" class="btn btn-primary mb1 mt1 black bg-kcms right" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
    <div id="filelist" class="list-reset">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
    <div id="console"></div>
                <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                <input type="hidden" name="user_images" id="user_images" value = "" />
                <input type = "hidden" name="event_type" id="event_type" value="" />
</div>                
</form>                                                  
</div>
<?php include('img_uploader.php'); ?>

    