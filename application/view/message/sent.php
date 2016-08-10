<div class="container">
                <div class="box"><?php $this->renderFeedbackMessages(); ?></div>
             <div class="clearfix">
                <h2><?= _('MESSAGE_SENT_MESSAGES'); ?></h2>
            <table class="striped"><tbody>
            
<?php                             if ($this->messages) { ?>
<tr>
                        <th></th>
                        <th><?= _('MESSAGE_RECIPIENT'); ?></th>
                        <th><?= _('MESSAGE_TIME'); ?></th>
                        <th><?= _('MESSAGE_SUBJECT'); ?></th>
            </tr>
            
            <?php
            foreach ($this->messages as $row) {
             $message_time = (array) $row['system.dateof(time)'];
             $timeuuid = (array) $row['time'];
             $message_link = Config::get('URL').'message/single/'.$timeuuid['uuid'].'/'.$row['recipient'];
                        ?>

<tr class="msgstatus-<?= $row['status']; ?>">
                <td><?php
            if ($row['status'] == 'R') {echo '<i class ="icon-mail"> </i>';}
            if ($row['status'] == 'Q') {echo '<i class ="icon-mail-alt"> </i>';}
                ?></td>
            <td><a href="<?= $message_link; ?>"><?= UserModel::getUserNameByUUid($row['recipient']); ?></a></td>
            <td><a href="<?= $message_link; ?>"><?= MessageModel::formatDate($message_time['seconds']);  ?></a></td>
            <td><a href="<?= $message_link; ?>"><?= $row['subject']; ?></a></td>
</tr>

<?php 
            }      } ?>
                       
</tbody></table>
            <div>
                        <a href="<?= Config::get('URL') . 'message/new_message' ?>" class="h5 btn btn-primary mt1 black bg-kcms "><?php echo _("MESSAGE_NEW_MESSAGE"); ?></a>
            </div>
            <div>
                        <a href="<?= Config::get('URL') . 'message/index' ?>" class="h5 btn btn-primary mt1 black bg-kcms "><?php echo _("MESSAGE_INBOX"); ?></a>
            </div>
             </div>
</div>