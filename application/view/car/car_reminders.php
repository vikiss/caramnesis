<?php if ($this->car) { include('car_data_prep.php');    }    ?>
<div class="container">
                <div id="return" class="right bg-darken-4 center p2"><a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a></div>
                <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
                <h1><?= $car_name; ?></h1>
             <div class="clearfix">
                        <h2><?= _('REMINDERS'); ?></h2>
<?php
// subset of message/reminders for a single car

$table_data = array();

if ($reminders = $this->reminders){
    foreach ($reminders AS $reminder) {
                $time = $reminder->time;
                $microtime = $reminder->microtime;
                if (!$microtime) $microtime = 0; 
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
                    'reminder_time' => ReminderModel::formatDate($time),
                    'reminder_content' => $reminder->content,
                    'reminder_link' => Config::get('URL').'message/reminder/'.$car_id.'/'.$time.'/'.$microtime.'/'.$msgstatus, //if reminder is active (msgstatus = Q), we know we have to decrement active reminders count in single reminder page
                );
            }
    
}

if (count($table_data) > 0) {
    
  //  krsort($table_data); //sort ascending by time normally, descending if looking at all events, incl. past
  //no longer necessary, done in database
?>    
    <table  class="striped"><tbody>
             <tr>
                        <th></th>
                        <th><?= _('REMINDER_TIME'); ?></th>
                        <th><?= _('REMINDER_CONTENT'); ?></th>
            </tr>
<?php
    foreach ($table_data as $row) {
?>
             <tr class="msgstatus-<?= $row['msgstatus']; ?>">
                        <td><?= $row['icon']; ?></td>
                        <td><a href="<?= $row['reminder_link']; ?>" title="<?= _('DISMISS_REMINDER'); ?>"><?= $row['reminder_time']; ?></a></td>
                        <td><a href="<?= $row['reminder_link']; ?>" title="<?= _('DISMISS_REMINDER'); ?>"><?= $row['reminder_content']; ?></a></td>
             </tr>   
<?php
    }
    
?>
    
    </tbody></table>
   
<?php }; ?>
             </div></div>