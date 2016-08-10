<?php if ($this->car) {
 include('car_data_prep.php');
 if ($this->public_access) {$public_access=true;} else {$public_access=false;}
                      $selectlist='';
                            foreach ($this->makes as $make) {
                                $selectlist.='<div class="left mb1 mr1 px1 black bg-kclite field';
                                if ($make->rank == 'B') {$selectlist.=' hide';}
                                $selectlist.='"><a class="nwmake" data-make-id="'.$make->id.'" href="'.$make->make.'">'.$make->make.'</a></div>';
                                                            }
                                $selectlist.='<button id="show-all-makes" class="btn btn-primary mb1 mt1 black bg-kcms">'._('ALL_MAKES').'</button>';                                 
         ?>
<div class="container">
    <?php $this->renderFeedbackMessages(); ?>
    <h1><?= $car_name.' / '._("EDIT_CAR"); ?></h1>
<div class="box">
<form method="post" action="<?php echo Config::get('URL');?>car/save_car">
<?php if ($this->user_to_auth) { ?>
<input type="hidden" name="user_to_auth" id = "user_to_auth" value="<?= $this->user_to_auth; ?>" />
<?php }; ?>
<div id="accordion">
<h3><?= _('CAR_IDENTIFICATION'); ?></h3>  
<div>
    <?php  include ('edit_car_id.php');?>          
  </div><!-- #car-id -->
<h3><?= _('CAR_ATTRIBUTES'); ?></h3>  
  <div>
    <?php include ('edit_car_data_bit.php');?>
  </div> <!-- #car-attribs -->
<h3><?= _('CAR_PERMISSIONS'); ?></h3>  
    <div id="car-access">
     <?php include ('edit_car_access.php');?>           
  </div> <!-- #access_tags -->
<h3><?= _('CAR_AUTHORISED_USERS'); ?></h3>  
    <div>
     <?php include ('authorised_users.php');?>           
  </div>  
</div> <!-- #accordion -->
<input type="submit" id="start-upload" class="btn btn-primary mb1 mt1 black bg-kcms right" value='<?php echo _("SAVE"); ?>' autocomplete="off" />
<a href= "<?= Config::get('URL'); ?>car/index/<?= $car_id; ?>" class="btn btn-primary mb1 mt1 black bg-kcms left"><?= _("RETURN"); ?></a>
</form>         

<?php } else { ?>
            <p><?= _("CAR_DOES_NOT_EXIST"); ?></p>
<?php } ?>
        
    </div> <!-- .box -->
</div> <!-- .container -->
            