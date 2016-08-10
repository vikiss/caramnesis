<?php
// <div class="left mb1 mr1 px1 black bg-kclite fauxfield"><a href="??= Config::get('URL'); ??login/resetregion/">??= _('RESET_REGION'); ??</a></div>
 foreach ($this->states as $key => $state) {
      print '<div class="left mb1 mr1 px1 black bg-kclite fauxfield';
      if ($key == $this->selected_state) print ' hilite';
      print '" >';
      print '<a href="'.Config::get('URL').'login/setregion/'.$key.'/'.$this->selected_state.'">'.$state;
        print '</a></div>';
}
?>