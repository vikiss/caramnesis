<?php
$databit_units = Config::get('DATABIT_UNITS');
if (!$usrloc = $_SESSION['user_cons']) $usrloc == 'eu'; //default units if not filled in yet for a user based on his consumption preference: eu, us, or uk
$defunits=array(
                'eu' => array(
                      'weight' => 'kg',
                      'power' => 'kW',
                      'volume' => 'cm3',
                      'inches' => 'in',
                      'millimeters' => 'mm',
                              ),
                'uk' => array(
                      'weight' => 'kg',
                      'power' => 'kW',
                      'volume' => 'cm3',
                      'inches' => 'in',
                      'millimeters' => 'mm',
                      ),
                'us' => array(
                      'weight' => 'lbs',
                      'power' => 'HP',
                      'volume' => 'ci',
                      'inches' => 'in',
                      'millimeters' => 'mm',
                      ),
                );

?>
<div class="container">
    <div class="box">
        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
	</div>
<div class="clearfix">
    <div class="md-col md-col-3 carcol">
      <?php  include 'carcol.php'; ?>
    </div>
    <div class="md-col md-col-9 ">
<h1><?= _($this->chapter); ?></h1>
<div class="right response"> </div>
<div id="response" class="hide"> </div>
<?php
  /*
   [chapter] => BODYWORK_DATA
            [entry] => PICTURES
            [type] => PICTURES
            [ord] => 0
            [items] =>
  */
  $car_items = $this->car_items;  // print_r($this->structural_items);
  $i = 1;
  $inline_buffer = array();
    foreach ($this->structural_items AS $entry) {
    $saved = array(); $saved['value'] = ''; $saved['unit'] = '';
    if(isset($car_items[$entry->entry])) {$saved = $car_items[$entry->entry];}
?>
   <div class="mt2">
<?php
    switch($entry->type)
    {
    case 'DATE':
?>
    <div class="small"><?= _($entry->entry); ?></div>
    <input type = "text" class = "attrtxt small-field stealthfield databit-date col-10"
        data-chapter = "<?= $entry->chapter; ?>" data-entry = "<?= $entry->entry; ?>"
        id = "attritem-<?= $i; ?>" data-validate = "text" value = "<?= $saved['value']; ?>" />
<?php
    break;
    case 'TEXT':
?>
    <div class="small"><?= _($entry->entry); ?></div>
    <input type = "text" class = "attrtxt small-field stealthfield  col-10"
        data-chapter = "<?= $entry->chapter; ?>" data-entry = "<?= $entry->entry; ?>"
        id = "attritem-<?= $i; ?>" data-validate = "text" value = "<?= $saved['value']; ?>" />
<?php
    break;
    case 'PICTURES':
                       if (strlen($saved['value']) > 10) {
                       $images_list = $saved['value'];
                        $chapter_images = explode(',', $saved['value']);
                       } else {
                       $chapter_images = false; }
    break;
    case 'WEIGHT':
        $units =   $databit_units['WEIGHT'];
        $saved['unit'] ? $thisunit = $saved['unit'] : $thisunit = $defunits[$usrloc]['weight'];

?>
        <div class="small"><?= _($entry->entry); ?></div>
        <input type = "text" pattern="[0-9]+([\.,][0-9]+)?" class = "attrtxt small-field stealthfield  col-10"
        data-chapter = "<?= $entry->chapter; ?>" data-entry = "<?= $entry->entry; ?>"
        id = "attritem-<?= $i; ?>" data-validate = "dec2" value = "<?= $saved['value']; ?>" />
        <select class = "attrunit unit-weight small-field stealthfield" data-id = "attritem-<?= $i; ?>">';
  <?php  foreach($units as $unit) { ?>
            <option value="<?= $unit; ?>"<?php if ($thisunit == $unit) {echo ' selected';} ?>><?= _($unit); ?></option>
  <?php }; ?>
        </select>
<?php
    break;
    case 'INT':
?>
    <div class="small"><?= _($entry->entry); ?></div>
    <input type = "text" pattern="\d*" class = "attrtxt small-field stealthfield  col-10"
        data-chapter = "<?= $entry->chapter; ?>" data-entry = "<?= $entry->entry; ?>"
        id = "attritem-<?= $i; ?>" data-validate = "int" value = "<?= $saved['value']; ?>" />
<?php
    break;
    case 'POWER':
        $units =   $databit_units['POWER'];
        $saved['unit'] ? $thisunit = $saved['unit'] : $thisunit = $defunits[$usrloc]['power'];
?>
        <div class="small"><?= _($entry->entry); ?></div>
        <input type = "text" pattern="[0-9]+([\.,][0-9]+)?" class = "attrtxt small-field stealthfield  col-10"
        data-chapter = "<?= $entry->chapter; ?>" data-entry = "<?= $entry->entry; ?>"
        id = "attritem-<?= $i; ?>" data-validate = "dec2" value = "<?= $saved['value']; ?>" />
        <select class = "attrunit unit-power small-field stealthfield" data-id = "attritem-<?= $i; ?>">';
  <?php  foreach($units as $unit) { ?>
            <option value="<?= $unit; ?>"<?php if ($thisunit == $unit) {echo ' selected';} ?>><?= _($unit); ?></option>
  <?php }; ?>
        </select>
<?php
    break;
    case 'VOLUME':
        $units =   $databit_units['VOLUME'];
        $saved['unit'] ? $thisunit = $saved['unit'] : $thisunit = $defunits[$usrloc]['volume'];
?>
        <div class="small"><?= _($entry->entry); ?></div>
        <input type = "text" pattern="[0-9]+([\.,][0-9]+)?" class = "attrtxt small-field stealthfield  col-10"
        data-chapter = "<?= $entry->chapter; ?>" data-entry = "<?= $entry->entry; ?>"
        id = "attritem-<?= $i; ?>" data-validate = "dec2" value = "<?= $saved['value']; ?>" />
        <select class = "attrunit unit-volume small-field stealthfield" data-id = "attritem-<?= $i; ?>">';
  <?php  foreach($units as $unit) { ?>
            <option value="<?= $unit; ?>"<?php if ($thisunit == $unit) {echo ' selected';} ?>><?= _($unit); ?></option>
  <?php }; ?>
        </select>
<?php
    break;
     case 'CHOICE':
     $items = unserialize($entry->items);
?>
        <div class="small"><?= _($entry->entry); ?></div>
        <select class = "attrtxt small-field stealthfield  col-10"
        data-chapter = "<?= $entry->chapter; ?>" data-entry = "<?= $entry->entry; ?>"
        id = "attritem-<?= $i; ?>" data-validate = "text">
        <option value="">..</option>
  <?php  foreach($items as $item) { ?>
            <option value="<?= $item; ?>"<?php if ($saved['value'] == $item) {echo ' selected';} ?>><?= _($item); ?></option>
  <?php }; ?>
        </select>
<?php
     break;
     case 'INCHOICE':
     $items = unserialize($entry->items);
     $buffer = '<div class="small">'._($entry->entry).'</div>';
     $buffer.= '<select class = "attrtxt small-field stealthfield col-12"';
     $buffer.= 'data-chapter = "'.$entry->chapter.'" data-entry = "'.$entry->entry.'"';
     $buffer.= 'id = "attritem-'.$i.'" data-validate = "text">';
     $buffer.= '<option value="">..</option>';
     foreach($items as $item) {
        $buffer.= '<option value="'.$item.'"';
        if ($saved['value'] == $item) {$buffer.= ' selected';}
        $buffer.= '>'._($item).'</option>';
        }
      $buffer.= '</select>';
  $inline_buffer[$entry->entry] = $buffer;
     break;
      case 'INCHES':
        $units =   $databit_units['INCHES'];
        $saved['unit'] ? $thisunit = $saved['unit'] : $thisunit = $defunits[$usrloc]['inches'];
?>
      <div class="small"><?= _($entry->entry); ?></div>
        <input type = "text" pattern="[0-9]+([\.,][0-9]+)?" class = "attrtxt small-field stealthfield  col-10"
        data-chapter = "<?= $entry->chapter; ?>" data-entry = "<?= $entry->entry; ?>"
        id = "attritem-<?= $i; ?>" data-validate = "dec2" value = "<?= $saved['value']; ?>" />
        <select class = "attrunit unit-inches small-field stealthfield" data-id = "attritem-<?= $i; ?>">';
  <?php  foreach($units as $unit) { ?>
            <option value="<?= $unit; ?>"<?php if ($thisunit == $unit) {echo ' selected';} ?>><?= _($unit); ?></option>
  <?php }; ?>
        </select>
<?php
      break;
       case 'MILLIMETERS':
        $units =   $databit_units['MILLIMETERS'];
        $saved['unit'] ? $thisunit = $saved['unit'] : $thisunit = $defunits[$usrloc]['millimeters'];
?>
      <div class="small"><?= _($entry->entry); ?></div>
        <input type = "text" pattern="[0-9]+([\.,][0-9]+)?" class = "attrtxt small-field stealthfield  col-10"
        data-chapter = "<?= $entry->chapter; ?>" data-entry = "<?= $entry->entry; ?>"
        id = "attritem-<?= $i; ?>" data-validate = "dec2" value = "<?= $saved['value']; ?>" />
        <select class = "attrunit unit-millimeters small-field stealthfield" data-id = "attritem-<?= $i; ?>">';
  <?php  foreach($units as $unit) { ?>
            <option value="<?= $unit; ?>"<?php if ($thisunit == $unit) {echo ' selected';} ?>><?= _($unit); ?></option>
  <?php }; ?>
        </select>
<?php
      break;
    }
    ?>
    </div>
<?php
$i++;
}
if (substr($this->chapter, 0, 9) == 'TYRE_DATA') {
print '<div class="clearfix">';
print '<div class="col col-2">'.$inline_buffer['SECTION_WIDTH'].'</div><div class="col col-1 center mt2"> / </div><div class="col col-2">'.$inline_buffer['SIDEWALL_RATIO'].'</div><div class="col col-1">&nbsp;</div><div class="col col-2">'.$inline_buffer['RIM_DIAMETER'].'</div>';
print '</div>';
}
?>
<div id="container" class="clearfix">
      <div id="sortimages" class="clearfix mt1 autosortsave">
                          <?php
                           if ($chapter_images)
    {
    $script =  '';
    $pic_dir = Config::get('CAR_IMAGE_PATH').$car_id.'/';
    $i = 1;
      foreach($chapter_images AS $image) {
      if (file_exists($pic_dir.$image)) {
          $is_pdf = ($fullsize = getimagesize ($pic_dir.$image)) ? false : true;
      }
            print '<div class="portlet mb1 mr1 p1 black bg-kclite left fauxfield square truncate " data-number="'.$i.'" id="'.$image.'">
                    <div class="portlet-header bg-kcms move-cursor">
                    <div class="right meta z4"><a class="context_menu_opener" data-element="editpic'.$i.'" href="#" title="'._("EDIT").'"><i class="icon-wrench white"> </i></a></div>
                    <a href="#" class="jqtooltip" title="'._("MOVE_PICTURE_TO_SORT").'"><i class="icon-move"> </i></a>
                    </div>
                    <div class="portlet-content relative">';
            print '<div id="editpic'.$i.'" class="absolute top-0 right-0 border z3 active bg-white display-hide p2 closeonclick ">
                                <ul class="list-reset">
                                       <li><a href="#" class="imgdel"><i class="icon-trash"> </i></a></li>';
            if (!$is_pdf) {print '     <li><a href="#" class="imgrotate imgcw"><i class="icon-cw"> </i></a></li>
                                       <li><a href="#" class="imgrotate imgccw"><i class="icon-ccw"> </i></a></li>';};
            print '  </ul>
                         </div>';
            print '<a href="/car/image/'.$car_id.'/'.$image.'" data-index="'.$i.'" class="pswpitem"><img src="/car/image/'.$car_id.'/'.$image.'/120" /></a> ';
            print '</div></div>';
            if (!$is_pdf) {$script.="{
            src: '/car/image/$car_id/$image',
            w: {$fullsize[0]},
            h: {$fullsize[1]},
            msrc: '/car/image/$car_id/$image/120',
            },";};
            $i++;
      }
    print "
<script>
var itemclass = '.pswpitem';
var items = [$script];
</script>";
?>
<script src="<?php echo Config::get('URL'); ?>js/pswipe.js"></script>
<?php
  }
?>
      </div>
                        <div id="dropbox" class="left">
                     <div class="fileupl btn btn-primary mb1 mt1 black bg-kcms z-1 jqtooltip" title="<?= _('UPLOAD_IMAGES_OR_TAKE_A_PICTURE'); ?>" >
                        <i class="icon-camera"> </i><input type="file" name="fileinput[]" id="fileinput" multiple="multiple" accept="image/*|application/pdf" />
                     </div>
                     <div id="console"></div>
                  </div>

         <div id="filelist" class="list-reset">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
</div>



<div id="imgdeldlg" class="center" title="<?= _('ARE_YOU_REALLY_REALLY_SURE'); ?>"><p><?= _('ARE_YOU_REALLY_REALLY_SURE'); ?></p>
                <input type="hidden" id="image_no" name="image_no" value = "" />
                <input type="hidden" id="image_id" name="image_id" value = "" />
                <input type="hidden" id="wherefrom" name="wherefrom" value = "attribute" />
                <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog cancel" href="#"><?= _('CANCEL'); ?></a></div>
                <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog delete" href="#"><?= _('DELETE'); ?></a></div>
           </div>
<?php
  /*



  $buffer = '';






        if ((string) $entry['type'] == 'HIDDEN') {
        $buffer.= '<div>';
        } else {
        $buffer.= '<div class="mt2"><div class="small">'._($entry['name']).'</div>';
        };
        switch((string) $entry['type']) {
    case 'DATE':
        $buffer.= '<input type = "text" placeholder="'.date('Y-m-d').'" class = "databittxt small-field stealthfield databit-date col-10"
        data-chapter = "'.$chapter['name'].'" data-entry = "'.$entry['name'].'" data-chapterno = "'.$chapter->number.'"
        id = "xmlbit-'.$i.'" data-validate = "date"  value = "'.$entry->value.'" /> ';
        break;
    case 'TEXT':
        $buffer.= '<input type = "text" class = "databittxt small-field stealthfield  col-10"
        data-chapter = "'.$chapter['name'].'" data-entry = "'.$entry['name'].'" data-chapterno = "'.$chapter->number.'"
        id = "xmlbit-'.$i.'" data-validate = "text" value = "'.$entry->value.'" /> ';
        break;
    case 'HIDDEN':
        $buffer.= '<input type = "hidden" class = "databittxt small-field stealthfield  col-10"
        data-chapter = "'.$chapter['name'].'" data-entry = "'.$entry['name'].'" data-chapterno = "'.$chapter->number.'"
        id = "xmlbit-'.$i.'" data-validate = "text" value = "'.$entry->value.'" /> ';
        break;
     case 'WEIGHT':
        $units =   $databit_units['WEIGHT'];
        $buffer.= '<input type = "text" class = "databittxt small-field stealthfield  col-10"
        data-chapter = "'.$chapter['name'].'" data-entry = "'.$entry['name'].'" data-chapterno = "'.$chapter->number.'"
        id = "xmlbit-'.$i.'" data-validate = "dec2" value = "'.$entry->value.'" /> ';
        $buffer.= '<select class = "databitunit unit-weight small-field stealthfield " data-id = "xmlbit-'.$i.'">';
            (string) $entry->unit ? $thisunit = (string) $entry->unit : $thisunit = $defunits[$usrloc]['weight'];
            foreach($units as $unit) {
            $buffer.= '<option value="'.$unit.'" ';
            if ($thisunit == $unit) {$buffer.= ' selected';}
            $buffer.= '>'._($unit).'</option>';
            }
        $buffer.= '</select>';
        break;
     case 'INT':
        $buffer.= '<input type = "text" pattern="\d*" class = "databittxt small-field stealthfield  col-10"
        data-chapter = "'.$chapter['name'].'" data-entry = "'.$entry['name'].'" data-chapterno = "'.$chapter->number.'"
        id = "xmlbit-'.$i.'" data-validate = "int" value = "'.$entry->value.'" /> ';
        break;
     case 'POWER':
        $units =   $databit_units['POWER'];
        $buffer.= '<input type = "text" pattern="[0-9]+([\.,][0-9]+)?" class = "databittxt small-field stealthfield  col-10"
        data-chapter = "'.$chapter['name'].'" data-entry = "'.$entry['name'].'" data-chapterno = "'.$chapter->number.'"
        id = "xmlbit-'.$i.'" data-validate = "dec2" value = "'.$entry->value.'" /> ';
        $buffer.= '<select class = "databitunit unit-power small-field stealthfield " data-id = "xmlbit-'.$i.'">';
            (string) $entry->unit ? $thisunit = (string) $entry->unit : $thisunit = $defunits[$usrloc]['power'];
            foreach($units as $unit) {
            $buffer.= '<option value="'.$unit.'" ';
            if ($thisunit == $unit) {$buffer.= ' selected';}
            $buffer.= '>'._($unit).'</option>';
            }
        $buffer.= '</select>';
        break;
     case 'VOLUME':
        $units =   $databit_units['VOLUME'];
        $buffer.= '<input type = "text" pattern="[0-9]+([\.,][0-9]+)?" class = "databittxt small-field stealthfield col-10"
        data-chapter = "'.$chapter['name'].'" data-entry = "'.$entry['name'].'" data-chapterno = "'.$chapter->number.'"
        id = "xmlbit-'.$i.'" data-validate = "dec2" value = "'.$entry->value.'" /> ';
        $buffer.= '<select class = "databitunit unit-volume small-field stealthfield " data-id = "xmlbit-'.$i.'">';
            (string) $entry->unit ? $thisunit = (string) $entry->unit : $thisunit = $defunits[$usrloc]['volume'];
            foreach($units as $unit) {
            $buffer.= '<option value="'.$unit.'" ';
            if ($thisunit == $unit) {$buffer.= ' selected';}
            $buffer.= '>'._($unit).'</option>';
            }
        $buffer.= '</select>';
        break;
     case 'CHOICE':
        $buffer.= '<select class = "databittxt small-field stealthfield  col-10"
        data-chapter = "'.$chapter['name'].'" data-entry = "'.$entry['name'].'" data-chapterno = "'.$chapter->number.'" data-validate = "text" id = "xmlbit-'.$i.'"> ';
            $buffer.= '<option value="">..</option>';
            foreach($entry->item as $item) {
            $buffer.= '<option value="'.$item.'" ';
            if ((string) $entry->value == $item) {$buffer.= ' selected';}
            $buffer.= '>'._($item).'</option>';
            }
        $buffer.= '</select>';
        break;
    case 'INCHES':
        $units =   $databit_units['INCHES'];
        $buffer.= '<input type = "text" pattern="[0-9]+([\.,][0-9]+)?" class = "databittxt small-field stealthfield col-10"
        data-chapter = "'.$chapter['name'].'" data-entry = "'.$entry['name'].'" data-chapterno = "'.$chapter->number.'"
        id = "xmlbit-'.$i.'" data-validate = "dec2" value = "'.$entry->value.'" /> ';
        $buffer.= '<select class = "databitunit unit-inches small-field stealthfield " data-id = "xmlbit-'.$i.'">';
            $buffer.= '<option value="">..</option>';
            foreach($units as $unit) {
            $buffer.= '<option value="'.$unit.'" ';
            if ((string) $entry->unit == $unit) {$buffer.= ' selected';}
            $buffer.= '>'._($unit).'</option>';
            }
        $buffer.= '</select>';
        break;
     case 'MILLIMETERS':
        $units =   $databit_units['MILLIMETERS'];
        $buffer.= '<input type = "text" pattern="[0-9]+([\.,][0-9]+)?" class = "databittxt small-field stealthfield col-10"
        data-chapter = "'.$chapter['name'].'" data-entry = "'.$entry['name'].'" data-chapterno = "'.$chapter->number.'"
        id = "xmlbit-'.$i.'" data-validate = "dec2" value = "'.$entry->value.'" /> ';
         $buffer.= '<select class = "databitunit unit-millimeters small-field stealthfield " data-id = "xmlbit-'.$i.'">';
            $buffer.= '<option value="">..</option>';
            foreach($units as $unit) {
            $buffer.= '<option value="'.$unit.'" ';
            if ((string) $entry->unit == $unit) {$buffer.= ' selected';}
            $buffer.= '>'._($unit).'</option>';
            }
        $buffer.= '</select>';
        break;
    }
    }


    */


include('multi_img_uploader.php');
  ?>
<input type="hidden" name="chapter" id="chapter" value = "<?= $this->chapter; ?>" />
<input type="hidden" name="user_images" id="user_images" value = "<?php if ($chapter_images) {print $images_list;} ?>" />

<div class="clearfix mt4">
<?php
foreach ($this->chapters as $chapter) {

          ?>
    <div class="mb1 mr1 p1 black bg-kclite left fauxfield square center  " >
      <a href = "<?= Config::get('URL') . 'car/attribute_chapter/' . $car_id.'/'.$chapter; ?>">
        <?= _($chapter); ?></a>
    </div>
<?php }  ?>
</div>
 </div>
 </div>
