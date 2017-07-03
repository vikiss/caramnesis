<?php

class ViewController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function car($car_id)
    {
    if (CarModel::is_valid_car_id($car_id)) {


      if (ViewModel::getCarAccessByCarId($car_id, 10)) {

        $this->View->render('view/car', array(
            'car' => CarModel::getCar($car_id),
            'events' => ViewModel::getEvents($car_id),
            //'car_data_bits' => Config::get('CAR_DATA_BITS'),
            //'car_data' => CarModel::getCarXmlData($car_id),
            //'public_events' => ViewModel::get_public_event_types($car_id),
            //'tags' => Config::get('AVAILABLE_TAGS'),
            'units' => UserModel::getUserUnits(CarModel::getCarOwner($car_id)),
            'car_items' => CarModel::readAttributes($car_id, 'TECHNICAL_DATA'),
            'car_meta' => CarModel::readCarMeta($car_id, array('allow_public_vin', 'allow_public_plates')),
            //'owner' => CarModel::getCarOwner($car_id)
            )
        );

                                                       } else {

        $this->View->render('view/private', array(
            'car' => CarModel::getCar($car_id)        ));
                                                       }




                 }

                 else Redirect::to('index/index');
    }




    public function event($event_id)
    {
        if ($event_array = @unserialize(urldecode($event_id)))
        {
        $owner = CarModel::getCarOwner($event_array['c']);
        $this->View->render('view/event', array(
            'event' => CarModel::getEvent($event_id),
            'units' => UserModel::getUserUnits($owner),
            'owner' => $owner,
        ));
        } else {
        header('HTTP/1.0 404 Not Found', true, 404);
        $this->View->render('error/404');
        }
    }




         public function image($car_id, $image_id, $size = 'full')
    {    if ($image_id) {
           if (ViewModel::getCarAccessByCarId($car_id, 10)) { //is this car public? we still need to check if the pic belongs to public event or public car profile
        $this->View->renderWithoutHeaderAndFooter('view/image', array('car' => $car_id, 'image' => $image_id, 'size' => $size));
          }
    }}

    public function imagepg($car_id, $image_id, $size = 'full')
    {    if ($image_id) {
            if (ViewModel::getCarAccessByCarId($car_id, 10)) { //is this car public?
        $this->View->renderWithoutHeaderAndFooter('view/imagepg', array('car' => $car_id, 'image' => $image_id, 'size' => $size));
            }
    }}





}

/**********************
public function attribute_chapter($car_id, $chapter)
{
 if (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) >= 80) {
  $this->View->render('car/attribute_chapter', array(
        'structural_items' => CarModel::getAttributeChapterItems($chapter),
        'car_items' => CarModel::readAttributes($car_id, $chapter),
        //'car_id' => $car_id,
        'car' => CarModel::getCar($car_id),
        'chapter' => $chapter,
      //  'owner' => CarModel::getCarOwner($car_id),
        'chapters' => CarModel::getAttributeChapters(),
    ));
}  else { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
        Redirect::to('index/index');
        }
}
*/
