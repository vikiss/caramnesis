<?php
$dates=array(
    'INSURANCE' => '?',
    'INSPECTION' => '?',
    'TAXES' => '?',
    'OIL'  => '?',
    'TIMING_BELT' => '?',
             );
$visibility = $dates;
$odos=$dates;
$units = $this->units;

	 function calcRemainingDays(&$dateno) { //calculates difference in days
                if ($dateno) {
                $date = explode('-', $dateno);
                $timestamp = mktime(0,0,0,$date[1],$date[2],$date[0]);
                $now = time();
                $datediff = $timestamp - $now;
                $dateno = floor($datediff / (60 * 60 * 24));
		} else {
			$dateno = '?';
		}
               }
	   
	   function setDayColor(&$days) {
                $color = 'green';
                if ($days < 14) {$color = 'orange';}
                if ($days < 1) {$color = 'red';}
	    if ($days == '?') {$color = 'red';}
                $days = $color;
               }
	    
	    
	     function setOdoDayColor(&$odo_diff) {
                $color = 'green';
                if ($odo_diff < 800) {$color = 'orange';}
                if ($odo_diff < 300) {$color = 'red';}
                $odo_diff = $color;
               }
	    
	    

if ($xml = simplexml_load_string($this->expiries)) {

	foreach ($xml->expiry as $expiry) {
		foreach($expiry->entry as $entry) {
			if ($entry['name'] == 'VALID_UNTIL') {
                                        $dates[(string)$expiry['name']] = (string)$entry->value;
			}
			if ($entry['name'] == 'SHOW') {
				$visibility[(string)$expiry['name']] = (string)$entry->value;
			}
			if ($entry['name'] == 'ODOMETER') {
                                        $odos[(string)$expiry['name']] = intval((string)$entry->value) - intval($this->odo);
			}
		}
	}
array_walk($dates, 'calcRemainingDays');
}	
               
             $styles = $dates;  
               array_walk($styles, 'setDayColor');
	    $odo_styles = $odos;
	    array_walk($odo_styles, 'setOdoDayColor');
               

?>

<ul class="list-reset">
	<?php if ($visibility['INSURANCE'] !== 'NO') { ?>
<li><a class="<?= $styles['INSURANCE'] ?>" href="<?= Config::get('URL') . 'car/expiries/' . $this->car_id; ?>" title="<?= _('INSURANCE'); ?>">
		<i class="icon-umbrella"> </i><?= $dates['INSURANCE'] ?></li>
	<?php }; if ($visibility['INSPECTION'] !== 'NO') { ?>
<li><a class="<?= $styles['INSPECTION'] ?>" href="<?= Config::get('URL') . 'car/expiries/' . $this->car_id; ?>" title="<?= _('INSPECTION'); ?>">
		<i class="icon-check"> </i><?= $dates['INSPECTION'] ?></li>
	<?php }; if ($visibility['TAXES'] !== 'NO') { ?>
<li><a class="<?= $styles['TAXES'] ?>" href="<?= Config::get('URL') . 'car/expiries/' . $this->car_id; ?>" title="<?= _('TAXES'); ?>">
		<i class="icon-credit-card"> </i><?= $dates['TAXES'] ?></li>
	<?php }; if ($visibility['OIL'] !== 'NO') {
		$readout = $dates['OIL']; $color = $styles['OIL'];
		if ( $odos['OIL'] !== '?' ) {	$readout = $odos['OIL'].' '.$units->user_distance; $color = $odo_styles['OIL'];	};
		if ($dates['OIL'] < 1) { $readout = $dates['OIL']; $color = $styles['OIL']; }
		?>
<li><a class="<?= $color ?>" href="<?= Config::get('URL') . 'car/expiries/' . $this->car_id; ?>" title="<?= _('OIL_CHANGE'); ?>">
		<i class="icon-oil-change"> </i><?= $readout ?></li>
	<?php }; if ($visibility['TIMING_BELT'] !== 'NO') {
		$readout = $dates['TIMING_BELT']; $color = $styles['TIMING_BELT'];
		if ( $odos['TIMING_BELT'] !== '?' ) {	$readout = $odos['TIMING_BELT'].' '.$units->user_distance; $color = $odo_styles['TIMING_BELT'];	};
		if ($dates['TIMING_BELT'] < 1) { $readout = $dates['TIMING_BELT']; $color = $styles['TIMING_BELT']; }
		
		?>
<li><a class="<?= $color; ?>" href="<?= Config::get('URL') . 'car/expiries/' . $this->car_id; ?>" title="<?= _('TIMING_BELT'); ?>">
		<i class="icon-timing-belt"> </i><?= $readout; ?></li>
	<?php };	?>
                            </ul>