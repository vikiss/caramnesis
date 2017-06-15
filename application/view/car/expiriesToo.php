<div class="container">
  <div class="box">
    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
  </div>
  <div class="clearfix">
    <div class="md-col md-col-3 carcol">
        [...]
      <?php  include 'carcol.php'; ?>
    </div>
    <div class="md-col md-col-9 ">
    <div class="right response"> </div>
<?php

$structure = $this->structure;
$saved = $this->expiries;
$i = 1;
foreach ($structure as $title => $entries) {
$saved_chapter = false;
if ($saved) {if (array_key_exists($title, $saved)) {$saved_chapter = $saved[$title]; }}
?>
     <div class="md-col md-col-6"><h3><?= _($title); ?></h3>
<?php
     foreach ($entries as $entryname => $entry) {

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
        <div class="small"><?= _($entryname); ?></div>
        <input type = "text" pattern="\d*" class = "exptxt small-field stealthfield  col-6"
        data-chapter = "<?= $title; ?>" data-entry = "<?= $entry; ?>"
        id = "expitem-<?= $i; ?>" data-validate = "int" value = "<?php if ($saved_chapter) {echo $saved_chapter->odo;}; ?>" />
<?php
        break;

        case 'prev_odo':
          if ($saved_chapter) {
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
     }
     ?>
    </div>
<?php
}


/*
$data = array(
  'car_id' => $car_id,
  'expiry' => 'whatever',
  'expiration' => 123456,
  'prev_expiration' => 654987,
  'description' => 'description here',
  'reference' => 'reference here',
  'ord' => 77,
);

ExpiriesModel::writeExpiry($data);
*/

  ?>
    </div>
  </div>
</div>
