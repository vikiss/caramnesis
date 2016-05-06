<?php

class ViewController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function car($car_id)
    {
    if ($car_id) {
    
      if (ViewModel::getCarAccessByCarId($car_id, 10)) {
      
        $this->View->render('view/car', array(
            'car' => CarModel::getCar($car_id), 'events' => CarModel::getEvents($car_id), 'car_data_bits' => Config::get('CAR_DATA_BITS'), 'car_data' => CarModel::getCarData($car_id)
        ));      
        
                                                       } else {
                                                       
        $this->View->render('view/private', array(
            'car' => CarModel::getCar($car_id)        ));                                               
                                                       }
        
                 }         else Redirect::to('index/index'); 
    }
    
    public function event($car_id, $event_time)
    {
    if (($car_id) && ($event_time)) {
        $this->View->render('view/event', array(
            'car' => CarModel::getCar($car_id), 'events' => CarModel::getEvents($car_id)
        ));       } else Redirect::to('index/index'); 
    }
    
    
         public function image($user_id, $image_id, $size = 'full')
    {    if ($image_id) {
    if (ViewModel::getCarAccessByCarId(ViewModel::getCarIdByImg($image_id), 10)) { //is this public?
    
        $this->View->renderWithoutHeaderAndFooter('car/image', array('user' => $user_id, 'image' => $image_id, 'size' => $size));
        }
    }}
    
    public function imagepg($user_id, $image_id, $size = 'full')
    {    if ($image_id) {
    
    if (ViewModel::getCarAccessByCarId(ViewModel::getCarIdByImg($image_id), 10)) { //is this public?
      
    
        $this->View->renderWithoutHeaderAndFooter('view/imagepg', array('user' => $user_id, 'image' => $image_id, 'size' => $size));
        } 
    }}
    
   
    
        

}
                 
                          