<?php

//iš expiriesToo

$defaults = Config::get('DEFAULT_INTERVALS');
$units = $this->units;
$current_odo = intval($this->odo);
$oil_interval = ($units->user_distance == 'km') ? $defaults['oil-km'] : $defaults['oil-miles'];
$distr_interval = ($units->user_distance == 'km') ? $defaults['distr-km'] : $defaults['distr-miles'];
$intervals = $this->intervals;
if (is_array($intervals)) {
    if (array_key_exists( 'oil_interval', $intervals )) { $oil_interval = $intervals['oil_interval']; };
    if (array_key_exists( 'distr_interval', $intervals )) { $distr_interval = $intervals['distr_interval']; };
};

$next_oil_change = ''; $next_distr_change = '';
$last_change = false;

$saved = $this->expiries;
if (is_array($saved))
{

foreach($saved as $name => $entry) {
        switch($name) {

            case 'OIL':
            $last_change = intval($entry->odo);
            $next_oil_change = intval($last_change) + intval($oil_interval);
            $remaining_oil = intval($next_oil_change - $current_odo);
            $percent_done = intval((($oil_interval - $remaining_oil) / $oil_interval) * 100);
            if ($percent_done > 100) {$percent_done = 100;}
            print thermometer($percent_done, 'icon-oil-change', _($name).' '.$remaining_oil.' '.$units->user_distance);
            break;

            case 'INSURANCE':
            $expires = $entry->expiration;
            //$year_before = $expires - 31536000; // (365 * 24 * 60 * 60)
            $time_remaining = $expires - time();
            $days_remaining = intval($time_remaining / 86400); //(24 * 60 * 60)
            $percent_done = intval(((31536000 - $time_remaining) / 31536000) * 100);
            if ($percent_done > 100) {$percent_done = 100;}
            print thermometer($percent_done, 'icon-umbrella', _($name).' '.$days_remaining.' '._('DAYS'));
            break;

            case 'TAXES':
            $expires = $entry->expiration;
            $time_remaining = $expires - time();
            $days_remaining = intval($time_remaining / 86400);
            $percent_done = intval(((31536000 - $time_remaining) / 31536000) * 100);
            if ($percent_done > 100) {$percent_done = 100;}
            print thermometer($percent_done, 'icon-credit-card', _($name).' '.$days_remaining.' '._('DAYS'));
            break;

            case 'INSPECTION':
            $expires = $entry->expiration;
            $time_remaining = $expires - time();
            $days_remaining = intval($time_remaining / 86400);
            $percent_done = intval(((31536000 - $time_remaining) / 31536000) * 100);
            if ($percent_done > 100) {$percent_done = 100;}
            print thermometer($percent_done, 'icon-check', _($name).' '.$days_remaining.' '._('DAYS'));
            break;

            case 'TIMING_BELT':
            $last_change = intval($entry->odo);
            $next_distr_change = intval($last_change) + intval($distr_interval);
            $remaining_distr = intval($next_distr_change - $current_odo);
            $percent_done = intval((($distr_interval - $remaining_distr) / $distr_interval) * 100);
            if ($percent_done > 100) {$percent_done = 100;}
            print thermometer($percent_done, 'icon-timing-belt', _($name).' '.$remaining_distr.' '.$units->user_distance);
            break;
        }
    }
} ;


function thermometer($percent, $icon, $text)
{
    $color = 'green';
    if ($percent > 80) {$color = 'orange';}
    if ($percent > 90) {$color = 'red';}
    $percent = intval(100 - intval($percent));  //invert so that 100% done means no bar, 0% done full color bar
    $output = '
        <div class="mt2"><div class="inline white mr2" style="background-color: '.$color.'"><i class="'.$icon.'"> </i></div> <span class="small">'.$text.'</span></div>
        <div style="width: 100%; height: 1em;" class="bg-silver mt05 center">';
    if ($percent == 0)   {$output.='<strong class="white bg-red px1">X</strong>';}
    $output.=
            '<div style="width: '.$percent.'%; background-color: '.$color.'; height: 1em;" > </div>
        </div>
    ';
    return $output;
}
