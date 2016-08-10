<?php

class CarController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

    public function index($car_id = '')
    {
    
    
    
    if ($car_id)  {
        IF (CarModel::checkAccessLevel($car_id, Session::get('user_uuid')) >= 80) {
        $this->View->render('car/index', array(
            'car' => CarModel::getCar($car_id), 'events' => CarModel::getEvents($car_id), 'car_data_bits' => Config::get('CAR_DATA_BITS'), 'car_data' => CarModel::getCarData($car_id),
            'units' => UserModel::getUserUnits(CarModel::getCarOwner($car_id))
            )); 
            }
            elseif (CarModel::getCarOwner($car_id) == Session::get('user_uuid')) { //new car viewed by the owner for the first time needs to get permission set
                CarModel::newAccessNode($car_id, 99, Session::get('user_uuid'));
                Redirect::to('car/index/'.$car_id);
            }
            else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); }
              } else Redirect::to('index/index'); 
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
        CarModel::createCar(
          Request::post('car_name'),
          Request::post('car_make'),
          Request::post('car_model'),
          Request::post('car_vin'),
          Request::post('car_plates'),
          Request::post('car_year'),
          Request::post('car_make_id'),
          Request::post('car_model_id'),
          Request::post('car_variant_id'),
          Request::post('car_variant')
                           );
        Redirect::to('index/index');
    }
    
    
    public function edit_car($car_id, $user_to_auth='') //user to auth - user who requested access to your car
    {
    
     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) > 80) {
    
      $this->View->render('car/edit_car', array(
            'car' => CarModel::getCar($car_id),
            'public_access' => ViewModel::getCarAccessByCarId($car_id, 10),  //level 10 - car publically visible
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
            'makes' => CarModel::getCarMakeList('all'), // better not allow to change the make after creating a new car 
            'car_data_bits' => Config::get('CAR_DATA_BITS'),
            'auth_users' => CarModel::getAuthUsrForCar($car_id, Session::get('user_uuid'), 50, 80),
            'user_to_auth' => $user_to_auth
        ));        
    } elseif (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) == 80)  {
         $this->View->render('car/edit_car_data_bit_limited', array(
            'car' => CarModel::getCar($car_id),
            'units' => UserModel::getUserUnits(CarModel::getCarOwner($car_id)),            
            'car_data_bits' => Config::get('CAR_DATA_BITS')

                                                            ));

    }    else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); } 
    
    }
        
        public function delete_car($car_id)
    {
    
     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) > 98) {
    
      CarModel::deleteCar($car_id);
      Redirect::to('index/index'); 
      
      
    }  else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION'));
            Redirect::to('index/index'); } 
    
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
          'car_year' => Request::post('car_year'),
          'car_make_id' => Request::post('car_make_id'),
          'car_model_id' => Request::post('car_model_id'),
          'car_variant_id' => Request::post('car_variant_id'),
          'car_variant' => Request::post('car_variant'),
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
    
      public function remove_car_data_bits()  //loaded via ajax
    {
    CarModel::removeCarBit(array(
       'bit_id' => Request::post('bit_id'),
       'car_id' => Request::post('car_id')
       ));
       $this->View->renderWithoutHeaderAndFooter('car/car_data_bits', array('car_data' => CarModel::getCarData(Request::post('car_id')), 'car_data_bits' => Config::get('CAR_DATA_BITS')));
        
    }


  public function edit_car_data_bits()  //loaded via ajax
    {
    CarModel::editCarBit(array(
       'bit_id' => Request::post('bit_id'),
       'key'   => Request::post('key'),
       'value'   => Request::post('value'),
       'car_id' => Request::post('car_id')
       ));
       $this->View->renderWithoutHeaderAndFooter('car/car_data_bits', array('car_data' => CarModel::getCarData(Request::post('car_id')), 'car_data_bits' => Config::get('CAR_DATA_BITS')));
        
    }

    
    public function edit_event($event_id)
    {
        $event_array = unserialize(urldecode($event_id));
        $this->View->render('car/edit_event', array(
            'event' => CarModel::getEvent($event_id),
            'units' => UserModel::getUserUnits(CarModel::getCarOwner($event_array['c']))
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
    
      public function model_list($make_id, $year)
    {
        $this->View->renderWithoutHeaderAndFooter('car/model_list', array(
            'models' => CarModel::getCarModelList($make_id, $year)
        ));
    }
    
    
      public function variant_list($model_id)
    {
        $this->View->renderWithoutHeaderAndFooter('car/variant_list', array(
            'variants' => CarModel::getCarVariantList($model_id)
        ));
    }
    
    
      public function neighboring_events($car_id, $event_id)
    {
        $this->View->renderWithoutHeaderAndFooter('car/neighboring_events', array(
            'events' => CarModel::getNeighboringEvents($car_id, $event_id)
        ));
    }
    
    public function new_proxy() {
        $cars=array(); $auth_car_list=array();
         if ($auth_cars = CarModel::getAuthCarsForUser(Session::get('user_uuid'), 80, 80))
         {
            foreach ($auth_cars as $auth_car)
                {
                    $auth_car = (array)$auth_car;
                    $cars[]=CarModel::getCar($auth_car['car']);
                    $auth_car_list[]=$auth_car['car'];
                }
         }
         $this->View->render('car/new_proxy', array('auth_car_list' =>$auth_car_list, 'cars' => $cars));
        
    }
    
    public function car_list()
    {
        if ($owner = UserModel::getUUidByUserName(Request::post('owner_name'))) {
        $this->View->renderWithoutHeaderAndFooter('car/car_list', array(
            'cars' => CarModel::getCars($owner),
            'owner' => $owner,
            'auth_car_list' => (Request::post('auth_car_list'))
        ));
        }
    }
    
      public function add_auth_usr()
    {
        if ($auth_usr = UserModel::getUUidByUserName(Request::post('auth_usr_name')))
        {
            if (CarModel::newAccessNode(Request::post('car_id'), 80, $auth_usr)) {
        MessageModel::sendMessage( //from, to, message, subject
        Session::get('user_uuid'),  
        $auth_usr,
        sprintf(_('USER_%s_HAS_GIVEN_ACCESS_TO_CAR_%s'),Session::get('user_name'),CarModel::getCarName(Request::post('car_id'))),
        _('NEW_CAR_AVAILABLE_FOR_EDIT'),
        false
                              ) ;                
                
                
                
                
            }
        }
        $this->View->renderWithoutHeaderAndFooter('car/add_auth_usr', array(
            'owner' => $auth_usr,
            'auth_users' => CarModel::getAuthUsrForCar(Request::post('car_id'), Session::get('user_uuid'), 50, 80)
        ));
        
    }
    
    
         public function remove_auth_usr()
    {
        if ($auth_usr = UserModel::getUUidByUserName(Request::post('auth_usr_name')))
        {
            CarModel::removeAccessNode(Request::post('car_id'), 80, $auth_usr);
        }
        $this->View->renderWithoutHeaderAndFooter('car/add_auth_usr', array(
            'owner' => $auth_usr,
            'auth_users' => CarModel::getAuthUsrForCar(Request::post('car_id'), Session::get('user_uuid'), 50, 80)
        ));
        
    }


      public function request_auth()
    {
        //todo: add a limit to request frequency to avoid abuse
        $car_id = Request::post('car_id'); $response = 'REQUEST_NOT_SENT';
        if ($owner_id = CarModel::getCarOwner($car_id))
        {
         $owner_name = UserModel::getUserNameByUUid($owner_id);
         $car_name = CarModel::getCarName($car_id);
         $link = Config::get('URL').'car/edit_car/'.$car_id.'/'.Session::get('user_name');
         $message = sprintf(_('USER_%s_HAS_REQUESTED_ACCESS_TO_CAR_%s'),Session::get('user_name'),$car_name).'<br /><a href="'.$link.'">'.$link.'</a>';
        if (MessageModel::sendMessage( //from, to, message, subject
        Session::get('user_uuid'),  
        $owner_id,
        $message,
        _('NEW_CAR_ACCESS_REQUEST'),
        false
                              ))
        {$response = 'REQUEST_SENT';}
        }
        $this->View->renderWithoutHeaderAndFooter('car/request_auth_response', array(
            'owner_id' => $owner_id,
            'owner_name' => $owner_name,
            'car_id' => $car_id,
            'car_name' => $car_name,
            'response' => $response,
            'message' => $message
        ));
        
    }
    
        public function event($event_id)
    {
        if ($event_array = @unserialize(urldecode($event_id)))
        {
        $owner = CarModel::getCarOwner($event_array['c']);
        $this->View->render('car/event', array(
            'event' => CarModel::getEvent($event_id),
            'units' => UserModel::getUserUnits($owner),
            'owner' => $owner
        ));
        } else {
        header('HTTP/1.0 404 Not Found', true, 404);
        $this->View->render('error/404');
        }
    }
    
         public function eventm($event_id, $pic_ord = '') //modal
    {
        $event_array = unserialize(urldecode($event_id));
        $owner = CarModel::getCarOwner($event_array['c']);
        $this->View->renderWithoutHeaderAndFooter('car/eventm', array(
            'event' => CarModel::getEvent($event_id),
            'units' => UserModel::getUserUnits($owner),
            'owner' => $owner,
            'event_id' => $event_id,
            'pic_ord' => $pic_ord
        ));
    }
        

}
                 
                          