<?php
//$comparison = CarModel::updateXmlBits($this->xml_structure, $this->xml_databits, $car_id);
$databit_units = Config::get('DATABIT_UNITS');

if (!$usrloc = $_SESSION['user_cons']) $usrloc == 'eu'; //default units if not filled in yet for a user based on his consumption preference: eu, us, or uk
$defunits=array(
                'eu' => array(
                      'weight' => 'kg',
                      'power' => 'kW',
                      'volume' => 'cm3',
                              ),
                'uk' => array(
                      'weight' => 'kg',
                      'power' => 'kW',
                      'volume' => 'cm3',
                      ),
                'us' => array(
                      'weight' => 'lbs',
                      'power' => 'HP',
                      'volume' => 'ci',
                      ),
                );
//print '<p class="small">Ver: '.$structure->version.': '.$comparison.'</p>';

  print '
            <div id="deletedialog" class="center" title="'._('ARE_YOU_REALLY_REALLY_SURE').'"><h3>'._('ARE_YOU_REALLY_REALLY_SURE').'</h3>
            <form method="post" enctype="multipart/form-data" action="'.Config::get('URL').'car/del_attribute_xml/'.$car_id.'">
            <input type="hidden" name="key" id="key" value = "" />
            <input type="hidden" name="val" id="val" value = "" />
            <input type="hidden" name="chapter" id="chapter" value = "" />
            <input type="hidden" name="chapterno" id="chapterno" value = "" />
            <input type="hidden" name="entry" id="entry" value = "" />
            <input type="hidden" name="unit" id="unit" value = "" />
            <input type="hidden" name="validate" id="validate" value = "" />
            <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog" href="#">'._('CANCEL').'</a></div>
            <input type="submit" class="btn mb1 mr1 px1 black bg-kclite " value="'._('DELETE').'" autocomplete="off" />
            </form>
            </div>';
                
$i=1; $output = array(); $output_multiples = array();
//buffer output into array so that we can sort them by chapter name, since new ones are always tacked on at the end
foreach ($structure->chapter as $chapter) {
    $buffer = '';
    (string)$chapter['type'] == 'MULTIPLE' ? $multiple = true : $multiple = false;
    $buffer.='<h3>'._($chapter['name']);
    
 //   if ($multiple) {        $buffer.= ' ('.$chapter->number.')';         }
    $buffer.= '</h3><div class="relative">';
    
    
    /*/*img
    $buffer.= '
    <div class="right">
        <div class="dropbox left '.$chapter['name'].'">
            <div class="fileupl btn btn-primary mb1 mt1 black bg-kcms z-1 jqtooltip" title="'. _('UPLOAD_IMAGES_OR_TAKE_A_PICTURE').'" >
                <i class="icon-camera"> </i><input type="file" name="fileinput[]" multiple="multiple" accept="image/*|application/pdf" />
            </div>
            <div class="console"></div>
        </div>
    </div>
    ';
    //ing*/
    
        if (($multiple) && ((string)$chapter->number == '1')) { //show plus icon once per multiple type
        $buffer.='<div class="absolute top-0 left-0 bg-kcms"><a title="';
        $buffer.= sprintf(_('ADD_NEW_%s'), _($chapter['name']));
        $buffer.='" href="';
        $buffer.= Config::get('URL') . 'car/add_attributes_xml_chapter/' . $car_id .'/'. urlencode($chapter['name']);
        $buffer.='"><i class="icon-plus-circled white"> </i></a></div>';
        }
    
    foreach($chapter->entry as $entry){
        //custom user defined name entry
     if ((string)$entry['name'] == 'NEW_ENTRY') {
        $buffer.= '<div class="mt2"><div class="small">'._('NEW_ENTRY_NAME').'</div>';
        $buffer.= '<input type = "text" class = "databitcustname small-field stealthfield col-10" 
        data-chapter = "'.$chapter['name'].'" data-chapterno = "'.$chapter->number.'"
        data-id = "xmlbit-'.$i.'" data-validate = "text" value = "" /> ';
        $buffer.= '<div class="small mt1">'._('NEW_ENTRY_VALUE').'</div>';
        $buffer.= '<input type = "text" class = "databitcustval small-field stealthfield col-10" 
        data-chapter = "'.$chapter['name'].'" data-chapterno = "'.$chapter->number.'"
        id = "xmlbit-'.$i.'" data-validate = "text" value = "" /> ';
        
    } else {    
        //up to here
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
        
        
        $buffer.= '<span class="response" data-key="xmlbit-'.$i.'">';
        if (($chapter['name'] == 'CUSTOM_DATA') && ((string)$entry['name'] !== 'NEW_ENTRY'))  {  //allow to delete entry if it's user generated
            $buffer.= ' <a href="#" class="deletedatabitdlg" title="'._('DELETE').'"><i class="icon-minus-circled black"> </i></a>';
        }
        $buffer.= ' </span></div>';
        $i++;
    }
    $buffer.= '</div>';
    
    if ($multiple) {
        $keyname = $chapter['name']. '('.$chapter->number.')';
        $output_multiples[$keyname] = $buffer;
    } else {
        $output["{$chapter['name']}"] = $buffer;
        }
    
}
print '<div id="accordion">';
foreach ($output as $piece) {print $piece;}
ksort($output_multiples);
foreach ($output_multiples as $piece) {print $piece;}
print '</div>';
//print '<pre>'; print $structure->asXML(); print '</pre>'; 
?>