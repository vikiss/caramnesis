<div class="container"><?php  //var prep moved into header include to get fb graph api values into header ?>
<div class="box"><?php $this->renderFeedbackMessages(); ?></div>
<div class="clearfix">

    <div class="md-col md-col-5">
        <div class="clearfix relative mr2 mt2">
            <?php if (Session::userIsLoggedIn()) { ?><h3><?= $car_name ?></h3><?php }; ?>
            <?php if ($thisowner) { ?><div class=""><?= $thisowner ?></div><?php }; ?>
<hr />
    <table class="cardatatable mt2">
        <tr>
            <th><?= _("NEWCAR_MAKE"); ?></th>
            <td><?= $car_make; ?></td>
        </tr>
        <tr>
            <th><?= _("NEWCAR_MODEL"); ?></th>
            <td><?= $car_model; ?></td>
        </tr>
        <tr>
            <th><?= _("NEWCAR_VARIANT"); ?></th>
            <td><?= $car_variant; ?></td>
        </tr>
        <tr>
            <th><?= _("NEWCAR_YEAR"); ?></th>
            <td><?= $car_year; ?></td>
        </tr>
<?php if ((array_key_exists('allow_public_vin', $this->car_meta)) &&
    ($this->car_meta['allow_public_vin'] == 'yes')) {    ?>
        <tr>
            <th><?= _("NEWCAR_VIN"); ?></th>
            <td><?= $car_vin; ?></td>
        </tr>
<?php }; if ((array_key_exists('allow_public_plates', $this->car_meta)) &&
($this->car_meta['allow_public_plates'] == 'yes')) {   ?>
        <tr>
            <th><?= _("NEWCAR_PLATES"); ?></th>
            <td><?= $car_plates; ?></td>
        </tr>
<?php }; if ($odometer) { ?>
        <tr>
            <th><?= _("EVENT_ODO"); ?></th>
            <td style="font-weight: 100;"><?= $odometer; ?></td>
        </tr>
    <?php
}; if ($this->car_items) {
        foreach ($this->car_items as $key => $attribute)
        {
            if ($attribute['value'])
            {
                print '<tr><th>'._($key).'</th><td>';
                print _($attribute['value']);
                if ($attribute['unit']) print ' '._($attribute['unit']);
                print '</td></tr>';
            };
        }; };
    ?>
    </table>

    </div>
    <div class="mt2 mr2">
        <?php  if (!Session::userIsLoggedIn()) {  include ('joincaramnesis.php'); }; ?>
        <div id="shareBtn" class="mt2 bg-kclite rounded p1 center pointer"><i class = "icon-facebook-official"> </i> <?= _('SHARE'); ?></div>
    </div>
    <script>
    document.getElementById('shareBtn').onclick = function() {
      FB.ui({
        method: 'share',
        display: 'popup',
        href: '<?= Config::get('URL').$filename.'/'.$car_id; ?>',
      }, function(response){});
    };
    </script>
  </div>

  <div class="md-col md-col-7 ">
      <div class="clearfix">
    <?php
         if ($images)
    {
    $script =  '';
    $pic_dir = Config::get('CAR_IMAGE_PATH').$car_id.'/';
    $i = 1;
    foreach($images AS $image) {
    if (file_exists($pic_dir.$image)) {
    $is_pdf = ($fullsize = getimagesize ($pic_dir.$image)) ? false : true;
    }
    print '<div class="left mr2" data-number="'.$i.'" id="'.$image.'">';
    print '<a href="/car/image/'.$car_id.'/'.$image.'" data-index="'.$i.'" class="pswpitem"><img src="/view/image/'.$car_id.'/'.$image;
    if ($i > 1) print '/120';
    print '" /></a> ';
    print '</div>';
    if (!$is_pdf) {$script.="{
    src: '/view/image/$car_id/$image',
    w: {$fullsize[0]},
    h: {$fullsize[1]},
    msrc: '/car/image/$car_id/$image/120',
    },";};
    $i++;
    }
    print "
    <script>
    var itemclass = '.pswpitem';
    var items = [$script];
    </script>";
    ?>
    <script src="<?php echo Config::get('URL'); ?>js/pswipe.js"></script>
    <?php
    }
    ?>
    </div>


<?php
    if ($this->events)
    {
        print '<div class="py2"><h3>'._('EVENTS').'</h3>';
        print '<table class="eventlist"><tr><th></th><th>'.$units->user_distance.'</th><th></th><th>'._('CURRENCY_'.$units->user_currency).'</th><th></th></tr>';
        foreach ($this->events as $event)
        {

                displayEventUltraTerse($event, $car_id, $owner);

        }
        print '</table></div>';
    }
?>


</div>
</div>
</div>



<?php
        function displayEventUltraTerse($event, $car_id, $owner) {
 $event_types_present = array();

    $serialized_event_time =  CarModel::parsetimestamp($event->event_time);
     $event_time = $serialized_event_time['seconds'];
     $event_microtime = $serialized_event_time['microseconds'];
$images = unserialize($event->images);

    $event_id = urlencode(serialize(array('c' => $car_id, 't' => $event_time, 'm' => $event_microtime)));
                                         //car           //time               /microtime

     $entry_data = unserialize($event->event_data);


     $event_link = Config::get('URL') . 'view/event/' . $event_id;
      ?>

<tr>
        <td class="pl0"><a href="<?= $event_link; ?>"><?= strftime('%x', $event_time); ?></a></td>
        <td><a href="<?= $event_link; ?>"><?= $event->event_odo; ?></a></td>
        <td class="small"><div class="eventlisttxt"><a href="<?= $event_link; ?>"><?= $event->event_content; ?></a></div></td>
        <td><a href="<?= $event_link; ?>"><?= $entry_data['amount']; ?></a></td>
        <td class="pr0"><a href="<?= $event_link; ?>"><?php if ($images) print '<i class = "icon-camera-alt"> </i>'; ?></a></td>
  </tr>


        <?php
        return $event_types_present;
        } ?>
