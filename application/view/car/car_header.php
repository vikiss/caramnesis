<div class="flex flowed-flex p1">
    <div class="pic120width">
        <?php if ($image_out) {  ?>
               <a id="heroimg-link" href="">
               <a href="<?= $image_out; ?>" data-index="1" class="pswpitem">
                      <div class="pic120width bg-white relative z1"  style="background-image: url(<?= $image_out; ?>/120)"><?= $image_meta; ?></div>
               </a>
        <?php } else { ?>
                      <div class="pic120width bg-white relative center bg-kclite " >
                             &nbsp;<br />&nbsp;<br/>
                             <a class="py4" href="<?= Config::get('URL') . 'car/edit_car_pictures/' . $car_id; ?>" title="<?= _('CAR_PICTURES'); ?>">
                                 <i class="icon-camera"> </i>
                             </a>
                      </div>
        <?php }; ?>
        <?php //<div class="mt2 hide" id="carindex_my_car_list"></div> ?>



    </div>
    <div class="">
        <div class="bold"><?= $thisowner.$car_name; ?>
            <div class="right"><a href="<?= Config::get('URL') . 'car/edit_car_id/' . $car_id; ?>" title="<?= _('CAR_IDENTIFICATION'); ?>">
                <i class="icon-pencil"> </i></a></div>
        </div>
        <table class="cardatatable">
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
                <td><?= $row->car_year; ?></td>
            </tr>
            <tr>
                <th><?= _("NEWCAR_VIN"); ?></th>
                <td><?= $car_vin; ?></td>
            </tr>
            <tr>
                <th><?= _("NEWCAR_PLATES"); ?></th>
                <td><?= $car_plates; ?></td>
            </tr>
        <?php if ($odometer) { ?>
            <tr>
                <th><?= _("EVENT_ODO"); ?></th>
                <td style="font-weight: 100;"><div class="mono smallish pointer" id="odo_dlg_opnr"><?= $odometer; ?></div></td>
            </tr>
        <?php
        }; if (is_array($this->car_items)) {
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
    <div class="">
        <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/attribute_overview/' . $car_id; ?>"><i class="icon-th-list"> </i><?= _('CAR_ATTRIBUTES'); ?></a></div>
        <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/edit_car_access/' . $car_id; ?>" <?php if ($public_access) echo 'class="green"'; ?>><i class="icon-users"> </i><?= _('CAR_PERMISSIONS'); ?></a></div>
        <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/authorised_users/' . $car_id; ?>"><i class="icon-wrench"> </i><?= _('CAR_AUTHORISED_USERS'); ?></a></div>
        <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/expiries/' . $car_id; ?>"><i class="icon-oil-change"> </i><?= _('RECURRING_EVENTS'); ?></a></div>
        <div class="smallish mt1"><a href="#" class="new_event_todo_opener"><i class="icon-wrench"> </i><?= _('TODO_LIST_HEADER'); ?></a></div>
         <?php if ($outstanding) {
             print '<div class=""><ul class="smallish list-reset">';
               foreach ($outstanding as $key => $value) {
        $tstamp = explode(':', $key);
        $link_event = urlencode(serialize(array('c' => $car_id, 't' => $tstamp[0], 'm' => $tstamp[1])));
        $link_event = Config::get('URL') . 'car/event/' . $link_event;
        print '<li><a href='.$link_event.'>'.CarModel::trunc($value, 5).'</a></li>';
               }
        print '</ul></div>';
           } ?>
        <div class="smallish mt1"><a href="<?= Config::get('URL') . 'car/car_reminders/' . $car_id; ?>"><i class="icon-bell-alt"> </i><?= _('REMINDERS'); ?></a></div>
        <div class="small"><?= $reminders; ?></div>

    </div>
</div>
<?php
print "
<script>
var itemclass = '.pswpitem';
var items = [$script];
</script>";
 ?>
<script src="<?php echo Config::get('URL'); ?>js/pswipe.js"></script>
