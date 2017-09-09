<?php     $car_id = $this->car_id; ?>
<div class="container">
    <div id="return" class="right bg-darken-4 center p2">
        <a href="<?= Config::get('URL') . 'car/index/' . $car_id; ?>" title="<?= _('RETURN'); ?>"><i class="icon-cancel white"> </i></a>
    </div>
    <h1><?= _("NEW_RECORD"); ?></h1>
    <div class="box">
<?php
    $this->renderFeedbackMessages();
    $tags = Config::get('AVAILABLE_TAGS');
    $units = $this->units;
    $owner = $this->owner;
    $editing = false; //editing or creating an event
    include('universal_event_form.php');
?>
    </div>
</div>
