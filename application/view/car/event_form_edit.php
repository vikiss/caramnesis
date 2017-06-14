<?php

$owner = $this->owner;
$eventtypelist = '<select name="event_type_select" id="event_type_select" class="btn black bg-kcms w90">';
$eventtypelist.= '<option value="" disabled selected>'._('ADD_EVENT_TYPES').'</option>';
foreach ($tags as $key => $tag) {
   $eventtypelist.='<option value="'.$tag.'">'._($tag).'</option>';
 };
 $eventtypelist.= '</select>';
 $taglist=''; $event_type_field = '';
 
 
 $existingevents = ''; 
 if ($event_types) {
   $event_type_field = array(); $taglist = array();
   foreach ($event_types AS $type) {
      $existingevents.='<li><a href="'.$type.'"><i class="icon-cancel white brdrw"> </i></a> <span>'._($type).'</span></li>';
      $taglist[] = _($type);
      $event_type_field[] = $type;
      };
      count($event_type_field) > 0 ? $event_type_field = implode(',', $event_type_field) : $event_type_field = '';
      count($taglist) > 0 ? $taglist = implode(PHP_EOL, $taglist) : $taglist = '';
 }
  ?>

   <form method="post" enctype="multipart/form-data" action="<?php echo Config::get('URL');?>car/eventEditSave" id="edit_event_form">
      <div id="container" class="lg-flex clearfix">
      
            <div class="flex-auto mr2 fauxfield mt1 bg-white">
               <div class="m1 small clearfix">
               
               <div class="col col-3"><?= $eventtypelist; ?></div>
               <ul class="tags list-reset m0 col col-9" id="taglisting"><?= $existingevents; ?></ul>
               
               </div>
               <textarea name="event_content" class="col-12 h96 no-border" placeholder="<?php echo _("EVENT_CONTENT"); ?>" ><?= $event_content; ?></textarea>
               
            </div>
            
            <div class="">
               <div class="lblgrp"><label for="event_odo"><?= _("EVENT_ODO").', '.$units->user_distance; ?></label>
                        <input type="number" min="0" step="1" name="event_odo" id="event_odo" class="block field mt1 col-12" value="<?php if ($row->event_odo) {echo $row->event_odo;}; ?>"  />
               </div>
               <div class="lblgrp"><label for="event_amount"><?= _("EVENT_AMOUNT").', '._('CURRENCY_'.$units->user_currency); ?></label>
                        <input type="number" min="0" step="0.01" name="event_amount"  id="event_amount" class="block field mt1 col-12" value="<?php if ($event_data['amount'] > 0) {echo $event_data['amount']; } ?>" />   
               </div>
               <div class="lblgrp"><label for="event_date"><?= _("EVENT_DATE"); ?></label>
                        <input type="text" name="event_date_proxy" id="event_date_proxy" class="block field mt1 col-12" value="<?= strftime("%x", $event_time); ?>" />
               </div>
            </div>
            
            <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
            <input type="hidden" name="user_images" id="user_images" value = "<?php if ($event_images) {print $images_list;} ?>" />
            <input type="hidden" name="event_type" id="event_type" value="<?= $event_type_field; ?>" />
            <input type="hidden" name="event_date" id="event_date" value="<?= $event_time; ?>000" />
            <input type="hidden" id="remindertoggle" name="remindertoggle" value="off" />
            <input type="hidden" id="timestampdate" name="timestampdate" value="<?= strtotime('+1 year'); ?>000" />
            <input type="hidden" id="reminder_content" name="reminder_content" value="<?= $taglist; ?>" />
            <input type="hidden" id="event_time" name="event_time" value = "<?= $event_time; ?>" />
            <input type="hidden" id="event_microtime" name="event_microtime" value = "<?= $event_microtime; ?>" />
            <input type="hidden" name="event_entered" value = "<?= $entry_time.'-'.$entry_microtime; ?>" />
            <input type="hidden" name="oldversions" value = "<?php if (isset($event_data['oldversions'])) echo $event_data['oldversions']; ?>" />
            <input type="hidden" name="owner" id="owner" value = "<?= $owner; ?>" />
            <div id="response" class="hide"></div>
            
      </div>
      

      
      <div id="sortimages" class="clearfix mt1">
                          <?php
                           if ($event_images) { $i = 1;
                            foreach($event_images AS $image) {
        print '<div class="portlet mb1 mr1 p1 black bg-kclite left fauxfield square truncate " data-number="'.$i.'" id="'.$image.'">
                <div class="portlet-header bg-kcms move-cursor">
                <div class="right meta z4"><a class="context_menu_opener" data-element="editpic'.$i.'" href="#" title="'._("EDIT").'"><i class="icon-wrench white"> </i></a></div>
                <a href="#" class="jqtooltip" title="'._("MOVE_PICTURE_TO_SORT").'"><i class="icon-move"> </i></a>
                </div>
                <div class="portlet-content relative">';
        print '<div id="editpic'.$i.'" class="absolute top-0 right-0 border z3 active bg-white display-hide p2 closeonclick ">
                            <ul class="list-reset">
                                   <li><a href="#" class="imgdel"><i class="icon-trash"> </i></a></li>
                                   <li><a href="#" class="imgrotate imgcw"><i class="icon-cw"> </i></a></li>
                                   <li><a href="#" class="imgrotate imgccw"><i class="icon-ccw"> </i></a></li>
                            </ul>
                     </div>';
print '<img src="/car/image/'.$car_id.'/'.$image.'/120" /> ';
        print '</div></div>';
        $i++;
                                                           }
                                             } ?>
      </div>
      
      
      
      
      
      <div class="clearfix">
               <div class="left">
                  <div id="dropbox" class="left">
                     <div class="fileupl btn btn-primary mb1 mt1 black bg-kcms z-1 jqtooltip" title="<?= _('UPLOAD_IMAGES_OR_TAKE_A_PICTURE'); ?>" >
                        <i class="icon-camera"> </i><input type="file" name="fileinput[]" id="fileinput" multiple="multiple" accept="image/*|application/pdf" />
                     </div>
                     <div id="console"></div>
                  </div>
                  
               <div id="justanopener" class="left btn btn-primary mb1 mt1 ml1 black bg-kcms jqtooltip z-4" title="<?= _("SET_REMINDER"); ?>" /><i class="icon-bell-alt"> </i></div>
               <div id="justadialog" class="center" title="<?= _('SET_REMINDER'); ?>">
                  <div>
                    
                     <select name="reminder_time_offset" id="reminder_time_offset" class="btn black bg-kcms w90">
                     <option data-timestamp="<?= strtotime('+1 week'); ?>000" value="<?= strftime("%x", strtotime('+1 week')); ?>"><?= _('IN_A_WEEK'); ?></option>
                     <option data-timestamp="<?= strtotime('+1 month'); ?>000" value="<?= strftime("%x", strtotime('+1 month')); ?>"><?= _('IN_A_MONTH'); ?></option>
                     <option data-timestamp="<?= strtotime('+1 year'); ?>000" value="<?= strftime("%x", strtotime('+1 year')); ?>" selected><?= _('IN_A_YEAR'); ?></option>
                     </select>
                     <input type="text" name="reminder_time" id="reminder_time" class="block field mt1 col-12" value="<?= strftime("%x", strtotime('+1 year')); ?>" />
                     
                     
                     
                     
                        <textarea id="reminder_content_proxy" name="reminder_content_proxy" placeholder="<?= _('ENTER_REMINDER_TEXT'); ?>" class="field mt1 mb1 col-12 " /><?= $taglist; ?></textarea>

                     <div>
                        <div class="btn mb1 mr1 px1 black bg-kcms " ><a class=" clear_reminder" href="#"><?= _('CANCEL'); ?></a></div>
                        <div class="btn mb1 mr1 px1 black bg-kcms " ><a class=" set_reminder" href="#"><?= _('SET_REMINDER'); ?></a></div>
                     </div>
                  </div>
               </div>
               <div class="left btn btn-primary mb1 mt1 ml1 black bg-kcms " >
                  <?php
                  if ($this->outstanding) {  ?>
                      <input type="checkbox" class="cbrad" name="tododone_checkbox" id="tododone_checkbox" value="true" />
                      <label for="tododone_checkbox"><?= _('MARK_THIS_AS_DONE'); ?></label>
                  <?php } else { ?> 
                      <input type="checkbox" class="cbrad" name="todolist_checkbox" id="todolist_checkbox" value="true" />
                      <label for="todolist_checkbox"><?= _('PUT_THIS_ON_TODO_LIST'); ?></label>
                      <?php } ?>
               </div>
      
            </div>   
            <div class = "right">
               <div class="btn mb1 mt1 black bg-kcms " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
               <input type="submit" id="start-upload" class="btn mb1 mt1 black bg-kcms" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
            </div>
         
            
      
      
      
         <div id="filelist" class="list-reset">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
         
         
      </div>
   </form>
           <div id="imgdeldlg" class="center" title="<?= _('ARE_YOU_REALLY_REALLY_SURE'); ?>"><p><?= _('ARE_YOU_REALLY_REALLY_SURE'); ?></p>
                <input type="hidden" id="image_no" name="image_no" value = "" />
                <input type="hidden" id="image_id" name="image_id" value = "" />
                <input type="hidden" id="wherefrom" name="wherefrom" value = "event" />
                <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog cancel" href="#"><?= _('CANCEL'); ?></a></div>
                <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog delete" href="#"><?= _('DELETE'); ?></a></div>
           </div>
<?php include('img_uploader.php'); ?>

    