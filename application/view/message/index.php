<div class="container">
                <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
             <div class="clearfix">
                        <h2><?= _('MESSAGE_INBOX'); ?></h2>
            <table  class="striped"><tbody>
            
<?php            if ($this->messages) { ?>
            <tr>
                        <th></th>
                        <th><?= _('MESSAGE_SENDER'); ?></th>
                        <th><?= _('MESSAGE_TIME'); ?></th>
                        <th><?= _('MESSAGE_SUBJECT'); ?></th>
            </tr>
            <?php
            
            $totalrows = array_pop($this->messages);
            $totalrows = (array) $totalrows; 
            $totalrows = intval($totalrows['FOUND_ROWS()']);
            //print $totalrows; 
            
            
            foreach ($this->messages as $row) {
            $serialized_message_time =  CarModel::parsetimestamp($row->time);
            $message_time = $serialized_message_time['seconds'];
            $message_microtime = $serialized_message_time['microseconds'];
     
             $timeuuid = $row->time;
             $message_link = Config::get('URL').'message/single/'.$timeuuid;
                        ?>

<tr class="msgstatus-<?= $row->status; ?>">
            <td><?php
            if ($row->status == 'R') {echo '<i class ="icon-mail"> </i>';}
            if ($row->status == 'Q') {echo '<i class ="icon-mail-alt"> </i>';}
                ?></td>
            <td><a href="<?= $message_link; ?>"><?= UserModel::getUserNameByUUid($row->sender); ?></a></td>
            <td><a href="<?= $message_link; ?>"><?= MessageModel::formatDate($message_time);  ?></a></td>
            <td><a href="<?= $message_link; ?>"><?= $row->subject; ?></a></td>
</tr>

<?php 
                       }}

                       ?>
                       
</tbody></table>
            <div>
                        <a href="<?= Config::get('URL') . 'message/new_message' ?>" class="h5 btn btn-primary mt1 black bg-kcms "><?php echo _("MESSAGE_NEW_MESSAGE"); ?></a>
            </div><div>
                        <a href="<?= Config::get('URL') . 'message/sent' ?>" class="h5 btn btn-primary mt1 black bg-kcms "><?php echo _("MESSAGE_SENT_MESSAGES"); ?></a>
            </div>
             </div>
</div>