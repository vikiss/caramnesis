<?php
 $page = $this->saved_page[0];
 $images = unserialize($page->images);
 $car_id = $page->user_id; // picture folder
$location = explode(',', $page->location);
$showmap = (is_array($location)) ? true : false;
?>
<div class="container">
<div class="box"><?php $this->renderFeedbackMessages(); ?></div>
<div class="mt1 md-col md-col-5 mr2">
<h1><?= $page->title; ?></h1>
<p><?= nl2br($page->description); ?></p>
<p class="py2 bold"><?= $page->contact; ?></p>

</div>

<div class="mt3 md-col md-col-5 ml2">
<div class="clearfix ">
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
print '<div class="left ';
if ($i > 1) print 'mr2';
print '" data-number="'.$i.'" id="'.$image.'">';
print '<a href="/view/image/'.$car_id.'/'.$image.'" data-index="'.$i.'" class="pswpitem"><img src="/view/image/'.$car_id.'/'.$image;
if ($i > 1) print '/120';
print '" /></a> ';
print '</div>';
if (!$is_pdf) {$script.="{
src: '/view/image/$car_id/$image',
w: {$fullsize[0]},
h: {$fullsize[1]},
msrc: '/view/image/$car_id/$image/120',
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
<div class="mt4">
<?php if ($showmap) { ?>
    <div id="map" style="width: 100%; height: 300px;"></div>
       <script>
         function initMap() {
           var uluru = {lat: <?= $location[0]; ?>, lng: <?= $location[1]; ?>};
           var map = new google.maps.Map(document.getElementById('map'), {
             zoom: 14,
             center: uluru
           });
           var marker = new google.maps.Marker({
             position: uluru,
             map: map
           });
         }
       </script>
       <script async defer
       src="https://maps.googleapis.com/maps/api/js?key=<?= Config::get('GOOGLEMAPS_API_KEY'); ?>&callback=initMap">
       </script>
<?php } ?>
</div>

</div>
</div>
