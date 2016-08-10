<ul><?php 
 foreach ($this->cities as $city) {
      print '<div class="left mb1 mr1 px1 black bg-kclite fauxfield';
      if ($city->AccentCity == $this->selected_city) print ' hilite';
      print '" >';
      print '<a href="'.Config::get('URL').'login/setcity/'.urlencode($city->City).'/'.$this->state_id.'">'.$city->AccentCity;
        print '</a></div>';
}
?></ul>