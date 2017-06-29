 <?php if ($this->event) { // rest in header template include  ?>
<div class="container">
    <div class="box">

<div class="absolute left-0 top-48 display-hide bg-darken-4 left-arrow py2 z4" id="previous-event-link"><a href="#"><i class="icon-left-open white"> </i></a></div>
<div class="absolute right-0 top-48 display-hide bg-darken-4 right-arrow py2 z4" id="next-event-link"><a href="#"><i class="icon-right-open white"> </i></a></div>
<div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'view/car/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>


<div id="container" class="clearfix">

    <div class="py2 clearfix">
    <a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= $car_name; ?>"><?php
    if ($thisowner) { echo $thisowner.': '; } ?><strong><?= $car_name; ?></strong></a>
      <a href="#" class="jqtooltip" title="<?= $entrytime.$oldversions; ?>"><?= strftime('%x', $event_time); ?></a><span class="smallish"></span>
        <a href="<?= Config::get('URL') . 'car/edit_event/' . $event_id; ?>" title="<?= _("EDIT"); ?>"><i class="icon-edit"> </i></a>
    <div class="inline "><?= $event_types_out; ?></div>
    </div>

     <div class=" ">
        <?php if ($initial_img) { ?>
        <div id="heroimg-container" class="relative "><a id="heroimg-link" href="<?= $initial_img; ?>"><img src="<?= $initial_img; ?>"  id="heroimg"  /></a>
            <div id="heroimgcenter" class="absolute abscenter"></div>
        </div>
        <?php }; ?>
    <div id="picstrip"><?= $picstrip_out; ?></div>
    <div id="piccount" class="hide"><?= count($event_images); ?></div>
    </div>

    <div class=" ">


        <?php if($row->event_odo) { ?>
        <div class="inline"><?= $row->event_odo.' '.$units->user_distance; ?></div>
        <?php } ?>
        <?php if(intval($event_data['amount']) > 0) { ?>
        <div class="inline"><?= $event_data['amount'].' '._('CURRENCY_'.$units->user_currency); ?></div>
        <?php } ?>
        <div class=" "><?= nl2br($event_content); ?></div>
    </div>

             <div id="shareBtn" class="btn btn-success clearfix"><?= _('SHARE'); ?></div>
<script>
document.getElementById('shareBtn').onclick = function() {
  FB.ui({
    method: 'share',
    display: 'popup',
    href: '<?= Config::get('URL').$filename.'/'.$event_id; ?>',
  }, function(response){});
}
</script>




</div>
        <?php } else { ?>
            <p>This event does not even exist.</p>
        <?php } ?>
    </div>
</div>
<?php include 'fsgallery.php'; ?>

<script>
/* <![CDATA[ */
var event_id = '<?= $event_id; ?>'; var event_car_id = '<?= $car_id; ?>';
/* ]]> */
</script>
