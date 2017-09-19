<div class="container">
<div class="box"><?php $this->renderFeedbackMessages(); ?></div>
<div class="mt1 clearfix">
<?php
$images_list = '';
$page_images = false;
$saved_description = ''; $saved_contact = ''; $saved_title = '';
$saved_location = ''; $cboxes = array();
if ($saved_page = $this->saved_page[0]) {
$saved_description = (isset($saved_page->description)) ? htmlentities($saved_page->description) : '';
$saved_contact = (isset($saved_page->contact)) ? htmlentities($saved_page->contact) : '';
$saved_title = (isset($saved_page->title)) ? htmlentities($saved_page->title) : '';
$saved_location = (isset($saved_page->location)) ? $saved_page->location : '';
$page_images = unserialize($saved_page->images);
if (is_array($page_images)) {
  $images_list = implode(',', $page_images);}

$cboxes = json_decode($saved_page->content);
}
 ?>




<form method="post" enctype="multipart/form-data" action="<?= Config::get('URL');?>profile/save" id="profile_edit_form">
    <div id="container" class="lg-flex clearfix">
        <div class="flex-auto mr2 fauxfield mt1 bg-white">
           <textarea
           name="description" id="description"
           class="col-12 h96 no-border autoheight"
           placeholder="<?php echo _("PROFILE_PAGE_DESCRIPTION"); ?>" ><?= $saved_description; ?></textarea>
           <textarea
           name="contact" id="contact"
           class="col-12 border "
           placeholder="<?php echo _("PROFILE_PAGE_CONTACT_INFO"); ?>" ><?= $saved_contact; ?></textarea>
        </div>
        <div class="">
            <div class="lblgrp"><label for="title"><?= _("PROFILE_PAGE_TITLE"); ?></label>
                <input type="text" name="title" id="title" class="block field mt1 col-12" value="<?= $saved_title; ?>" />
            </div>
            <div class="lblgrp"><label for="location"><?= _("PROFILE_PAGE_LOCATION"); ?></label>
                <input type="text" name="location" id="location" class="block field mt1 col-12" placeholder="1.234,1.234 (lat,lon)" value="<?= $saved_location; ?>" />
            </div>
            <div>
                <input type="checkbox" class="cbrad" name="show_cars4sale" id="show_cars4sale" value="Y" <?php if (in_array("show_cars4sale", $cboxes)) echo 'checked'; ?> />
                <label for="show_cars4sale"><?= _('SHOW_CARS_4SALE_PROFILE_PAGE'); ?></label>
            </div>
            <div>
                <input type="checkbox" class="cbrad" name="show_partcars4sale" id="show_partcars4sale" value="Y" <?php if (in_array("show_partcars4sale", $cboxes)) echo 'checked'; ?> />
                <label for="show_partcars4sale"><?= _('SHOW_PARTCARS_4SALE_PROFILE_PAGE'); ?></label>
            </div>
        </div>
        <input type="hidden" name="user_id" id="user_id" value = "<?= $this->page_owner; ?>" />
        <input type="hidden" name="user_images" id="user_images" value = "<?= $images_list; ?>" />
        <div id="response" class="hide"></div>
    </div>
    <div id="sortimages" class="clearfix mt1">
                          <?php
                           if ($page_images) { $i = 1;
                            foreach($page_images AS $image) {
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
print '<img src="/car/image/'.$this->page_owner.'/'.$image.'/120" /> ';
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
<?php
$car_id = $this->page_owner; //user id becomes folder name for images
include (realpath(dirname(__FILE__).'/../') . '/car/img_uploader.php');
 ?>






</div>
</div>
