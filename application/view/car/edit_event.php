
        <?php if ($this->event) { 
             
          foreach ($this->event as $row) { //this runs only once
          
       //  print '<pre>'; print_r($row); print '</pre>';
           
                       $event_content = $row->event_content;
                       $event_types = unserialize($row->event_type);

                       $event_id = $row->event_time; 
                       $serialized_event_time =  CarModel::parsetimestamp($event_id);
                       $event_time = $serialized_event_time['seconds'];
                       $event_microtime = $serialized_event_time['microseconds'];
                       
                       $event_entered = $row->event_entered;
                       $serialized_entry_time =  CarModel::parsetimestamp($event_entered);
                       $entry_time =  $serialized_entry_time['seconds'];
                       $entry_microtime = $serialized_entry_time['microseconds'];
                        
                       $car_id = $row->car; 
                       $event_data = unserialize($row->event_data);
                       
                       $event_images = unserialize($row->images);
                       if (is_array($event_images)) {
                       $images_list = '';
                       $images_list = implode(',', $event_images);} else {
                       $event_images = false; }  
                       
}                                            
        
         ?>
         
<div class="container">
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
    <h1><?= _("EDIT_EVENT"); ?></h1>

    <div class="box">
       

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
        
            <?php $tags = Config::get('AVAILABLE_TAGS'); $units = $this->units;  include('event_form_edit.php'); //include('event_edit_form.php');
                        ?>
            
            
            <?php /* delete event */?>
        <div id="deleteopener" class="small mb1 mt1 pointer" /><?= _("DELETE_EVENT"); ?></div>
        <div id="deletedialog" class="center" title="<?= _('ARE_YOU_REALLY_REALLY_SURE'); ?>"><p><?= _('ARE_YOU_REALLY_REALLY_SURE'); ?></p>
            <form method="post" action="<?php echo Config::get('URL');?>car/eventDelete">
                <input type="hidden" name="car_id" value = "<?= $car_id; ?>" />
                <input type="hidden" name="event_time" value = "<?= $event_time; ?>" />
                <input type="hidden" name="event_microtime" value = "<?= $event_microtime; ?>" />
                <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
                <input type="submit" class="btn mb1 mr1 px1 black bg-kclite" value="<?= _("DELETE"); ?>" autocomplete="off" />
            </form>
        </div>
            
            
            
        <?php } else { ?>
            <p><?= _("EVENT_DOES_NOT_EXIST"); ?></p>
        <?php } ?>
    </div>
</div>
            