<div class="container">
    <h1><?= _("EDIT_EVENT"); ?></h1>

    <div class="box">
       

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <?php if ($this->event) { 
             
          foreach ($this->event as $row) { //this runs only once
          
       //  print '<pre>'; print_r($row); print '</pre>';
           
                       $event_content = $row['event_content'];
                       $event_types = $row['event_type'];
                       $event_id = (array) $row['event_time']; 
                       $event_time = $event_id['seconds'];
                       $event_microtime = $event_id['microseconds'];
                       $event_entered = (array) $row['event_entered'];
                       $car_id = (array) $row['car']; 
                       $car_id = $car_id['uuid'];
                       $event_data = unserialize($row['event_data']);
                       $event_images = (array) $row['images'];
                       if (isset($event_images['values'])) {
                       $event_images = $event_images['values'];
                       $images_list = '';
                       $images_list = implode(',', $event_images);} else {
                       $event_images = false; }  
                       
}                                            
        
         ?> 
        
            <?php $tags = Config::get('AVAILABLE_TAGS'); $units = $this->units; include('event_edit_form.php');
                        ?>
            
            
            <?php /* delete event */?>
            <form method="post" action="<?php echo Config::get('URL');?>car/eventDelete">
                <input type="hidden" name="car_id" value = "<?= $car_id; ?>" />
                <input type="hidden" name="event_time" value = "<?= $event_time; ?>" />
                <input type="hidden" name="event_microtime" value = "<?= $event_microtime; ?>" />
                <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms right" value="<?php echo _("DELETE"); ?>" autocomplete="off" />
            </form>
            
            
            
        <?php } else { ?>
            <p>This event does not exist.</p>
        <?php } ?>
    </div>
</div>
            