<div class="container">
    <h1>Edit event</h1>

    <div class="box">
       

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <?php if ($this->event) { 
             
          foreach ($this->event as $row) { //this runs only once
           
           /*
           
           Array
(
    [car] => Cassandra\Uuid Object
        (
            [uuid] => 0ff01d79-9af4-401b-99f0-52a46bdf247e
            [version] => 4
        )

    [event_time] => Cassandra\Timestamp Object
        (
            [seconds] => 1448738649
            [microseconds] => 547000
        )

    [event_author] => Cassandra\Uuid Object
        (
            [uuid] => c1d79520-8dfe-11e5-8145-001cc07ade33
            [version] => 1
        )

    [event_content] => Blaiviai apsvarsčiau
    [event_type] => Analitiniai darbai
    [id] => Cassandra\Uuid Object
        (
            [uuid] => 5489c439-17d3-4e68-906a-e26a24dcf600
            [version] => 4
        )

)
           
           */
                       $event_content = $row['event_content'];
                       $event_type = $row['event_type'];
                       $event_id = (array) $row['event_time']; 
                       $event_time = $event_id['seconds'];
                       $event_microtime = $event_id['microseconds'];
                       $car_id = (array) $row['car']; 
                       $car_id = $car_id['uuid'];
}                                            
        
         ?>
        
                <form method="post" action="<?php echo Config::get('URL');?>car/eventEditSave">
                <label>Event content: </label><input type="text" name="event_content" value="<?php echo htmlentities($event_content); ?>" />
                <label>Event type: </label><input type="text" name="event_type" value="<?php echo htmlentities($event_type); ?>" />
                <input type="hidden" name="car_id" value = "<?= $car_id; ?>" />
                <input type="hidden" name="event_time" value = "<?= $event_time; ?>" />
                <input type="hidden" name="event_microtime" value = "<?= $event_microtime; ?>" />
                <input type="submit" value='Save' autocomplete="off" />
            </form>
            
            
            <?php /* delete event */?>
            <form method="post" action="<?php echo Config::get('URL');?>car/eventDelete">
                <input type="hidden" name="car_id" value = "<?= $car_id; ?>" />
                <input type="hidden" name="event_time" value = "<?= $event_time; ?>" />
                <input type="hidden" name="event_microtime" value = "<?= $event_microtime; ?>" />
                <input type="submit" value='Delete' autocomplete="off" />
            </form>
            
            
            
        <?php } else { ?>
            <p>This event does not exist.</p>
        <?php } ?>
    </div>
</div>
            