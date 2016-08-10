<ul><?php 
 foreach ($this->countries as $country) {
      print '<div class="left mb1 mr1 px1 black bg-kclite fauxfield';
      if ($country->Country == $this->selected_country) print ' hilite';
      print '" >';
      print '<a href="'.Config::get('URL').'login/setcountry/'.$country->Country.'/'.$this->selected_country.'">'._('COUNTRY_'.$country->Country);
        print '</a></div>';
}
?></ul>