<div class="container">

<?php

$this->public_access ? $public_access=true : $public_access=false;

//if (SmsModel::SendSms(37061436005, 'Parašiau elektrinį laišką. Ir siuntėjo pavadinimas dabar toks padabnesnis, skaitau.')) print 'sms sent ok';

       $units = $this->units;
       $tags = Config::get('AVAILABLE_TAGS');
     
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
                       $outstanding = unserialize((string)$row['car_outstanding']);
                       if (!is_array($outstanding)) { $outstanding = false;  }
                       $odometer = '';
                       $odo = (string)$row['car_odo'];
                       for ($k=0; $k<strlen($odo); $k++) {
                            $odometer.='<span class="bg-black white odo">'.$odo[$k].'</span>';
                       }

                      
      $images = (array)$row['images']; $image_out=''; $image_meta=''; $picstrip_out = ''; $i=1; $initial_img = '#';
     if ($images)
       { //we only show the first one and count
     $images = $images['values'];
                             if (CarModel::get_extension($images[0]) == 'pdf')
                        {
                                $image_meta.= '<div class="red-triangle absolute top-0 right-0"> </div> ';
                                $image_meta.= '<div class="icon-file-pdf white absolute top-0 right-0"> </div> ';
                        }
                                $image_out = Config::get('URL') .'car/image/'.$car_id.'/'.$images[0].'/240';
                                $initial_img  = Config::get('URL') .'car/image/'.$car_id.'/'.$images[0];
                                $image_meta.= '<div class="white bg-darken-4 absolute px1 top-0 right-0 nestedlink pointer jqtooltip" data-href="'.Config::get('URL').'car/edit_car_pictures/'.$car_id.'" title = "'._('CAR_PICTURES').'"><i class="icon-cog"> </i></div> ';
                        if (count($images) > 1) {
                                $image_meta.= '<div class="white bg-darken-4 absolute bold px1 bottom-0 right-0">+'.(count($images)-1).'</div> ';
                                $first = true;
                                          foreach($images AS $image) {
                                                $imglink = Config::get('URL').'car/imagepg/'.$car_id.'/'.$image;
                                                 $imgurl = Config::get('URL').'car/image/'.$car_id.'/'.$image;
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
        
        
       //reminders for this car
       $reminders = '';
       
        if($this->reminders) {
              $reminders.='<ul class="list-reset py1 border-bottom">';
              foreach ($this->reminders AS $reminder)
              {
              $reminderdate = (array) $reminder['time'];
              $reminderdate = $reminderdate['seconds'];
              $reminders.='<li><b>'.strftime('%x', $reminderdate).'</b> '.$reminder['content'].'</li>';
              }
              $reminders.='</ul>';
        }
         
       ?>
<div id="piccount" class="hide"><?= count($images); ?></div>
<div class="box"><?php $this->renderFeedbackMessages(); ?></div>
<div class="clearfix">
<input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
       <div class="md-col md-col-3 carcol">
              <div class="clearfix relative">
                     <div class="absolute top-0 right-0">
                            <div id="carindex_expiry_list" class="smallish right-align"></div>
                            <div class="mono smallish mt1 pointer" id="odo_dlg_opnr"><?= $odometer; ?></div>
                     </div>
                     
                     
       <?php if ($image_out) {  ?>
              <a id="heroimg-link" href="<?= $initial_img; ?>">   
                     <div class="pic120width bg-white relative"  style="background-image: url(<?= $image_out; ?>)"><?= $image_meta; ?></div>
              </a>
       <?php } else { ?>
                     <div class="pic120width bg-white relative center bg-kclite " >
                            &nbsp;<br />&nbsp;<br/><a class="py4" href="<?= Config::get('URL') . 'car/edit_car_pictures/' . $car_id; ?>" title="<?= _('CAR_PICTURES'); ?>"><i class="icon-camera"> </i></a>
                     </div>
       <?php }; ?>
                                
                            <div class="bold mt2"><?= $thisowner.$car_name; ?></div>                        
                            <div class="smallish mt1"><?= $car_make.' '.$car_model.' '.$car_variant; ?></div>
                            <div class="smallish mt1"><?= $car_vin; ?></div>
                           <?php if  ($car_plates[0]) print '<div class="smallish mt1">'.$car_plates[0].'</div>'; ?>
                            
                            
                  <?php 
                  if (CarModel::checkAccessLevel($car_id ,Session::get('user_uuid')) >= 98) { ?>
                     
                            <div class="mt2">
                                   <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/edit_car_id/' . $car_id; ?>"><i class="icon-cab"> </i><?= _('CAR_IDENTIFICATION'); ?></a></div>
                                   <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/edit_attributes_xml/' . $car_id; ?>"><i class="icon-th-list"> </i><?= _('CAR_ATTRIBUTES'); ?></a></div>
                                   <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/edit_car_access/' . $car_id; ?>" <?php if ($public_access) echo 'class="green"'; ?>><i class="icon-users"> </i><?= _('CAR_PERMISSIONS'); ?></a></div>
                                   <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/authorised_users/' . $car_id; ?>"><i class="icon-wrench"> </i><?= _('CAR_AUTHORISED_USERS'); ?></a></div>
                                   <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/car_reminders/' . $car_id; ?>"><i class="icon-bell-alt"> </i><?= _('REMINDERS'); ?></a></div>
                                   <div class="small"><?= $reminders; ?></div>
                            </div>
                            <div class="mt2">
                             <p class= "bold m0"><?= _('TODO_LIST_HEADER'); ?></p>
                             <ul class="smallish list-reset">
                             <?php if ($outstanding) {
                                   foreach ($outstanding as $key => $value) {
                            $tstamp = explode(':', $key);
                            $link_event = urlencode(serialize(array('c' => $car_id, 't' => $tstamp[0], 'm' => $tstamp[1])));
                            $link_event = Config::get('URL') . 'car/event/' . $link_event;
                            print '<li><a href='.$link_event.'>'.CarModel::trunc($value, 5).'</a></li>';
                                   } } ?>
                              <li><a href="#" class="new_event_todo_opener"><?= _('ADD_NEW_TODO_LIST_ITEM'); ?></a></li>            
                             </ul>
                            </div>
                            <div class="mt2 hide" id="carindex_my_car_list"></div>
                   <?php }; ?>
              </div>
       </div>
        
        
<div class="bg-kclite p2 mt2 fauxfield relative" id="odo_dlg" title="<?php echo _("ENTER_CURRENT_ODO"); ?>">
   <form method="post" enctype="multipart/form-data" id="odo_warning_form" action="<?php echo Config::get('URL');?>car/save_odo">
              <div class="lblgrp"><label for="event_odo"><?= _("EVENT_ODO").', '.$units->user_distance; ?></label>
                        <input type="number" min="0" step="1" name="this_event_odo" id="this_event_odo" class="block field mt1 col-12" value="<?= $odo; ?>" />
               </div>
             <input type="hidden" name="this_car_id" id="this_car_id" value = "<?= $car_id; ?>" />
             <input type="hidden" name="this_car_odo" id="this_car_odo" value = "<?= $odo; ?>" />
             <div class = "center">
              <p class="mt2 mb2 hide warning" id="odo_warning_box">
              <input type="checkbox" class="cbrad px2" name="odo_warning_checkbox" id="odo_warning_checkbox" value="true" />
              <?= _("ODOMETER_ROLL_BACK_WARNING"); ?></p>
               <div class="btn mb1 mt1 black bg-kcms " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
               <input type="submit" class="btn mb1 mt1 black bg-kcms" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
            </div>
    </form>                                                  
</div> 
        
        
        
       
       
   
      <?php include('event_form.php'); ?>
  
        
   
   
    
    <div class="md-col md-col-9 ">
       <div id="event-types-present" class="smallish bold hide"><?= _("EVENT_TYPES"); ?>: <a href="#" class="smallish bold kcms mx1 evfltreset">[<?= _("ALL"); ?>]</a></div>    
    <div class="mt1 p1 border mw480 new_event_dialog_opener pointer ">
                  <a href="#" id="new_event_dialog_opener" title="<?= _("NEW_RECORD"); ?>"><i class="icon-edit"> </i> <?= _("NEW_RECORD"); ?></a>
                  <p class="smallish mt1"><?= _("NEW_RECORD_BLURB"); ?></p>
                </div>
    
    <?php     if ($this->events) {
        $event_no = 0;
        $event_types_present = array(); //list of event types present for filtering by type
        
        foreach ($this->events as $event) {
                $these_event_types = displayEventTerse($event, $car_id, $owner, $units, $event_no, $public_access);
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
    
    

    
</div>


<?php include 'fsgallery.php'; ?>


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
        function displayEventTerse($event, $car_id, $owner, $units, $event_no, $public_access) {

 $event_types_present = array();

        
        //print '<pre>'; print_r($event); print '</pre>';
     $serialized_event_time = (array) $event['event_time'];
     $event_time = $serialized_event_time['seconds'];
     $event_microtime = $serialized_event_time['microseconds'];
        
     $serialized_entry_time = (array) $event['event_entered'];
     $entry_time =  $serialized_entry_time['seconds'];
     $entry_microtime = $serialized_entry_time['microseconds'];
     
     
     if ($public_access)
     {
       if ($event['visibility'] == 'pub') {
         $eventclass = 'public-event'; $vispubclass = ''; $visprivclass = 'hide';
                } else {
         $eventclass = ''; $vispubclass = 'hide'; $visprivclass = '';
                }
     } else {
//           $eventclass = ''; $vispubclass = ''; $visprivclass = '';
       };
     
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
                                $image_out = Config::get('URL') .'/car/image/'.$car_id.'/'.$images[0].'/480';
                        if (count($images) > 1) {
                                $image_meta.= '<div class="white bg-darken-4 absolute bold px1 bottom-0 right-0">+'.(count($images)-1).'</div> ';
                        }
        } 
     

     
     
     $event_types = (array) $event['event_type'];
     @$event_types = $event_types['values'];
     $event_types_out = ''; $event_types_tags = '';
      if(is_array($event_types)) { 
        foreach ($event_types AS $event_type) {
                $event_types_out.= '<a href="#" data-type="'.$event_type.'" class="smallish bold kcms mr1 evfilter ">['._($event_type).']</a>';
                if (!in_array($event_type, $event_types_present)) { $event_types_present[] = $event_type;}; 
         };
         $event_types_tags = implode(' ', $event_types);
         };

        $oldversions = '';      $entrytime = '';         
     /* //oldversions are being recorded, they just need some presentation work
         $oldversion = array();
        if ((is_array($entry_data)) && (key_exists('oldversions', $entry_data)) && ($entry_data['oldversions']))
        {
                $oldversions = ' '._("EVENT_REVISIONS").': '; 	   
                $oversions = explode(',', $entry_data['oldversions']);
                foreach ($oversions as $oversion) {
                       $oversion = explode('-', $oversion);
                       if ($oversion[0]) {$oldversion[]=strftime('%x', intval($oversion[0]));}
                }
                $oldversions.=implode(', ', $oldversion);
        }
        
        
        if ($entry_time !== $event_time) {
        $entrytime = ' '._("EVENT_CREATED").': '.strftime('%x', $entry_time); 
        }
        */
     
     $event_link = Config::get('URL') . 'car/event/' . $event_id;
      ?>
    
<div class="mt1 p1 border mw480 bg-kcultralite event <?= $eventclass; ?> <?= $event_types_tags; ?>" data-event="<?= $event_id; ?>">
  <div class="clearfix relative">
   <div class="">
        <div class="inline smallish bold"><?= strftime('%x', $event_time); if ($entrytime or $oldversions) { ?> <a href="#" class="jqtooltip" title="<?= $entrytime.$oldversions; ?>"><i class="icon-history"> </i></a> <?php }; ?></div>
        <div class="inline"><?= $event_types_out; ?></div>
        <?php if($event['event_odo']) { ?><div class="inline small"><?= $event['event_odo'].' '.$units->user_distance; ?></div><?php }; ?>
       <?php if(($entry_data['amount']) && ($entry_data['amount'] > 0)) { ?><div class="inline small"><?= $entry_data['amount'].' '._('CURRENCY_'.$units->user_currency); ?></div><?php }; ?>
        <div class="right">
                <a href="<?= Config::get('URL') . 'car/edit_event/' . $event_id; ?>" title="<?= _("EDIT"); ?>"><i class="icon-pencil"> </i></a>
        </div>
        <?php if ($public_access) : ?> 
        <div class="right visibility_setting_private <?= $visprivclass; ?>" data-event="<?= $event_id; ?>">
       <a href="#" title="<?= _("MAKE_EVENT_PUBLIC"); ?>"><i class="icon-lock"> </i></a>
      </div>
        <div class="right visibility_setting_public <?= $vispubclass; ?>" data-event="<?= $event_id; ?>">
       <a href="#" title="<?=_("MAKE_EVENT_PRIVATE"); ?>"><i class="icon-lock-open-alt"> </i></a>
        </div>
        <?php endif; ?>
   </div>
   <a href="<?= $event_link; ?>" title="<?= _("VIEW"); ?>">
   <div class="relative">
        <?php
        if ($image_out) { ?>
        <div class="relative pic480width"  style="background-image: url(<?= $image_out; ?>)"><?= $image_meta; ?></div>
        <?php }; ?>
        <div class="mt1 smallish"><?= $event['event_content']; ?></div>
   </div>
   </a>
  </div>
</div>
        
        <?php
        return $event_types_present;
        } ?>



