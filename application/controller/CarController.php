<?php               //AUTH_CAR_LIST PADARYSTI SU JSON_ENCODE O NE SU SERIALIZE

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
            'car' => CarModel::getCar($car_id),
            'events' => CarModel::getEvents($car_id),
            'car_data_bits' => Config::get('CAR_DATA_BITS'),
            'units' => UserModel::getUserUnits(CarModel::getCarOwner($car_id)),
            'public_access' => ViewModel::getCarAccessByCarId($car_id, 10),
            'reminders' => ReminderModel::getReminders($car_id, -5184000), //2 months
            'car_items' => CarModel::readAttributes($car_id, 'TECHNICAL_DATA'),
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


        public function new_car_vin()
    {
        $this->View->render('car/new_car_vin');
    }


      public function pic_upl()
    {
        $this->View->renderWithoutHeaderAndFooter('car/pic_upl');
    }

     public function image($car_id, $image_id, $size = 'full')
    {    if ($image_id) {
        $this->View->renderWithoutHeaderAndFooter('car/image', array('car' => $car_id, 'image' => $image_id, 'size' => $size));
    }}

    public function imagepg($car_id, $image_id, $size = 'full')
    {    if ($image_id) {
        $this->View->renderWithoutHeaderAndFooter('car/imagepg', array('car' => $car_id, 'image' => $image_id, 'size' => $size));
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




          public function edit_car($car_id, $user_to_auth='') //only used to add new authorised user upon receipt of request message
    {
        if ($auth_usr = UserModel::getUUidByUserName($user_to_auth))
        {
            if (CarModel::newAccessNode($car_id, 80, $auth_usr)) {
        MessageModel::sendMessage( //from, to, message, subject
        Session::get('user_uuid'),
        $auth_usr,
        sprintf(_('USER_%s_HAS_GIVEN_ACCESS_TO_CAR_%s'),Session::get('user_name'),CarModel::getCarName($car_id)),
        _('NEW_CAR_AVAILABLE_FOR_EDIT'),
        false     ) ;
            }
        }
 Redirect::to('car/authorised_users/'.$car_id);
    }




        public function edit_car_id($car_id)
    {

     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) > 80) {

      $this->View->render('car/edit_car_id', array(
            'car' => CarModel::getCar($car_id),
            'public_access' => ViewModel::getCarAccessByCarId($car_id, 10),  //level 10 - car publically visible
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
            'makes' => CarModel::getCarMakeList('all'), // better not allow to change the make after creating a new car
        ));
    }  else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); }

    }


        public function edit_attributes($car_id) //no longer used
    {

     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) >= 80) {

      $this->View->render('car/edit_car_data_bit', array(
            'car' => CarModel::getCar($car_id),
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
            'car_data_bits' => Config::get('CAR_DATA_BITS'),
        ));
    }  else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); }

    }


           public function edit_attributes_xml($car_id)
    {




     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) >= 80) {

        //update structure first if necessary:
     //   CarModel::updateXmlBits(CarModel::getCarXmlData($car_id), Config::get('DATA_BITS'), $car_id);




      $this->View->render('car/edit_car_data_bit_xml', array(
            'car' => CarModel::getCar($car_id),
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
//            'car_data_bits' => Config::get('CAR_DATA_BITS'),
            'xml_structure' => Config::get('DATA_BITS'),
            'xml_databits' => CarModel::getCarXmlData($car_id),
        ));
    }  else { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index');
            }
    }


               public function attribute_overview($car_id)
    {
     if (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) >= 80) {
      $this->View->render('car/attribute_overview', array(
            'chapters' => CarModel::getAttributeChapters(),
            //'car_id' => $car_id,
            'car' => CarModel::getCar($car_id),
        ));
    }  else { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index');
            }
    }

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



    public function add_attributes_xml_chapter($car_id, $chapter)
    {

     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) >= 80) {

       $response = CarModel::editXmlCarBit(array(
       'key'   => '',
       'value'   => '',
       'car_id' => $car_id,
       'chapter' => urldecode($chapter),
       'chapterno' => 'NEW',
       'entry' => '',
       'unit' => '',
       'validate' => '',
       ));

       Redirect::to('car/edit_attributes_xml/'.$car_id);


    }  else {
        Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
        Redirect::to('index/index');
            }

    }





            public function edit_car_access($car_id)
    {

     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) > 80) {

      $this->View->render('car/edit_car_access', array(
            'car' => CarModel::getCar($car_id),
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
            'public_access' => ViewModel::getCarAccessByCarId($car_id, 10),  //level 10 - car publically visible
            'car_meta' => CarModel::readCarMeta($car_id, array('allow_public_vin', 'allow_public_plates')),
        ));
    }  else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); }

    }




        public function authorised_users($car_id, $user_to_auth='')
    {

     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) > 80) {

      $this->View->render('car/authorised_users', array(
            'car' => CarModel::getCar($car_id),
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
            'auth_users' => CarModel::getAuthUsrForCar($car_id, Session::get('user_uuid'), 50, 80),
            'user_to_auth' => $user_to_auth
        ));
    }   else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); }

    }

        public function edit_car_pictures($car_id)
    {

     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) >= 80) {

      $this->View->render('car/edit_car_pictures', array(
            'car' => CarModel::getCar($car_id),
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
        ));
    }    else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); }

    }




    public function car_reminders($car_id)
    {

     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) >= 80) {

      $this->View->render('car/car_reminders', array(
            'car' => CarModel::getCar($car_id),
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
            'reminders' => ReminderModel::getReminders($car_id, $time = 'all'),
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

    public function save_car()  //unused!!!!
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

    public function save_car_id()
    {
        CarModel::editCarId(array(
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
          ));
         Redirect::to('car/edit_car_id/'.Request::post('car_id'));
    }


     public function save_car_access()
    {
        CarModel::editCarAccess(array(
          'car_id' => Request::post('car_id'),
          'disable_car_access' => Request::post('disable_car_access'),
          'enable_car_access' => Request::post('enable_car_access'),
          ));
        CarModel::updateCarLookupEntry(Request::post('car_id'), implode(',',CarModel::getCarPlates(Request::post('car_id'))), CarModel::getCarVin(Request::post('car_id')));
        Redirect::to('car/edit_car_access/'.Request::post('car_id'));
    }



    public function write_car_meta()
    {
       $response = CarModel::writeCarMeta(Request::post('car_id'), Request::post('meta_key'), Request::post('meta_value'));
       $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));
    }


    public function change_car_access()
   {
     $response = CarModel::editCarAccess(array(
         'car_id' => Request::post('car_id'),
         'disable_car_access' => Request::post('disable_car_access'),
         'enable_car_access' => Request::post('enable_car_access'),
         ));
$this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));
   }



        public function save_car_picture()
    {
        CarModel::editCarImages(array(
          'car_id' => Request::post('car_id'),
          'images' => Request::post('user_images'),
          ));
        Redirect::to('car/edit_car_pictures/'.Request::post('car_id'));
    }


     public function create_event()
    {
      $new_event = CarModel::createEvent(array(
        'car_id' => Request::post('car_id'),
        'event_amount' => Request::post('event_amount'),
        'event_content' => Request::post('event_content'),
        'event_type' => Request::post('event_type'),
        'event_date' => Request::post('event_date'),
        'event_odo' => Request::post('event_odo'),
        'images' => Request::post('user_images'),
        'reminder_time' => Request::post('timestampdate'),
        'reminder_content' => Request::post('reminder_content'),
        'reminder_toggle' => Request::post('remindertoggle'),
        'oil_type' => Request::post('oil_type'),
        'new_oil' => Request::post('new_oil'),
        'oil_filter' => Request::post('oil_filter'),
        'air_filter' => Request::post('air_filter'),
        'fuel_filter' => Request::post('fuel_filter'),
        'cabin_filter' => Request::post('cabin_filter'),
        'timing_belt' => Request::post('timing_belt'),
        'idler_pulley' => Request::post('idler_pulley'),
        'tensioner_pulley' => Request::post('tensioner_pulley'),
        'water_pump' => Request::post('water_pump'),
        ));
        CarModel::updateCarLookupEntry(
            Request::post('car_id'),
            implode(',',CarModel::getCarPlates(Request::post('car_id'))),
            CarModel::getCarVin(Request::post('car_id'))
        );
      if (Request::post('todolist_checkbox')) {
          CarModel::addTodoItem(
              $new_event,
              Request::post('car_id'),
              Request::post('event_content'));
      }
      if (Request::post('new_oil')) { //write oil change to expiries
          ExpiriesModel::commitExpiry(array(
              'car_id' => Request::post('car_id'),
              'expiry' => 'OIL',
              'description' => Request::post('oil_type'),
              'reference' => $new_event,
              'odo' => Request::post('event_odo'),
          ));
      }

        if (Request::post('oil_interval')) { //write update interval to car meta
            CarModel::writeCarMeta(Request::post('car_id'), 'oil_interval', Request::post('oil_interval'));
        }

        if (Request::post('timing_interval')) { //write updated timing belt interval to car meta
            CarModel::writeCarMeta(Request::post('car_id'), 'distr_interval', Request::post('timing_interval'));
        }





        Redirect::to('car/index/'.Request::post('car_id'));
    }

     public function save_odo()
    {
      CarModel::updateOdoReading(Request::post('this_car_id'), Request::post('this_event_odo'), true);
      Redirect::to('car/'.Request::post('return_to').'/'.Request::post('this_car_id'));
    }

    public function save_odo_ajax()
    {
     $response = CarModel::updateOdoReading(Request::post('this_car_id'), Request::post('this_event_odo'), true);
     $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));
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


      public function edit_xml_car_data_bits()  //loaded via ajax
    {
    $response = CarModel::editXmlCarBit(array(
       'key'   => Request::post('key'),
       'value'   => Request::post('value'),
       'car_id' => Request::post('car_id'),
       'chapter' => Request::post('chapter'),
       'chapterno' => Request::post('chapterno'),
       'entry' => Request::post('entry'),
       'unit' => Request::post('unit'),
       'validate' => Request::post('validate'),
       ));
       $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));
    }

          public function write_attr()  //loaded via ajax
    {
    $response = CarModel::commitAttribute(array(
       'key'   => Request::post('key'),
       'value'   => Request::post('value'),
       'car_id' => Request::post('car_id'),
       'chapter' => Request::post('chapter'),
       'entry' => Request::post('entry'),
       'unit' => Request::post('unit'),
       'validate' => Request::post('validate'),
       ));
       $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));
    }


      public function add_xml_car_data_bits()  //loaded via ajax
    {
    $response = CarModel::addXmlCarBit(array(
       'key'   => Request::post('key'),
       'value'   => Request::post('value'),
       'car_id' => Request::post('car_id'),
       'chapter' => Request::post('chapter'),
       'chapterno' => Request::post('chapterno'),
       'entry' => Request::post('entry'),
       'unit' => Request::post('unit'),
       'validate' => Request::post('validate'),
       ));
       $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));
    }

    public function del_attribute_xml($car_id) {
        CarModel::delXmlCarBit(array(
       'key'   => Request::post('key'),
       'value'   => Request::post('val'),
       'car_id' => $car_id,
       'chapter' => Request::post('chapter'),
       'chapterno' => Request::post('chapterno'),
       'entry' => Request::post('entry'),
       'unit' => Request::post('unit'),
       'validate' => Request::post('validate'),
       ));
        Redirect::to('car/edit_attributes_xml/'.$car_id);
    }


    public function edit_event($event_id)
    {
        $event_array = unserialize(urldecode($event_id));
        $owner = CarModel::getCarOwner($event_array['c']);
        $this->View->render('car/edit_event', array(
            'event' => CarModel::getEvent($event_id),
            'units' => UserModel::getUserUnits($owner),
            'owner' => $owner,
            'outstanding' => CarModel::getEventOutstandingStatus($event_id),
            'oil_interval' => CarModel::readCarMeta($event_array['c'], 'oil_interval'),
            'distr_interval' => CarModel::readCarMeta($event_array['c'], 'distr_interval'),
            'defaults' => Config::get('DEFAULT_INTERVALS'),
        ));
    }

    public function new_event($car_id, $tag = '')
    {
        $owner = CarModel::getCarOwner($car_id);
        $this->View->render('car/new_event', array(
            'units' => UserModel::getUserUnits($owner),
            'owner' => $owner,
            'car_id' => $car_id,
            'odo' => CarModel::getCarsField('car_odo', $car_id),
            'oil_interval' => CarModel::readCarMeta($car_id, 'oil_interval'),
            'distr_interval' => CarModel::readCarMeta($car_id, 'distr_interval'),
            'defaults' => Config::get('DEFAULT_INTERVALS'),
            'initial_tag' => $tag,
        ));
    }


    public function remove_todo($event_id)
    {
        $event_array = unserialize(urldecode($event_id));
        if (is_array($event_array))
        {
        CarModel::removeTodoItem( $event_array['t'].':'.$event_array['m'], $event_array['c']);
        }
        Redirect::to('car/index/'.$event_array['c']);
    }




    public function make_event_public($event_id)
    {
        $event_array = unserialize(urldecode($event_id));
        $response = 'false';
        if (is_array($event_array))
        {
        $response = CarModel::setEventVisibility($event_array['c'], $event_array['t'], $event_array['m'], 'pub');
        }
        $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));
    }


    public function make_event_private($event_id)
    {
        $event_array = unserialize(urldecode($event_id));
        $response = 'false';
        if (is_array($event_array))
        {
        $response = CarModel::setEventVisibility($event_array['c'], $event_array['t'], $event_array['m'], 'prv');
        }
        $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));
    }


    public function eventEditSave()
    {
        $edited_event = CarModel::editEvent(array(
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
        'oldversions'  => Request::post('oldversions'),
        'reminder_time' => Request::post('timestampdate'),
        'reminder_content' => Request::post('reminder_content'),
        'reminder_toggle' => Request::post('remindertoggle'),
        'oil_type' => Request::post('oil_type'),
        'new_oil' => Request::post('new_oil'),
        'oil_filter' => Request::post('oil_filter'),
        'air_filter' => Request::post('air_filter'),
        'fuel_filter' => Request::post('fuel_filter'),
        'cabin_filter' => Request::post('cabin_filter'),
        'timing_belt' => Request::post('timing_belt'),
        'idler_pulley' => Request::post('idler_pulley'),
        'tensioner_pulley' => Request::post('tensioner_pulley'),
        'water_pump' => Request::post('water_pump'),

        ));
        CarModel::deleteEvent(array('c' => Request::post('car_id'), 't' => Request::post('event_time'), 'm' => Request::post('event_microtime'), 'source' => 'edit' ));
        if (Request::post('todolist_checkbox')) {CarModel::addTodoItem($edited_event, Request::post('car_id'), Request::post('event_content')); }
        if (Request::post('tododone_checkbox')) {CarModel::removeTodoItem( Request::post('event_time').':'.Request::post('event_microtime'), Request::post('car_id')); }

        if (Request::post('oil_interval')) { //write update interval to car meta
            CarModel::writeCarMeta(Request::post('car_id'), 'oil_interval', Request::post('oil_interval'));
        }

        if (Request::post('timing_interval')) { //write updated timing belt interval to car meta
            CarModel::writeCarMeta(Request::post('car_id'), 'distr_interval', Request::post('timing_interval'));
        }

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
        if (Request::post('car_plates_or_vin')) {
        $this->View->renderWithoutHeaderAndFooter('car/car_list', array(
            'cars' => CarModel::getCarsByPlatesOrVin(Request::post('car_plates_or_vin')),
            'auth_car_list' => Request::post('auth_car_list')
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
        $this->View->renderWithoutHeaderAndFooter('car/generic_response', array(
            'owner_id' => $owner_id,
            'owner_name' => $owner_name,
            'car_id' => $car_id,
            'car_name' => $car_name,
            'response' => $response,
            'message' => $message
        ));

    }


          public function owner_distance()
    {
        //todo: add a limit to request frequency to avoid abuse
        $remote_owner_id = Request::post('owner_id');
        $local_owner_id = Session::get('user_uuid');
        $units = UserModel::getUserUnits(Session::get('user_uuid'));
        $units = $units->user_distance; // km / mi
        $response = ''; //if response is empty, the coords are unset for that user, so we keep silent in the response
        if ($remote_owner_id == $local_owner_id) {
            $response = 0;
            }        else {
            $response = CarModel::getOwnerDistance($local_owner_id, $remote_owner_id, $units);
            }
        $this->View->renderWithoutHeaderAndFooter('car/owner_distance_response', array(
            'response' => $response,
            'units' => $units
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
            'owner' => $owner,
            'outstanding' => CarModel::getEventOutstandingStatus($event_id),
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

    public function imgedit() { //ajax

        if (Request::post('task') == 'del') {
            $response = CarModel::delImage(array(
          'owner' => Request::post('owner'),
          'car_id' => Request::post('car_id'),
          'chapter' => Request::post('chapter'),
          'img' => Request::post('img'),
          'user_images' => Request::post('user_images'),
          'event_time' => Request::post('event_time'),
          'event_microtime' => Request::post('event_microtime'),
          'wherefrom' => Request::post('wherefrom'),
                                          ));
        }

        if (Request::post('task') == 'cw') {
            $response = CarModel::rotateImage(array(
          'owner' => Request::post('owner'),
          'car_id' => Request::post('car_id'),
          'chapter' => Request::post('chapter'),
          'img' => Request::post('img'),
          'user_images' => Request::post('user_images'),
          'event_time' => Request::post('event_time'),
          'event_microtime' => Request::post('event_microtime'),
          'wherefrom' => Request::post('wherefrom'),
          'angle' => 90,
                                             ));
        }

        if (Request::post('task') == 'ccw') {
            $response = CarModel::rotateImage(array(
          'owner' => Request::post('owner'),
          'car_id' => Request::post('car_id'),
          'chapter' => Request::post('chapter'),
          'img' => Request::post('img'),
          'user_images' => Request::post('user_images'),
          'event_time' => Request::post('event_time'),
          'event_microtime' => Request::post('event_microtime'),
          'wherefrom' => Request::post('wherefrom'),
          'angle' => 270,
                                             ));
        }

        $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));

    }


        public function my_car_list()  //ajax
    {
					$this->View->renderWithoutHeaderAndFooter('car/my_car_list', array(
                                                             'my_cars' => CarModel::getCars(Session::get('user_uuid')),
                                                             'others_cars' => CarModel::getAuthCarsForUser(Session::get('user_uuid'), 80, 80),
                                                             ));
    }


            public function expiry_list($car_id)  //ajax
    {
					$this->View->renderWithoutHeaderAndFooter('car/expiry_list', array(
                                                             //'expiries' => CarModel::getCarsField('car_expiries', $car_id), this cars field no longer used
                                                             'expiries' => ExpiriesModel::readAllExpiries($car_id, true),
                                                             'intervals' => CarModel::readCarMeta($car_id, array('oil_interval', 'distr_interval')),
                                                             'odo' => CarModel::getCarsField('car_odo', $car_id),
                                                             'units' => UserModel::getUserUnits(CarModel::getCarOwner($car_id)),
                                                             'car_id' => $car_id,
                                                             ));
    }

/*
    'car' => $car_row,
    'units' => UserModel::getUserUnits(Session::get('user_uuid')),
    'expiries' => ExpiriesModel::readAllExpiries($car_id),
    'structure' => ExpiriesModel::structure(),
    'intervals' => CarModel::readCarMeta($car_id, array('oil_interval', 'distr_interval')),
    */


        public function expiries_old($car_id = '')
    {



    if ($car_id)  {
        IF (CarModel::checkAccessLevel($car_id, Session::get('user_uuid')) >= 80) {

        //update structure first if necessary:

      $xml_structure = Config::get('EXPIRIES_XML');

  $response = CarModel::updateExpiryBits( $xml_structure, CarModel::getCarsField('car_expiries', $car_id), $car_id);

   $car_row = CarModel::getCar($car_id);


      $this->View->render('car/expiries', array(
            'car' => $car_row,
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
            'xml_structure' => $xml_structure,
            'updater_response' => $response,
        ));


            }

            else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); }
              } else Redirect::to('index/index');
    }


    public function save_expiries()
    {
        CarModel::saveExpiries($_POST, Config::get('EXPIRIES_XML'));
        Redirect::to('car/index/'.Request::post('car_id'));
    }


    public function car_transfer($car_id, $user_to_auth='') //displays car transfer page
    {

     IF (CarModel::checkAccessLevel($car_id,Session::get('user_uuid')) > 98) {

      $this->View->render('car/car_transfer', array(
            'car' => CarModel::getCar($car_id),
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
            'user_to_auth' => $user_to_auth
        ));
    }   else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); }

    }


    public function find_usr()  //displays found user boxes via ajax
    {
        if ($users = UserModel::getUserNamesByUserNameOrEmail(Request::post('usr_name_or_email')))
        {
        $this->View->renderWithoutHeaderAndFooter('car/find_usr', array(
            'users' => $users,
        ));
        }

    }

    public function confirm_transfer()
    {
        IF (CarModel::checkAccessLevel(Request::post('car_id'),Session::get('user_uuid')) > 98) {
      $this->View->render('car/confirm_transfer', array(
            'car' => CarModel::getCar(Request::post('car_id')),
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
            'new_user' => UserModel::getUserDataByUserNameOrEmail(Request::post('new_owner_name_or_email')),
        ));
        }   else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); }

    }


        public function execute_transfer()
    {
        print_r($_POST);
        if  (CarModel::OKToTransferCar(Request::post('car_id'), Request::post('old_owner'), Request::post('new_owner')))
        {
        if (Request::post('accept')) {
            CarModel::ExecuteTransfer(Request::post('car_id'), Request::post('old_owner'), Request::post('new_owner'));
            CarModel::unqueueCarTransfer(Request::post('transfer_id'));
        }
        if (Request::post('decline')) {
            CarModel::unqueueCarTransfer(Request::post('transfer_id'));
        }

        }   else   {
                Session::add('feedback_negative', _('FEEDBACK_CAR_TRANSFER_FAILED'));
            }
          Redirect::to('index/index');

    }


        public function send_transfer_request()
    {
        IF (CarModel::checkAccessLevel(Request::post('car_id'),Session::get('user_uuid')) > 98) {
        $car_name = CarModel::getCarName(Request::post('car_id'));
        $subject = _('CAR_TRANSFER_ACCEPT_REQUEST');
        $link = '<a href = "'.Config::get('URL').'car/acquire_transfer/">'.Config::get('URL').'car/acquire_transfer/'.'</a>';
        $body = sprintf(_('IF_YOU_ACCEPT_CAR_%s_FROM_USER_%s_FOLLOW_%s'), $car_name, Session::get('user_name'), $link);
        if (MessageModel::sendMessage(Session::get('user_uuid'), Request::post('user_id'), $body, $subject, false))
        {
            CarModel::queueCarTransfer(Request::post('car_id'), Session::get('user_uuid'), Request::post('user_id'));
           Session::add('feedback_positive', sprintf(_('CAR_TRANSFER_REQUEST_SENT_TO_%s'), UserModel::getUserNameByUUid(Request::post('user_id'))));
        } else {
           Session::add('feedback_negative', _('CAR_TRANSFER_REQUEST_SEND_FAILED'));

        }

    }   else   {
                Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
    }

     Redirect::to('index/index');
    }

    public function acquire_transfer()
    {
        if ($transferred_cars = CarModel::getTransferRequests(Session::get('user_uuid')))
        {
        $this->View->render('car/acquire_transfer', array(
            'transferred_cars' => $transferred_cars,
        ));
        } else {
            Session::add('feedback_negative', _('NO_CAR_TRANSFERS_RECEIVED'));
            Redirect::to('index/index');
        }


    }


    public function new_vin_data($new_vin)  //loaded via ajax
    {
        $vin = strtoupper(filter_var($new_vin, FILTER_SANITIZE_ENCODED));
            $vindata = '';
       if (CarModel::checkvin($vin)) {
              $vindata = CarModel::parsevindata(CarModel::capturevin($vin));
       };
        $this->View->renderWithoutHeaderAndFooter('car/new_vin_data', array(
            'vin' => $vin,
            'vin_data' => $vindata
        ));
    }


        public function omnipotentis()
    {

     IF (Session::get('user_name') == 'Testauskas') {

      $this->View->render('car/omnipotentis');
    }

    }


        public function expiries($car_id = '')
    {



    if ($car_id)  {
        IF (CarModel::checkAccessLevel($car_id, Session::get('user_uuid')) >= 80) {

   $car_row = CarModel::getCar($car_id);
      $this->View->render('car/expiriesToo', array(
            'car' => $car_row,
            'units' => UserModel::getUserUnits(Session::get('user_uuid')),
            'expiries' => ExpiriesModel::readAllExpiries($car_id),
            'structure' => ExpiriesModel::structure(),
            'intervals' => CarModel::readCarMeta($car_id, array('oil_interval', 'distr_interval')),
        ));


            }

            else
            { Session::add('feedback_negative', _('INSUFFICIENT PERMISSION TO ACCESS OTHER USERS CAR'));
            Redirect::to('index/index'); }
              } else Redirect::to('index/index');
    }


    public function write_expiry()  //loaded via ajax
    {
    $response = ExpiriesModel::writeExpiry(array(
       'key'   => Request::post('key'),
       'value'   => Request::post('value'),
       'car_id' => Request::post('car_id'),
       'chapter' => Request::post('chapter'),
       'entry' => Request::post('entry'),
       'validate' => Request::post('validate'),
       'siblings' => Request::post('siblings'),
       ));
       $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));
    }

    public function write_expiry_status()
    {
        $response = ExpiriesModel::commitExpiry(array(
            'car_id' => Request::post('car_id'),
            'expiry' => Request::post('expiry'),
            'status' => Request::post('status'),
        ));
        $this->View->renderWithoutHeaderAndFooter('car/generic_response', array('response' => $response, 'type' => 'passthrough'));
    }



}
