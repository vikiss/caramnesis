<?php

class CarController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

    public function index($car_id)
    {
    if ($car_id) {
        $this->View->render('car/index', array(
            'car' => CarModel::getCar($car_id), 'events' => CarModel::getEvents($car_id), 'car_data_bits' => Config::get('CAR_DATA_BITS'), 'car_data' => CarModel::getCarData($car_id)
        ));       } else Redirect::to('index/index'); 
    }
    
    
      public function new_car()
    {
        $this->View->render('car/new_car', array(
            'makes' => CarModel::getCarMakeList('all')
        ));
    }
    
      public function pic_upl()
    {
        $this->View->renderWithoutHeaderAndFooter('car/pic_upl');
    }
    
     public function image($user_id, $image_id, $size = 'full')
    {    if ($image_id) {
        $this->View->renderWithoutHeaderAndFooter('car/image', array('user' => $user_id, 'image' => $image_id, 'size' => $size));
    }}
    
    public function imagepg($user_id, $image_id, $size = 'full')
    {    if ($image_id) {
        $this->View->renderWithoutHeaderAndFooter('car/imagepg', array('user' => $user_id, 'image' => $image_id, 'size' => $size));
    }}
    
    
    public function create_car()
    {
        CarModel::createCar(Request::post('car_name'), Request::post('car_make'), Request::post('car_model'), Request::post('car_vin'), Request::post('car_plates'));
        Redirect::to('index/index');
    }
    
    
    public function edit_car($car_id)
    {
      $this->View->render('car/edit_car', array(
            'car' => CarModel::getCar($car_id),
            'public_access' => ViewModel::getCarAccessByCarId($car_id, 10)  //level 10 - car publically visible
           // 'makes' => CarModel::getCarMakeList('all'), // better not allow to change the make after creating a new car 
           // 'car_data_bits' => Config::get('CAR_DATA_BITS')
        ));        
    }
    
    public function save_car()
    {
        CarModel::editCar(array(
          'car_id' => Request::post('car_id'),
          'car_name' => Request::post('car_name'),
          'car_make' => Request::post('car_make'),
          'car_model' =>Request::post('car_model'),
          'car_vin' => Request::post('car_vin'),
          'car_plates' => Request::post('car_plates'),
          'car_data' => Request::post('car_data'),
          'images' => Request::post('user_images'),
          'disable_car_access' => Request::post('disable_car_access'),
          'enable_car_access' => Request::post('enable_car_access'),
          'public_tags' => Request::post('tag_access')
          ));
        Redirect::to('car/index/'.Request::post('car_id'));
    }
    
    
    
     public function create_event()
    {
        CarModel::createEvent(array(
        'car_id' => Request::post('car_id'),
        'event_amount' => Request::post('event_amount'),
        'event_content' => Request::post('event_content'),
        'event_type' => Request::post('event_type'),
        'event_date' => Request::post('event_date'),
        'event_odo' => Request::post('event_odo'),
        'images' => Request::post('user_images') ));
        Redirect::to('car/index/'.Request::post('car_id'));
    }
    
     public function car_data_bits()  //loaded via ajax
    {
    CarModel::addNewCarBit(array(
       'new_car_data_bit' => Request::post('new_car_data_bit'),
          'new_car_data_val' => Request::post('new_car_data_val'),
          'car_id' => Request::post('car_id')
       ));
       $this->View->renderWithoutHeaderAndFooter('car/car_data_bits', array('car_data' => CarModel::getCarData(Request::post('car_id')), 'car_data_bits' => Config::get('CAR_DATA_BITS')));
        
    }


    
    public function edit_event($event_id)
    { 
        $this->View->render('car/edit_event', array(
            'event' => CarModel::getEvent($event_id)
        ));
    }

    
    public function eventEditSave()
    {  
        CarModel::editEvent(array(
        'event_amount' => Request::post('event_amount'),
        'event_content' => Request::post('event_content'),
        'event_type' => Request::post('event_type'),
        'car_id' => Request::post('car_id'),
        'event_time' => Request::post('event_time'),
        'event_microtime' => Request::post('event_microtime'),
        'event_date' => Request::post('event_date'), 
        'images' => Request::post('user_images'),
        'event_odo' => Request::post('event_odo'),
        'event_entered' => Request::post('event_entered'),
        'oldversions'  => Request::post('oldversions')
        ));
        CarModel::deleteEvent(array('c' => Request::post('car_id'), 't' => Request::post('event_time'), 'm' => Request::post('event_microtime'), 'source' => 'edit' ));
        Redirect::to('car/index/'.Request::post('car_id'));
    }
    
     public function eventDelete()
    {  
        CarModel::deleteEvent(array('c' => Request::post('car_id'), 't' => Request::post('event_time'), 'm' => Request::post('event_microtime'), 'source' => 'delete' ));
        Redirect::to('car/index/'.Request::post('car_id'));
    }
    
        

}
                 
                          