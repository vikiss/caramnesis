<?php if ($this->car) { include('car_data_prep.php');

                      $initial_img = '';  $picstrip_out = ''; $i=1;
                   if ($car_images) { $first = true; 
                    foreach($car_images AS $image) {
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
                    if (count($car_images) < 2) {$picstrip_out = '';}
                                } 

?>
<div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
    <h1><?= $car_name; ?></h1>
<div class="box">
<form method="post" action="<?php echo Config::get('URL');?>car/save_car_picture">


<div class="bg-kclite p2 mt2 fauxfield relative">
        <div id="container" class="clearfix">
                <div id="dropbox" class="mb1 mr1 p1 black bg-kclite left fauxfield square truncate">
                        
                    <div class="fileupl center midsquare black z-1 jqtooltip" title="<?= _('UPLOAD_IMAGES_OR_TAKE_A_PICTURE'); ?>" >
                        <i class="icon-camera"> </i><input type="file" name="fileinput[]" id="fileinput" multiple="multiple" accept="image/*" />
                     </div>
                        

                </div>
                        <div id="filelist" class="list-reset">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
                          <div id="console"></div>
                          <div id="sortimages">
                          <?php
                           if ($car_images) { $i = 1;
                            foreach($car_images AS $image) {
                     $imgurl = Config::get('URL').'car/image/'.$car_id.'/'.$image;
        print '<div class="portlet mb1 mr1 p1 black bg-kclite left fauxfield square truncate " data-number="'.$i.'" id="'.$image.'">
                <div class="portlet-header bg-kcms move-cursor">
                <div class="right meta z4"><a class="context_menu_opener" data-element="editpic'.$i.'" href="#" title="'._("EDIT").'"><i class="icon-wrench white"> </i></a></div>
                <a href="#" class="jqtooltip" title="'._("MOVE_PICTURE_TO_SORT").'"><i class="icon-move"> </i></a>
                </div>
                <div class="portlet-content relative">';
//print '<a href="'.Config::get('URL').'/car/imagepg/'.$owner['uuid'].'/'.$car_id.'_'.$image.'"><img src="/car/image/'.$owner['uuid'].'/'.$car_id.'_'.$image.'/120" /></a> ';
        print '<div id="editpic'.$i.'" class="absolute top-0 right-0 border z3 active bg-white display-hide p2 closeonclick ">
                            <ul class="list-reset">
                                   <li><a href="#" class="imgdel"><i class="icon-trash"> </i></a></li>
                                   <li><a href="#" class="imgrotate imgcw"><i class="icon-cw"> </i></a></li>
                                   <li><a href="#" class="imgrotate imgccw"><i class="icon-ccw"> </i></a></li>
                            </ul>
                     </div>';
print '<a href="'.$imgurl.'" class="fsgalimg-link"><img src="'.$imgurl.'/120'.'" /></a>';
        print '</div></div>';
        $i++;
                                                           }
                                             } ?>
                          </div>
                        <div id="piccount" class="hide"><?= count($car_images); ?></div>
                        
                        <input type="hidden" name="owner" id="owner" value = "<?= $owner['uuid']; ?>" />
                        <input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
                        <input type="hidden" name="user_images" id="user_images" value = "<?=  $images_list; ?>" />
                        <div id="response" class="hide"></div>
        
        
        <?php include('img_uploader.php');  ?>
        
</div>       <!-- fauxfield -->
    </div> <!-- container -->
    




<input type="submit" id="start-upload" class="btn btn-primary mb1 mt1 black bg-kcms right" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
</form>

  <div id="imgdeldlg" class="center" title="<?= _('ARE_YOU_REALLY_REALLY_SURE'); ?>"><p><?= _('ARE_YOU_REALLY_REALLY_SURE'); ?></p>
                <input type="hidden" id="image_no" name="image_no" value = "" />
                <input type="hidden" id="image_id" name="image_id" value = "" />
                <input type="hidden" id="wherefrom" name="wherefrom" value = "car" />
                <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog cancel" href="#"><?= _('CANCEL'); ?></a></div>
                <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog delete" href="#"><?= _('DELETE'); ?></a></div>
           </div>

<?php } else { ?>
            <p><?= _("CAR_DOES_NOT_EXIST"); ?></p>
<?php } ?>
        
    </div> <!-- .box -->
</div> <!-- .container -->
<?php include 'fsgallery.php'; ?>
            