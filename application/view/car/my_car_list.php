<p class="bold m0"><?= _('MENU_MY_CARS'); ?></p>
<ul class="smallish list-reset"><?php
foreach ($this->my_cars as $my_car) {
    $id = (array) $my_car['id'];
    $link =  Config::get('URL') . 'car/index/' . $id['uuid'];
    print '<li><a href="'.$link.'" title="'.$my_car['car_name'].'">'.$my_car['car_name'].'</a></li>';
    }
?></ul>

<?php if (is_array($this->others_cars)) { ?>
<p class="bold m0"><?= _('OTHER_PEOPLES_CARS'); ?></p>
<ul class="smallish list-reset"><?php
foreach ($this->others_cars as $others_car) {
    $others_car = (array)$others_car;
    $car=CarModel::getCar($others_car['car']);
    $car = $car[0];
    $id = (array) $car['id'];
    $owner = (array) $car['owner'];         
    $link =  Config::get('URL') . 'car/index/' . $id['uuid'];
    print '<li><a href="'.$link.'" title="'.UserModel::getUserNameByUUid($owner['uuid']).': '.$car['car_name'].'">'.$car['car_name'].'</a></li>';
    }
?></ul>
<?php }; ?>