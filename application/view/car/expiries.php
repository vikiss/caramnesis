<?php
//print '<!--'.$this->updater_response.'-->';
//print '<p class="small">'.$this->updater_response.'</p>';
if ($this->car) {
include('car_data_prep.php');
$expiries ? $structure = simplexml_load_string($expiries) : $structure = simplexml_load_string($this->xml_structure);

   ?>
    <div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
    <h1><?= $car_name; ?></h1>
<div class="box">
	 
<form method="post" action="<?php echo Config::get('URL');?>car/save_expiries">
       
<?php
foreach ($structure->expiry as $expiry) {
       print '<h3>'._($expiry['name']).'</h3><div class="relative">';
       
        foreach($expiry->entry as $entry){
              if ((string) $entry['type'] !== 'HIDDEN') {
              print '<div class="mt2"><div class="small">'._($entry['name']).'</div>';
              };
              
              
              
              
              
              
              
       switch((string) $entry['type']) { 
    case 'DATE':
        print '<input type = "text" placeholder="YYYY-MM-DD" class = "exptxt small-field stealthfield expiry-date col-10" 
        id = "expbit-'.$expiry['name'].'-'.$entry['name'].'" name = "expbit-'.$expiry['name'].'-'.$entry['name'].'" value = "'.$entry->value.'" /> ';
        break;
    case 'TEXT':
        print '<input type = "text" class = "exptxt small-field stealthfield  col-10" 
        id = "expbit-'.$expiry['name'].'-'.$entry['name'].'" name = "expbit-'.$expiry['name'].'-'.$entry['name'].'" value = "'.$entry->value.'" /> ';
        break;
	case 'INT':
        print '<input type = "text" pattern="\d*" class = "exptxt small-field stealthfield  col-10" 
        id = "expbit-'.$expiry['name'].'-'.$entry['name'].'" name = "expbit-'.$expiry['name'].'-'.$entry['name'].'" value = "'.$entry->value.'" /> ';
        break;
       case 'HIDDEN':
        print '<input type = "hidden" 
        id = "expbit-'.$expiry['name'].'-'.$entry['name'].'" name = "expbit-'.$expiry['name'].'-'.$entry['name'].'" value = "'.$entry->value.'" /> ';
        break;
     case 'CHOICE':
        print '<select class = "exptxt small-field stealthfield  col-10" 
        id = "expbit-'.$expiry['name'].'-'.$entry['name'].'" name = "expbit-'.$expiry['name'].'-'.$entry['name'].'"> ';
            print '<option value="">..</option>';
            foreach($entry->item as $item) {
            print '<option value="'.$item.'" ';
            if ((string) $entry->value == $item) {print ' selected';}
            print '>'._($item).'</option>';
            }
        print '</select>';
        break;
     
     
    }
              
       if ((string) $entry['type'] !== 'HIDDEN') {
        print '</div>';
       };
        }
    print '</div>';      
              
              
              
              
        }

?>
<input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />
<input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value='<?php echo _("SAVE"); ?>' autocomplete="off" />        
</form>
     


	 
</div>
    </div>
    <?php } ?>
