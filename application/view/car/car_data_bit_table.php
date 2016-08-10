<table class="striped"><?php
$key_already_exists=array();
if (is_array($car_data)) {
foreach ($car_data AS $raktas => $row) {

if (is_array($row)) { 
  foreach ($row AS $key => $value) {
      print '<tr><td>';
      print _($key);   $key_already_exists[]=$key;
      print '</td><td class="bit_val" data-key="'.$key.'" data-id="'.$raktas.'">';
      print $value;
      print '</td><td><i class="icon-wrench pointer cardataeditsw" data-id="'.$raktas.'"> </i> <i class="icon-cancel pointer cardatadeletesw" data-id="'.$raktas.'"> </i></td></tr>';  
  
  }}
}
}
?></table>