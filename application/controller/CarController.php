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
            'car' => CarModel::getCar($car_id), 'events' => CarModel::getEvents($car_id)
        ));       } else Redirect::to('index/index'); 
    }
    
    
      public function new_car()
    {
        $this->View->render('car/new_car');
    }
    
      public function pic_upl()
    {
        $this->View->renderWithoutHeaderAndFooter('car/pic_upl');
    }
    
     public function image($image_id)
    {    if ($image_id) {
        $this->View->renderWithoutHeaderAndFooter('car/image', array('image' => $image_id));
    }}
    
    
    public function create_car()
    {
        CarModel::createCar(Request::post('car_name'), Request::post('car_make'), Request::post('car_model'), Request::post('car_vin'), Request::post('car_plates'));
        Redirect::to('index/index');
    }
    
    
     public function create_event()
    {
        CarModel::createEvent(array('car_id' => Request::post('car_id'), 'event_content' => Request::post('event_content'), 'event_type' => Request::post('event_type'), 'event_date' => Request::post('event_date'), 'event_odo' => Request::post('event_odo'), 'images' => Request::post('user_images') ));
        Redirect::to('car/index/'.Request::post('car_id'));
    }

    
    public function edit_event($event_id)
    { 
        $this->View->render('car/edit_event', array(
            'event' => CarModel::getEvent($event_id)
        ));
    }

    
    public function eventEditSave()
    {  
        CarModel::editEvent(array('event_content' => Request::post('event_content'), 'event_type' => Request::post('event_type'), 'car_id' => Request::post('car_id'), 'event_time' => Request::post('event_time'), 'event_microtime' => Request::post('event_microtime') ));
        Redirect::to('car/index/'.Request::post('car_id'));
    }
    
     public function eventDelete()
    {  
        CarModel::deleteEvent(array('car_id' => Request::post('car_id'), 'event_time' => Request::post('event_time'), 'event_microtime' => Request::post('event_microtime') ));
        Redirect::to('car/index/'.Request::post('car_id'));
    }
        

}
                 
                          