<?php
 $page = $this->saved_page[0];
 $images = unserialize($page->images);
 $car_id = $page->user_id; // picture folder
$location = explode(',', $page->location);
$showmap = (is_array($location)) ? true : false;
?>
<div class="container">
<div class="box"><?php $this->renderFeedbackMessages(); ?></div>
<div class="mt1 md-col md-col-5">
<h1><?= $page->title; ?></h1>
<p><?= $page->description; ?></p>
<p class="py2 smallish"><?= $page->contact; ?></p>
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
<a href = "/profile/edit" class="smallish"><?= _('EDIT'); ?></a>
</div>

<div class="mt1 md-col md-col-7">
<div class="clearfix ml2">
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
print '<a href="/car/image/'.$car_id.'/'.$image.'" data-index="'.$i.'" class="pswpitem"><img src="/car/image/'.$car_id.'/'.$image;
if ($i > 1) print '/120';
print '" /></a> ';
print '</div>';
if (!$is_pdf) {$script.="{
src: '/car/image/$car_id/$image',
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

</div>
</div>
