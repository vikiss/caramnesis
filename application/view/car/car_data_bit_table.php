<table class="striped"><?php
$key_already_exists=array();
if (is_array($car_data)) {

foreach ($car_data AS $raktas => $row) {
  foreach ($row AS $key => $value) {
      print '<tr><td>';
      print _($key);   $key_already_exists[]=$key;
      print '</td><td class="bit_val" data-id="'.$raktas.'">';
      print $value;
      print '</td></tr>';  
  
  }
}
}
?><tr><td>   
	<select id="new_car_data_bit" class="small field mt1 small-field ">
 				<?php foreach ($this->car_data_bits as $key => $bit) {
         if ((!in_array($bit, $key_already_exists)) or ($key == 'default'))  
          {
 						echo '<option value="'.$bit.'"';
 							if ($key == 'default') {echo ' selected';}
 								echo '>'._($bit).'</option>';
                }
 						}; ?></select>
            
        </td><td>    
        <input type="text" name="new_car_data_val" id="new_car_data_val" class=" small field mt1 small-field " />
 				<input type="submit" name="add_new_car_data" id="add_new_car_data" class="btn-primary black small bg-kcms" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
        </td></tr>
        </table>