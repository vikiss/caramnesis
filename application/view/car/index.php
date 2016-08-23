<div class="container">

<?php

$reflector = new ReflectionClass('Cassandra');
echo $reflector->getFileName();
echo $reflector->getStartLine();

       $units = $this->units;
       $tags = Config::get('AVAILABLE_TAGS');
       //databits
       /*
       $databits = Config::get('DATA_BITS');
      if ( $databits = DatabitModel::loadStructure($databits, true) ) {
      //print '<pre>'; print_r($databits); print '</pre>';
      foreach ($databits->chapter as $chapter) { ??
        <div>
                <h4>??= $chapter->name; ??</h4>
        </div>
        
        
      ??php }
      } */
      //databits
     
         foreach ($this->car as $row) { //this runs only once
                       $car_name = $row['car_name'];
                       $car_make = $row['car_make'];
                       $car_model = $row['car_model'];
                       $car_variant = $row['car_variant'];
                       $car_vin = $row['car_vin']; 
                       $car_id = (array) $row['id']; 
                       $car_id = $car_id['uuid'];
                       $car_plates = (array) $row['car_plates'];}
                       $car_plates = reset($car_plates);  //get 1st element
                       $owner = (array) $row['owner'];
                       $owner['uuid'] !== Session::get('user_uuid') ? $thisowner=UserModel::getUserNameByUUid($owner['uuid']).': ' : $thisowner='';
                       
                       
                       
      $images = (array)$row['images']; $image_out=''; $image_meta=''; $picstrip_out = ''; $i=1; 
     if ($images)
       { //we only show the first one and count
     $images = $images['values'];
                             if (CarModel::get_extension($images[0]) == 'pdf')
                        {
                                $image_meta.= '<div class="red-triangle absolute top-0 right-0"> </div> ';
                                $image_meta.= '<div class="icon-file-pdf white absolute top-0 right-0"> </div> ';
                        }
                                $image_out = Config::get('URL') .'/car/image/'.$owner['uuid'].'/'.$car_id.'_'.$images[0].'/240';
                                $initial_img  = Config::get('URL') .'/car/image/'.$owner['uuid'].'/'.$car_id.'_'.$images[0];
                        if (count($images) > 1) {
                                $image_meta.= '<div class="white bg-darken-4 absolute bold px1 bottom-0 right-0">+'.(count($images)-1).'</div> ';
                                $first = true;
                                          foreach($images AS $image) {
                                                $imglink = Config::get('URL').'car/imagepg/'.$owner['uuid'].'/'.$car_id.'_'.$image;
                                                 $imgurl = Config::get('URL').'car/image/'.$owner['uuid'].'/'.$car_id.'_'.$image;
                                                    if (CarModel::get_extension($image) == 'pdf')
                                                    { 
                                                    $picstrip_out.='<div class="border rounded mr1 inline-block overflow-hidden relative">';
                                                    $picstrip_out.='<div class="red-triangle absolute top-0 right-0"> </div>';
                                                    $picstrip_out.='<div class="icon-file-pdf white absolute top-0 right-0"> </div>';
                                                    $picstrip_out.='<a href="'.$imglink.'"><img src="'.$imgurl.'/120'.'" class="crop" /></a></div>';
                                                        
                                                    } else {
                                                
                                                 if ($first) {$addclass = 'active'; $initial_img = $imgurl;  $first = false; } else { $addclass = ''; }
                                                    
                                                $picstrip_out.='<div class="imgbutton border rounded mr1 inline-block overflow-hidden '.$addclass.'">';
                                                $picstrip_out.='<a href="'.$imglink.'" data-url="'.$imgurl.'" data-ord="'.$i.'"><img src="'.$imgurl.'/120'.'" class="crop" /></a></div>';
                                                    }
                                                    $i++;
                                                }
                                
                                
                                
                                
                        }
        }
        
        
        
                       
         
       ?>
       <div id="piccount" class="hide"><?= count($images); ?></div>

    <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
    
    <div class="clearfix">
        
        
        
        <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                <div class="mt2 ">
                  <div class="bg-white fauxfield clearfix relative">
                   <div class="border-bottom bg-kcultralite px2">
                        <div class="inline bold"><?= $thisowner.$car_name; ?></div>
                        <div class="inline smallish"><?= $car_make.' '.$car_model.' '.$car_variant.' | '.$car_vin;
                         if  ($car_plates[0]) print ' | '.$car_plates[0];
                         ?></div>
                        <?php if (!$thisowner) { // no edit link for non-owner ?>
                        <div class="right">
                                <a class="context_menu_opener" data-element="editcarmenu" href="#" title="<?= _("EDIT"); ?>"><i class="icon-down-open"> </i></a>
                        </div>
                        <?php }; ?>
                   </div>
                   <div class="relative ">
                     <div id="editcarmenu" class="absolute top-0 right-0 border z3 active bg-white display-hide p2 closeonclick ">
                            <ul class="list-reset">
                                   <li><a href="<?= Config::get('URL') . 'car/edit_car_id/' . $car_id; ?>"><i class="icon-cab"> </i><?= _('CAR_IDENTIFICATION'); ?></a></li>
                                   <li><a href="<?= Config::get('URL') . 'car/edit_attributes/' . $car_id; ?>"><i class="icon-th-list"> </i><?= _('CAR_ATTRIBUTES'); ?></a></li>
                                   <li><a href="<?= Config::get('URL') . 'car/edit_car_access/' . $car_id; ?>"><i class="icon-users"> </i><?= _('CAR_PERMISSIONS'); ?></a></li>
                                   <li><a href="<?= Config::get('URL') . 'car/authorised_users/' . $car_id; ?>"><i class="icon-wrench"> </i><?= _('CAR_AUTHORISED_USERS'); ?></a></li>
                                   <li><a href="<?= Config::get('URL') . 'car/edit_car_pictures/' . $car_id; ?>"><i class="icon-camera"> </i><?= _('CAR_PICTURES'); ?></a></li>
                                   
                            </ul>
                     </div>
                     
                  <a id="heroimg-link" href="<?= $initial_img; ?>">   
                        <div class="left pic120width bg-white border-right relative"  style="<?php
                        if ($image_out) {
                                ?>background-image: url(<?= $image_out; ?>)<?php
                                } else {
                                ?>background-color: rgba(204, 204, 153, 0.37)<?php
                                }; ?>"><?= $image_meta; ?></div></a>
                        <div class="absolute left-0 top-0 right-0 overflow-hidden pic120margin pic120height overflow-hidden small"><?php $car_data = $this->car_data; include ('car_data_bit_table_disp.php');?></div>
                   </div>
                   
                  </div>
                </div>
        
        
        
        
        
        
       
       
   
      <?php include('event_form.php'); ?>
  
        
               
        
        

        
   </div>
    

    
   
<?php /*<div id="event-view"></div>   */ ?> 
    <div id="event-types-present" class="mt2 smallish bold hide"><?= _("EVENT_TYPES"); ?>: <a href="#" class="smallish bold kcms mx1 evfltreset">[<?= _("ALL"); ?>]</a></div>
    <div class="clearfix mxn1 ">
    
    <div class="mt2 sm-col sm-col-6 px1 ">
                  <div class="bg-white fauxfield clearfix relative">
                   <div class="border-bottom bg-kcultralite px2">
                        <div class="inline bold"><?= _("NEW_RECORD"); ?></div>
                        <div class="right">
                                <a href="#" id="new_event_dialog_opener" title="<?= _("EDIT"); ?>"><i class="icon-edit"> </i></a>
                        </div> 
                   </div>
                   <a href="<?= ''; ?>" title="<?= _("VIEW"); ?>">
                   <div class="relative overflow-hidden">
                        <div class="left pic120width bg-white border-right relative" > </div>                
                        <div class="absolute left-0 top-0 right-0 pic120margin small">content</div>
                   </div>
                   </a>
                  </div>
                </div>
    
    <?php     if ($this->events) {
        $event_no = 0;
        $event_types_present = array(); //list of event types present for filtering by type
        
        foreach ($this->events as $event) {
                $these_event_types = displayEventTerse($event, $car_id, $owner, $units, $event_no);
                $event_no++;
                
                foreach ($these_event_types as $this_event_type) {
                        if (!in_array($this_event_type, $event_types_present)) {
                                $event_types_present[_($this_event_type)] = $this_event_type;
                        }
                }
                
                
                }
        }            ?>
        
    
    
    
    
    
    
    
              
    
    
    
    
    
    
    </div>
    
    

    
</div>


       <div id="fs" class="display-none bg-darken-5">
              <div id="fsimg" class="relative" data-ord="1"><img src="/img/empty.gif" title="" />
              <div id="fsimgcenter" class="absolute abscenter"></div>
              </div>
              <div id="fsclose" class="absolute top-0 right-0 bg-darken-4 center py2"><a href="#"><i class="icon-cancel white"> </i></a></div>
              <div id="fsnext" class="absolute right-0 bg-darken-4 center p2 right-arrow display-hide"><a href="#"><i class="icon-right-open white"> </i></a></div>
              <div id="fsprev" class="absolute left-0 bg-darken-4 center p2 left-arrow display-hide"><a href="#"><i class="icon-left-open white"> </i></a></div>
              <div id="fspscont" class="absolute bottom-0 left-0 right-0">
                  <div id="fspsprev" class="absolute left-0 bg-darken-4 center p2 left-arrow display-hide"><a href="#"><i class="icon-left-open white"> </i></a></div>
                  <div id="fspsnext" class="absolute right-0 bg-darken-4 center p2 right-arrow display-hide"><a href="#"><i class="icon-right-open white"> </i></a></div>
                  <div id="fspicstrip" ><?= $picstrip_out; ?></div>
              </div>
       </div>


<?php if ((isset($event_types_present)) && (count($event_types_present) > 1)) { ?>
<script>
        var event_types_present = {<?php
        $first = true;
        foreach ($event_types_present AS $key => $value) {
                if ($first) {$first = false;} else {print ', ';}
                print '"'.$key.'": "'.$value.'"';
                };
        ?>};
</script>
<?php }; ?>

<?php 
        function displayEventTerse($event, $car_id, $owner, $units, $event_no) {
 $event_types_present = array();

        
        //print '<pre>'; print_r($event); print '</pre>';
     $serialized_event_time = (array) $event['event_time'];
     $event_time = $serialized_event_time['seconds'];
     $event_microtime = $serialized_event_time['microseconds'];
        
     $serialized_entry_time = (array) $event['event_entered'];
     $entry_time =  $serialized_entry_time['seconds'];
     $entry_microtime = $serialized_entry_time['microseconds'];
    $event_id = urlencode(serialize(array('c' => $car_id, 't' => $event_time, 'm' => $event_microtime)));
                                         //car           //time               /microtime
        
     $entry_data = unserialize($event['event_data']);
          
     $images = (array)$event['images']; $image_out=''; $image_meta='';
     if ($images)
       { //we only show the first one and count
     $images = $images['values'];
                             if (CarModel::get_extension($images[0]) == 'pdf')
                        {
                                $image_meta.= '<div class="red-triangle absolute top-0 right-0"> </div> ';
                                $image_meta.= '<div class="icon-file-pdf white absolute top-0 right-0"> </div> ';
                        }
                                $image_out = Config::get('URL') .'/car/image/'.$owner['uuid'].'/'.$car_id.'_'.$images[0].'/240';
                        if (count($images) > 1) {
                                $image_meta.= '<div class="white bg-darken-4 absolute bold px1 bottom-0 right-0">+'.(count($images)-1).'</div> ';
                        }
        } 
     

     
     
     $event_types = (array) $event['event_type'];
     $event_types = $event_types['values'];
     $event_types_out = ''; 
      if($event_types) { 
        foreach ($event_types AS $event_type) {
                $event_types_out.= '<a href="#" data-type="'.$event_type.'" class="smallish bold kcms mr1 evfilter ">['._($event_type).']</a>';
                if (!in_array($event_type, $event_types_present)) { $event_types_present[] = $event_type;}; 
         };         };
         $event_types_tags = implode(' ', $event_types);
     
        $oldversions = ''; $oldversion = array();
        if ((key_exists('oldversions', $entry_data)) && ($entry_data['oldversions'])) {
                $oldversions = ' '._("EVENT_REVISIONS").': '; 	   
                $oversions = explode(',', $entry_data['oldversions']);
                foreach ($oversions as $oversion) {
                       $oversion = explode('-', $oversion);
                       if ($oversion[0]) {$oldversion[]=strftime('%x', intval($oversion[0]));}
                }
                $oldversions.=implode(', ', $oldversion);
        }
        
        $entrytime = '';
        if ($entry_time !== $event_time) {
        $entrytime = ' '._("EVENT_CREATED").': '.strftime('%x', $entry_time); 
        }
     
     $event_link = Config::get('URL') . 'car/event/' . $event_id;
      ?>
    
<div class="mt2 sm-col sm-col-6 px1 event <?= $event_types_tags; ?>">
  <div class="bg-white fauxfield clearfix relative">
   <div class="border-bottom bg-kcultralite px2">
        <div class="inline smallish bold"><?= strftime('%x', $event_time); if ($entrytime or $oldversions) { ?> <a href="#" class="jqtooltip" title="<?= $entrytime.$oldversions; ?>"><i class="icon-calendar"> </i></a> <?php }; ?></div>
        <div class="inline"><?= $event_types_out; ?></div>
        <?php if($event['event_odo']) { ?><div class="inline small"><?= $event['event_odo'].' '.$units->user_distance; ?></div><?php }; ?>
       <?php if(($entry_data['amount']) && ($entry_data['amount'] > 0)) { ?><div class="inline small"><?= $entry_data['amount'].' '._('CURRENCY_'.$units->user_currency); ?></div><?php }; ?>
        <div class="right">
                <a href="<?= Config::get('URL') . 'car/edit_event/' . $event_id; ?>" title="<?= _("EDIT"); ?>"><i class="icon-edit"> </i></a>
        </div> 
   </div>
   <a href="<?= $event_link; ?>" title="<?= _("VIEW"); ?>">
   <div class="relative overflow-hidden">
        <div class="left pic120width bg-white border-right relative"  style="<?php
        if ($image_out) {
                ?>background-image: url(<?= $image_out; ?>)<?php
                } else {
                ?>background-color: rgba(204, 204, 153, 0.37)<?php
                }; ?>"><?= $image_meta; ?></div>                
        <div class="absolute left-0 top-0 right-0 pic120margin "><?= $event['event_content']; ?></div>
   </div>
   </a>
  </div>
</div>
        
        <?php
        return $event_types_present;
        } ?>



