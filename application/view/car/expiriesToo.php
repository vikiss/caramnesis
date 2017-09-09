<div class="container">
  <div class="box">
    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
  </div>
  <div class="clearfix">
    <div class="md-col md-col-3 carcol">
      <?php  include 'carrow.php'; ?>
    </div>
    <div class="md-col md-col-9 ">
    <div class="right response"> </div>
<?php
$defaults = Config::get('DEFAULT_INTERVALS');
$units = $this->units;
$oil_interval = ($units->user_distance == 'km') ? $defaults['oil-km'] : $defaults['oil-miles'];
$distr_interval = ($units->user_distance == 'km') ? $defaults['distr-km'] : $defaults['distr-miles'];
$intervals = $this->intervals;
if (is_array($intervals)) {
    if (array_key_exists( 'oil_interval', $intervals )) { $oil_interval = $intervals['oil_interval']; };
    if (array_key_exists( 'distr_interval', $intervals )) { $distr_interval = $intervals['distr_interval']; };
};

$next_oil_change = ''; $next_distr_change = '';
$last_change = false;
$remaining_to_oil_change = false;


$structure = $this->structure;
$saved = $this->expiries;
if (is_array($saved))
{
    if (array_key_exists('OIL', $saved))
    {
    $last_change = intval($saved['OIL']->odo);
    $next_oil_change = intval($last_change) + intval($oil_interval);
    $remaining_to_oil_change = intval($next_oil_change - $odo);
    };

    if (array_key_exists('TIMING_BELT', $saved))
    {
    $last_change = intval($saved['TIMING_BELT']->odo);
    $next_distr_change = intval($last_change) + intval($distr_interval);
    };
} ;
$i = 1;
foreach ($structure as $title => $entries) {
$saved_chapter = false;
if ($saved) {if (array_key_exists($title, $saved)) {$saved_chapter = $saved[$title]; }}
?>
     <div class="md-col md-col-6">
         <h3><?= _($title); ?></h3>
         <input type="checkbox" class="expchk"
         data-chapter = "<?= $title; ?>"
         value="yes" <?php if ( ($saved_chapter) &&  ($saved_chapter->status == 'V'))
         echo ' checked'; ?> /><?= _('SHOW_EXPIRY_STATUS'); ?>
<?php
    if ($title == 'OIL') { ?>
        <?php if ($remaining_to_oil_change) { ?>
        <div class="small"><?= _('REMAINING_TO_OIL_CHANGE'); ?></div>
        <div class = "car-meta-txt small-field <?php if ($remaining_to_oil_change < 0) echo 'bg-red white bold'; else echo 'bg-kcms'; ?> col-6"
        id = "remaining-to-oil-change"><?= $remaining_to_oil_change; ?></div>
        <?php } ?>
        <div class="small"><?= _('CHANGE_INTERVAL'); ?></div>
        <input type = "text" class = "car-meta-txt small-field stealthfield col-6"
        pattern="\d*" data-key = "oil_interval"
        id = "oil-change-interval" value = "<?= $oil_interval; ?>" />
        <?php if ($next_oil_change) { ?>
        <div class="small"><?= _('NEXT_OIL_CHANGE'); ?></div>
        <div class = "car-meta-txt small-field bg-kcms col-6"
        id = "next-oil-change"><?= $next_oil_change; ?></div>
        <?php } ?>
        <a href="<?= Config::get('URL'); ?>/car/new_event/<?= $car_id; ?>/TAG_MAINTENANCE"><i class="icon-oil-change"> </i> <?= _('ENTER_NEW_OIL_CHANGE'); ?></a>

<?php  };

    if ($title == 'TIMING_BELT') { ?>
        <div class="small"><?= _('CHANGE_INTERVAL'); ?></div>
        <input type = "text" class = "car-meta-txt small-field stealthfield col-6"
        pattern="\d*" data-key = "distr_interval"
        id = "distr-change-interval" value = "<?= $distr_interval; ?>" />
        <?php if ($next_distr_change) { ?>
        <div class="small"><?= _('NEXT_DISTR_CHANGE'); ?></div>
        <div class = "car-meta-txt small-field bg-kcms col-6"
        id = "next-distr-change"><?= $next_distr_change; ?></div>
        <?php }
        };

     foreach ($entries as $entryname => $entry) {
         if ($title !== 'OIL') {

       switch($entry)
        {
        case 'expiration':
?>
        <div class="small"><?= _($entryname); ?></div>
        <input type = "text" class = "exptxt small-field stealthfield expiry-date col-6"
        data-chapter = "<?= $title; ?>" data-entry = "<?= $entry; ?>"
        id = "expitem-<?= $i; ?>" data-validate = "text" value = "<?php if ($saved_chapter) {echo date('Y-m-d', $saved_chapter->expiration);}; ?>" />
<?php
        break;

        case '---prev_expiration': //nuimam kol kas prev_expiration, nereikia jo rodyti
?>
        <div class="small"><?= _($entryname); ?></div>
        <div class = "col-6"
        data-chapter = "<?= $title; ?>" data-entry = "<?= $entry; ?>"
        id = "expitem-<?= $i; ?>" ><?= $saved[$title]->prev_expiration; ?></div>
<?php
        break;

        case 'description':
?>
        <div class="small"><?= _($entryname); ?></div>
        <textarea class = "exptxt p0 stealthfield col-6 autoheight"
        data-chapter = "<?= $title; ?>" data-entry = "<?= $entry; ?>"
        id = "expitem-<?= $i; ?>" data-validate = "text"><?php if ($saved_chapter) {echo $saved_chapter->description;}; ?></textarea>
<?php
        break;

        case 'reference':
?>
        <div class="small"><?= _($entryname); ?></div>
        <input type = "text" class = "exptxt small-field stealthfield  col-6"
        data-chapter = "<?= $title; ?>" data-entry = "<?= $entry; ?>"
        id = "expitem-<?= $i; ?>" data-validate = "text" value = "<?php if ($saved_chapter) {echo $saved_chapter->reference;}; ?>" />
<?php
        break;

        case 'odo':
?>
        <div class="small"><?php if ($title == 'OIL') {echo _('LAST_OIL_CHANGE'); } elseif ($title == 'TIMING_BELT') {echo _('LAST_DISTR_CHANGE'); } else {echo _($entryname);} ?></div>
        <input type = "text" pattern="\d*" class = "<?php if ($title == 'OIL') {echo 'exptxtsp oilchange'; } elseif ($title == 'TIMING_BELT') {echo 'exptxtsp distrbelt'; } else {echo 'exptxt';} ?> small-field stealthfield  col-6"
        data-chapter = "<?= $title; ?>" data-entry = "<?= $entry; ?>"
        id = "expitem-<?= $i; ?>" data-validate = "int" value = "<?php if ($saved_chapter) {echo $saved_chapter->odo;}; ?>" />
<?php
        break;

        case 'prev_odo':
          if (($saved_chapter) && ($saved_chapter->prev_odo)) {
?>
        <div class="small"><?= _($entryname); ?></div>
        <div class = "col-6"
        data-chapter = "<?= $title; ?>" data-entry = "<?= $entry; ?>"
        id = "expitem-<?= $i; ?>"><?= $saved_chapter->prev_odo; ?></div>
<?php
          }
        break;
        }
     $i++;
 } }
     ?>
    </div>
<?php
}

$return_to = 'expiries';
include('odo_dlg.php');


  ?>
  <input type="hidden" name="current_odo" id="current_odo" value = "<?= $odo; ?>" />
  <input type="hidden" name="saved_oil_interval" id="saved_oil_interval" value = "<?= $oil_interval; ?>" />
  <input type="hidden" name="saved_distr_interval" id="saved_distr_interval" value = "<?= $distr_interval; ?>" />
    </div>
  </div>
</div>
