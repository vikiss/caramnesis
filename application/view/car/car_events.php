<?php    // include('event_form.php');
?>
<div class=" ">
    <div id="event-types-present" class="smallish bold hide"><?= _("EVENT_TYPES"); ?>: <a href="#" class="smallish bold kcms mx1 evfltreset">[<?= _("ALL"); ?>]</a></div>
    <div class="mt1 p1 border mw480 ">
          <a href="<?= Config::get('URL') . 'car/new_event/' . $car_id; ?>" title="<?= _("NEW_RECORD"); ?>"><i class="icon-edit"> </i> <?= _("NEW_RECORD"); ?></a>
              <p class="smallish mt1"><?= _("NEW_RECORD_BLURB"); ?></p>
    </div>
<?php
    if ($this->events)
    {
        $event_no = 0;
        $event_types_present = array(); //list of event types present for filtering by type
        foreach ($this->events as $event)
        {
            $these_event_types = displayEventTerse($event, $car_id, $owner, $units, $event_no, $public_access);
            $event_no++;
            foreach ($these_event_types as $this_event_type)
            {
                if (!in_array($this_event_type, $event_types_present))
                {
                    $event_types_present[_($this_event_type)] = $this_event_type;
                }
            }
        }
    }
?>

<?php include 'fsgallery.php'; ?>

<?php if ((isset($event_types_present)) && (count($event_types_present) > 1)) { ?>
<script>
        var event_types_present = {<?php
        $first = true;
        foreach ($event_types_present AS $key => $value) {
                if ($first) {$first = false;} else {print ', ';}
                print '"'.$key.'": "'.$value.'"';
                };
        ?>};
</script>
<?php }; ?>

<?php
function displayEventTerse($event, $car_id, $owner, $units, $event_no, $public_access) {

$event_types_present = array();
$serialized_event_time =  CarModel::parsetimestamp($event->event_time);
$event_time = $serialized_event_time['seconds']; $event_microtime =
$serialized_event_time['microseconds'];
$serialized_entry_time =  CarModel::parsetimestamp($event->event_entered);
$entry_time =  $serialized_entry_time['seconds'];
$entry_microtime = $serialized_entry_time['microseconds'];

     if ($public_access)
     {
         if ($event->visibility == 'pub')
         {
             $eventclass = 'public-event'; $vispubclass = ''; $visprivclass = 'hide';
         } else {
             $eventclass = ''; $vispubclass = 'hide'; $visprivclass = '';
         }
     } else {
     $eventclass = ''; $vispubclass = ''; $visprivclass = '';
     };

$event_id = urlencode(serialize(array('c' => $car_id, 't' => $event_time,
    'm' => $event_microtime)));

$entry_data = unserialize($event->event_data);
$images = unserialize($event->images); $image_out=''; $image_meta='';
if ($images)
{
    if (CarModel::get_extension($images[0]) == 'pdf')
    {
        $image_meta.= '<div class="red-triangle absolute top-0 right-0"> </div> ';
        $image_meta.= '<div class="icon-file-pdf white absolute top-0 right-0"> </div> ';
    }

    $image_out = Config::get('URL') .'/car/image/'.$car_id.'/'.$images[0].'/480';
    if (count($images) > 1)
    {
        $image_meta.= '<div class="white bg-darken-4 absolute bold px1 bottom-0 right-0">+'.(count($images)-1).'</div> ';
    }
}

     $event_types = unserialize($event->event_type); $event_types_out = '';
     $event_types_tags = ''; if(is_array($event_types)) { foreach ($event_types
     AS $event_type) { $event_types_out.= '<a href="#"
     data-type="'.$event_type.'" class="smallish bold kcms mr1 evfilter
     ">['._($event_type).']</a>'; if (!in_array($event_type,
     $event_types_present)) { $event_types_present[] = $event_type;}; };
     $event_types_tags = implode(' ', $event_types); };

        $oldversions = '';      $entrytime = '';

     $event_link = Config::get('URL') . 'car/event/' . $event_id; ?>

<div class="mt1 p1 border mw480 bg-kcultralite event <?= $eventclass; ?> <?=
$event_types_tags; ?>" data-event="<?= $event_id; ?>"> <div class="clearfix
relative"> <div class=""> <div class="inline smallish bold"><?= strftime('%x',
$event_time); if ($entrytime or $oldversions) { ?> <a href="#" class="jqtooltip"
title="<?= $entrytime.$oldversions; ?>"><i class="icon-history"> </i></a> <?php };
?></div> <div class="inline"><?= $event_types_out; ?></div> <?php
if($event->event_odo) { ?><div class="inline small"><?= $event->event_odo.'
'.$units->user_distance; ?></div><?php }; ?> <?php if(($entry_data['amount']) &&
($entry_data['amount'] > 0)) { ?><div class="inline small"><?=
$entry_data['amount'].' '._('CURRENCY_'.$units->user_currency); ?></div><?php }; ?>
<div class="right"> <a href="<?= Config::get('URL') . 'car/edit_event/' .
$event_id; ?>" title="<?= _("EDIT"); ?>"><i class="icon-pencil"> </i></a> </div>
<?php if ($public_access) : ?> <div class="right visibility_setting_private <?=
$visprivclass; ?>" data-event="<?= $event_id; ?>"> <a href="#" title="<?=
_("MAKE_EVENT_PUBLIC"); ?>"><i class="icon-lock"> </i></a> </div> <div
class="right visibility_setting_public <?= $vispubclass; ?>" data-event="<?=
$event_id; ?>"> <a href="#" title="<?=_("MAKE_EVENT_PRIVATE"); ?>"><i
class="icon-lock-open-alt"> </i></a> </div> <?php endif; ?> </div> <a href="<?=
$event_link; ?>" title="<?= _("VIEW"); ?>"> <div class="relative"> <?php if
($image_out) { ?> <div class="relative pic480width"  style="background-image:
url(<?= $image_out; ?>)"><?= $image_meta; ?></div> <?php }; ?> <div class="mt1
smallish"><?= $event->event_content; ?></div> </div> </a> </div> </div>

        <?php return $event_types_present; } ?>










<input type="hidden" name="car_id" id="car_id" value = "<?= $car_id; ?>" />

</div>
