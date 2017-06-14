<div class="container"><?php  //var prep moved into header include to get fb graph api values into header ?>
<div id="piccount" class="hide"><?= count($images); ?></div>
<div class="box"><?php $this->renderFeedbackMessages(); ?></div>
<div class="clearfix">
<input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
       <div class="md-col md-col-3 carcol">
              <div class="clearfix relative">
                    
                     
       <?php if ($image_out) {  ?>
              <a id="heroimg-link" href="<?= $initial_img; ?>">   
                     <div class="pic120width bg-white relative"  style="background-image: url(<?= $image_out; ?>)"><?= $image_meta; ?></div>
              </a>
       <?php } ; ?>
                                
                            <div class="bold mt2"><?= $car_name;  //$thisowner ?></div>                        
                            <div class="smallish mt1"><?= $car_make.' '.$car_model.' '.$car_variant; ?></div>
                            <?php include ('joincaramnesis.php'); ?>
                            
                            <div id="shareBtn" class="btn btn-success clearfix"><?= _('SHARE'); ?></div>
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
        
        
       
        
        
   
   
    
    <div class="md-col md-col-9 ">
 
    <?php     if ($this->events) {
        

        foreach ($this->events as $event) {
              if ($event->visibility == 'pub') {
        displayEventTerse($event, $car_id, $owner, $units);
              };
        
                
                
                
                
                }
        }            ?>
        
    
    
    
    </div>
</div>
    
    

    
</div>


<?php include 'fsgallery.php'; ?>



<?php 
        function displayEventTerse($event, $car_id, $owner, $units) {
 $event_types_present = array();

        
     
    $serialized_event_time =  CarModel::parsetimestamp($event->event_time);
     $event_time = $serialized_event_time['seconds'];
     $event_microtime = $serialized_event_time['microseconds'];

     $serialized_entry_time =  CarModel::parsetimestamp($event->event_entered);
     $entry_time =  $serialized_entry_time['seconds'];
     $entry_microtime = $serialized_entry_time['microseconds'];
     
     
    $event_id = urlencode(serialize(array('c' => $car_id, 't' => $event_time, 'm' => $event_microtime)));
                                         //car           //time               /microtime
        
     $entry_data = unserialize($event->event_data);
          
       $images = unserialize($event->images); $image_out=''; $image_meta='';
     if ($images)
       { //we only show the first one and count
                             if (CarModel::get_extension($images[0]) == 'pdf')
                        {
                                $image_meta.= '<div class="red-triangle absolute top-0 right-0"> </div> ';
                                $image_meta.= '<div class="icon-file-pdf white absolute top-0 right-0"> </div> ';
                        }
                                $image_out = Config::get('URL') .'view/image/'.$car_id.'/'.$images[0].'/480';
                        if (count($images) > 1) {
                                $image_meta.= '<div class="white bg-darken-4 absolute bold px1 bottom-0 right-0">+'.(count($images)-1).'</div> ';
                        }
        } 
     

     
     
          $oldversions = '';      $entrytime = '';         

     
     $event_link = Config::get('URL') . 'view/event/' . $event_id;
      ?>
    
<div class="mt1 p1 border mw480 bg-kcultralite event " data-event="<?= $event_id; ?>">
  <div class="clearfix relative">
   <div class="">
        <div class="inline smallish bold"><?= strftime('%x', $event_time); if ($entrytime or $oldversions) { ?> <a href="#" class="jqtooltip" title="<?= $entrytime.$oldversions; ?>"><i class="icon-history"> </i></a> <?php }; ?></div>
        <?php if($event->event_odo) { ?><div class="inline small"><?= $event->event_odo.' '.$units->user_distance; ?></div><?php }; ?>
       <?php if(($entry_data['amount']) && ($entry_data['amount'] > 0)) { ?>
       <div class="inline small"><?= $entry_data['amount'].' '._('CURRENCY_'.$units->user_currency); ?></div>
       <?php }; ?>
   </div>
   <a href="<?= $event_link; ?>" title="<?= _("VIEW"); ?>">
   <div class="relative">
        <?php
        if ($image_out) { ?>
        <div class="relative pic480width"  style="background-image: url(<?= $image_out; ?>)"><?= $image_meta; ?></div>
        <?php }; ?>
        <div class="mt1 smallish"><?= $event->event_content; ?></div>
   </div>
   </a>
  </div>
</div>
        
        <?php
        return $event_types_present;
        } ?>



