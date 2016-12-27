<?php
$eventtypelist = '<select name="event_type_select" id="event_type_select" class="btn black bg-kcms w90">';
$eventtypelist.= '<option value="" disabled selected>'._('ADD_EVENT_TYPES').'</option>';
foreach ($tags as $key => $tag) {
   $eventtypelist.='<option value="'.$tag.'">'._($tag).'</option>';
 };
 $eventtypelist.= '</select>';
 $taglist='';
  ?>

<div class="bg-kclite p2 mt2 fauxfield relative" id="new_event_dialog" title="<?php echo _("NEW_RECORD"); ?>">
   <form method="post" enctype="multipart/form-data" action="<?php echo Config::get('URL');?>car/create_event" id="new_event_form">
      <div id="container" class="lg-flex clearfix">
      
            <div class="flex-auto mr2 fauxfield mt1 bg-white">
               <div class="m1 small clearfix">
               
               <div class="col col-3"><?= $eventtypelist; ?></div>
               <ul class="tags list-reset m0 col col-9" id="taglisting"></ul>
               
               </div>
               <textarea name="event_content" class="col-12 h96 no-border" placeholder="<?php echo _("EVENT_CONTENT"); ?>" ></textarea>
            </div>
            
            <div class="">
               <div class="lblgrp"><label for="event_odo"><?= _("EVENT_ODO").', '.$units->user_distance; ?></label>
                        <input type="number" min="0" step="1" name="event_odo" id="event_odo" class="block field mt1 col-12" value="<?= $odo; ?>"  />
               </div>
               <div class="lblgrp"><label for="event_amount"><?= _("EVENT_AMOUNT").', '._('CURRENCY_'.$units->user_currency); ?></label>
                        <input type="number" min="0" step="0.01" name="event_amount"  id="event_amount" class="block field mt1 col-12"  />   
               </div>
               <div class="lblgrp"><label for="event_date"><?= _("EVENT_DATE"); ?></label>
                        <input type="text" name="event_date_proxy" id="event_date_proxy" class="block field mt1 col-12" value="<?= strftime("%x"); ?>" />
               </div>
            </div>
            
            <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
            <input type="hidden" name="user_images" id="user_images" value = "" />
            <input type="hidden" name="event_type" id="event_type" value="" />
            <input type="hidden" name="event_date" id="event_date" value="<?= time(); ?>000" />
            <input type="hidden" id="remindertoggle" name="remindertoggle" value="off" />
            <input type="hidden" id="timestampdate" name="timestampdate" value="<?= strtotime('+1 year'); ?>000" />
            <input type="hidden" id="reminder_content" name="reminder_content" value="<?= $taglist; ?>" />
            
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
                      <input type="checkbox" class="cbrad" name="todolist_checkbox" id="todolist_checkbox" value="true" />
                      <label for="todolist_checkbox"><?= _('PUT_THIS_ON_TODO_LIST'); ?></label>
               </div>
            </div>   
            <div class = "right">
               <div class="btn mb1 mt1 black bg-kcms " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
               <input type="submit" id="start-upload" class="btn mb1 mt1 black bg-kcms" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
            </div>
         
            
      
      
      
         <div id="filelist" class="list-reset">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
         
      </div>
   </form>                                                  
</div>
<?php include('img_uploader.php'); ?>

    