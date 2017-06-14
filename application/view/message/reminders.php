<div class="container">
                <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
             <div class="clearfix">
                        <h2><?= _('REMINDERS'); ?></h2>
<?php
//list all reminders for each car of this user

$table_data = array();
$this->since_when ? $since_when = $this->since_when : $since_when = 172800; //172800 = 2*24*60*60 = 2 days back

if ($cars = $this->cars) {
    foreach ($cars as $car) {
        $car_id = $car->id;
        if ($reminders = ReminderModel::getReminders($car_id, $since_when)) {
            foreach ($reminders AS $reminder) {
              $time = $reminder->time;
              $microtime = $reminder->microtime;
                $msgstatus = 'R'; //bold events, Q for future, R for past to use message classes
                $icon = '<i class ="icon-bell"> </i>';
                if ($time >= time()) {
                    if (ReminderModel::checkReminderStatus($car_id, $time)) {
                            $msgstatus = 'Q';
                            $icon = '<i class ="icon-bell-alt"> </i>';
                            }
                    } 
                $table_data[$time] = array(
                    'icon' => $icon,
                    'msgstatus' => $msgstatus,
                    'car_name' => $car->car_name,
                    'reminder_time' => ReminderModel::formatDate($time),
                    'reminder_content' => $reminder->content,
                    'reminder_link' => Config::get('URL').'message/reminder/'.$car_id.'/'.$time.'/'.$microtime.'/'.$msgstatus, //if reminder is active (msgstatus = Q), we know we have to decrement active reminders count in single reminder page
                    'time' => $time,
                    'microtime' => $microtime,
                    'car_id' => $car_id,
                );
            }
        }
    }
}


if (count($table_data) > 0) {
    
    $this->since_when === 'all' ? krsort($table_data) : ksort($table_data); //sort ascending by time normally, descending if looking at all events, incl. past
?>    
    <table  class="striped"><tbody>
             <tr>
                        <th></th>
                        <th><?= _('CAR_NAME'); ?></th>
                        <th><?= _('REMINDER_TIME'); ?></th>
                        <th><?= _('REMINDER_CONTENT'); ?></th>
                        <th></th>
            </tr>
<?php
    foreach ($table_data as $row) {
?>
             <tr class="msgstatus-<?= $row['msgstatus']; ?>">
                        <td><?= $row['icon']; ?></td>
                        <td><a href="<?= $row['reminder_link']; ?>" class="jqtooltip" title="<?= _('DISMISS_REMINDER'); ?>"><?= $row['car_name']; ?></a></td>
                        <td><a href="<?= $row['reminder_link']; ?>" class="jqtooltip" title="<?= _('DISMISS_REMINDER'); ?>"><?= $row['reminder_time']; ?></a></td>
                        <td><a href="<?= $row['reminder_link']; ?>" class="jqtooltip" title="<?= _('DISMISS_REMINDER'); ?>"><?= $row['reminder_content']; ?></a></td>
                        <td><a href="#" class="jqtooltip deleteconfdlgopnr" data-car_id="<?= $row['car_id']; ?>" data-time="<?= $row['time']; ?>" data-microtime="<?= $row['microtime']; ?>"  title="<?= _('DELETE_REMINDER'); ?>"><i class ="icon-trash"> </i></a></td>
             </tr>   
<?php
    }
    
?>
    
    </tbody></table>
   
<?php
};

if ($this->since_when !== 'all') { ?>
<div><a href="<?= Config::get('URL') . 'message/reminders/all' ?>" class="h5 btn btn-primary mt1 black bg-kcms "><?= _("ALL_REMINDERS"); ?></a></div>
<?php }; ?>







        <div id="deleteconfdlg" class="center" title="<?= _('ARE_YOU_REALLY_REALLY_SURE'); ?>"><p><?= _('ARE_YOU_REALLY_REALLY_SURE'); ?></p>
                <form method="post" action="<?php echo Config::get('URL');?>message/delete_reminder">
                <input type="hidden" name="dlg_car_id" id="dlg_car_id" value = "" />
                <input type="hidden" name="dlg_time" id="dlg_time" value = "" />
                <input type="hidden" name="dlg_microtime" id="dlg_microtime" value = "" />
                <div class="btn mb1 mr1 px1 black bg-kclite " ><a class="close_dialog" href="#"><?= _('CANCEL'); ?></a></div>
                <input type="submit" class="btn mb1 mr1 px1 black bg-kclite" value="<?= _("DELETE"); ?>" autocomplete="off" />
                </form>
        </div>









             </div></div>