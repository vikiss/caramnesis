<table class="striped">
<?php if (is_array($car_data)) {

foreach ($car_data AS $raktas => $row) {
  foreach ($row AS $key => $value) {
      print '<tr><td>';
      print _($key);  
      print '</td><td class="bit_val" data-id="'.$raktas.'">';
      print $value;
      print '</td></tr>';  
  
  }
}
}
?></table>